<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\PracticeResult;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PracticeController extends Controller
{
    /**
     * Display the practice course selection page.
     */
    public function index()
    {
        // Get courses
        $courses = Course::all();
            
        return view('practice.index', compact('courses'));
    }
    
    /**
     * Show course details and practice options.
     */
    public function course($id)
    {
        $course = Course::findOrFail($id);
        $questionCount = Question::where('course_id', $id)->count();
        
        // Get available question counts in multiples of 5, up to 30 but not more than available questions
        $availableQuestionCounts = [];
        for ($i = 5; $i <= min(30, $questionCount); $i += 5) {
            $availableQuestionCounts[] = $i;
        }
        
        // Define difficulty levels using a mapping from database values to display values
        $difficultyMap = [
            'easy' => 'Básico',
            'medium' => 'Intermedio',
            'hard' => 'Avanzado'
        ];
        
        // Simplificado a valores fijos para mantener consistencia
        $difficultyLevels = array_values($difficultyMap);
            
        return view('practice.course', compact('course', 'questionCount', 'difficultyLevels', 'availableQuestionCounts'));
    }
    
    /**
     * Start a practice session for a course.
     */
    public function start(Request $request, $courseId)
    {
        $validated = $request->validate([
            'difficulty' => 'required|in:Básico,Intermedio,Avanzado,Todos',
            'question_count' => 'required|integer|min:5|max:30',
        ]);
        
        $course = Course::findOrFail($courseId);
        
        // Build the query for questions
        $query = Question::where('course_id', $courseId);
        
        if ($validated['difficulty'] != 'Todos') {
            // Mapear los valores en español a inglés
            $difficultyMap = [
                'Básico' => 'easy',
                'Intermedio' => 'medium',
                'Avanzado' => 'hard'
            ];
            $query->where('difficulty_level', $difficultyMap[$validated['difficulty']] ?? $validated['difficulty']);
        }
        
        // Get random questions with their options
        $questions = $query->inRandomOrder()
            ->limit($validated['question_count'])
            ->get();
            
        // Load and randomize options for each question
        $questions->each(function ($question) {
            $question->load('options');
        });
        
        if ($questions->count() < 1) {
            return redirect()->back()
                ->with('error', 'No hay preguntas disponibles para los criterios seleccionados.');
        }
        
        // Store practice data in session
        session(['current_practice' => [
            'course' => $course,
            'questions' => $questions,
            'current_question' => 0,
            'correct_answers' => 0,
            'start_time' => now(),
        ]]);
        
        return redirect()->route('practice.question');
    }
    
    /**
     * Show current practice question.
     */
    public function question()
    {
        try {
            $practiceData = session('current_practice');
            
            Log::info('Loading practice question', [
                'session_exists' => isset($practiceData),
                'session_data' => $practiceData ?? 'No data'
            ]);
            
            if (!$practiceData) {
                return redirect()->route('practice.index')
                    ->with('error', 'No hay una sesión de práctica activa.');
            }
            
            $currentQuestionIndex = $practiceData['current_question'];
            $totalQuestions = count($practiceData['questions']);
            
            // Verify that the current question index is valid
            if ($currentQuestionIndex >= $totalQuestions) {
                \Log::warning('Question index out of bounds', [
                    'current_index' => $currentQuestionIndex, 
                    'total_questions' => $totalQuestions
                ]);
                return redirect()->route('practice.results');
            }
            
            $question = $practiceData['questions'][$currentQuestionIndex];
            
            return view('practice.question', [
                'question' => $question,
                'current' => $currentQuestionIndex + 1,
                'total' => $totalQuestions,
                'course' => $practiceData['course'],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error showing practice question', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('practice.index')
                ->with('error', 'Ocurrió un error al cargar la pregunta. Por favor inicia una nueva sesión de práctica.');
        }
    }
    
    /**
     * Submit an answer for the current question.
     */
    public function submitAnswer(Request $request)
    {
        // Log the request data for debugging
        \Log::info('Practice submission received', [
            'option_id' => $request->option_id,
            'all_data' => $request->all()
        ]);
        
        try {
            $request->validate([
                'option_id' => 'required|exists:question_options,id',
            ]);
            
            $practiceData = session('current_practice');
            
            if (!$practiceData) {
                \Log::warning('No active practice session found');
                return redirect()->route('practice.index')
                    ->with('error', 'No hay una sesión de práctica activa.');
            }
        
        $currentQuestionIndex = $practiceData['current_question'];
        $question = $practiceData['questions'][$currentQuestionIndex];
        
        // Verify if the selected option belongs to the current question
        $selectedOptionId = $request->option_id;
        $questionOptionIds = $question->options->pluck('id')->toArray();
        
        \Log::info('Validating selected option', [
            'selected_option_id' => $selectedOptionId,
            'question_options' => $questionOptionIds,
            'question_id' => $question->id
        ]);
        
        if (!in_array($selectedOptionId, $questionOptionIds)) {
            \Log::warning('Selected option does not belong to current question', [
                'selected_option' => $selectedOptionId,
                'question_id' => $question->id
            ]);
            return redirect()->back()->with('error', 'La opción seleccionada no corresponde a la pregunta actual.');
        }
        
        // Check if the answer is correct
        $selectedOption = $question->options->where('id', $request->option_id)->first();
        $isCorrect = $selectedOption && $selectedOption->is_correct;
        
        // Update session data
        if ($isCorrect) {
            $practiceData['correct_answers']++;
        }
        
        // Store the answer and additional information
        $practiceData['questions'][$currentQuestionIndex]['selected_option'] = $selectedOption;
        $practiceData['questions'][$currentQuestionIndex]['is_correct'] = $isCorrect;
        $practiceData['questions'][$currentQuestionIndex]['topic'] = $question->topic ?? 'General';
        $practiceData['questions'][$currentQuestionIndex]['difficulty_level'] = $question->difficulty_level;
        
        // Move to next question or finish
        $currentQuestionIndex++;
        $practiceData['current_question'] = $currentQuestionIndex;
        
        session(['current_practice' => $practiceData]);
        
        // Check if we've reached the end of the questions
        if ($currentQuestionIndex < count($practiceData['questions'])) {
            // Go directly to the next question without showing answer
            \Log::info('Moving to next question', ['index' => $currentQuestionIndex]);
            return redirect()->route('practice.question');
        } else {
            // All questions answered, go to results
            \Log::info('Practice completed, showing results');
            return redirect()->route('practice.results');
        }
        } catch (\Exception $e) {
            \Log::error('Error processing practice submission', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Ocurrió un error al procesar tu respuesta. Por favor intenta de nuevo.');
        }
    }
    
    /**
     * Show practice session results.
     */
    public function results()
    {
        $practiceData = session('current_practice');
        
        if (!$practiceData) {
            return redirect()->route('practice.index')
                ->with('error', 'No hay una sesión de práctica activa o resultados disponibles.');
        }
        
        $timeTaken = abs(round(now()->floatDiffInMinutes($practiceData['start_time']), 1));
        $totalQuestions = count($practiceData['questions']);
        $correctAnswers = $practiceData['correct_answers'];
        $score = ($correctAnswers / $totalQuestions) * 100;
        
        $results = [
            'course' => $practiceData['course'],
            'totalQuestions' => $totalQuestions,
            'correctAnswers' => $correctAnswers,
            'score' => $score,
            'timeTaken' => $timeTaken,
            'questions' => $practiceData['questions'],
        ];
        
        // Guardar resultados en la base de datos
        PracticeResult::create([
            'user_id' => auth()->id(),
            'course_id' => $practiceData['course']->id,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'score' => $score,
            'time_taken' => $timeTaken,
            'question_details' => json_encode($practiceData['questions']),
        ]);
        
        // Clear the current practice
        session()->forget('current_practice');
        
        return view('practice.results', ['results' => $results]);
    }
    
    /**
     * Show practice history for the current user.
     */
    public function history(Request $request)
    {
        // Build query for practice results
        $query = auth()->user()->practiceResults()
            ->with('course')
            ->orderBy('created_at', 'desc');
        
        // Apply course filter if specified
        if ($request->has('course_id') && $request->course_id) {
            $query->where('course_id', $request->course_id);
        }
        
        // Apply date filter if specified
        if ($request->has('date_range') && $request->date_range) {
            $days = (int) $request->date_range;
            $query->where('created_at', '>=', now()->subDays($days));
        }
        
        // Get paginated results
        $practiceResults = $query->paginate(10)->appends($request->query());
            
        // Get all courses for filtering
        $courses = Course::all();
        
        // Calculate progress by course
        $courseProgress = [];
        foreach ($courses as $course) {
            $results = auth()->user()->practiceResults()
                ->where('course_id', $course->id)
                ->get();
                
            if ($results->count() > 0) {
                $courseProgress[] = [
                    'course' => $course,
                    'avg_score' => $results->avg('score'),
                    'total_practices' => $results->count(),
                ];
            } else {
                $courseProgress[] = [
                    'course' => $course,
                    'avg_score' => 0,
                    'total_practices' => 0,
                ];
            }
        }
        
        // Get topic performance from question details
        $topicPerformance = [];
        $allPractices = auth()->user()->practiceResults;
        
        foreach ($allPractices as $practice) {
            // Acceder directamente a question_details sin usar json_decode ya que el accessor del modelo lo maneja
            $questionDetails = $practice->question_details;
            
            // Verificar si es array o si necesita ser decodificado
            if (is_string($questionDetails)) {
                $questionDetails = json_decode($questionDetails, true);
            }
            
            // Si no es un array o está vacío, continuar con la siguiente práctica
            if (!is_array($questionDetails) || empty($questionDetails)) {
                continue;
            }
            
            foreach ($questionDetails as $question) {
                // Asegurar que question sea un array
                if (!is_array($question)) {
                    continue;
                }
                
                $topic = $question['topic'] ?? 'General';
                
                if (!isset($topicPerformance[$topic])) {
                    $topicPerformance[$topic] = [
                        'name' => $topic,
                        'correct' => 0,
                        'total' => 0,
                        'score' => 0
                    ];
                }
                
                $topicPerformance[$topic]['total']++;
                if (isset($question['is_correct']) && $question['is_correct']) {
                    $topicPerformance[$topic]['correct']++;
                }
            }
        }
        
        // Calculate scores for topics
        foreach ($topicPerformance as $topic => &$data) {
            if ($data['total'] > 0) {
                $data['score'] = ($data['correct'] / $data['total']) * 100;
            }
        }
        
        // Sort topics by performance
        uasort($topicPerformance, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        // Get best and worst topics
        $bestTopics = array_slice($topicPerformance, 0, 5);
        $worstTopics = array_slice(array_reverse($topicPerformance), 0, 5);
        
        return view('practice.history', compact(
            'practiceResults', 
            'courses', 
            'courseProgress',
            'bestTopics',
            'worstTopics'
        ));
    }

    /**
     * Show statistics page with charts for user's practice and exams
     */
    public function stats(Request $request)
    {
        $user = auth()->user();

        // Filters from request
        $filterCourse = $request->query('course_id');
        $dateRange = (int) $request->query('date_range', 0); // days
        $order = $request->query('order', 'none'); // 'asc'|'desc'|'none'

        // Base query for practice results
        $practiceQuery = $user->practiceResults()->with('course');
        if ($filterCourse) {
            $practiceQuery->where('course_id', $filterCourse);
        }
        if ($dateRange > 0) {
            $practiceQuery->where('created_at', '>=', now()->subDays($dateRange));
        }

        $allPracticeResults = $practiceQuery->get();

        // Compute per-course averages (only courses the user has practiced)
        $courseGroups = $allPracticeResults->groupBy('course_id');
        $courseAverages = [];
        foreach ($courseGroups as $courseId => $group) {
            $courseModel = $group->first()->course;
            $avg = $group->avg('score') ?: 0;
            $courseAverages[] = [
                'course_id' => $courseId,
                'course' => $courseModel ? $courseModel->name : 'Curso',
                'avg_score' => round($avg, 1),
                'total_practices' => $group->count(),
            ];
        }

        // Optional ordering
        if ($order === 'asc') {
            usort($courseAverages, fn($a, $b) => $a['avg_score'] <=> $b['avg_score']);
        } elseif ($order === 'desc') {
            usort($courseAverages, fn($a, $b) => $b['avg_score'] <=> $a['avg_score']);
        }

        // Overall average
        $overallAvg = $allPracticeResults->count() ? round($allPracticeResults->avg('score'), 1) : 0;

        // Per-course history (for drilldown): dates and scores from practice results
        $perCourseHistory = [];
        foreach ($courseGroups as $courseId => $group) {
            $perCourseHistory[$courseId] = $group->map(function($r) {
                return [
                    'date' => $r->created_at->format('Y-m-d'),
                    'score' => (float) $r->score,
                    'id' => $r->id,
                ];
            })->values();
        }

        // Exam scores, include course name (try to extract from question_details if possible)
        $examQuery = $user->examResults()->orderBy('created_at', 'desc');
        if ($dateRange > 0) {
            $examQuery->where('created_at', '>=', now()->subDays($dateRange));
        }
        $examResults = $examQuery->limit(50)->get();
        $examScores = $examResults->map(function($e) {
            $courseName = null;
            // Try to extract a course_name from question_details first question
            $details = $e->question_details;
            if (is_array($details) && count($details) > 0) {
                $first = reset($details);
                $courseName = $first['course_name'] ?? $first['course'] ?? null;
            }
            return [
                'id' => $e->id,
                'date' => $e->created_at->format('Y-m-d'),
                'label' => $courseName ? sprintf('%s (Exam %d)', $courseName, $e->id) : sprintf('Examen %d', $e->id),
                'score' => (float) $e->score,
                'created_at' => $e->created_at->toIso8601String(),
            ];
        })->values()->toArray();

        // Courses available for filter
        $courses = Course::whereIn('id', $user->practiceResults()->pluck('course_id')->unique()->toArray())->get();

        return view('practice.stats', compact('courseAverages', 'examScores', 'overallAvg', 'perCourseHistory', 'courses'));
    }

    /**
     * Return JSON payload for stats (used by AJAX)
     */
    public function statsData(Request $request)
    {
        // Reuse the stats logic but accept filters via query
        $user = auth()->user();
        $filterCourse = $request->query('course_id');
        $dateRange = (int) $request->query('date_range', 0);

        $practiceQuery = $user->practiceResults()->with('course');
        if ($filterCourse) $practiceQuery->where('course_id', $filterCourse);
        if ($dateRange > 0) $practiceQuery->where('created_at', '>=', now()->subDays($dateRange));

        $allPracticeResults = $practiceQuery->get();
        $courseGroups = $allPracticeResults->groupBy('course_id');
        $courseAverages = [];
        foreach ($courseGroups as $courseId => $group) {
            $courseModel = $group->first()->course;
            $avg = $group->avg('score') ?: 0;
            $courseAverages[] = ['course_id' => $courseId, 'course' => $courseModel ? $courseModel->name : 'Curso', 'avg_score' => round($avg,1), 'total_practices' => $group->count()];
        }

        $overallAvg = $allPracticeResults->count() ? round($allPracticeResults->avg('score'),1) : 0;

        $perCourseHistory = [];
        foreach ($courseGroups as $courseId => $group) {
            $perCourseHistory[$courseId] = $group->map(function($r) {
                return ['date' => $r->created_at->format('Y-m-d'), 'score' => (float)$r->score, 'id' => $r->id];
            })->values();
        }

        $examQuery = $user->examResults()->orderBy('created_at','desc');
        if ($dateRange > 0) $examQuery->where('created_at','>=', now()->subDays($dateRange));
        $examResults = $examQuery->limit(50)->get();
        $examScores = $examResults->map(function($e){ $details = $e->question_details; $courseName = null; if(is_array($details) && count($details)>0){ $first = reset($details); $courseName = $first['course_name'] ?? $first['course'] ?? null; } return ['id'=>$e->id,'date'=>$e->created_at->format('Y-m-d'),'label'=>$courseName ? sprintf('%s', $courseName) : sprintf('Examen %d', $e->id),'score'=>(float)$e->score]; })->values()->toArray();

        return response()->json([ 'courseAverages' => $courseAverages, 'examScores' => $examScores, 'overallAvg' => $overallAvg, 'perCourseHistory' => $perCourseHistory ]);
    }
    
    /**
     * Show detailed view of a specific practice result
     */
    public function detail($id)
    {
        $result = PracticeResult::with('course')
            ->where('user_id', auth()->id())
            ->findOrFail($id);
        
        // Obtenemos directamente los detalles de las preguntas de la columna original
        $rawDetails = DB::table('practice_results')
            ->where('id', $id)
            ->value('question_details');
            
        \Log::info('Datos originales de question_details', [
            'raw_type' => gettype($rawDetails),
            'raw_sample' => substr($rawDetails, 0, 100)
        ]);
        
        // Procesamos los detalles de las preguntas manualmente con mejor manejo de errores
        $parsedDetails = [];
        
        if (!empty($rawDetails)) {
            try {
                // Primer intento: si es un array normal
                $decoded = json_decode($rawDetails, true);
                
                // Si el primer intento falla, intentamos con diferentes enfoques
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Eliminar comillas extras si existen
                    if (substr($rawDetails, 0, 2) === '\"' || substr($rawDetails, 0, 1) === '"') {
                        $trimmedDetails = trim($rawDetails, '"');
                        $decoded = json_decode($trimmedDetails, true);
                    }
                    
                    // Si sigue fallando, intentar reemplazar escapes adicionales
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $fixedDetails = str_replace('\\', '', $rawDetails);
                        $decoded = json_decode($fixedDetails, true);
                    }
                    
                    // Si todo falla, intentar acceder directamente a los atributos de la instancia del modelo
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $modelAttribute = $result->getRawOriginal('question_details');
                        if (is_string($modelAttribute)) {
                            $decoded = json_decode($modelAttribute, true);
                        }
                    }
                }
                
                // Verificar si finalmente tenemos datos válidos
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $parsedDetails = $decoded;
                    \Log::info('Decodificación exitosa', [
                        'count' => count($parsedDetails),
                        'first_item_keys' => !empty($parsedDetails) ? array_keys(reset($parsedDetails)) : []
                    ]);
                    
                    // Normalizar la estructura para asegurar coherencia
                    $parsedDetails = $this->normalizeQuestionDetails($parsedDetails);
                } else {
                    \Log::error('Error al decodificar JSON después de múltiples intentos', [
                        'json_error' => json_last_error_msg(),
                        'sample' => substr($rawDetails, 0, 200)
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Excepción procesando detalles de pregunta', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        
        // Asignamos los detalles procesados al resultado
        $result->question_details = $parsedDetails;
        
        return view('practice.detail', compact('result'));
    }
    
    /**
     * Normaliza la estructura de los detalles de preguntas para asegurar coherencia
     * 
     * @param array $details
     * @return array
     */
    protected function normalizeQuestionDetails(array $details)
    {
        $normalized = [];
        
        foreach ($details as $key => $question) {
            // Si es un objeto o un array asociativo
            if (is_array($question) || is_object($question)) {
                $normalizedQuestion = [];
                
                // Convertir a array si es un objeto
                $questionArray = is_object($question) ? (array)$question : $question;
                
                // Copiar propiedades básicas
                $normalizedQuestion['question_text'] = $questionArray['question_text'] ?? '';
                $normalizedQuestion['difficulty_level'] = $questionArray['difficulty_level'] ?? '';
                $normalizedQuestion['topic'] = $questionArray['topic'] ?? 'General';
                $normalizedQuestion['is_correct'] = $questionArray['is_correct'] ?? false;
                
                // Normalizar opciones
                if (isset($questionArray['options']) && (is_array($questionArray['options']) || is_object($questionArray['options']))) {
                    $normalizedQuestion['options'] = [];
                    $options = is_object($questionArray['options']) ? (array)$questionArray['options'] : $questionArray['options'];
                    
                    foreach ($options as $option) {
                        if (is_array($option) || is_object($option)) {
                            $optionArray = is_object($option) ? (array)$option : $option;
                            $normalizedQuestion['options'][] = [
                                'id' => $optionArray['id'] ?? 0,
                                'option_text' => $optionArray['option_text'] ?? '',
                                'is_correct' => $optionArray['is_correct'] ?? false,
                            ];
                        }
                    }
                }
                
                // Normalizar la opción seleccionada
                if (isset($questionArray['selected_option']) && 
                   (is_array($questionArray['selected_option']) || is_object($questionArray['selected_option']))) {
                    $selectedOption = is_object($questionArray['selected_option']) 
                        ? (array)$questionArray['selected_option'] 
                        : $questionArray['selected_option'];
                        
                    $normalizedQuestion['selected_option'] = [
                        'id' => $selectedOption['id'] ?? 0,
                        'option_text' => $selectedOption['option_text'] ?? '',
                        'is_correct' => $selectedOption['is_correct'] ?? false,
                    ];
                }
                
                // Agregar explicación si existe
                if (isset($questionArray['explanation'])) {
                    $normalizedQuestion['explanation'] = $questionArray['explanation'];
                }
                
                $normalized[$key] = $normalizedQuestion;
            }
        }
        
        return $normalized;
    }
}
