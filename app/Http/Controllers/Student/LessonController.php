<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class LessonController extends Controller
{
    public function index(Request $request, string $slug)
    {
        $course = Course::where('slug', $slug)
            ->active()
            ->firstOrFail();

        if ($request->user()->isEnrolledToCourse($course->id)) {
            $lesson = Lesson::where('course_id', $course->id)->active()->ordered()->first();

            if ($lesson) {
                return Redirect::route('lessons.show', ['course_slug' => $course->slug, 'lesson_slug' => $lesson->slug]);
            } else {
                return Redirect::route('courses.show', ['slug' => $course->slug])->with('toast', [
                    'type' => 'info',
                    'message' => 'Course is empty right now, please visit lessons later.',
                ]);
            }
        } else {
            return Redirect::route('courses.show', ['slug' => $course->slug])->with('toast', [
                'type' => 'error',
                'message' => 'You are not enrolled in this course.',
            ]);
        }
    }

    public function show(Request $request, string $course_slug, string $lesson_slug)
    {
        $course = Course::where('slug', $course_slug)
            ->active()
            ->with('assessments')
            ->firstOrFail();

        if (! $request->user()->isEnrolledToCourse($course->id)) {
            return Redirect::route('courses.show', ['slug' => $course->slug])->with('toast', [
                'type' => 'error',
                'message' => 'You are not enrolled in this course.',
            ]);
        } else {
            $lesson = $course->lessons()
                ->where('slug', $lesson_slug)
                ->active()
                ->first();

            if (! $lesson) {
                return Redirect::route('courses.show', ['slug' => $course->slug])->with('toast', [
                    'type' => 'error',
                    'message' => 'Lesson not found.',
                ]);
            }

            $lessons = $course->lessons()
                ->active()
                ->ordered()
                ->get();

            $chapters = $lesson->chapters()
                ->with(['video', 'markdown', 'assessment'])
                ->ordered()
                ->get()
                ->map(function ($chapter) {
                    if ($chapter->video) {
                        $chapter->video->setAttribute('embed_url', $chapter->video->getEmbedUrl());
                    }

                    return $chapter;
                });
            return Inertia::render('lessons/show', compact('course', 'lessons', 'lesson', 'chapters'));
            // return response()->json(compact('course', 'lessons', 'lesson', 'chapters'));
            // return view('student.lessons.show', compact('course', 'lessons', 'lesson', 'chapters'));
        }
    }
}
