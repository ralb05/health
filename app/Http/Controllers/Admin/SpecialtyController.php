<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SpecialtyController extends Controller
{
    public function index(): View
    {
        $specialties = Specialty::withCount('doctors')->orderBy('name')->get();

        return view('admin.specialties.index', compact('specialties'));
    }

    public function create(): View
    {
        return view('admin.specialties.form', ['specialty' => new Specialty()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = Str::slug($data['name']);
        Specialty::create($data);

        return redirect()->route('admin.specialties.index')->with('status', 'Especialidad creada.');
    }

    public function edit(Specialty $specialty): View
    {
        return view('admin.specialties.form', compact('specialty'));
    }

    public function update(Request $request, Specialty $specialty): RedirectResponse
    {
        $specialty->update($this->validateData($request));

        return redirect()->route('admin.specialties.index')->with('status', 'Especialidad actualizada.');
    }

    public function toggle(Specialty $specialty): RedirectResponse
    {
        $specialty->update(['is_active' => ! $specialty->is_active]);

        return back()->with('status', 'Estado actualizado.');
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'in:brain,chat'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
