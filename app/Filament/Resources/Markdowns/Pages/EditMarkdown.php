<?php

namespace App\Filament\Resources\Markdowns\Pages;

use App\Filament\Resources\Markdowns\MarkdownResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMarkdown extends EditRecord
{
    protected static string $resource = MarkdownResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
