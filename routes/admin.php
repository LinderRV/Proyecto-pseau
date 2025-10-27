<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

// Apply middleware to all admin routes
Route::middleware(['auth', 'verified', AdminMiddleware::class])->group(function () {
    // Admin dashboard
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

// Course management
Route::get('/admin/courses', [CourseController::class, 'index'])->name('admin.courses.index');
Route::get('/admin/courses/create', [CourseController::class, 'create'])->name('admin.courses.create');
Route::post('/admin/courses', [CourseController::class, 'store'])->name('admin.courses.store');
Route::get('/admin/courses/{course}/edit', [CourseController::class, 'edit'])->name('admin.courses.edit');
Route::put('/admin/courses/{course}', [CourseController::class, 'update'])->name('admin.courses.update');
Route::delete('/admin/courses/{course}', [CourseController::class, 'destroy'])->name('admin.courses.destroy');

// Question management
Route::get('/admin/courses/{course}/questions', [CourseController::class, 'questions'])->name('admin.courses.questions');
Route::get('/admin/courses/{course}/questions/create', [CourseController::class, 'createQuestion'])->name('admin.questions.create');
Route::post('/admin/courses/{course}/questions', [CourseController::class, 'storeQuestion'])->name('admin.questions.store');
Route::get('/admin/questions/{question}/edit', [CourseController::class, 'editQuestion'])->name('admin.questions.edit');
Route::put('/admin/questions/{question}', [CourseController::class, 'updateQuestion'])->name('admin.questions.update');
Route::delete('/admin/questions/{question}', [CourseController::class, 'destroyQuestion'])->name('admin.questions.destroy');

// Analytics
Route::get('/admin/analytics/course-difficulty', [AnalyticsController::class, 'courseDifficulty'])->name('admin.analytics.course-difficulty');

// Universities CRUD
Route::get('/admin/universities', [\App\Http\Controllers\Admin\UniversityController::class, 'index'])->name('admin.universities.index');
Route::get('/admin/universities/create', [\App\Http\Controllers\Admin\UniversityController::class, 'create'])->name('admin.universities.create');
Route::post('/admin/universities', [\App\Http\Controllers\Admin\UniversityController::class, 'store'])->name('admin.universities.store');
Route::get('/admin/universities/{university}/edit', [\App\Http\Controllers\Admin\UniversityController::class, 'edit'])->name('admin.universities.edit');
Route::put('/admin/universities/{university}', [\App\Http\Controllers\Admin\UniversityController::class, 'update'])->name('admin.universities.update');
Route::delete('/admin/universities/{university}', [\App\Http\Controllers\Admin\UniversityController::class, 'destroy'])->name('admin.universities.destroy');

// Careers CRUD
Route::get('/admin/careers', [\App\Http\Controllers\Admin\CareerController::class, 'index'])->name('admin.careers.index');
Route::get('/admin/careers/create', [\App\Http\Controllers\Admin\CareerController::class, 'create'])->name('admin.careers.create');
Route::post('/admin/careers', [\App\Http\Controllers\Admin\CareerController::class, 'store'])->name('admin.careers.store');
Route::get('/admin/careers/{career}/edit', [\App\Http\Controllers\Admin\CareerController::class, 'edit'])->name('admin.careers.edit');
Route::put('/admin/careers/{career}', [\App\Http\Controllers\Admin\CareerController::class, 'update'])->name('admin.careers.update');
Route::delete('/admin/careers/{career}', [\App\Http\Controllers\Admin\CareerController::class, 'destroy'])->name('admin.careers.destroy');
});