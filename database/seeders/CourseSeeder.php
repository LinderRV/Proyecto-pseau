<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Matemáticas',
                'description' => 'Algebra, geometría, trigonometría, cálculo y estadística',
                'icon' => 'calculate',
                'color' => '#2563eb',
                'difficulty_level' => 'Intermedio',
                'careers' => [
                    'Ingeniería de Sistemas' => 10,
                    'Administración de Empresas' => 7,
                    'Arquitectura' => 8,
                ],
            ],
            [
                'name' => 'Física',
                'description' => 'Mecánica, termodinámica, electromagnetismo, óptica y física moderna',
                'icon' => 'science',
                'color' => '#0891b2',
                'difficulty_level' => 'Avanzado',
                'careers' => [
                    'Ingeniería de Sistemas' => 9,
                    'Medicina Humana' => 6,
                    'Arquitectura' => 7,
                ],
            ],
            [
                'name' => 'Química',
                'description' => 'Química general, orgánica, inorgánica y bioquímica',
                'icon' => 'science',
                'color' => '#15803d',
                'difficulty_level' => 'Intermedio',
                'careers' => [
                    'Medicina Humana' => 10,
                    'Arquitectura' => 5,
                ],
            ],
            [
                'name' => 'Biología',
                'description' => 'Anatomía, fisiología, genética y evolución',
                'icon' => 'biotech',
                'color' => '#16a34a',
                'difficulty_level' => 'Intermedio',
                'careers' => [
                    'Medicina Humana' => 10,
                ],
            ],
            [
                'name' => 'Historia',
                'description' => 'Historia universal y del Perú, cultura general',
                'icon' => 'history_edu',
                'color' => '#b45309',
                'difficulty_level' => 'Básico',
                'careers' => [
                    'Derecho' => 8,
                    'Administración de Empresas' => 5,
                ],
            ],
            [
                'name' => 'Literatura',
                'description' => 'Literatura universal y peruana, análisis literario',
                'icon' => 'menu_book',
                'color' => '#b91c1c',
                'difficulty_level' => 'Básico',
                'careers' => [
                    'Derecho' => 7,
                ],
            ],
            [
                'name' => 'Razonamiento Verbal',
                'description' => 'Comprensión lectora, analogías y construcción de textos',
                'icon' => 'spellcheck',
                'color' => '#6366f1',
                'difficulty_level' => 'Intermedio',
                'careers' => [
                    'Derecho' => 10,
                    'Administración de Empresas' => 8,
                    'Ingeniería de Sistemas' => 7,
                    'Medicina Humana' => 7,
                    'Arquitectura' => 6,
                ],
            ],
            [
                'name' => 'Razonamiento Matemático',
                'description' => 'Problemas de lógica, secuencias y patrones',
                'icon' => 'psychology',
                'color' => '#8b5cf6',
                'difficulty_level' => 'Intermedio',
                'careers' => [
                    'Ingeniería de Sistemas' => 10,
                    'Medicina Humana' => 8,
                    'Arquitectura' => 9,
                    'Administración de Empresas' => 8,
                    'Derecho' => 6,
                ],
            ],
        ];

        foreach ($courses as $courseData) {
            $careerAssociations = $courseData['careers'];
            unset($courseData['careers']);
            
            $course = Course::create($courseData);
            
            // Associate careers with importance levels
            foreach ($careerAssociations as $careerName => $importance) {
                $career = Career::where('name', $careerName)->first();
                if ($career) {
                    $course->careers()->attach($career->id, ['importance' => $importance]);
                }
            }
        }
    }
}
