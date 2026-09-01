<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use App\Models\Course;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->default(null),
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if (!$state) {
                            $set('expires_at', null);
                            return;
                        }

                        $course = Course::find($state);

                        if ($course?->access_duration_days) {
                            $set(
                                'expires_at',
                                now()->addDays($course->access_duration_days)
                            );
                        }
                    })
                    ->default(null),
                DateTimePicker::make('expires_at')
                    ->default(function ($record) {
                        if (!$record?->course) {
                            return null;
                        }

                        return $record->created_at
                            ->addDays($record->course->access_duration_days);
                    }),
                TextInput::make('created_by')
                    ->label('Created By')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($record) => $record?->creator?->username),
            ]);
    }
}
