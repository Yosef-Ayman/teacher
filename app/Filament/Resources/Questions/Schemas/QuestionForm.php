<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Enums\QuestionType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('assessment_id')
                    ->relationship('assessment', 'title')
                    ->searchable()
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Select::make('type')
                    ->options([
                        QuestionType::MCQ->value => 'MCQ',
                        QuestionType::SHORT_ANSWER->value => 'Short Answer',
                        QuestionType::TRUE_FALSE->value => 'True / False',
                    ])
                    ->required()
                    ->default(QuestionType::MCQ->value),
                TextInput::make('points')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
