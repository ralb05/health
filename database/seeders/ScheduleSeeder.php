<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // Lunes a viernes (1..5 en convención Carbon: 0=Domingo)
        $weekdays = [1, 2, 3, 4, 5];

        // Bloques de atención por día (mañana y tarde)
        $blocks = [
            ['start' => '08:00', 'end' => '12:00'],
            ['start' => '14:00', 'end' => '18:00'],
        ];

        Doctor::all()->each(function (Doctor $doctor) use ($weekdays, $blocks) {
            foreach ($weekdays as $weekday) {
                foreach ($blocks as $block) {
                    Schedule::updateOrCreate(
                        [
                            'doctor_id' => $doctor->id,
                            'weekday' => $weekday,
                            'start_time' => $block['start'],
                        ],
                        [
                            'end_time' => $block['end'],
                            'slot_minutes' => 60,
                            'is_active' => true,
                        ]
                    );
                }
            }
        });
    }
}
