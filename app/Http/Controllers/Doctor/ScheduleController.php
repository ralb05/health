<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public const WEEKDAYS = [
        1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves',
        5 => 'Viernes', 6 => 'Sábado', 0 => 'Domingo',
    ];

    public function index(Request $request): View
    {
        $doctor = $request->user()->doctor;
        abort_unless($doctor, 403, 'Tu usuario no está vinculado a un perfil de especialista.');

        $schedules = $doctor->schedules()
            ->orderBy('weekday')->orderBy('start_time')->get()
            ->groupBy('weekday');

        return view('doctor.schedules.index', [
            'doctor' => $doctor,
            'schedules' => $schedules,
            'weekdays' => self::WEEKDAYS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $doctor = $request->user()->doctor;
        abort_unless($doctor, 403);

        $validated = $request->validate([
            'weekday' => ['required', 'integer', 'between:0,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_minutes' => ['required', 'integer', 'min:15', 'max:240'],
        ]);

        $doctor->schedules()->create($validated + ['is_active' => true]);

        return back()->with('status', 'Horario agregado.');
    }

    public function destroy(Request $request, Schedule $schedule): RedirectResponse
    {
        $doctor = $request->user()->doctor;
        abort_unless($doctor && $schedule->doctor_id === $doctor->id, 403);

        $schedule->delete();

        return back()->with('status', 'Horario eliminado.');
    }
}
