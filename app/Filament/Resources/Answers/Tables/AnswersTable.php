<?php

namespace App\Filament\Resources\Answers\Tables;

use App\Enums\QuestionType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AnswersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('submission.id')
                    ->searchable(),
                TextColumn::make('question.title')
                    ->searchable(),
                TextColumn::make('question_type')
                    ->searchable(),
                TextColumn::make('option.title')
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
                SelectFilter::make('submission_id')
                    ->label('Submission')
                    ->relationship('submission', 'id')
                    ->searchable(),
                SelectFilter::make('question_type')
                    ->label('Question Type')
                    ->options([
                        QuestionType::MCQ->value => 'MCQ',
                        QuestionType::SHORT_ANSWER->value => 'Short Answer',
                        QuestionType::TRUE_FALSE->value => 'True / False',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
