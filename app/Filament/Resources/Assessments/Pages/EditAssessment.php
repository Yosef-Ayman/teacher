<?php

namespace App\Filament\Resources\Assessments\Pages;

use App\Enums\SubmissionStatus;
use App\Filament\Resources\Assessments\AssessmentResource;
use App\Filament\Resources\Submissions\SubmissionResource;
use App\Models\Assessment;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAssessment extends EditRecord
{
    protected static string $resource = AssessmentResource::class;

    protected function getHeaderActions(): array
    {
        $assessment = $this->getRecord();

        return [
            Action::make('viewAllSubmissions')
                ->label('All Submissions')
                ->color('gray')
                ->icon('heroicon-m-square-3-stack-3d')
                ->url(fn () => SubmissionResource::getUrl('index') . "?filters[assessment_id][value]=" . $assessment->id),

            Action::make('viewInProgressSubmissions')
                ->label('In Progress')
                ->color('info')
                ->icon('heroicon-m-arrow-path')
                ->url(fn () => SubmissionResource::getUrl('index') . "?filters[assessment_id][value]=" . $assessment->id . "&filters[status][value]=" . SubmissionStatus::PROGRESS->value),

            Action::make('viewSubmittedSubmissions')
                ->label('Submitted')
                ->color('warning')
                ->icon('heroicon-m-document-text')
                ->url(fn () => SubmissionResource::getUrl('index') . "?filters[assessment_id][value]=" . $assessment->id . "&filters[status][value]=" . SubmissionStatus::SUBMITTED->value),

            Action::make('viewGradedSubmissions')
                ->label('Graded')
                ->color('success')
                ->icon('heroicon-m-check-badge')
                ->url(fn () => SubmissionResource::getUrl('index') . "?filters[assessment_id][value]=" . $assessment->id . "&filters[status][value]=" . SubmissionStatus::GRADED->value),

            DeleteAction::make(),
        ];
    }
}
