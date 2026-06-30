<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Appointment::with(['doctor', 'patient'])->latest('starts_at');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('doctor')) {
            $query->where('doctor_id', $request->input('doctor'));
        }

        $appointments = $query->paginate(20)->withQueryString();
        $doctors = Doctor::orderBy('full_name')->get();

        return view('admin.appointments.index', compact('appointments', 'doctors'));
    }

    /** Cancela una cita desde el panel (libera el cupo). */
    public function cancel(Appointment $appointment): RedirectResponse
    {
        if ($appointment->is_active) {
            $appointment->update([
                'status' => Appointment::STATUS_CANCELLED,
                'reservation_key' => null,
            ]);
        }

        return back()->with('status', 'Cita cancelada.');
    }
}
