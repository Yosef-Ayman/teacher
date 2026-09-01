<?php

namespace App\Filament\Resources\Markdowns\Schemas;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MarkdownForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                MarkdownEditor::make('body')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('hidden')
                    ->disabled()
                    ->required(),
                TextInput::make('created_by')
                    ->label('Created By')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($record) => $record?->creator?->username),
            ]);
    }
}
