<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuario administrador de prueba
        $admin = User::firstOrCreate(
            ['email' => 'admin@mindhealth.test'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password'),
            ]
        );
        if ($admin->role !== 'admin') {
            $admin->role = 'admin';
            $admin->save();
        }

        $this->call(CatalogSeeder::class);
        $this->call(ScheduleSeeder::class);
    }
}
