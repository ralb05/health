<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('appointments:expire')]
#[Description('Expira las citas pendientes de pago vencidas y libera su cupo.')]
class ExpirePendingAppointments extends Command
{
    public function handle(): int
    {
        $count = Appointment::where('status', Appointment::STATUS_PENDING)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update([
                'status' => Appointment::STATUS_EXPIRED,
                'reservation_key' => null, // libera el cupo
            ]);

        $this->info("Citas expiradas: {$count}");

        return self::SUCCESS;
    }
}
