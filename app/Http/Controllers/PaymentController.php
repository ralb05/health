<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Services\MercadoPagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private readonly MercadoPagoService $mp)
    {
    }

    /** Inicia el checkout: crea la preferencia y redirige a la pasarela. */
    public function checkout(Appointment $appointment): RedirectResponse
    {
        $this->authorizeOwner($appointment);

        // Solo se paga una cita pendiente y no vencida.
        if ($appointment->status !== Appointment::STATUS_PENDING) {
            return redirect()->route('citas.show', $appointment)
                ->with('error', 'Esta cita ya no está pendiente de pago.');
        }
        if ($appointment->is_expired_hold) {
            $appointment->update(['status' => Appointment::STATUS_EXPIRED, 'reservation_key' => null]);
            return redirect()->route('citas.show', $appointment)
                ->with('error', 'La reserva expiró. Agenda nuevamente.');
        }

        $payment = $appointment->payment()->firstOrNew([]);
        $payment->fill([
            'provider' => $this->mp->isSimulated() ? 'simulado' : 'mercadopago',
            'amount_cop' => $appointment->price_cop,
            'status' => Payment::STATUS_PENDING,
        ])->save();

        $preference = $this->mp->createPreference($appointment);

        $payment->update(['preference_id' => $preference['preference_id']]);

        return redirect()->away($preference['init_point']);
    }

    /** Pasarela de pago SIMULADA (solo modo de pruebas). */
    public function simulated(Appointment $appointment): View|RedirectResponse
    {
        $this->authorizeOwner($appointment);
        abort_unless($this->mp->isSimulated(), 404);

        if ($appointment->status !== Appointment::STATUS_PENDING) {
            return redirect()->route('citas.show', $appointment);
        }

        return view('pagos.simulado', compact('appointment'));
    }

    /** Procesa el resultado de la pasarela simulada (aprobar/rechazar). */
    public function simulateProcess(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->authorizeOwner($appointment);
        abort_unless($this->mp->isSimulated(), 404);

        $approved = $request->input('result') === 'approved';
        $payment = $appointment->payment;

        if ($approved) {
            $this->markPaid($appointment, $payment, 'SIM-'.$payment->id, $appointment->price_cop, ['simulated' => true]);

            return redirect()->route('payment.success', $appointment);
        }

        $payment?->update(['status' => Payment::STATUS_REJECTED]);

        return redirect()->route('payment.failure', $appointment);
    }

    /** Webhook de Mercado Pago (notificación servidor-a-servidor). Modo real. */
    public function webhook(Request $request): Response
    {
        // MP envía el tipo y el id del recurso (pago).
        $type = $request->input('type', $request->input('topic'));
        $paymentId = $request->input('data.id', $request->input('id'));

        if ($type !== 'payment' || ! $paymentId) {
            return response('ignored', 200); // otros eventos: respondemos 200 y salimos.
        }

        try {
            $mpPayment = $this->mp->getPayment((string) $paymentId);

            if (! $mpPayment) {
                return response('no-data', 200);
            }

            $appointment = Appointment::find($mpPayment['external_reference']);
            if (! $appointment) {
                return response('appointment-not-found', 200);
            }

            $payment = $appointment->payment()->firstOrNew([]);
            $payment->fill([
                'provider' => 'mercadopago',
                'amount_cop' => $appointment->price_cop,
            ]);
            if (! $payment->exists) {
                $payment->status = Payment::STATUS_PENDING;
            }
            $payment->save();

            if ($mpPayment['status'] === 'approved') {
                // Validación de monto antes de confirmar.
                if ($mpPayment['amount'] !== $appointment->price_cop) {
                    Log::warning('Pago con monto distinto', ['appointment' => $appointment->id, 'mp' => $mpPayment]);
                    return response('amount-mismatch', 200);
                }

                $this->markPaid($appointment, $payment, (string) $mpPayment['id'], $mpPayment['amount'], $mpPayment['raw']);
            } elseif ($mpPayment['status'] === 'rejected') {
                $payment->update(['status' => Payment::STATUS_REJECTED, 'raw_payload' => $mpPayment['raw']]);
            }
        } catch (\Throwable $e) {
            Log::error('Webhook Mercado Pago falló', ['error' => $e->getMessage()]);
            return response('error', 200); // 200 para que MP no reintente en bucle por errores nuestros.
        }

        return response('ok', 200);
    }

    // Páginas de retorno
    public function success(Appointment $appointment): View
    {
        $this->authorizeOwner($appointment);
        return view('pagos.resultado', ['appointment' => $appointment, 'state' => 'success']);
    }

    public function pending(Appointment $appointment): View
    {
        $this->authorizeOwner($appointment);
        return view('pagos.resultado', ['appointment' => $appointment, 'state' => 'pending']);
    }

    public function failure(Appointment $appointment): View
    {
        $this->authorizeOwner($appointment);
        return view('pagos.resultado', ['appointment' => $appointment, 'state' => 'failure']);
    }

    /**
     * Marca el pago como aprobado y confirma la cita. Idempotente: si ya estaba
     * aprobado, no hace nada (evita procesar dos veces el mismo webhook).
     */
    protected function markPaid(Appointment $appointment, Payment $payment, string $providerPaymentId, int $amount, array $payload): void
    {
        if ($payment->is_approved) {
            return; // ya procesado
        }

        DB::transaction(function () use ($appointment, $payment, $providerPaymentId, $amount, $payload) {
            $payment->update([
                'status' => Payment::STATUS_APPROVED,
                'provider_payment_id' => $providerPaymentId,
                'amount_cop' => $amount,
                'raw_payload' => $payload,
                'paid_at' => now(),
            ]);

            if ($appointment->status !== Appointment::STATUS_CONFIRMED) {
                $appointment->update([
                    'status' => Appointment::STATUS_CONFIRMED,
                    'expires_at' => null,
                ]);
                // (El correo de confirmación se envía en el Entregable 6.)
            }
        });
    }

    protected function authorizeOwner(Appointment $appointment): void
    {
        abort_unless($appointment->patient_id === auth()->id(), 403);
    }
}
