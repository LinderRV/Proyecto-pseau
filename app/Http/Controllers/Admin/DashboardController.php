<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Question;
use App\Models\User;
use App\Models\University;
use App\Models\Career;
use App\Models\ExamResult;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get counts for dashboard stats
        $stats = [
            'users' => User::count(),
            'courses' => Course::count(),
            'questions' => Question::count(),
            'universities' => University::count(),
            'careers' => Career::count(),
            'examResults' => ExamResult::count(),
        ];
        
        // Get recent users
        $recentUsers = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }
}