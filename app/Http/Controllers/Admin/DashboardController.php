<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Payment;
use App\Models\Specialty;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'specialties' => Specialty::count(),
            'doctors' => Doctor::where('is_active', true)->count(),
            'confirmed' => Appointment::where('status', Appointment::STATUS_CONFIRMED)->count(),
            'pending' => Appointment::where('status', Appointment::STATUS_PENDING)->count(),
            'revenue' => Payment::where('status', Payment::STATUS_APPROVED)->sum('amount_cop'),
        ];

        $upcoming = Appointment::with(['doctor', 'patient'])
            ->where('status', Appointment::STATUS_CONFIRMED)
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'upcoming'));
    }
}
