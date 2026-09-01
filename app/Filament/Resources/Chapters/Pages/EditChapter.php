<?php

namespace App\Filament\Resources\Chapters\Pages;

use App\Filament\Resources\Assessments\AssessmentResource;
use App\Filament\Resources\Chapters\ChapterResource;
use App\Filament\Resources\Courses\CourseResource;
use App\Filament\Resources\Lessons\LessonResource;
use App\Filament\Resources\Questions\QuestionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChapter extends EditRecord
{
    protected static string $resource = ChapterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    public function getBreadcrumbs(): array
    {
        $chapter = $this->getRecord();
        $lesson = $chapter->assessment;

        $breadcrumbs = [];

        $breadcrumbs[CourseResource::getUrl('index')] = 'Courses';

        if ($lesson) {
            $breadcrumbs[LessonResource::getUrl('edit', ['record' => $lesson])] = $lesson->title;
        }

        $breadcrumbs[QuestionResource::getUrl('edit', ['record' => $chapter])] = "Edit Chapter";

        return $breadcrumbs;
    }
}
