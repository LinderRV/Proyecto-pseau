<?php

namespace Database\Seeders;

use App\Models\Career;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $careers = [
            [
                'name' => 'Ingeniería de Sistemas',
                'description' => 'Carrera enfocada en el desarrollo de software, sistemas informáticos y tecnología de la información.',
                'icon' => 'computer',
                'color' => '#3b82f6',
            ],
            [
                'name' => 'Medicina Humana',
                'description' => 'Carrera orientada a la formación de profesionales de la salud para el diagnóstico, tratamiento y prevención de enfermedades.',
                'icon' => 'medical_services',
                'color' => '#ef4444',
            ],
            [
                'name' => 'Administración de Empresas',
                'description' => 'Carrera enfocada en la gestión y dirección de organizaciones empresariales.',
                'icon' => 'business',
                'color' => '#f97316',
            ],
            [
                'name' => 'Derecho',
                'description' => 'Carrera centrada en el estudio y aplicación de las leyes y el sistema judicial.',
                'icon' => 'gavel',
                'color' => '#8b5cf6',
            ],
            [
                'name' => 'Arquitectura',
                'description' => 'Carrera que combina arte y técnica para el diseño y construcción de edificaciones y espacios urbanos.',
                'icon' => 'architecture',
                'color' => '#10b981',
            ],
        ];

        foreach ($careers as $career) {
            Career::create($career);
        }
    }
}
