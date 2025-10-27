<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $universities = [
            [
                'name' => 'Universidad Nacional Mayor de San Marcos',
                'location' => 'Lima, Perú',
                'description' => 'La universidad más antigua de América, fundada el 12 de mayo de 1551.',
                'logo' => 'unmsm_logo.png',
            ],
            [
                'name' => 'Pontificia Universidad Católica del Perú',
                'location' => 'Lima, Perú',
                'description' => 'Una de las universidades privadas más prestigiosas de Perú, fundada en 1917.',
                'logo' => 'pucp_logo.png',
            ],
            [
                'name' => 'Universidad Nacional de Ingeniería',
                'location' => 'Lima, Perú',
                'description' => 'Una universidad pública de ingeniería, ciencia y arquitectura, fundada en 1876.',
                'logo' => 'uni_logo.png',
            ],
            [
                'name' => 'Universidad de Lima',
                'location' => 'Lima, Perú',
                'description' => 'Universidad privada fundada en 1962, especializada en negocios y tecnología.',
                'logo' => 'ulima_logo.png',
            ],
            [
                'name' => 'Universidad Nacional Agraria La Molina',
                'location' => 'Lima, Perú',
                'description' => 'Especializada en ciencias agrícolas y ambientales, fundada en 1902.',
                'logo' => 'unalm_logo.png',
            ],
        ];

        foreach ($universities as $university) {
            University::create($university);
        }
    }
}
