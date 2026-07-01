<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /** Días de la semana (convención Carbon: 0=Domingo). */
    public const WEEKDAYS = [
        1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves',
        5 => 'Viernes', 6 => 'Sábado', 0 => 'Domingo',
    ];

    public function store(Request $request, Doctor $doctor): RedirectResponse
    {
        $validated = $request->validate([
            'weekday' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_minutes' => ['required', 'integer', 'min:15', 'max:240'],
        ]);

        $doctor->schedules()->create($validated + ['is_active' => true]);

        return back()->with('status', 'Horario agregado.');
    }

    public function destroy(Doctor $doctor, Schedule $schedule): RedirectResponse
    {
        abort_unless($schedule->doctor_id === $doctor->id, 404);

        $schedule->delete();

        return back()->with('status', 'Horario eliminado.');
    }
}
