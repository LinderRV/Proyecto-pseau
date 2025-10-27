<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ExamResult;
use App\Models\Question;
use App\Models\University;
use App\Traits\HandlesUserProgress;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExamController extends Controller
{
    use HandlesUserProgress;
    /**
     * Show the exam start page.
     */
    public function index()
    {
        $universities = University::all();
        
        // Get courses
        $courses = Course::all();
        
        // Define difficulty levels using a mapping from database values to display values
        $difficultyMap = [
            'easy' => 'Básico',
            'medium' => 'Intermedio',
            'hard' => 'Avanzado'
        ];
        
        // Get distinct difficulty levels from the questions table
        $difficultyLevels = array_values($difficultyMap);
        
        // Define question counts available
        $questionCounts = [10, 20, 30, 40, 50];
            
        return view('exams.index', compact('universities', 'courses', 'difficultyLevels', 'questionCounts'));
    }
    
    /**
     * Generate and start a new exam.
     */
    public function start(Request $request)
    {
        $validated = $request->validate([
            'university_id' => 'nullable|exists:universities,id',
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id',
            'question_count' => 'required|integer|min:5|max:50',
            'difficulty' => 'required|in:Básico,Intermedio,Avanzado,Todos',
        ]);
        
        // Get questions based on filters
        $query = Question::query();
        
        // Filter by university if selected
        if (!empty($validated['university_id'])) {
            $query->where('university_id', $validated['university_id']);
        }
        
        // Filter by courses
        $query->whereIn('course_id', $validated['course_ids']);
        
        // Filter by difficulty
        if ($validated['difficulty'] != 'Todos') {
            // Mapear los valores en español a inglés
            $difficultyMap = [
                'Básico' => 'easy',
                'Intermedio' => 'medium',
                'Avanzado' => 'hard'
            ];
            $query->where('difficulty_level', $difficultyMap[$validated['difficulty']] ?? $validated['difficulty']);
        }
        
        // Verificar primero si hay suficientes preguntas disponibles
        $availableCount = $query->count();
        
        if ($availableCount < 5) {
            return redirect()->back()
                ->with('error', 'No hay suficientes preguntas disponibles con los criterios seleccionados. Por favor, modifica tu selección.');
        }
        
        // Ajustar el número de preguntas si es mayor que lo disponible
        $questionCount = min($availableCount, $validated['question_count']);
        
        // Get random questions up to the requested count
        $questions = $query->inRandomOrder()
            ->limit($questionCount)
            ->with(['options', 'course'])
            ->get();
        
        // Preparar datos del examen
        $examData = [
            'questions' => $questions->toArray(),
            'start_time' => now()->toDateTimeString(),
            'university_id' => $validated['university_id'] ?? null,
            'courses' => Course::whereIn('id', $validated['course_ids'])->pluck('name')->toArray(),
            'current_question' => 0
        ];

        // Guardar el progreso
        $this->saveProgress('exam', $examData);

        // Guardar en sesión para acceso rápido
        session(['current_exam' => $examData]);
        
        return redirect()->route('exams.take');
    }
    
    /**
     * Show the exam questions.
     */
    public function take()
    {
        // Intentar recuperar de la sesión primero (más rápido)
        $examData = session('current_exam');
        
        // Si no está en sesión, intentar recuperar de la base de datos
        if (!$examData) {
            $progress = $this->getProgress('exam');
            
            if ($progress) {
                $examData = $progress->current_state;
                session(['current_exam' => $examData]);
            } else {
                return redirect()->route('exams.index')
                    ->with('error', 'No hay un examen en progreso. Por favor, inicia un nuevo examen.');
            }
        }
        
        // Convertir las preguntas a colección de objetos (stdClass) si vienen de la base de datos
        // Esto permite acceder a propiedades con -> en las vistas (por ejemplo $question->course->name)
        $questions = collect($examData['questions'])->map(function ($q) {
            return json_decode(json_encode($q));
        });
        
        // Convertir start_time a Carbon para que la vista pueda usar métodos de fecha
        $start_time = isset($examData['start_time']) ? Carbon::parse($examData['start_time']) : now();

        return view('exams.take', [
            'questions' => $questions,
            'start_time' => $start_time,
        ]);
    }
    
    /**
     * Submit and grade the exam.
     */
    public function submit(Request $request)
    {
        // Intentar recuperar de la sesión primero
        $examData = session('current_exam');
        
        // Si no está en sesión, intentar recuperar de la base de datos
        if (!$examData) {
            $progress = $this->getProgress('exam');
            if ($progress) {
                $examData = $progress->current_state;
                session(['current_exam' => $examData]);
            } else {
                return redirect()->route('exams.index')
                    ->with('error', 'No hay un examen en progreso. Por favor, inicia un nuevo examen.');
            }
        }
        
        $answers = $request->input('answers', []);

        // Convertir preguntas a objetos para procesar (asegurar compatibilidad si vienen como arrays)
        $questions = collect($examData['questions'])->map(function ($q) {
            return json_decode(json_encode($q));
        });

        // Guardar las respuestas en el progreso
        $this->saveProgress('exam', $examData, $answers);

        $results = [
            'total_questions' => $questions->count(),
            'correct_answers' => 0,
            'incorrect_answers' => 0,
            'unanswered' => 0,
            'score' => 0,
            'time_taken' => abs(now()->diffInMinutes(Carbon::parse($examData['start_time']))),
            'questions' => []
        ];

        foreach ($questions as $question) {
            // $question is a stdClass (from stored array) or an Eloquent model; normalize access
            $q = is_array($question) ? json_decode(json_encode($question)) : $question;

            // Find correct option text and options list
            $correctOptionText = null;
            $options = [];
            if (isset($q->options) && is_array($q->options) || is_object($q->options)) {
                foreach ($q->options as $opt) {
                    $optObj = is_array($opt) ? json_decode(json_encode($opt)) : $opt;
                    $options[] = $optObj;
                    if (!empty($optObj->is_correct)) {
                        $correctOptionText = $optObj->option_text;
                    }
                }
            }

            // build embed URL if possible
            $videoUrl = $q->video_url ?? ($q->videoUrl ?? null);
            $youtubeId = null;
            if (!empty($videoUrl)) {
                preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/', $videoUrl, $m);
                if (isset($m[1])) {
                    $youtubeId = $m[1];
                }
            }

            $questionResult = [
                'id' => $q->id,
                'question_text' => $q->question_text ?? null,
                'correct_option' => $correctOptionText,
                'selected_option' => null,
                'is_correct' => false,
                'explanation' => $q->explanation ?? null,
                'image' => $q->image ?? null,
                'course_id' => $q->course_id ?? ($q->course->id ?? null),
                'course_name' => $q->course->name ?? ($q->course['name'] ?? null),
                'difficulty_level' => $q->difficulty_level ?? null,
                'video_url' => $videoUrl,
                'video_embed_url' => $youtubeId ? ('https://www.youtube.com/embed/' . $youtubeId) : null,
            ];

            if (isset($answers[$q->id])) {
                $selectedId = $answers[$q->id];
                $selectedOption = null;
                foreach ($options as $opt) {
                    if (($opt->id ?? null) == $selectedId) {
                        $selectedOption = $opt;
                        break;
                    }
                }

                $questionResult['selected_option'] = $selectedOption ? ($selectedOption->option_text ?? null) : null;
                $questionResult['is_correct'] = $selectedOption && !empty($selectedOption->is_correct);

                if ($questionResult['is_correct']) {
                    $results['correct_answers']++;
                } else {
                    $results['incorrect_answers']++;
                }
            } else {
                $results['unanswered']++;
            }

            $results['questions'][] = $questionResult;
        }

        // Calculate score as percentage (avoid division by zero)
        $results['score'] = $results['total_questions'] > 0 ? ($results['correct_answers'] / $results['total_questions']) * 100 : 0;
        
        // Store results in session
        session(['exam_results' => $results]);
        
        // Save results to database
        $examResult = ExamResult::create([
            'user_id' => auth()->id(),
            'university_id' => $examData['university_id'],
            'total_questions' => $results['total_questions'],
            'correct_answers' => $results['correct_answers'],
            'incorrect_answers' => $results['incorrect_answers'],
            'unanswered' => $results['unanswered'],
            'score' => $results['score'],
            'time_taken' => $results['time_taken'],
            'question_details' => json_encode($results['questions']),
        ]);
        
        // Clear the current exam
        session()->forget('current_exam');
        
        return redirect()->route('exams.results');
    }
    
    /**
     * Show exam results.
     */
    public function results()
    {
        $results = session('exam_results');
        
        if (!$results) {
            return redirect()->route('exams.index')
                ->with('error', 'No hay resultados de examen disponibles. Por favor, inicia un nuevo examen.');
        }
        
        return view('exams.results', ['results' => $results]);
    }
    
    /**
     * Show exam history for the current user.
     */
    public function history()
    {
        // Get all exam results for the current user with pagination
        $examResults = auth()->user()->examResults()
            ->with('university')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Get all universities for filtering
        $universities = \App\Models\University::all();
        
        // Calculate statistics
        $allExams = auth()->user()->examResults;
        $lastMonthExams = auth()->user()->examResults()
            ->where('created_at', '>=', now()->subMonth())
            ->get();
        $previousMonthExams = auth()->user()->examResults()
            ->where('created_at', '>=', now()->subMonths(2))
            ->where('created_at', '<', now()->subMonth())
            ->get();
        
        // Calculate trend - difference between last month and previous month averages
        $lastMonthAvg = $lastMonthExams->count() > 0 ? $lastMonthExams->avg('score') : 0;
        $previousMonthAvg = $previousMonthExams->count() > 0 ? $previousMonthExams->avg('score') : 0;
        $trend = $previousMonthAvg > 0 ? (($lastMonthAvg - $previousMonthAvg) / $previousMonthAvg) * 100 : 0;
        
        // Get best and worst subjects based on questions in exam_results
        $subjectPerformance = [];
        foreach ($allExams as $exam) {
            $questionDetails = json_decode($exam->question_details, true);
            foreach ($questionDetails as $question) {
                $courseId = isset($question['course_id']) ? $question['course_id'] : null;
                $courseName = isset($question['course_name']) ? $question['course_name'] : 'General';
                
                if (!isset($subjectPerformance[$courseName])) {
                    $subjectPerformance[$courseName] = [
                        'correct' => 0,
                        'total' => 0,
                        'score' => 0
                    ];
                }
                
                $subjectPerformance[$courseName]['total']++;
                if (isset($question['is_correct']) && $question['is_correct']) {
                    $subjectPerformance[$courseName]['correct']++;
                }
            }
        }
        
        // Calculate scores for each subject
        foreach ($subjectPerformance as $name => &$data) {
            if ($data['total'] > 0) {
                $data['score'] = ($data['correct'] / $data['total']) * 100;
            }
        }
        
        // Sort by score
        uasort($subjectPerformance, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        // Prepare stats for the view
        $stats = [
            'total_exams' => $allExams->count(),
            'avg_score' => $allExams->count() > 0 ? $allExams->avg('score') : 0,
            'trend' => $trend,
            'best_subjects' => [],
            'worst_subjects' => []
        ];
        
        // Get best and worst subjects
        $subjectNames = array_keys($subjectPerformance);
        foreach (array_slice($subjectNames, 0, 3) as $name) {
            $stats['best_subjects'][] = [
                'name' => $name,
                'score' => $subjectPerformance[$name]['score']
            ];
        }
        
        foreach (array_slice($subjectNames, -3) as $name) {
            $stats['worst_subjects'][] = [
                'name' => $name,
                'score' => $subjectPerformance[$name]['score']
            ];
        }
        
        return view('exams.history', compact('examResults', 'universities', 'stats'));
    }
}
