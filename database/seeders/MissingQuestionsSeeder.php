<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissingQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Añadiendo preguntas para cursos sin suficientes preguntas...');
        
        // Obtener cursos que tienen menos de 5 preguntas
        $courseIds = DB::table('courses')->select('id', 'name')->get();
        $coursesWithQuestions = DB::table('questions')
            ->select('course_id', DB::raw('count(*) as question_count'))
            ->groupBy('course_id')
            ->pluck('question_count', 'course_id')
            ->toArray();
            
        $minQuestionsPerCourse = 5;
        
        foreach ($courseIds as $course) {
            $courseId = $course->id;
            $courseName = $course->name;
            $currentCount = $coursesWithQuestions[$courseId] ?? 0;
            
            if ($currentCount < $minQuestionsPerCourse) {
                $questionsToAdd = $minQuestionsPerCourse - $currentCount;
                $this->command->info("Añadiendo $questionsToAdd preguntas para el curso: $courseName (ID: $courseId)");
                
                $this->createQuestionsForCourse($courseId, $courseName, $questionsToAdd);
            } else {
                $this->command->info("El curso $courseName (ID: $courseId) ya tiene $currentCount preguntas");
            }
        }
        
        $this->command->info('Preguntas añadidas exitosamente.');
    }
    
    /**
     * Crear preguntas para un curso específico
     */
    private function createQuestionsForCourse($courseId, $courseName, $count)
    {
        // Niveles de dificultad
        $difficulties = ['easy', 'medium', 'hard'];
        
        for ($i = 1; $i <= $count; $i++) {
            // Alternar entre los niveles de dificultad
            $difficulty = $difficulties[($i - 1) % count($difficulties)];
            
            // Crear pregunta basada en el curso
            $questionText = $this->generateQuestionForCourse($courseId, $courseName, $i);
            
            $question = Question::create([
                'course_id' => $courseId,
                'question_text' => $questionText,
                'explanation' => "Explicación para la pregunta #$i de $courseName.",
                'difficulty_level' => $difficulty,
            ]);
            
            // Crear opciones para la pregunta (4 opciones, 1 correcta)
            $correctOptionIndex = rand(0, 3); // La opción correcta será aleatoria
            
            for ($j = 0; $j < 4; $j++) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => "Opción " . chr(65 + $j) . " para la pregunta #$i de $courseName",
                    'is_correct' => ($j === $correctOptionIndex), // Solo una opción es correcta
                ]);
            }
        }
    }
    
    /**
     * Generar el texto de una pregunta basada en el curso
     */
    private function generateQuestionForCourse($courseId, $courseName, $index)
    {
        // Diccionario de preguntas por tipo de curso
        $courseQuestions = [
            'Matemáticas' => [
                "¿Cuál es la solución de la ecuación x² - 5x + 6 = 0?",
                "Si f(x) = 2x² - 3x + 1, ¿cuál es el valor de f(2)?",
                "¿Cuál es la derivada de f(x) = sin(x²)?",
                "Resuelve el límite: lim(x→0) (sin(3x)/x)",
                "¿Cuál es el área bajo la curva f(x) = x² entre x=1 y x=3?"
            ],
            'Física' => [
                "Si un objeto cae libremente, ¿cuál es su aceleración?",
                "¿Cuál es la ecuación que relaciona energía y masa según Einstein?",
                "¿Qué fuerza experimenta una partícula con carga q en un campo magnético B?",
                "¿Cómo se calcula la energía potencial elástica de un resorte?",
                "¿Qué establece la Segunda Ley de la Termodinámica?"
            ],
            'Química' => [
                "¿Cuál es la fórmula química del ácido sulfúrico?",
                "¿Qué describe la Ley de Boyle?",
                "¿Cuál es el número atómico del carbono?",
                "¿Qué tipo de enlace existe entre el sodio y el cloro?",
                "¿Qué son los isótopos?"
            ],
            'Biología' => [
                "¿Cuál es la unidad funcional del riñón?",
                "¿Qué organelo celular contiene el material genético?",
                "¿Qué vitamina se produce cuando la piel se expone al sol?",
                "¿Cuál es la función principal de los lisosomas?",
                "¿Qué enzima cataliza la degradación del almidón?"
            ],
            'Historia' => [
                "¿En qué año se independizó Perú?",
                "¿Quién fue el último emperador inca?",
                "¿Qué civilización construyó Machu Picchu?",
                "¿Quién fue el primer presidente del Perú?",
                "¿Cuándo se inició la Guerra del Pacífico?"
            ],
            'Literatura' => [
                "¿Quién escribió 'La ciudad y los perros'?",
                "¿A qué movimiento literario perteneció César Vallejo?",
                "¿Quién es el autor de 'Cien años de soledad'?",
                "¿Cuál es la obra más conocida de Ricardo Palma?",
                "¿Qué es un soneto?"
            ],
            'Razonamiento Verbal' => [
                "¿Cuál es el antónimo de 'efímero'?",
                "Complete el siguiente enunciado: 'La _____ es a la vejez como la _____ es a la juventud'",
                "¿Qué figura literaria se usa en 'sus cabellos de oro'?",
                "Identifica la palabra que no pertenece al campo semántico",
                "¿Cuál es la idea principal del siguiente texto?"
            ],
            'Razonamiento Matemático' => [
                "Si A=3 y B=5, ¿cuánto es (A²+B²)/(A-B)?",
                "¿Cuál es el siguiente número en la secuencia: 2, 6, 18, 54, ...?",
                "Si todos los gatos tienen 4 patas y 1 cola, ¿cuántas patas y colas tienen 8 gatos?",
                "¿Cuántos cuadrados hay en un tablero de ajedrez?",
                "Si 5 máquinas hacen 5 piezas en 5 minutos, ¿cuántas piezas hacen 100 máquinas en 100 minutos?"
            ]
        ];
        
        // Obtener las preguntas para el curso específico o usar un conjunto genérico
        $questionPool = $courseQuestions[$courseName] ?? [
            "Pregunta #$index para el curso de $courseName.",
            "Segunda pregunta para el curso de $courseName.",
            "Tercera pregunta para el curso de $courseName.",
            "Cuarta pregunta para el curso de $courseName.",
            "Quinta pregunta para el curso de $courseName."
        ];
        
        // Seleccionar una pregunta del pool o generar una genérica si se acaban
        $questionIndex = ($index - 1) % count($questionPool);
        return $questionPool[$questionIndex];
    }
}