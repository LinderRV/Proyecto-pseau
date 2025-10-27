<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Course;
use App\Models\QuestionOption;

class VideoProblemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar los cursos para agregar problemas
        $mathCourse = Course::where('name', 'like', '%Matemáticas%')->orWhere('icon', 'math')->first();
        $physicsCourse = Course::where('name', 'like', '%Física%')->orWhere('icon', 'physics')->first();
        $reasoningCourse = Course::where('name', 'like', '%Razonamiento%')->orWhere('icon', 'math')->first();
        
        if (!$mathCourse) {
            $mathCourse = Course::create([
                'name' => 'Matemáticas',
                'description' => 'Curso de matemáticas básicas',
                'icon' => 'math'
            ]);
        }
        
        if (!$physicsCourse) {
            $physicsCourse = Course::create([
                'name' => 'Física',
                'description' => 'Curso de física básica',
                'icon' => 'physics'
            ]);
        }
        
        if (!$reasoningCourse) {
            $reasoningCourse = Course::create([
                'name' => 'Razonamiento Matemático',
                'description' => 'Curso de razonamiento matemático',
                'icon' => 'math'
            ]);
        }
        
        // 1. Matemáticas - Ecuaciones de primer grado
        $mathProblem = Question::create([
            'course_id' => $mathCourse->id,
            'question_text' => 'Ecuaciones de primer grado',
            'problem_statement' => "Resolver la siguiente ecuación: \n\n2x - 6 = 3x + 1",
            'is_problem_solving' => true,
            'difficulty_level' => 'medium',
            'video_url' => 'https://www.youtube.com/watch?v=tLPokYrrbaY',
            'explanation' => 'Para resolver esta ecuación, llevamos todos los términos con x al lado izquierdo y los términos sin x al lado derecho, luego despejamos x.'
        ]);
        
        // Crear opciones para el problema de matemáticas
        QuestionOption::create([
            'question_id' => $mathProblem->id,
            'option_text' => 'x = -7',
            'is_correct' => true
        ]);
        
        QuestionOption::create([
            'question_id' => $mathProblem->id,
            'option_text' => 'x = 7',
            'is_correct' => false
        ]);
        
        QuestionOption::create([
            'question_id' => $mathProblem->id,
            'option_text' => 'x = -5',
            'is_correct' => false
        ]);
        
        QuestionOption::create([
            'question_id' => $mathProblem->id,
            'option_text' => 'x = 5',
            'is_correct' => false
        ]);
        
        // 2. Física - Segunda Ley de Newton
        $physicsProblem = Question::create([
            'course_id' => $physicsCourse->id,
            'question_text' => 'Segunda Ley de Newton',
            'problem_statement' => "Calcular la magnitud de la aceleración que una fuerza de 50 N le produce a un objeto cuya masa es de 10 kg.",
            'is_problem_solving' => true,
            'difficulty_level' => 'medium',
            'video_url' => 'https://www.youtube.com/watch?v=TfSI-0PBfRY',
            'explanation' => 'Utilizamos la Segunda Ley de Newton: F = m·a, despejando a = F/m'
        ]);
        
        // Crear opciones para el problema de física
        QuestionOption::create([
            'question_id' => $physicsProblem->id,
            'option_text' => 'a = 5 m/s²',
            'is_correct' => true
        ]);
        
        QuestionOption::create([
            'question_id' => $physicsProblem->id,
            'option_text' => 'a = 0.2 m/s²',
            'is_correct' => false
        ]);
        
        QuestionOption::create([
            'question_id' => $physicsProblem->id,
            'option_text' => 'a = 500 m/s²',
            'is_correct' => false
        ]);
        
        QuestionOption::create([
            'question_id' => $physicsProblem->id,
            'option_text' => 'a = 50 m/s²',
            'is_correct' => false
        ]);
        
        // 3. Razonamiento Matemático - Sucesiones
        $reasoningProblem = Question::create([
            'course_id' => $reasoningCourse->id,
            'question_text' => 'Sucesiones Numéricas',
            'problem_statement' => "¿Qué número sigue en la serie: 2, 5, 10, 17, 26, ...?",
            'is_problem_solving' => true,
            'difficulty_level' => 'hard',
            'video_url' => 'https://www.youtube.com/watch?v=oAlo3R7JyD0',
            'explanation' => 'Para resolver este problema analizamos las diferencias entre términos consecutivos y buscamos un patrón.'
        ]);
        
        // Crear opciones para el problema de razonamiento
        QuestionOption::create([
            'question_id' => $reasoningProblem->id,
            'option_text' => '37',
            'is_correct' => true
        ]);
        
        QuestionOption::create([
            'question_id' => $reasoningProblem->id,
            'option_text' => '36',
            'is_correct' => false
        ]);
        
        QuestionOption::create([
            'question_id' => $reasoningProblem->id,
            'option_text' => '35',
            'is_correct' => false
        ]);
        
        QuestionOption::create([
            'question_id' => $reasoningProblem->id,
            'option_text' => '39',
            'is_correct' => false
        ]);

        // Additional problems provided by user (avoid duplicates)
        $additional = [
            [
                'course_name_like' => '%Matemáticas%',
                'icon' => 'math',
                'question_text' => 'Ecuación de Segundo Grado - Factorización',
                'problem_statement' => "Resolver la ecuación de segundo grado por factorización: x² - 5x + 6 = 0",
                'video_url' => 'https://www.youtube.com/watch?v=oXm9s1iFSpw',
                'explanation' => 'Factorizamos x² - 5x + 6 = (x-2)(x-3); por lo tanto las soluciones son x=2 y x=3.',
                'options' => [
                    ['text' => 'x = 2 y x = 3', 'correct' => true],
                    ['text' => 'x = -2 y x = -3', 'correct' => false],
                    ['text' => 'x = 1 y x = 6', 'correct' => false],
                    ['text' => 'x = 3 y x = -2', 'correct' => false],
                ],
            ],
            [
                'course_name_like' => '%Matemáticas%',
                'icon' => 'math',
                'question_text' => 'Sistemas de Ecuaciones 2x2 - Sustitución',
                'problem_statement' => "Resuelve el siguiente sistema de ecuaciones 2x2 por el método de sustitución: 2x + y = 5; x - y = 1",
                'video_url' => 'https://www.youtube.com/watch?v=L0QuX9RpEoM',
                'explanation' => 'Despejando y de la segunda ecuación y = x - 1, sustituimos en la primera: 2x + (x-1) = 5 → 3x = 6 → x = 2, luego y = 1.',
                'options' => [
                    ['text' => 'x = 2, y = 1', 'correct' => true],
                    ['text' => 'x = 1, y = 3', 'correct' => false],
                    ['text' => 'x = 3, y = -1', 'correct' => false],
                    ['text' => 'x = 0, y = 5', 'correct' => false],
                ],
            ],
            [
                'course_name_like' => '%Matemáticas%',
                'icon' => 'math',
                'question_text' => 'Teorema de Pitágoras - Hipotenusa',
                'problem_statement' => "Si un triángulo rectángulo tiene catetos que miden 3 cm y 4 cm, ¿cuánto mide la hipotenusa?",
                'video_url' => 'https://www.youtube.com/watch?v=eTEBvBIz8Ok',
                'explanation' => 'Usando el Teorema de Pitágoras: c = √(3² + 4²) = √(9+16) = √25 = 5 cm.',
                'options' => [
                    ['text' => '5 cm', 'correct' => true],
                    ['text' => '6 cm', 'correct' => false],
                    ['text' => '4 cm', 'correct' => false],
                    ['text' => '√13 cm', 'correct' => false],
                ],
            ],
            [
                'course_name_like' => '%Matemáticas%',
                'icon' => 'math',
                'question_text' => 'Razones Trigonométricas - Definición',
                'problem_statement' => "Dado un triángulo rectángulo, ¿cómo se definen y calculan las razones trigonométricas básicas (Seno, Coseno y Tangente)?",
                'video_url' => 'https://www.youtube.com/watch?v=8zVW0U2jn8U',
                'explanation' => 'Seno = opuesto/hipotenusa, Coseno = adyacente/hipotenusa, Tangente = opuesto/adyacente. Aplicar según los lados del triángulo.',
                'options' => [
                    ['text' => 'Seno = opuesto/hipotenusa; Coseno = adyacente/hipotenusa; Tangente = opuesto/adyacente', 'correct' => true],
                    ['text' => 'Seno = adyacente/hipotenusa; Coseno = opuesto/hipotenusa; Tangente = hipotenusa/adyacente', 'correct' => false],
                    ['text' => 'Seno = opuesto/adyacente; Coseno = hipotenusa/opuesto; Tangente = adyacente/hipotenusa', 'correct' => false],
                    ['text' => 'Seno = opuesto*hipotenusa; Coseno = adyacente*hipotenusa; Tangente = opuesto*adyacente', 'correct' => false],
                ],
            ],
            [
                'course_name_like' => '%Matemáticas%',
                'icon' => 'math',
                'question_text' => 'Límites por Factorización - Indeterminación 0/0',
                'problem_statement' => "Calcular el siguiente límite (indeterminación 0/0): lim (x² - 9) / (x - 3) cuando x tiende a 3.",
                'video_url' => 'https://www.youtube.com/watch?v=h9lEAU5-CSg',
                'explanation' => 'Factorizamos x² - 9 = (x-3)(x+3). Simplificando y evaluando en x=3 obtenemos 6.',
                'options' => [
                    ['text' => '6', 'correct' => true],
                    ['text' => '0', 'correct' => false],
                    ['text' => '3', 'correct' => false],
                    ['text' => 'Undefined', 'correct' => false],
                ],
            ],
        ];

        foreach ($additional as $item) {
            // Find or create course
            $course = Course::where('name', 'like', $item['course_name_like'])->orWhere('icon', $item['icon'])->first();
            if (!$course) {
                $course = Course::create([
                    'name' => 'Matemáticas',
                    'description' => 'Curso de matemáticas básicas',
                    'icon' => $item['icon']
                ]);
            }

            // Avoid duplicate by video_url or exact problem_statement
            $exists = Question::where('video_url', $item['video_url'])
                ->orWhere('problem_statement', $item['problem_statement'])
                ->first();

            if ($exists) {
                continue; // skip duplicates
            }

            $q = Question::create([
                'course_id' => $course->id,
                'question_text' => $item['question_text'],
                'problem_statement' => $item['problem_statement'],
                'is_problem_solving' => true,
                'difficulty_level' => 'medium',
                'video_url' => $item['video_url'],
                'explanation' => $item['explanation']
            ]);

            foreach ($item['options'] as $opt) {
                QuestionOption::create([
                    'question_id' => $q->id,
                    'option_text' => $opt['text'],
                    'is_correct' => $opt['correct']
                ]);
            }
        }
    }
}
