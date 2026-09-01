<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::active()
            ->latest()
            ->paginate(9);

        if (Auth::check()) {
            return Inertia::render('courses/index', compact('courses'));
        } else {
            return view('student.courses.index', compact('courses'));
        }
        // return response()->json(compact('courses'));
    }

    public function show(Request $request, string $slug)
    {
        $course = Course::where('slug', $slug)
            ->active()
            ->with('assessments')
            ->firstOrFail();

        $is_enrolled = false;

        if (Auth::check()) {
            $is_enrolled = $request->user()->isEnrolledToCourse($course->id);
        }

        $course->load([
            'lessons' => fn ($q) => $q
                ->active()
                ->ordered()
                ->with([
                    'chapters' => fn ($q) => $q->ordered(),
                ]),
        ]);

        $course->lessons->each(function ($lesson) {
            $lesson->chapters->each(function ($chapter) {
                $type = $chapter->type();

                $chapter->setAttribute('type', $type);

                $chapter->setAttribute(
                    'title',
                    match ($type) {
                        'video' => $chapter->video?->title,
                        'markdown' => $chapter->markdown?->title,
                        'assessment' => $chapter->assessment?->title,
                        default => null,
                    }
                );

                $chapter->makeHidden([
                    'video_id',
                    'markdown_id',
                    'assessment_id',
                    'video',
                    'markdown',
                    'assessment',
                    'created_by',
                    'created_at',
                    'updated_at',
                ]);
            });
        });

        $enrollment = Enrollment::forUserAndCourse($course->id)
            ->notExpired()
            ->latest('created_at')
            ->first();

        if (Auth::check()) {
            return Inertia::render('courses/show', compact('course', 'enrollment'));
        } else {
        return view('student.courses.show', compact('course', 'enrollment'));
        }
        // return response()->json(compact('course', 'enrollment'));
    }

    public function buy(Request $request, string $slug)
    {
        $course = Course::where('slug', $slug)
            ->active()
            ->first();
        $type = '';
        $message = '';

        if (! $course) {
            return Redirect::route('courses.index')->with('toast', [
                'type' => 'info',
                'message' => $course->title . ' - Not Found',
            ]);
        }
        if (! $request->user()->isEnrolledToCourse($course->id)) {
            DB::transaction(function () use ($course, & $type, & $message) {
                $student = Student::whereKey(auth()->id())
                    ->lockForUpdate()
                    ->firstOrFail();

                if (auth()->user()->isEnrolledToCourse($course->id)) {
                    $type = 'info';
                    $message = 'You already enrolled this course.';

                    return;
                }

                if ($student->balance < $course->price) {
                    $type = 'error';
                    $message = 'Your balance is not enough to buy this course, Your Balance is: $'.$student->balance.' and course price is: $'.$course->price;

                    return;
                }

                $student->decrement('balance', $course->price);

                Enrollment::insert([
                    'user_id' => $student->user_id,
                    'course_id' => $course->id,
                    'expires_at' => is_null($course->access_duration_days) ? null : now()->addDays($course->access_duration_days),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $type = 'success';
                $message = 'You have successfully enrolled to '.$course->title.'.';
            });
        }

        return Redirect::route('courses.show', ['slug' => $course->slug])->with('toast', [
            'type' => $type,
            'message' => $message,
        ]);
    }
}
