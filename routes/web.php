<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Páginas legales (públicas)
Route::view('/terminos', 'legal.terminos')->name('legal.terminos');
Route::view('/privacidad', 'legal.privacidad')->name('legal.privacidad');

Route::middleware('auth')->group(function () {
    // Catálogo: home del paciente, especialidades y perfiles de especialistas
    Route::get('/inicio', [CatalogController::class, 'home'])->name('inicio');
    Route::get('/especialidades/{specialty}', [CatalogController::class, 'specialty'])->name('especialidades.show');
    Route::get('/especialistas/{doctor}', [CatalogController::class, 'doctor'])->name('especialistas.show');

    // Agendar cita
    Route::get('/especialistas/{doctor}/agendar', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/especialistas/{doctor}/agendar', [BookingController::class, 'store'])->name('booking.store');

    // Citas del paciente
    Route::get('/citas', [BookingController::class, 'index'])->name('citas.index');
    Route::get('/citas/{appointment}', [BookingController::class, 'show'])->name('citas.show');
    Route::post('/citas/{appointment}/cancelar', [BookingController::class, 'cancel'])->name('citas.cancel');

    // Pagos (Mercado Pago)
    Route::post('/citas/{appointment}/pagar', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/pago/{appointment}/simulado', [PaymentController::class, 'simulated'])->name('payment.simulated');
    Route::post('/pago/{appointment}/simulado', [PaymentController::class, 'simulateProcess'])->name('payment.simulate');
    Route::get('/pago/{appointment}/exito', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/pago/{appointment}/pendiente', [PaymentController::class, 'pending'])->name('payment.pending');
    Route::get('/pago/{appointment}/fallo', [PaymentController::class, 'failure'])->name('payment.failure');

    // Alias 'dashboard' (compat. Breeze): redirige al home según el rol.
    Route::get('/dashboard', fn () => redirect()->route(auth()->user()->homeRoute()))->name('dashboard');

    // Perfil de cuenta (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Webhook de Mercado Pago: público (servidor-a-servidor), sin login ni CSRF.
Route::post('/webhooks/mercadopago', [PaymentController::class, 'webhook'])->name('payment.webhook');

/*
| Panel del ADMINISTRADOR
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('especialidades', [\App\Http\Controllers\Admin\SpecialtyController::class, 'index'])->name('specialties.index');
    Route::get('especialidades/crear', [\App\Http\Controllers\Admin\SpecialtyController::class, 'create'])->name('specialties.create');
    Route::post('especialidades', [\App\Http\Controllers\Admin\SpecialtyController::class, 'store'])->name('specialties.store');
    Route::get('especialidades/{specialty}/editar', [\App\Http\Controllers\Admin\SpecialtyController::class, 'edit'])->name('specialties.edit');
    Route::put('especialidades/{specialty}', [\App\Http\Controllers\Admin\SpecialtyController::class, 'update'])->name('specialties.update');
    Route::patch('especialidades/{specialty}/estado', [\App\Http\Controllers\Admin\SpecialtyController::class, 'toggle'])->name('specialties.toggle');

    Route::get('especialistas', [\App\Http\Controllers\Admin\DoctorController::class, 'index'])->name('doctors.index');
    Route::get('especialistas/crear', [\App\Http\Controllers\Admin\DoctorController::class, 'create'])->name('doctors.create');
    Route::post('especialistas', [\App\Http\Controllers\Admin\DoctorController::class, 'store'])->name('doctors.store');
    Route::get('especialistas/{doctor}/editar', [\App\Http\Controllers\Admin\DoctorController::class, 'edit'])->name('doctors.edit');
    Route::put('especialistas/{doctor}', [\App\Http\Controllers\Admin\DoctorController::class, 'update'])->name('doctors.update');
    Route::patch('especialistas/{doctor}/estado', [\App\Http\Controllers\Admin\DoctorController::class, 'toggle'])->name('doctors.toggle');

    // Horarios (disponibilidad) de cada especialista
    Route::post('especialistas/{doctor}/horarios', [\App\Http\Controllers\Admin\ScheduleController::class, 'store'])->name('doctors.schedules.store');
    Route::delete('especialistas/{doctor}/horarios/{schedule}', [\App\Http\Controllers\Admin\ScheduleController::class, 'destroy'])->name('doctors.schedules.destroy');

    Route::get('citas', [\App\Http\Controllers\Admin\AppointmentController::class, 'index'])->name('citas.index');
    Route::post('citas/{appointment}/cancelar', [\App\Http\Controllers\Admin\AppointmentController::class, 'cancel'])->name('citas.cancel');

    Route::get('pagos', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
});

/*
| Panel del ESPECIALISTA
*/
Route::middleware(['auth', 'role:doctor'])->prefix('especialista')->name('doctor.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Doctor\DashboardController::class, 'index'])->name('dashboard');

    Route::post('citas/{appointment}/enlace', [\App\Http\Controllers\Doctor\AppointmentController::class, 'updateMeetingUrl'])->name('citas.meeting');
    Route::post('citas/{appointment}/completar', [\App\Http\Controllers\Doctor\AppointmentController::class, 'complete'])->name('citas.complete');

    Route::get('disponibilidad', [\App\Http\Controllers\Doctor\ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('disponibilidad', [\App\Http\Controllers\Doctor\ScheduleController::class, 'store'])->name('schedules.store');
    Route::delete('disponibilidad/{schedule}', [\App\Http\Controllers\Doctor\ScheduleController::class, 'destroy'])->name('schedules.destroy');
});

require __DIR__.'/auth.php';
