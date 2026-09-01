<?php

namespace App\Filament\Resources\Submissions\Pages;

use App\Filament\Resources\Assessments\AssessmentResource;
use App\Filament\Resources\Submissions\SubmissionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSubmission extends EditRecord
{
    protected static string $resource = SubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    public function getBreadcrumbs(): array
    {
        $submission = $this->getRecord();
        $assessment = $submission->assessment;

        $breadcrumbs = [];

        $breadcrumbs[SubmissionResource::getUrl('index')] = 'Submissions';

        if ($assessment) {
            $breadcrumbs[AssessmentResource::getUrl('edit', ['record' => $assessment])] = $assessment->title;
        }

        $breadcrumbs[SubmissionResource::getUrl('edit', ['record' => $submission])] = "Edit Submission";

        return $breadcrumbs;
    }
}
