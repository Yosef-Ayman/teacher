<?php

namespace App\Filament\Resources\Lessons\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->required(),
                TextInput::make('title')
                    ->placeholder('Lesson Title')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Set $set) {
                        if ($operation === 'create') {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->dehydrated(),
                TextInput::make('slug')
                    ->placeholder('lesson-title')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->suffixAction(
                        Action::make('regenerateSlug')
                            ->icon('heroicon-m-arrow-path')
                            ->visible(fn (string $operation): bool => $operation === 'create')
                            ->action(function (Get $get, Set $set) {
                                $set('slug', Str::slug($get('title')));
                            }),
                    ),

                TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('hidden')
                    ->required()
                    ->default(false),
                TextInput::make('created_by')
                    ->label('Created By')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($record) => $record?->creator?->username),
            ]);
    }
}
