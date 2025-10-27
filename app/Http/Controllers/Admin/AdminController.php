<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\ExamResult;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'courses' => Course::count(),
            'questions' => Question::count(),
            'examResults' => ExamResult::count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
    
    /**
     * Show course difficulty analytics.
     */
    public function courseDifficulty()
    {
        // Get course difficulty data based on exam results
        $courseDifficulty = DB::table('exam_results')
            ->join('courses', 'exam_results.course_id', '=', 'courses.id')
            ->select(
                'courses.id',
                'courses.name',
                DB::raw('COUNT(*) as attempt_count'),
                DB::raw('AVG(score) as avg_score'),
                DB::raw('AVG(time_spent) as avg_time')
            )
            ->groupBy('courses.id', 'courses.name')
            ->orderBy('avg_score')
            ->get();
            
        // Get the most difficult questions
        $difficultQuestions = DB::table('exam_results')
            ->join('exam_result_questions', 'exam_results.id', '=', 'exam_result_questions.exam_result_id')
            ->join('questions', 'exam_result_questions.question_id', '=', 'questions.id')
            ->join('courses', 'questions.course_id', '=', 'courses.id')
            ->select(
                'questions.id',
                'questions.question_text',
                'courses.name as course_name',
                DB::raw('COUNT(*) as attempt_count'),
                DB::raw('SUM(CASE WHEN exam_result_questions.is_correct = 1 THEN 1 ELSE 0 END) as correct_count'),
                DB::raw('SUM(CASE WHEN exam_result_questions.is_correct = 0 THEN 1 ELSE 0 END) as incorrect_count'),
                DB::raw('SUM(CASE WHEN exam_result_questions.is_correct = 1 THEN 1 ELSE 0 END) / COUNT(*) * 100 as success_rate')
            )
            ->groupBy('questions.id', 'questions.question_text', 'courses.name')
            ->orderBy('success_rate')
            ->limit(10)
            ->get();
            
        return view('admin.analytics.course-difficulty', compact('courseDifficulty', 'difficultQuestions'));
    }
}
