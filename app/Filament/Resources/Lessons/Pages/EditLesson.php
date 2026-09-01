<?php

namespace App\Filament\Resources\Lessons\Pages;

use App\Filament\Resources\Courses\CourseResource;
use App\Filament\Resources\Lessons\LessonResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLesson extends EditRecord
{
    protected static string $resource = LessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    public function getBreadcrumbs(): array
    {
        $lesson = $this->getRecord();
        $course = $lesson->course;

        $breadcrumbs = [];

        $breadcrumbs[CourseResource::getUrl('index')] = 'Courses';

        if ($course) {
            $breadcrumbs[CourseResource::getUrl('edit', ['record' => $course])] = $course->title;
        }

        $breadcrumbs[LessonResource::getUrl('edit', ['record' => $lesson])] = "Edit Lesson";

        return $breadcrumbs;
    }

}
