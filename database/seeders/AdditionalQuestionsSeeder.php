<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Question;
use App\Models\QuestionOption;

class AdditionalQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createAdditionalMathQuestions();
        $this->createAdditionalVerbalQuestions();
        $this->createAdditionalHistoryQuestions();
        $this->createAdditionalChemistryQuestions();
    }
    
    private function createAdditionalMathQuestions()
    {
        $course = Course::where('name', 'Matemáticas')->first();
        
        if (!$course) return;
        
        // Pregunta 1
        $question = Question::create([
            'question_text' => 'Si una función f(x) = 3x² - 5x + 2, ¿cuál es el valor mínimo de la función?',
            'explanation' => 'Para encontrar el valor mínimo, primero hallamos el punto crítico igualando la derivada a cero: f\'(x) = 6x - 5 = 0, x = 5/6. El valor mínimo es f(5/6) = -25/12 + 2 = -1/12.',
            'difficulty_level' => 'hard',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '-1/12',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '0',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '2',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '-2',
            'is_correct' => false,
        ]);
        
        // Pregunta 2
        $question = Question::create([
            'question_text' => 'En una progresión geométrica, el primer término es 3 y el quinto término es 48. ¿Cuál es la razón de la progresión?',
            'explanation' => 'Si el primer término a₁ = 3 y a₅ = 48, entonces a₅ = a₁·r⁴, donde r es la razón. Por lo tanto, 48 = 3·r⁴, r⁴ = 16, r = 2.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '2',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '3',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '4',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1.5',
            'is_correct' => false,
        ]);
        
        // Pregunta 3
        $question = Question::create([
            'question_text' => 'Si log₁₀(x) = 2.5, ¿cuál es el valor de x?',
            'explanation' => 'Si log₁₀(x) = 2.5, entonces x = 10^2.5 = 10^2 · 10^0.5 = 100 · √10 ≈ 316.23.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '316.23',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '250',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '300',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '350',
            'is_correct' => false,
        ]);
        
        // Pregunta 4
        $question = Question::create([
            'question_text' => 'En un triángulo, dos lados miden 5 cm y 8 cm, y el ángulo entre ellos es de 30°. ¿Cuál es el área del triángulo?',
            'explanation' => 'El área de un triángulo cuando se conocen dos lados y el ángulo entre ellos es A = (1/2) · a · b · sin(C). Por lo tanto, A = (1/2) · 5 · 8 · sin(30°) = 20 · 0.5 = 10 cm².',
            'difficulty_level' => 'hard',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '10 cm²',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '20 cm²',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '15 cm²',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '12 cm²',
            'is_correct' => false,
        ]);
        
        // Pregunta 5
        $question = Question::create([
            'question_text' => 'Si se lanza un dado 6 veces, ¿cuál es la probabilidad de obtener exactamente 3 seises?',
            'explanation' => 'Esta es una distribución binomial con n = 6 (número de lanzamientos), p = 1/6 (probabilidad de éxito), y k = 3 (número de éxitos deseados). P(X = 3) = C(6,3) · (1/6)³ · (5/6)³ = 20 · (1/6)³ · (5/6)³ ≈ 0.0579.',
            'difficulty_level' => 'hard',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '0.0579',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '0.5',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '0.1',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '0.0123',
            'is_correct' => false,
        ]);
    }
    
    private function createAdditionalVerbalQuestions()
    {
        $course = Course::where('name', 'Habilidad Verbal')->first();
        
        if (!$course) return;
        
        // Pregunta 1
        $question = Question::create([
            'question_text' => 'Selecciona el antónimo de "Frugal":',
            'explanation' => 'Frugal significa moderado o austero en el gasto. Su antónimo es "Derrochador", que implica gastar dinero sin moderación.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Derrochador',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Austero',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Ahorrativo',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Moderado',
            'is_correct' => false,
        ]);
        
        // Pregunta 2
        $question = Question::create([
            'question_text' => 'Identifica la palabra escrita correctamente:',
            'explanation' => 'La forma correcta es "Conciencia", que significa conocimiento que el ser humano tiene de sí mismo y de su entorno.',
            'difficulty_level' => 'easy',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Conciencia',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Consiencia',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Conciensa',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Consiensa',
            'is_correct' => false,
        ]);
        
        // Pregunta 3
        $question = Question::create([
            'question_text' => 'Complete la analogía: Poeta es a verso como Novelista es a _______.',
            'explanation' => 'La relación es de creador y su creación. Un poeta escribe versos, así como un novelista escribe capítulos.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Capítulo',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Historia',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Libro',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Ficción',
            'is_correct' => false,
        ]);
        
        // Pregunta 4
        $question = Question::create([
            'question_text' => 'Identifica el sustantivo colectivo:',
            'explanation' => 'Un sustantivo colectivo es aquel que, en singular, designa un conjunto de cosas o seres. "Manada" designa un conjunto de animales.',
            'difficulty_level' => 'easy',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Manada',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Perro',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Casa',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Lápiz',
            'is_correct' => false,
        ]);
        
        // Pregunta 5
        $question = Question::create([
            'question_text' => 'Lee el siguiente fragmento y contesta: "El viento soplaba con fuerza, inclinando los árboles. Las nubes oscuras anunciaban la tormenta inminente." ¿Qué tipo de texto es?',
            'explanation' => 'Es un texto descriptivo porque presenta detalles sobre cómo es algo (el ambiente antes de una tormenta), apelando a los sentidos del lector.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Descriptivo',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Narrativo',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Argumentativo',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Expositivo',
            'is_correct' => false,
        ]);
    }
    
    private function createAdditionalHistoryQuestions()
    {
        $course = Course::where('name', 'Historia')->first();
        
        if (!$course) return;
        
        // Pregunta 1
        $question = Question::create([
            'question_text' => '¿Cuál de los siguientes personajes NO participó en el movimiento de Independencia de México?',
            'explanation' => 'Benito Juárez no participó en la Independencia de México (1810-1821). Él fue un político mexicano destacado posteriormente, siendo presidente durante la Reforma y la Intervención Francesa (1858-1872).',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Benito Juárez',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Miguel Hidalgo',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'José María Morelos',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Agustín de Iturbide',
            'is_correct' => false,
        ]);
        
        // Pregunta 2
        $question = Question::create([
            'question_text' => '¿Qué evento marcó el inicio de la Primera Guerra Mundial?',
            'explanation' => 'La Primera Guerra Mundial inició tras el asesinato del archiduque Francisco Fernando de Austria en Sarajevo el 28 de junio de 1914, lo que desencadenó una serie de alianzas militares que llevaron al conflicto global.',
            'difficulty_level' => 'easy',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'El asesinato del archiduque Francisco Fernando',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'La invasión de Polonia',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'El hundimiento del Lusitania',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'La Revolución Rusa',
            'is_correct' => false,
        ]);
        
        // Pregunta 3
        $question = Question::create([
            'question_text' => '¿Cuál fue la capital del Imperio Azteca?',
            'explanation' => 'Tenochtitlán fue la capital del Imperio Azteca, fundada en 1325. Estaba ubicada en lo que hoy es la Ciudad de México y era una de las ciudades más grandes y avanzadas del mundo en su tiempo.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Tenochtitlán',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Texcoco',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Tlaxcala',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Tula',
            'is_correct' => false,
        ]);
        
        // Pregunta 4
        $question = Question::create([
            'question_text' => '¿En qué año se promulgó la Constitución Política de México que sigue vigente actualmente?',
            'explanation' => 'La Constitución Política de los Estados Unidos Mexicanos actual fue promulgada el 5 de febrero de 1917, como resultado de la Revolución Mexicana. Aunque ha tenido numerosas reformas, sigue siendo el documento fundamental del Estado mexicano.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1917',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1857',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1910',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '1921',
            'is_correct' => false,
        ]);
        
        // Pregunta 5
        $question = Question::create([
            'question_text' => '¿Cuál de las siguientes civilizaciones NO es mesoamericana?',
            'explanation' => 'La civilización Inca no es mesoamericana sino andina. Se desarrolló en la región de los Andes en Sudamérica (principalmente Perú actual), mientras que las civilizaciones mesoamericanas se ubicaron en México y Centroamérica.',
            'difficulty_level' => 'hard',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Inca',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Maya',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Olmeca',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Zapoteca',
            'is_correct' => false,
        ]);
    }
    
    private function createAdditionalChemistryQuestions()
    {
        $course = Course::where('name', 'Química')->first();
        
        if (!$course) return;
        
        // Pregunta 1
        $question = Question::create([
            'question_text' => '¿Cuál es el número atómico del carbono?',
            'explanation' => 'El número atómico del carbono es 6, lo que significa que tiene 6 protones en su núcleo.',
            'difficulty_level' => 'easy',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '6',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '12',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '8',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => '4',
            'is_correct' => false,
        ]);
        
        // Pregunta 2
        $question = Question::create([
            'question_text' => '¿Cuál de las siguientes sustancias es una base según la teoría de Arrhenius?',
            'explanation' => 'Según la teoría de Arrhenius, una base es una sustancia que libera iones hidroxilo (OH-) en solución acuosa. El hidróxido de sodio (NaOH) se disocia en Na+ y OH- en agua.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'NaOH',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'HCl',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'CO2',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'H2SO4',
            'is_correct' => false,
        ]);
        
        // Pregunta 3
        $question = Question::create([
            'question_text' => '¿Cuál es la fórmula química del ácido sulfúrico?',
            'explanation' => 'El ácido sulfúrico tiene la fórmula química H2SO4. Es un ácido mineral fuerte y uno de los productos químicos industriales más importantes.',
            'difficulty_level' => 'easy',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'H2SO4',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'HCl',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'HNO3',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'H3PO4',
            'is_correct' => false,
        ]);
        
        // Pregunta 4
        $question = Question::create([
            'question_text' => '¿Cuál de los siguientes elementos es un gas noble?',
            'explanation' => 'El neón (Ne) es un gas noble, perteneciente al grupo 18 de la tabla periódica. Los gases nobles se caracterizan por tener su último nivel de energía completo, lo que los hace muy estables y poco reactivos.',
            'difficulty_level' => 'medium',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Ne',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Na',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'N',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Ni',
            'is_correct' => false,
        ]);
        
        // Pregunta 5
        $question = Question::create([
            'question_text' => '¿Qué ley establece que "la energía no se crea ni se destruye, solo se transforma"?',
            'explanation' => 'La Ley de Conservación de la Energía, también conocida como Primera Ley de la Termodinámica, establece que la energía no puede crearse ni destruirse, solo puede transformarse de una forma a otra o transferirse de un cuerpo a otro.',
            'difficulty_level' => 'hard',
            'course_id' => $course->id,
            'question_type' => 'multiple_choice',
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Ley de Conservación de la Energía',
            'is_correct' => true,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Ley de Boyle-Mariotte',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Ley de Charles',
            'is_correct' => false,
        ]);
        
        QuestionOption::create([
            'question_id' => $question->id,
            'option_text' => 'Ley de Avogadro',
            'is_correct' => false,
        ]);
    }
}