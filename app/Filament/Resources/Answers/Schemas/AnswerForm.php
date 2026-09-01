<?php

namespace App\Filament\Resources\Answers\Schemas;

use App\Enums\QuestionType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AnswerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('submission_id')
                    ->relationship('submission', 'id')
                    ->disabled()
                    ->required(),
                Select::make('question_id')
                    ->relationship('question', 'title')
                    ->disabled()
                    ->required(),
                Select::make('question_type')
                    ->options([
                        QuestionType::MCQ->value => 'MCQ',
                        QuestionType::SHORT_ANSWER->value => 'Short Answer',
                        QuestionType::TRUE_FALSE->value => 'True / False',
                    ])
                    ->required(),
                Select::make('option_id')
                    ->relationship('option', 'title')
                    ->default(null),
                Textarea::make('answer_text')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('is_correct'),
                TextInput::make('earned_points')
                    ->numeric()
                    ->default(null),
                TextInput::make('question_points')
                    ->numeric()
                    ->required(),
            ]);
    }
}
