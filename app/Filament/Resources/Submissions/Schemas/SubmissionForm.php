<?php

namespace App\Filament\Resources\Submissions\Schemas;

use App\Enums\SubmissionStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('assessment_id')
                    ->relationship('assessment', 'title')
                    ->searchable()
                    ->required(),
                Select::make('student_id')
                    ->relationship(
                        name: 'student',
                        titleAttribute: 'id',
                        modifyQueryUsing: fn ($query) => $query->with('user'),
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->user?->username} ({$record->user?->email})")
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return \App\Models\Student::query()
                            ->whereHas('user', function ($query) use ($search) {
                                $query->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->orWhere('username', 'like', "%{$search}%");
                            })
                            ->with('user')
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn ($student) => [
                                $student->id => "{$student->user?->username} ({$student->user?->email})",
                            ]);
                    })
                    ->disabled()
                    ->default(null),
                TextInput::make('assessment_score')
                    ->label('Main Assessment Score')
                    ->required()
                    ->numeric(),
                TextInput::make('score')
                    ->label('Student Score')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options([
                        SubmissionStatus::PROGRESS->value => 'In Progress',
                        SubmissionStatus::SUBMITTED->value => 'Submitted',
                        SubmissionStatus::GRADED->value => 'Graded',
                    ])
                    ->required(),
                DateTimePicker::make('started_at')
                    ->required(),
                DateTimePicker::make('submitted_at'),
            ]);
    }
}
