<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $psiquiatria = Specialty::updateOrCreate(
            ['slug' => 'psiquiatria'],
            [
                'name' => 'Psiquiatría',
                'description' => 'Atención médica especializada para tu bienestar mental.',
                'icon' => 'brain',
                'is_active' => true,
            ]
        );

        $psicologia = Specialty::updateOrCreate(
            ['slug' => 'psicologia'],
            [
                'name' => 'Psicología',
                'description' => 'Apoyo emocional y terapéutico para acompañarte.',
                'icon' => 'chat',
                'is_active' => true,
            ]
        );

        $doctors = [
            [
                'specialty_id' => $psiquiatria->id,
                'full_name' => 'Dra. Sofía Álvarez',
                'title' => 'Psiquiatra',
                'bio' => 'Psiquiatra con enfoque en ansiedad, depresión y manejo emocional en adultos y adolescentes. Atención cálida, cercana y 100% confidencial.',
                'photo_url' => 'https://i.pravatar.cc/300?img=47',
                'experience_years' => 8,
                'rating' => 5.0,
                'reviews_count' => 120,
                'price_cop' => 120000,
                'tags' => ['Adultos', 'Adolescentes'],
            ],
            [
                'specialty_id' => $psiquiatria->id,
                'full_name' => 'Dr. Andrés Martínez',
                'title' => 'Psiquiatra',
                'bio' => 'Especialista en trastornos del estado de ánimo y del sueño. Acompañamiento integral con seguimiento personalizado.',
                'photo_url' => 'https://i.pravatar.cc/300?img=12',
                'experience_years' => 10,
                'rating' => 4.9,
                'reviews_count' => 98,
                'price_cop' => 130000,
                'tags' => ['Adultos'],
            ],
            [
                'specialty_id' => $psiquiatria->id,
                'full_name' => 'Dra. Camila Restrepo',
                'title' => 'Psiquiatra',
                'bio' => 'Enfoque en salud mental de adolescentes y jóvenes. Atención empática y basada en evidencia.',
                'photo_url' => 'https://i.pravatar.cc/300?img=45',
                'experience_years' => 6,
                'rating' => 4.8,
                'reviews_count' => 76,
                'price_cop' => 110000,
                'tags' => ['Adolescentes', 'Jóvenes'],
            ],
            [
                'specialty_id' => $psicologia->id,
                'full_name' => 'Ps. Daniel Gómez',
                'title' => 'Psicólogo clínico',
                'bio' => 'Terapia cognitivo-conductual para ansiedad, estrés y desarrollo personal. Espacio seguro y sin juicios.',
                'photo_url' => 'https://i.pravatar.cc/300?img=33',
                'experience_years' => 7,
                'rating' => 4.9,
                'reviews_count' => 85,
                'price_cop' => 90000,
                'tags' => ['Adultos', 'Parejas'],
            ],
            [
                'specialty_id' => $psicologia->id,
                'full_name' => 'Ps. Valentina Ruiz',
                'title' => 'Psicóloga',
                'bio' => 'Acompañamiento emocional y terapéutico con enfoque en bienestar y manejo del estrés.',
                'photo_url' => 'https://i.pravatar.cc/300?img=27',
                'experience_years' => 5,
                'rating' => 4.7,
                'reviews_count' => 64,
                'price_cop' => 85000,
                'tags' => ['Adultos', 'Jóvenes'],
            ],
        ];

        foreach ($doctors as $data) {
            Doctor::updateOrCreate(
                ['specialty_id' => $data['specialty_id'], 'full_name' => $data['full_name']],
                array_merge($data, ['is_active' => true]),
            );
        }
    }
}
