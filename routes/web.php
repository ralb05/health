<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('auth')->group(function () {
    // Catálogo: home del paciente, especialidades y perfiles de especialistas
    Route::get('/inicio', [CatalogController::class, 'home'])->name('inicio');
    Route::get('/especialidades/{specialty}', [CatalogController::class, 'specialty'])->name('especialidades.show');
    Route::get('/especialistas/{doctor}', [CatalogController::class, 'doctor'])->name('especialistas.show');

    // Agendar cita
    Route::get('/especialistas/{doctor}/agendar', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/especialistas/{doctor}/agendar', [BookingController::class, 'store'])->name('booking.store');

    // Citas del paciente
    Route::get('/citas/{appointment}', [BookingController::class, 'show'])->name('citas.show');
    Route::post('/citas/{appointment}/cancelar', [BookingController::class, 'cancel'])->name('citas.cancel');

    // Pagos (Mercado Pago)
    Route::post('/citas/{appointment}/pagar', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/pago/{appointment}/simulado', [PaymentController::class, 'simulated'])->name('payment.simulated');
    Route::post('/pago/{appointment}/simulado', [PaymentController::class, 'simulateProcess'])->name('payment.simulate');
    Route::get('/pago/{appointment}/exito', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/pago/{appointment}/pendiente', [PaymentController::class, 'pending'])->name('payment.pending');
    Route::get('/pago/{appointment}/fallo', [PaymentController::class, 'failure'])->name('payment.failure');

    // Perfil de cuenta (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Webhook de Mercado Pago: público (servidor-a-servidor), sin login ni CSRF.
Route::post('/webhooks/mercadopago', [PaymentController::class, 'webhook'])->name('payment.webhook');

require __DIR__.'/auth.php';
