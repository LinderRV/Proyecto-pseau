<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Course;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * Show the onboarding form.
     */
    public function show()
    {
        $universities = University::all();
        $careers = Career::all();
        
        return view('onboarding', compact('universities', 'careers'));
    }
    
    /**
     * Process the onboarding form submission.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'career_id' => 'required|exists:careers,id',
        ]);
        
        $user = Auth::user();
        $user->university_id = $validated['university_id'];
        $user->career_id = $validated['career_id'];
        $user->first_login = false;
        $user->save();
        
        return redirect()->route('dashboard');
    }
    
    /**
     * Get recommended courses for a career.
     */
    public function getRecommendedCourses(Request $request)
    {
        $careerId = $request->input('career_id');
        
        if (!$careerId) {
            return response()->json([
                'courses' => [],
            ]);
        }
        
        $career = Career::findOrFail($careerId);
        
        // Get courses with their importance for this career
        $courses = $career->courses()
            ->orderByPivot('importance', 'desc')
            ->get()
            ->map(function($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'description' => $course->description,
                    'icon' => $course->icon,
                    'color' => $course->color,
                    'importance' => $course->pivot->importance,
                ];
            });
        
        return response()->json([
            'courses' => $courses,
        ]);
    }
}
