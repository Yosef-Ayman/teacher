<?php

namespace App\Actions\Assessments;

use App\Enums\SubmissionStatus;
use App\Models\Answer;
use App\Models\Submission;
use Illuminate\Support\Facades\DB;

class SubmitAssessmentAction
{
    public function execute($assessment, $submission, $validated): Submission
    {
        return DB::transaction(function () use ($assessment, $submission, $validated) {
            $submission = Submission::query()
                ->whereKey($submission->id)
                ->lockForUpdate()
                ->first();

            if ($submission->status !== SubmissionStatus::PROGRESS->value) {
                abort(409, 'This submission has already been finalized.');
            }

            $answers_by_question = collect($validated['answers'] ?? [])->keyBy('question_id');

            $total_score = 0;
            $has_ungraded = false;

            foreach ($assessment->questions as $question) {

                $answer = $answers_by_question->get($question->id);

                $selected_option = null;

                if ($answer && ! empty($answer['option_id'])) {
                    $selected_option = $question->options
                        ->firstWhere('id', $answer['option_id']);

                    abort_unless(
                        $selected_option,
                        422,
                        'Invalid option.'
                    );
                }

                $answer_text = $answer['answer_text'] ?? null;

                if ($selected_option) {
                    $is_correct = (bool) $selected_option->is_correct;
                    $earned_points = $is_correct
                        ? ($question->points ?? 0)
                        : 0;
                } elseif (filled($answer_text)) {
                    $is_correct = null;
                    $earned_points = null;
                    $has_ungraded = true;
                } else {
                    $is_correct = false;
                    $earned_points = 0;
                }

                Answer::updateOrCreate(
                    [
                        'submission_id' => $submission->id,
                        'question_id' => $question->id,
                    ],
                    [
                        'question_type' => $question->type,
                        'question_points' => $question->points,
                        'option_id' => $selected_option?->id,
                        'answer_text' => $answer_text,
                        'is_correct' => $is_correct,
                        'earned_points' => $earned_points,
                    ]
                );

                $total_score += $earned_points ?? 0;
            }

            $submission->update([
                'score' => $total_score,
                'status' => SubmissionStatus::SUBMITTED->value,
                'submitted_at' => now(),
            ]);

            return $submission;
        });
    }
}
