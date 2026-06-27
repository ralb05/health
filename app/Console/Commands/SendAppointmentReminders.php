<?php

namespace App\Console\Commands;

use App\Mail\AppointmentReminder;
use App\Models\Appointment;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('appointments:remind')]
#[Description('Envía recordatorios por correo de las citas próximas.')]
class SendAppointmentReminders extends Command
{
    public function handle(): int
    {
        $hoursBefore = (int) config('booking.reminder_hours_before', 24);

        $appointments = Appointment::with(['patient', 'doctor'])
            ->where('status', Appointment::STATUS_CONFIRMED)
            ->whereNull('reminded_at')
            ->where('starts_at', '>', now())
            ->where('starts_at', '<=', now()->addHours($hoursBefore))
            ->get();

        foreach ($appointments as $appointment) {
            Mail::to($appointment->patient->email)->send(new AppointmentReminder($appointment));
            $appointment->update(['reminded_at' => now()]);
        }

        $this->info("Recordatorios enviados: {$appointments->count()}");

        return self::SUCCESS;
    }
}
