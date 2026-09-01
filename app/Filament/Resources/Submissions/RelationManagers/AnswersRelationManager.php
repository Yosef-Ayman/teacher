<?php

namespace App\Filament\Resources\Submissions\RelationManagers;

use App\Enums\QuestionType;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('question_id')
                    ->relationship('question', 'title')
                    ->required(),
                Select::make('question_type')
                    ->options([
                        QuestionType::MCQ->value => 'MCQ',
                        QuestionType::SHORT_ANSWER->value => 'Short Answer',
                        QuestionType::TRUE_FALSE->value => 'True / False',
                    ])
                    ->required(),
                Textarea::make('answer_text')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('is_correct'),
                TextInput::make('earned_points')
                    ->numeric()
                    ->default(null),
                TextInput::make('question_points')
                    ->numeric()
                    ->disabled()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question_id')
            ->columns([
                TextColumn::make('question.title')
                    ->searchable(),
                TextColumn::make('question_type')
                    ->searchable(),
                TextColumn::make('option.title')
                    ->searchable(),
                TextColumn::make('answer_text')
                    ->searchable(),
                IconColumn::make('is_correct')
                    ->boolean(),
                TextColumn::make('earned_points')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('question_points')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('question_type')
                    ->label('Question Type')
                    ->options([
                        QuestionType::MCQ->value => 'MCQ',
                        QuestionType::SHORT_ANSWER->value => 'Short Answer',
                        QuestionType::TRUE_FALSE->value => 'True / False',
                    ]),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
