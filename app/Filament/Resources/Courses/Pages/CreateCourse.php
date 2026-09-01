<?php

namespace App\Filament\Resources\Courses\Pages;

use App\Filament\Resources\Courses\CourseResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;

    protected function afterCreate(): void
    {
        if ($thumbnail = ($this->data['thumbnail'] ?? null)) {
            $path = is_array($thumbnail) ? reset($thumbnail) : $thumbnail;

            if (! $path || ! Storage::disk('public')->exists($path)) {
                \Log::warning('Thumbnail file missing before processing', [
                    'course_id' => $this->record->id,
                    'path' => $thumbnail,
                ]);
                return;
            }

            $this->record->updateThumbnailFromDisk($path);
            Storage::disk('public')->delete($path);
        }
    }
}
