<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ExamResult;
use App\Models\PracticeResult;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard with progress and historical data.
     */
    public function index()
    {
        $user = Auth::user()->load(['university', 'career']);
        
        // Get the user's exam results
        $examResults = $user->examResults()
            ->with('university')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get the user's practice results grouped by course
        $practiceResultsBySubject = [];
        $courses = Course::all();
        
        foreach ($courses as $course) {
            $results = $user->practiceResults()
                ->where('course_id', $course->id)
                ->orderBy('created_at', 'desc')
                ->get();
                
            if ($results->count() > 0) {
                // Calculate average score for this subject
                $avgScore = $results->avg('score');
                
                $practiceResultsBySubject[$course->id] = [
                    'course' => $course,
                    'results' => $results->take(5), // Only take the 5 most recent
                    'avg_score' => $avgScore,
                    'progress' => min(round($avgScore), 100), // Use score as progress percentage
                    'total_practices' => $results->count(),
                ];
            } else {
                $practiceResultsBySubject[$course->id] = [
                    'course' => $course,
                    'results' => collect(),
                    'avg_score' => 0,
                    'progress' => 0,
                    'total_practices' => 0,
                ];
            }
        }
        
        // Get overall exam statistics
        $examStats = [
            'total' => $user->examResults()->count(),
            'avg_score' => $user->examResults()->avg('score') ?? 0,
            'highest_score' => $user->examResults()->max('score') ?? 0,
        ];
        
        return view('dashboard', compact(
            'examResults', 
            'practiceResultsBySubject', 
            'examStats',
            'user'
        ));
    }
}