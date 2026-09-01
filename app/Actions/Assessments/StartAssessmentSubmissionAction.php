<?php

namespace App\Actions\Assessments;

use App\Enums\SubmissionStatus;
use App\Models\Assessment;
use App\Models\Student;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StartAssessmentSubmissionAction
{
    public function execute(Assessment $assessment, Student $student): Submission
    {
        return DB::transaction(function () use ($assessment, $student) {
            $assessment->newQuery()
                ->whereKey($assessment->id)
                ->lockForUpdate()
                ->first();

            $existing = $assessment->submissions()
                ->where('student_id', $student->id)
                ->where('status', SubmissionStatus::PROGRESS->value)
                ->latest('id')
                ->first();

            if ($existing) {
                return $existing;
            }

            $attemptsUsed = $assessment->submissions()
                ->where('student_id', $student->id)
                ->count();

            if ($assessment->attempts <= $attemptsUsed) {
                abort(403, 'You have no attempts remaining.');
            }

            return $assessment->submissions()->create([
                'student_id' => $student->id,
                'assessment_score' => $assessment->total_points,
                'score' => 0,
                'status' => SubmissionStatus::PROGRESS->value,
                'started_at' => now(),
            ]);
        });
    }
}
