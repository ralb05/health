<?php

namespace App\Console\Commands;

use App\Models\Specialty;
use Database\Seeders\CatalogSeeder;
use Database\Seeders\ScheduleSeeder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:ensure-seed')]
#[Description('Carga los datos iniciales (especialidades, especialistas y horarios) solo si la base está vacía.')]
class EnsureSeed extends Command
{
    public function handle(): int
    {
        if (Specialty::count() > 0) {
            $this->info('Ya hay datos: no se siembra (se respeta lo existente).');
            return self::SUCCESS;
        }

        $this->info('Base vacía: cargando datos iniciales…');
        $this->call('db:seed', ['--class' => CatalogSeeder::class, '--force' => true]);
        $this->call('db:seed', ['--class' => ScheduleSeeder::class, '--force' => true]);
        $this->info('✅ Datos iniciales cargados.');

        return self::SUCCESS;
    }
}
