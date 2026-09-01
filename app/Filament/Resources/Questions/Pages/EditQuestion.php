<?php

namespace App\Filament\Resources\Questions\Pages;

use App\Filament\Resources\Assessments\AssessmentResource;
use App\Filament\Resources\Questions\QuestionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQuestion extends EditRecord
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    public function getBreadcrumbs(): array
    {
        $question = $this->getRecord();
        $assessment = $question->assessment;

        $breadcrumbs = [];

        $breadcrumbs[AssessmentResource::getUrl('index')] = 'Assessments';

        if ($assessment) {
            $breadcrumbs[AssessmentResource::getUrl('edit', ['record' => $assessment])] = $assessment->title;
        }

        $breadcrumbs[QuestionResource::getUrl('edit', ['record' => $question])] = "Edit Question";

        return $breadcrumbs;
    }
}
