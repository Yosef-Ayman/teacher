<?php

namespace App\Http\Controllers\Student\Assessment;

use App\Actions\Assessments\GetSubmissionResultAction;
use App\Actions\Assessments\StartAssessmentSubmissionAction;
use App\Actions\Assessments\SubmitAssessmentAction;
use App\Enums\SubmissionStatus;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\Student;
use App\Models\Submission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SubmissionController extends Controller
{
    public function index(Request $request, string $course_slug, string $assessment_slug)
    {
        $student = $request->user()->student;

        $course = Course::where('slug', $course_slug)
            ->active()
            ->firstOrFail();

        $enrollment = $this->authorizeUserEnrollment($request->user(), $course);

        $assessment = $course->assessments()
            ->where('slug', $assessment_slug)
            ->active()
            ->inNow()
            ->firstOrFail();

        $submissions = $assessment->submissions()
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        return Inertia::render('assessments/submissions/index', compact('submissions', 'assessment', 'course'));
        // return response()->json(compact('submissions', 'assessment', 'course'));
    }
    public function start(Request $request, string $course_slug, string $assessment_slug, StartAssessmentSubmissionAction $action)
    {
        $student = $request->user()->student;

        $course = Course::where('slug', $course_slug)
            ->active()
            ->firstOrFail();

        $enrollment = $this->authorizeUserEnrollment($request->user(), $course);

        $assessment = $course->assessments()
            ->where('slug', $assessment_slug)
            ->active()
            ->inNow()
            ->firstOrFail();

        $submission = $action->execute($assessment, $student);

        return Redirect::route('submissions.show', compact('submission'));
    }
    public function show(Request $request, Submission $submission)
    {
        $student = $request->user()->student;

        $this->authorizeSubmissionOwner($student, $submission);

        if ($submission->status !== SubmissionStatus::PROGRESS->value) {
            return Redirect::route('submissions.result', $submission);
        }

        $this->ensureNotExpired($submission);

        $submission->load([
            'assessment.questions' => function ($query) {
                $query->ordered();
            },
            'assessment.questions.options' => function ($query) {
                $query
                    ->select([
                        'id',
                        'question_id',
                        'title',
                        'position',
                    ])
                    ->ordered();
            },
            'answers',
        ]);

        $deadline = $this->deadlineFor($submission, $submission->assessment);

        return Inertia::render('assessments/submissions/show', compact('submission', 'deadline'));
        // return response()->json(compact('submission', 'deadline'));
        // return view('student.quiz.show', compact('submission', 'deadline'));
    }
    public function submit(Request $request, Submission $submission, SubmitAssessmentAction $action)
    {
        $student = $request->user()->student;

        $this->authorizeSubmissionOwner($student, $submission);

        abort_if(
            $submission->status !== SubmissionStatus::PROGRESS->value,
            409,
            'This submission has already been finalized.'
        );

        $this->ensureNotExpired($submission);

        $assessment = $submission->assessment()->with('questions.options')->first();

        $validated = $request->validate([
            'answers' => ['present', 'array'],
            'answers.*.question_id' => ['required', 'integer', 'exists:questions,id'],
            'answers.*.option_id' => ['nullable', 'integer', 'exists:options,id'],
            'answers.*.answer_text' => ['nullable', 'string'],
        ]);

        $questions = $assessment->questions->keyBy('id');

        foreach ($validated['answers'] as $answer) {
            abort_unless(
                $questions->has($answer['question_id']),
                422,
                'Invalid question.'
            );
        }

        $submission = $action->execute($assessment, $submission, $validated);

        return Redirect::route('submissions.result', $submission);
    }
    public function result(Request $request, Submission $submission, GetSubmissionResultAction $action)
    {
        $student = $request->user()->student;

        $this->authorizeSubmissionOwner($student, $submission);

        $payload = $action->execute($submission);

        return Inertia::render('assessments/submissions/result', compact('payload'));
        // return response()->json(compact('payload'));
    }
    /**
     * Helper Functions.
     */
    private function authorizeUserEnrollment(User $user, Course $course): bool
    {
        if (! $user->isEnrolledToCourse($course->id)) {
            abort(to_route('courses.show', ['slug' => $course->slug])->with('toast', [
                'type' => 'error',
                'message' => 'You are not enrolled in this course.',
            ]));
            return false;
        }
        else {
            return true;
        }
    }
    private function authorizeSubmissionOwner(Student $student, Submission $submission): void
    {
        abort_unless($submission->student_id === $student->id, 403);
    }
    private function ensureNotExpired(Submission $submission): void
    {
        $deadline = $this->deadlineFor($submission, $submission->assessment);

        if ($deadline && Carbon::parse($deadline)->addMinutes(2)->lt(now()) && $submission->status === SubmissionStatus::PROGRESS->value) {
            $submission->update([
                'status' => SubmissionStatus::SUBMITTED->value,
                'submitted_at' => now(),
            ]);

            abort(403, 'Time limit reached.');
        }
    }
    private function deadlineFor(Submission $submission, Assessment $assessment): Carbon
    {
        $candidates = collect([$assessment->ends_at]);

        if ($assessment->duration_minutes) {
            $candidates->push(Carbon::parse($submission->started_at)?->copy()->addMinutes($assessment->duration_minutes));
        }

        return Carbon::parse($candidates->filter()->sort()->first());
    }
}
