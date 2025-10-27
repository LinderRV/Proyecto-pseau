<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Preguntas para Matemáticas
        $this->createMathQuestions();
        
        // Preguntas para Habilidad Verbal
        $this->createVerbalQuestions();
        
        // Preguntas para Historia
        $this->createHistoryQuestions();
        
        // Preguntas para Química
        $this->createChemistryQuestions();
    }
    
    private function createMathQuestions()
    {
        $course = Course::where('name', 'Matemáticas')->first();
        
        if (!$course) return;
        
        // Pregunta 1
        $question = Question::create([
            'question_text' => 'Si f(x) = 2x² + 3x - 4, ¿cuál es el valor de f(2)?',
            'explanation' => 'Para encontrar f(2), sustituimos x por 2 en la función: f(2) = 2(2)² + 3(2) - 4 = 2(4) + 6 - 4 = 8 + 6 - 4 = 10',
            'difficulty_level' => 'easy',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '6',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '8',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '10',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '12',
            'is_correct' => false,
        ]);
        
        // Pregunta 2
        $question = Question::create([
            'question_text' => 'Resuelve la ecuación: 3x - 7 = 2x + 5',
            'explanation' => 'Agrupamos términos semejantes: 3x - 2x = 5 + 7, x = 12',
            'difficulty_level' => 'easy',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '10',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '12',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '14',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '16',
            'is_correct' => false,
        ]);
        
        // Pregunta 3
        $question = Question::create([
            'question_text' => 'Si un triángulo tiene lados de longitud 3, 4 y 5 unidades, ¿cuál es su área?',
            'explanation' => 'Este es un triángulo rectángulo (3-4-5), por lo que el área se calcula como A = (base × altura)/2 = (3 × 4)/2 = 6 unidades cuadradas.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '5 unidades cuadradas',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '6 unidades cuadradas',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '7.5 unidades cuadradas',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '10 unidades cuadradas',
            'is_correct' => false,
        ]);
        
        // Pregunta 4
        $question = Question::create([
            'question_text' => 'Si log₁₀(x) = 2, ¿cuál es el valor de x?',
            'explanation' => 'Si log₁₀(x) = 2, entonces 10² = x, por lo que x = 100.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '20',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '50',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '100',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1000',
            'is_correct' => false,
        ]);
        
        // Pregunta 5
        $question = Question::create([
            'question_text' => 'La derivada de f(x) = 3x² + 5x - 2 es:',
            'explanation' => 'La derivada se calcula como f\'(x) = 6x + 5',
            'difficulty_level' => 'hard',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '3x + 5',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '6x + 5',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '6x² + 5',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '6x + 5x - 2',
            'is_correct' => false,
        ]);
    }
    
    private function createVerbalQuestions()
    {
        $course = Course::where('name', 'Habilidad Verbal')->first();
        
        if (!$course) return;
        
        // Pregunta 1
        $question = Question::create([
            'question_text' => 'Identifica el sinónimo de "Efímero":',
            'explanation' => 'Efímero significa de corta duración, pasajero o transitorio.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Duradero',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Eterno',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Pasajero',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Permanente',
            'is_correct' => false,
        ]);
        
        // Pregunta 2
        $question = Question::create([
            'question_text' => 'Completa la analogía: Libro es a Lector como Película es a _______.',
            'explanation' => 'La relación es la de creación y receptor. Un lector consume un libro, así como un espectador consume una película.',
            'difficulty_level' => 'easy',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Director',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Actor',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Espectador',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Guionista',
            'is_correct' => false,
        ]);
        
        // Pregunta 3
        $question = Question::create([
            'question_text' => 'Identifica la oración con error gramatical:',
            'explanation' => 'La forma correcta es "Le dio un regalo a su amiga" o "Le dio un regalo a ella". "La dio un regalo" mezcla incorrectamente los pronombres.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Juan corrió rápidamente hacia la meta.',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'La dio un regalo a su amiga.',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Ellos vendrán mañana a la fiesta.',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Me gusta ese libro de poesía.',
            'is_correct' => false,
        ]);
        
        // Pregunta 4
        $question = Question::create([
            'question_text' => 'En la oración "El viento soplaba fuertemente mientras las hojas caían de los árboles", la palabra "mientras" funciona como:',
            'explanation' => 'La palabra "mientras" establece una relación temporal entre dos acciones, por lo que es una conjunción temporal.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Preposición',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Adverbio',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Conjunción temporal',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Pronombre relativo',
            'is_correct' => false,
        ]);
        
        // Pregunta 5
        $question = Question::create([
            'question_text' => 'Lee el siguiente párrafo y responde: "La tecnología ha cambiado nuestra forma de comunicarnos. Antes, escribíamos cartas que tardaban días en llegar; ahora, enviamos mensajes instantáneos que se reciben en segundos." ¿Cuál es la idea principal del texto?',
            'explanation' => 'El párrafo contrasta la comunicación antigua (cartas) con la moderna (mensajes instantáneos) para ilustrar cómo la tecnología ha transformado nuestra comunicación.',
            'difficulty_level' => 'hard',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Las cartas son más personales que los mensajes instantáneos.',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'La tecnología ha revolucionado la forma en que nos comunicamos.',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Los mensajes instantáneos son más eficientes que las cartas.',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Antes la comunicación era más lenta que ahora.',
            'is_correct' => false,
        ]);
    }
    
    private function createHistoryQuestions()
    {
        $course = Course::where('name', 'Historia')->first();
        
        if (!$course) return;
        
        // Pregunta 1
        $question = Question::create([
            'question_text' => '¿En qué año se produjo la Revolución Mexicana?',
            'explanation' => 'La Revolución Mexicana comenzó el 20 de noviembre de 1910 cuando Francisco I. Madero llamó a las armas contra el régimen de Porfirio Díaz.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1810',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1910',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1920',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1930',
            'is_correct' => false,
        ]);
        
        // Pregunta 2
        $question = Question::create([
            'question_text' => '¿Quién fue el primer presidente de México?',
            'explanation' => 'Guadalupe Victoria (Manuel Félix Fernández) fue el primer presidente de México, gobernando de 1824 a 1829.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Miguel Hidalgo',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Benito Juárez',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Guadalupe Victoria',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Porfirio Díaz',
            'is_correct' => false,
        ]);
        
        // Pregunta 3
        $question = Question::create([
            'question_text' => '¿Qué acontecimiento histórico ocurrió en 1789?',
            'explanation' => 'En 1789 comenzó la Revolución Francesa con la toma de la Bastilla, un evento que marcó el fin del régimen absolutista y el inicio de una nueva era política.',
            'difficulty_level' => 'hard',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'La Revolución Industrial',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'La Revolución Francesa',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'La Independencia de Estados Unidos',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'La Primera Guerra Mundial',
            'is_correct' => false,
        ]);
        
        // Pregunta 4
        $question = Question::create([
            'question_text' => '¿Cuál fue la civilización mesoamericana conocida por su preciso calendario y sistema de escritura jeroglífica?',
            'explanation' => 'Los mayas desarrollaron un sofisticado sistema de escritura jeroglífica y un calendario extremadamente preciso, además de grandes avances en matemáticas y astronomía.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Olmeca',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Maya',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Azteca',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Tolteca',
            'is_correct' => false,
        ]);
        
        // Pregunta 5
        $question = Question::create([
            'question_text' => '¿Cuándo terminó la Segunda Guerra Mundial?',
            'explanation' => 'La Segunda Guerra Mundial terminó oficialmente el 2 de septiembre de 1945 con la rendición de Japón, tras los bombardeos atómicos de Hiroshima y Nagasaki.',
            'difficulty_level' => 'easy',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1943',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1944',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1945',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1950',
            'is_correct' => false,
        ]);
    }
    
    private function createChemistryQuestions()
    {
        $course = Course::where('name', 'Química')->first();
        
        if (!$course) return;
        
        // Pregunta 1
        $question = Question::create([
            'question_text' => '¿Cuál es el símbolo químico del oro?',
            'explanation' => 'El símbolo químico del oro es Au, que proviene de la palabra latina "aurum".',
            'difficulty_level' => 'easy',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Au',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Or',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Go',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Ag',
            'is_correct' => false,
        ]);
        
        // Pregunta 2
        $question = Question::create([
            'question_text' => '¿Cuál es la fórmula química del agua?',
            'explanation' => 'La fórmula química del agua es H₂O, lo que significa que cada molécula está compuesta por dos átomos de hidrógeno (H) y un átomo de oxígeno (O).',
            'difficulty_level' => 'easy',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'H2O',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'H2O2',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'CO2',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'HO',
            'is_correct' => false,
        ]);
        
        // Pregunta 3
        $question = Question::create([
            'question_text' => '¿Qué partícula subatómica tiene carga positiva?',
            'explanation' => 'El protón es una partícula subatómica con carga eléctrica positiva que se encuentra en el núcleo del átomo.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Electrón',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Protón',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Neutrón',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Fotón',
            'is_correct' => false,
        ]);
        
        // Pregunta 4
        $question = Question::create([
            'question_text' => '¿Cuál es el pH de una solución neutra a 25°C?',
            'explanation' => 'Una solución neutra tiene un pH de 7 a 25°C. Las soluciones con pH menor que 7 son ácidas y las que tienen pH mayor que 7 son básicas o alcalinas.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '0',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '7',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '10',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '14',
            'is_correct' => false,
        ]);
        
        // Pregunta 5
        $question = Question::create([
            'question_text' => '¿Qué tipo de enlace se forma cuando los átomos comparten electrones?',
            'explanation' => 'Un enlace covalente se forma cuando dos átomos comparten uno o más pares de electrones para lograr estabilidad en su capa de valencia.',
            'difficulty_level' => 'hard',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Enlace iónico',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Enlace covalente',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Enlace metálico',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Enlace por fuerzas de Van der Waals',
            'is_correct' => false,
        ]);
    }
}