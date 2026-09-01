<?php

namespace App\Filament\Resources\Assessments\Schemas;

use App\Enums\AssessmentType;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->required(),
                Select::make('lesson_id')
                    ->relationship('lesson', 'title')
                    ->searchable()
                    ->default(null),
                TextInput::make('title')
                    ->placeholder('Assessment Title')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Set $set) {
                        if ($operation === 'create') {
                            $set('slug', Str::slug($state));
                        }
                    }),
                TextInput::make('slug')
                    ->placeholder('assessment-title')
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
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('type')
                    ->options([
                        AssessmentType::QUIZ->value => 'Quiz',
                        AssessmentType::HOMEWORK->value => 'Homework',
                        AssessmentType::SHORT_QUIZ->value => 'Short Quiz',
                        AssessmentType::EXAM->value => 'Exam',
                    ])
                    ->required()
                    ->default(AssessmentType::QUIZ->value),
                TextInput::make('duration_minutes')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(1),
                TextInput::make('total_points')
                    ->required()
                    ->minValue(0)
                    ->numeric()
                    ->default(1),
                TextInput::make('passing_score')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(1),

                TextInput::make('attempts')
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->default(1),
                DateTimePicker::make('starts_at'),
                DateTimePicker::make('ends_at'),

                Toggle::make('hidden')
                    ->required(),
                Toggle::make('show_result')
                    ->required()
                    ->default(true),
                Toggle::make('show_correct_answers')
                    ->required()
                    ->default(true),

                Toggle::make('shuffle_questions')
                    ->disabled()
                    ->required(),
                Toggle::make('shuffle_answers')
                    ->disabled()
                    ->required(),
            ]);
    }
}
