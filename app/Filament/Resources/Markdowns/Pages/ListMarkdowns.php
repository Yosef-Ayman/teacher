<?php

namespace App\Filament\Resources\Markdowns\Pages;

use App\Filament\Resources\Markdowns\MarkdownResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMarkdowns extends ListRecords
{
    protected static string $resource = MarkdownResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
