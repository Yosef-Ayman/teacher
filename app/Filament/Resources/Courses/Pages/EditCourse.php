<?php

namespace App\Filament\Resources\Courses\Pages;

use App\Filament\Resources\Courses\CourseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
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
