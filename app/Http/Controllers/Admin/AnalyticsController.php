<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display course difficulty analytics.
     *
     * @return \Illuminate\View\View
     */
    public function courseDifficulty()
    {
        // Get courses with their questions and success rates
        $courses = Course::withCount('questions')
            ->with(['questions' => function($query) {
                $query->withCount([
                    'practiceResults',
                    'practiceResults as correct_count' => function($query) {
                        $query->where('is_correct', true);
                    }
                ]);
            }])
            ->get()
            ->map(function($course) {
                // Calculate success rate per course
                $totalAnswers = $course->questions->sum('practice_results_count');
                $correctAnswers = $course->questions->sum('correct_count');
                
                $successRate = $totalAnswers > 0 
                    ? round(($correctAnswers / $totalAnswers) * 100, 1)
                    : 0;
                
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'question_count' => $course->questions_count,
                    'total_answers' => $totalAnswers,
                    'correct_answers' => $correctAnswers,
                    'success_rate' => $successRate,
                    'difficulty' => $this->getDifficultyLevel($successRate)
                ];
            });

        // Prepare courseDifficulty data for the view
        $courseDifficulty = $courses->map(function($c) {
            return (object) [
                'id' => $c['id'],
                'name' => $c['name'],
                'attempt_count' => $c['total_answers'],
                'avg_score' => $c['success_rate'],
                'avg_time' => 0, // placeholder, will calculate below
            ];
        });

        // Calculate average time per course from practice_results time_taken
        foreach ($courseDifficulty as $cd) {
            $avgTime = DB::table('practice_results')
                ->join('questions', 'practice_results.question_id', '=', 'questions.id')
                ->where('questions.course_id', $cd->id)
                ->avg('practice_results.time_taken');

            $cd->avg_time = $avgTime ? round($avgTime / 60, 1) : 0; // convert seconds to minutes
        }

        // Fetch 10 most difficult questions (lowest success rate)
        $difficultQuestions = DB::table('questions')
            ->leftJoin('practice_results', 'questions.id', '=', 'practice_results.question_id')
            ->join('courses', 'questions.course_id', '=', 'courses.id')
            ->selectRaw('questions.id, questions.question_text, courses.name as course_name, COUNT(practice_results.id) as attempt_count, SUM(CASE WHEN practice_results.is_correct = 1 THEN 1 ELSE 0 END) as correct_count, (CASE WHEN COUNT(practice_results.id) = 0 THEN 0 ELSE (SUM(CASE WHEN practice_results.is_correct = 1 THEN 1 ELSE 0 END) / COUNT(practice_results.id) * 100) END) as success_rate')
            ->groupBy('questions.id', 'questions.question_text', 'courses.name')
            ->orderByRaw('success_rate ASC')
            ->limit(10)
            ->get();

        return view('admin.analytics.course-difficulty', compact('courseDifficulty', 'difficultQuestions'));
    }

    /**
     * Determine difficulty level based on success rate
     *
     * @param float $successRate
     * @return string
     */
    private function getDifficultyLevel($successRate)
    {
        if ($successRate >= 80) {
            return 'Easy';
        } elseif ($successRate >= 60) {
            return 'Moderate';
        } elseif ($successRate >= 40) {
            return 'Challenging';
        } else {
            return 'Difficult';
        }
    }
}