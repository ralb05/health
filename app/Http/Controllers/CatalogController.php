<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /** Home del paciente: saludo + especialidades. */
    public function home(): View
    {
        $specialties = Specialty::where('is_active', true)
            ->withCount('activeDoctors')
            ->orderBy('id')
            ->get();

        return view('inicio', compact('specialties'));
    }

    /** Detalle de una especialidad + lista de sus especialistas. */
    public function specialty(Specialty $specialty): View
    {
        abort_unless($specialty->is_active, 404);

        $doctors = $specialty->activeDoctors()
            ->orderByDesc('rating')
            ->get();

        return view('especialidades.show', compact('specialty', 'doctors'));
    }

    /** Perfil de un especialista. */
    public function doctor(Doctor $doctor): View
    {
        abort_unless($doctor->is_active, 404);

        $doctor->load('specialty');

        return view('especialistas.show', compact('doctor'));
    }
}
