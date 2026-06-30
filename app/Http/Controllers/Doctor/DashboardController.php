<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $doctor = $request->user()->doctor;
        abort_unless($doctor, 403, 'Tu usuario no está vinculado a un perfil de especialista.');

        $appointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->whereIn('status', [Appointment::STATUS_CONFIRMED, Appointment::STATUS_COMPLETED])
            ->orderBy('starts_at')
            ->get();

        [$upcoming, $past] = $appointments->partition(fn (Appointment $a) => $a->ends_at->isFuture());

        return view('doctor.dashboard', [
            'doctor' => $doctor,
            'upcoming' => $upcoming->values(),
            'past' => $past->sortByDesc('starts_at')->values(),
        ]);
    }
}
