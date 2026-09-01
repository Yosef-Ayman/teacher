<?php

namespace App\Http\Controllers\Student\Assessment;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    public function index(Request $request, string $slug)
    {
        $course = Course::where('slug', $slug)
            ->active()
            ->firstOrFail();

        if (! $request->user()->isEnrolledToCourse($course->id)) {
            return Redirect::route('courses.show', ['slug' => $course->slug])->with('toast', [
                'type' => 'error',
                'message' => 'You are not enrolled in this course.',
            ]);
        } else {
            $assessments = $course->assessments()
                ->active()
                ->inNow()
                ->withCount('questions')
                ->get();

            return Inertia::render('assessments/index', compact('course', 'assessments'));
            // return response()->json(compact('course', 'assessments'));
        }
    }
    public function show(Request $request, string $course_slug, string $assessment_slug)
    {
        $course = Course::where('slug', $course_slug)
            ->active()
            ->firstOrFail();

        if (! $request->user()->isEnrolledToCourse($course->id)) {
            return Redirect::route('courses.show', ['slug' => $course->slug])->with('toast', [
                'type' => 'error',
                'message' => 'You are not enrolled in this course.',
            ]);
        } else {
            $assessment = $course->assessments()
                ->where('slug', $assessment_slug)
                ->active()
                ->inNow()
                ->withCount('questions')
                ->firstOrFail();

            $attempts_used = $assessment->submissions()
                ->where('student_id', auth()->user()->student->id)
                ->count();

            $last_submission = $assessment?->submissions()
                ->where('student_id', auth()->user()->student->id)
                ->latest()
                ->first();

            return Inertia::render('assessments/show', compact('course', 'assessment', 'attempts_used', 'last_submission'));
            // return response()->json(compact('course', 'assessment', 'attempts_used', 'last_submission'));
            // return view('student.courses.assessments.show', compact('course', 'assessment', 'attempts_used', 'last_submission'));
        }
    }
}
