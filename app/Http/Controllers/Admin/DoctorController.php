<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(): View
    {
        $doctors = Doctor::with(['specialty', 'user'])->orderBy('full_name')->get();

        return view('admin.doctors.index', compact('doctors'));
    }

    public function create(): View
    {
        return view('admin.doctors.form', [
            'doctor' => new Doctor(['experience_years' => 0, 'reviews_count' => 0]),
            'specialties' => Specialty::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $doctor = Doctor::create($this->validateData($request));

        $this->syncLoginAccount($request, $doctor);

        return redirect()->route('admin.doctors.index')->with('status', 'Especialista creado.');
    }

    public function edit(Doctor $doctor): View
    {
        return view('admin.doctors.form', [
            'doctor' => $doctor->load('schedules'),
            'specialties' => Specialty::orderBy('name')->get(),
            'weekdays' => ScheduleController::WEEKDAYS,
        ]);
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $doctor->update($this->validateData($request));

        $this->syncLoginAccount($request, $doctor);

        return redirect()->route('admin.doctors.index')->with('status', 'Especialista actualizado.');
    }

    public function toggle(Doctor $doctor): RedirectResponse
    {
        $doctor->update(['is_active' => ! $doctor->is_active]);

        return back()->with('status', 'Estado actualizado.');
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'specialty_id' => ['required', 'exists:specialties,id'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'photo_url' => ['nullable', 'url', 'max:500'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:80'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'reviews_count' => ['nullable', 'integer', 'min:0'],
            'price_cop' => ['required', 'integer', 'min:0'],
            'tags' => ['nullable', 'string', 'max:255'],
        ]);

        // "Adultos, Adolescentes" -> ["Adultos","Adolescentes"]
        $validated['tags'] = collect(explode(',', (string) $request->input('tags')))
            ->map(fn ($t) => trim($t))->filter()->values()->all();

        $validated['is_active'] = $request->boolean('is_active', true);

        return $validated;
    }

    /** Crea/actualiza el acceso (login) del especialista si se indica correo. */
    private function syncLoginAccount(Request $request, Doctor $doctor): void
    {
        $request->validate([
            'login_email' => ['nullable', 'email', 'max:255'],
            'login_password' => ['nullable', 'string', 'min:8'],
        ]);

        $email = $request->input('login_email');
        if (! $email) {
            return;
        }

        $user = $doctor->user ?? new User();
        $user->name = $doctor->full_name;
        $user->email = $email;
        $user->role = 'doctor';
        if ($request->filled('login_password')) {
            $user->password = Hash::make($request->input('login_password'));
        } elseif (! $user->exists) {
            $user->password = Hash::make('password'); // temporal, cambiar luego
        }
        $user->save();

        if ($doctor->user_id !== $user->id) {
            $doctor->update(['user_id' => $user->id]);
        }
    }
}
