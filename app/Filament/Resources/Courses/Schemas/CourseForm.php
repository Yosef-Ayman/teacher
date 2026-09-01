<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->placeholder('Course Title')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Set $set) {
                        if ($operation === 'create') {
                            $set('slug', Str::slug($state));
                        }
                    }),
                TextInput::make('slug')
                    ->placeholder('course-title')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->suffixAction(
                        Action::make('regenerateSlug')
                            ->icon('heroicon-m-arrow-path')
                            ->visible(fn (string $operation): bool => $operation === 'create')
                            ->action(function (Get $get, Set $set) {
                                $set('slug', Str::slug($get('title')));
                            }),
                    )
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->dehydrated(),
                TextInput::make('description'),
                TextInput::make('price')
                    ->placeholder('100')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
                TextInput::make('access_duration_days')
                    ->placeholder('7')
                    ->numeric()
                    ->minValue(0),
                DateTimePicker::make('publish_at'),
                Placeholder::make('current_thumbnail')
                    ->hidden(fn (?\App\Models\Course $record) => blank($record?->thumbnail_url))
                    ->content(fn (?\App\Models\Course $record) => new HtmlString(
                        "<img src='{$record->thumbnail_url}' style='max-width:200px;border-radius:8px'>"
                    )),
                FileUpload::make('thumbnail')
                    ->image()
                    ->disk('public')
                    ->directory('tmp')
                    ->dehydrated(false),
                TextInput::make('created_by')
                    ->label('Created By')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($record) => $record?->creator?->username),
            ]);
    }
}
