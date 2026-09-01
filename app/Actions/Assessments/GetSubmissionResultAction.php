<?php

namespace App\Actions\Assessments;

use App\Enums\SubmissionStatus;
use App\Models\Submission;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GetSubmissionResultAction
{
    public function execute(Submission $submission): array
    {
        $this->validateStatus($submission);

        $submission->load([
            'assessment.course',
            'assessment.questions',
            'answers.question' => fn ($q) => $q->ordered(),
            'answers.question.options' => fn ($q) => $q->ordered(),
            'answers.option'
        ]);

        $this->validateRevealTime($submission->assessment);

        [$calculatedScore, $pendingManualGrading] = $this->calculateScoreAndGradingState($submission);

        $this->syncScoreIfChanged($submission, $calculatedScore);

        return $this->buildPayload($submission, $calculatedScore, $pendingManualGrading);
    }
    /**
     * Helper Functions.
     */
    private function validateStatus(Submission $submission): void
    {
        if ($submission->status === SubmissionStatus::PROGRESS->value) {
            throw new HttpException(403, 'This submission has not been completed yet.');
        }
    }
    private function validateRevealTime($assessment): void
    {
        if (! $assessment->show_result) {
            abort(
                403,
                "The Result will doesn't available right now."
            );
        }
    }
    private function calculateScoreAndGradingState(Submission $submission): array
    {
        $calculatedScore = 0;
        $pendingManualGrading = false;

        foreach ($submission->answers as $answer) {
            if (is_null($answer->is_correct) && is_null($answer->earned_points)) {
                $pendingManualGrading = true;
            }

            $calculatedScore += ($answer->earned_points ?? 0);
        }

        return [$calculatedScore, $pendingManualGrading];
    }
    private function syncScoreIfChanged(Submission $submission, float $calculatedScore): void
    {
        if ((float) $submission->score !== $calculatedScore) {
            $submission->update(['score' => $calculatedScore]);
        }
    }
    private function buildPayload(Submission $submission, float $calculatedScore, bool $pendingManualGrading): array
    {
        $passed = is_null($submission->assessment->passing_score) ? null : $calculatedScore >= $submission->assessment->passing_score;

        if (! $submission->assessment->show_correct_answers) {
            $submission->answers->each(function ($answer) {
                if ($answer->question) {
                    $answer->question->makeHidden('options');
                }
            });
        }

        return [
            'submission' => $submission,
            'calculated_score' => $calculatedScore,
            'total_points' => $submission->assessment->total_points,
            'passing_score' => $submission->assessment->passing_score,
            'passed' => $passed,
            'pending_manual_grading' => $pendingManualGrading,
        ];
    }
}
