<?php

namespace App\Filament\Resources\Enrollments\Pages;

use App\Filament\Resources\Enrollments\EnrollmentResource;
use App\Models\Course;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $course = Course::find($data['course_id']);
        if ($course && $course->access_duration_days) {
            $data['expires_at'] = Carbon::now()->addDays($course->access_duration_days);
        } else {
            $data['expires_at'] = null;
        }
        $data['created_by'] = auth()->id();
        return $data;
    }
}
