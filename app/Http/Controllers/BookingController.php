<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(private readonly AvailabilityService $availability)
    {
    }

    /** Selector de fecha y hora para agendar con un especialista. */
    public function create(Doctor $doctor): View
    {
        abort_unless($doctor->is_active, 404);

        Carbon::setLocale('es');

        $days = $this->availability->slotsByDay($doctor)
            ->map(fn ($slots, $date) => [
                'date' => $date,
                'label' => Carbon::parse($date)->isoFormat('ddd D MMM'),
                'weekday' => Carbon::parse($date)->isoFormat('dddd'),
                'slots' => $slots->map(fn (Carbon $s) => [
                    'value' => $s->format('Y-m-d H:i'),
                    'label' => $s->format('g:i a'),
                ])->values(),
            ])
            ->values();

        return view('agendar.horario', compact('doctor', 'days'));
    }

    /** Crea la cita (pendiente de pago) y reserva el cupo. */
    public function store(Request $request, Doctor $doctor): RedirectResponse
    {
        abort_unless($doctor->is_active, 404);

        $validated = $request->validate([
            'slot' => ['required', 'date_format:Y-m-d H:i'],
        ], [], ['slot' => 'horario']);

        $slot = Carbon::createFromFormat('Y-m-d H:i', $validated['slot']);

        // Liberar holds vencidos en ese cupo antes de validar (por si el scheduler no ha corrido).
        $this->releaseExpiredHolds($doctor, $slot);

        if (! $this->availability->isAvailable($doctor, $slot)) {
            return back()->with('error', 'Ese horario ya no está disponible. Por favor elige otro.');
        }

        $minutes = $this->availability->slotMinutesFor($doctor, $slot) ?? 60;

        try {
            $appointment = DB::transaction(function () use ($request, $doctor, $slot, $minutes) {
                return Appointment::create([
                    'patient_id' => $request->user()->id,
                    'doctor_id' => $doctor->id,
                    'specialty_id' => $doctor->specialty_id,
                    'starts_at' => $slot,
                    'ends_at' => $slot->copy()->addMinutes($minutes),
                    'type' => 'online',
                    'status' => Appointment::STATUS_PENDING,
                    'price_cop' => $doctor->price_cop, // precio congelado
                    'expires_at' => now()->addMinutes((int) config('booking.hold_minutes', 15)),
                ]);
            });
        } catch (QueryException $e) {
            // Violación de la clave única de reserva = alguien tomó el cupo primero.
            return back()->with('error', 'Ese horario acaba de ser reservado por otra persona. Elige otro.');
        }

        return redirect()->route('citas.show', $appointment);
    }

    /** "Mis citas": próximas y pasadas del paciente. */
    public function index(Request $request): View
    {
        $appointments = Appointment::with('doctor')
            ->where('patient_id', $request->user()->id)
            ->orderByDesc('starts_at')
            ->get();

        // Próximas: activas y aún por ocurrir. Pasadas: el resto.
        [$upcoming, $past] = $appointments->partition(
            fn (Appointment $a) => $a->is_active && $a->ends_at->isFuture()
        );

        $upcoming = $upcoming->sortBy('starts_at')->values();

        return view('citas.index', compact('upcoming', 'past'));
    }

    /** Detalle/confirmación de una cita (pantalla de pago en el Entregable 5). */
    public function show(Appointment $appointment): View
    {
        $this->authorizeOwner($appointment);

        // Si el hold venció, marcarla expirada al verla.
        if ($appointment->is_expired_hold) {
            $appointment->update([
                'status' => Appointment::STATUS_EXPIRED,
                'reservation_key' => null,
            ]);
        }

        $appointment->load('doctor.specialty');

        return view('citas.show', compact('appointment'));
    }

    /** El paciente cancela una cita activa (libera el cupo). */
    public function cancel(Appointment $appointment): RedirectResponse
    {
        $this->authorizeOwner($appointment);

        if (! $appointment->is_active) {
            return redirect()->route('citas.show', $appointment);
        }

        // Una cita confirmada (pagada) solo se cancela con la anticipación mínima.
        if ($appointment->status === Appointment::STATUS_CONFIRMED) {
            $minHours = (int) config('booking.cancel_min_hours', 24);
            if ($appointment->starts_at->lessThan(now()->addHours($minHours))) {
                return redirect()->route('citas.show', $appointment)
                    ->with('error', "No puedes cancelar con menos de {$minHours} horas de anticipación. Escríbenos para reprogramar.");
            }
        }

        $appointment->update([
            'status' => Appointment::STATUS_CANCELLED,
            'reservation_key' => null,
        ]);

        return redirect()->route('citas.show', $appointment)
            ->with('status', 'Tu cita fue cancelada.');
    }

    /** Solo el dueño de la cita puede verla/gestionarla. */
    protected function authorizeOwner(Appointment $appointment): void
    {
        abort_unless($appointment->patient_id === auth()->id(), 403);
    }

    /** Marca como expirados los holds vencidos de un cupo concreto. */
    protected function releaseExpiredHolds(Doctor $doctor, Carbon $slot): void
    {
        Appointment::where('doctor_id', $doctor->id)
            ->where('starts_at', $slot)
            ->where('status', Appointment::STATUS_PENDING)
            ->where('expires_at', '<', now())
            ->update([
                'status' => Appointment::STATUS_EXPIRED,
                'reservation_key' => null,
            ]);
    }
}
