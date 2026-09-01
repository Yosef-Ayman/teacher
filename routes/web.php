<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Student\{ CourseController, LessonController, MyCourseController, MySubmissionController };
use App\Http\Controllers\Student\Assessment\{ AssessmentController, SubmissionController };
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('student.home');
})->name('home');

Route::get('about', function () {
    return view('student.about');
})->name('about');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');

    Route::redirect('dashboard', '/courses')->name('dashboard.redirect');

    Route::get('my/courses', [MyCourseController::class, 'index'])
        ->name('courses.index.my');

    Route::post('course/buy/{slug}', [CourseController::class, 'buy'])
        ->name('courses.buy');

    Route::get('course/{slug}/lessons', [LessonController::class, 'index'])
        ->name('lessons.index');

    Route::get('course/{course_slug}/lesson/{lesson_slug}', [LessonController::class, 'show'])
        ->name('lessons.show');

    Route::get('course/{course_slug}/assessment/{assessment_slug}', [AssessmentController::class, 'show'])
        ->name('assessments.show');

    Route::get('course/{slug}/assessments', [AssessmentController::class, 'index'])
        ->name('assessments.index');

    Route::get('course/{course_slug}/assessment/{assessment_slug}/submissions', [SubmissionController::class, 'index'])
        ->name('submissions.index');

    Route::post('course/{course_slug}/assessment/{assessment_slug}/submissions', [SubmissionController::class, 'start'])
        ->name('submissions.start')
        ->middleware('throttle:start-assessment');

    Route::get('my/submissions', [MySubmissionController::class, 'index'])
        ->name('submissions.index.my');

    Route::get('submission/{submission}', [SubmissionController::class, 'show'])
        ->name('submissions.show');

    Route::post('submission/{submission}/submit', [SubmissionController::class, 'submit'])
        ->name('submissions.submit')
        ->middleware('throttle:submit-assessment');

    Route::get('submission/{submission}/result', [SubmissionController::class, 'result'])
        ->name('submissions.result');
});

require __DIR__.'/settings.php';

Route::get('courses', [CourseController::class, 'index'])
    ->name('courses.index');

Route::get('course/{slug}', [CourseController::class, 'show'])
    ->name('courses.show');

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->middleware('throttle:social-redirect')
    ->name('social.redirect');

Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->middleware('throttle:social-callback')
    ->name('social.callback');
