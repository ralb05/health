<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Libera cupos de citas no pagadas: cada minuto expira los holds vencidos.
Schedule::command('appointments:expire')->everyMinute()->withoutOverlapping();
