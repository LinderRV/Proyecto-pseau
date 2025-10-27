<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Check if user needs onboarding, otherwise show dashboard
    Route::get('/dashboard', function () {
        if (auth()->user()->first_login) {
            return redirect()->route('onboarding.show');
        }
        return app(\App\Http\Controllers\DashboardController::class)->index();
    })->name('dashboard');
    
    // Onboarding routes
    Route::get('/onboarding', [App\Http\Controllers\OnboardingController::class, 'show'])
        ->name('onboarding.show');
    Route::post('/onboarding', [App\Http\Controllers\OnboardingController::class, 'store'])
        ->name('onboarding.store');
    Route::get('/onboarding/recommended-courses', [App\Http\Controllers\OnboardingController::class, 'getRecommendedCourses'])
        ->name('onboarding.courses');
    
    // Exam routes
    Route::get('/exams', [App\Http\Controllers\ExamController::class, 'index'])->name('exams.index');
    Route::post('/exams/start', [App\Http\Controllers\ExamController::class, 'start'])->name('exams.start');
    Route::get('/exams/take', [App\Http\Controllers\ExamController::class, 'take'])->name('exams.take');
    Route::post('/exams/submit', [App\Http\Controllers\ExamController::class, 'submit'])->name('exams.submit');
    Route::get('/exams/results', [App\Http\Controllers\ExamController::class, 'results'])->name('exams.results');
    Route::get('/exams/history', [App\Http\Controllers\ExamController::class, 'history'])->name('exams.history');
    
    // Practice routes
    Route::get('/practice', [App\Http\Controllers\PracticeController::class, 'index'])->name('practice.index');
    Route::get('/practice/question', [App\Http\Controllers\PracticeController::class, 'question'])->name('practice.question');
    Route::post('/practice/submit', [App\Http\Controllers\PracticeController::class, 'submitAnswer'])->name('practice.submit');
    Route::get('/practice/results', [App\Http\Controllers\PracticeController::class, 'results'])->name('practice.results');
    Route::get('/practice/history', [App\Http\Controllers\PracticeController::class, 'history'])->name('practice.history');
    Route::get('/practice/stats', [App\Http\Controllers\PracticeController::class, 'stats'])->name('practice.stats');
    Route::get('/practice/stats/data', [App\Http\Controllers\PracticeController::class, 'statsData'])->name('practice.stats.data');
    Route::get('/practice/detail/{id}', [App\Http\Controllers\PracticeController::class, 'detail'])->name('practice.detail');
    Route::get('/practice/{course}', [App\Http\Controllers\PracticeController::class, 'course'])->name('practice.course');
    Route::post('/practice/{course}/start', [App\Http\Controllers\PracticeController::class, 'start'])->name('practice.start');
    // AI chat proxy for Gemini (used by results pages)
    Route::post('/ai/gemini/chat', [App\Http\Controllers\Ai\GeminiController::class, 'chat'])->name('ai.gemini.chat');
    Route::get('/ai/gemini/history', [App\Http\Controllers\Ai\GeminiController::class, 'history'])->name('ai.gemini.history');
    Route::post('/ai/gemini/clear', [App\Http\Controllers\Ai\GeminiController::class, 'clear'])->name('ai.gemini.clear');
    
    // Learning routes
    Route::get('/learning', [App\Http\Controllers\LearningController::class, 'index'])->name('learning.index');
    Route::get('/learning/{course}', [App\Http\Controllers\LearningController::class, 'course'])->name('learning.course');
    Route::get('/learning/{course}/problem/{problem}', [App\Http\Controllers\LearningController::class, 'problem'])->name('learning.problem');
    Route::post('/learning/{course}/problem/{problem}/note', [App\Http\Controllers\LearningController::class, 'saveNote'])->name('learning.saveNote');
    Route::delete('/learning/note/{note}', [App\Http\Controllers\LearningController::class, 'deleteNote'])->name('learning.deleteNote');
    
    // Blog routes
    Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [App\Http\Controllers\BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [App\Http\Controllers\BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog/{slug}/edit', [App\Http\Controllers\BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'destroy'])->name('blog.destroy');
    Route::get('/my-posts', [App\Http\Controllers\BlogController::class, 'myPosts'])->name('blog.my-posts');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes protected by admin middleware
Route::middleware(['auth', 'verified', AdminMiddleware::class])->group(function () {
    require __DIR__.'/admin.php';
});

require __DIR__.'/auth.php';

// Rutas para la autenticación con Google
Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleAuthController::class, 'redirectToGoogle'])
    ->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleAuthController::class, 'handleGoogleCallback']);
