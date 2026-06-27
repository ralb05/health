<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('appointments:complete')]
#[Description('Marca como completadas las citas confirmadas que ya terminaron.')]
class CompleteFinishedAppointments extends Command
{
    public function handle(): int
    {
        $count = Appointment::where('status', Appointment::STATUS_CONFIRMED)
            ->where('ends_at', '<', now())
            ->update(['status' => Appointment::STATUS_COMPLETED]);

        $this->info("Citas completadas: {$count}");

        return self::SUCCESS;
    }
}
