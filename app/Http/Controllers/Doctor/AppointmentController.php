<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /** Guarda el enlace de la videollamada en la cita. */
    public function updateMeetingUrl(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->authorizeDoctor($request, $appointment);

        $validated = $request->validate([
            'meeting_url' => ['nullable', 'url', 'max:500'],
        ]);

        $appointment->update(['meeting_url' => $validated['meeting_url'] ?? null]);

        return back()->with('status', 'Enlace de la videollamada guardado.');
    }

    /** Marca la cita como completada. */
    public function complete(Request $request, Appointment $appointment): RedirectResponse
    {
        $this->authorizeDoctor($request, $appointment);

        if ($appointment->status === Appointment::STATUS_CONFIRMED) {
            $appointment->update(['status' => Appointment::STATUS_COMPLETED]);
        }

        return back()->with('status', 'Cita marcada como completada.');
    }

    /** La cita debe pertenecer al especialista autenticado. */
    private function authorizeDoctor(Request $request, Appointment $appointment): void
    {
        $doctor = $request->user()->doctor;
        abort_unless($doctor && $appointment->doctor_id === $doctor->id, 403);
    }
}
