<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Str;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoService
{
    /**
     * Modo simulado: sin credenciales reales, se usa una pasarela local de prueba
     * para poder validar todo el flujo sin conectarse a Mercado Pago.
     */
    public function isSimulated(): bool
    {
        return (bool) config('services.mercadopago.simulated', true);
    }

    /**
     * Crea la preferencia de pago y devuelve la URL de checkout (init_point).
     *
     * @return array{preference_id: string, init_point: string}
     */
    public function createPreference(Appointment $appointment): array
    {
        if ($this->isSimulated()) {
            return [
                'preference_id' => 'SIM-'.Str::upper(Str::random(12)),
                'init_point' => route('payment.simulated', $appointment),
            ];
        }

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        $client = new PreferenceClient();
        $preference = $client->create([
            'items' => [[
                'title' => 'Consulta · '.$appointment->doctor->full_name,
                'description' => $appointment->specialty->name.' · '.$appointment->starts_at->isoFormat('D MMM YYYY HH:mm'),
                'quantity' => 1,
                'unit_price' => (float) $appointment->price_cop,
                'currency_id' => 'COP',
            ]],
            'external_reference' => (string) $appointment->id,
            'back_urls' => [
                'success' => route('payment.success', $appointment),
                'pending' => route('payment.pending', $appointment),
                'failure' => route('payment.failure', $appointment),
            ],
            'auto_return' => 'approved',
            'notification_url' => route('payment.webhook'),
        ]);

        return [
            'preference_id' => $preference->id,
            'init_point' => $preference->init_point,
        ];
    }

    /**
     * Consulta un pago en Mercado Pago por su id (modo real).
     *
     * @return array{id: mixed, status: string, amount: int, external_reference: ?string, raw: array}|null
     */
    public function getPayment(string $paymentId): ?array
    {
        if ($this->isSimulated()) {
            return null;
        }

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        $client = new PaymentClient();
        $payment = $client->get((int) $paymentId);

        return [
            'id' => $payment->id,
            'status' => $payment->status, // approved | pending | rejected | ...
            'amount' => (int) $payment->transaction_amount,
            'external_reference' => $payment->external_reference,
            'raw' => json_decode(json_encode($payment), true) ?? [],
        ];
    }
}
