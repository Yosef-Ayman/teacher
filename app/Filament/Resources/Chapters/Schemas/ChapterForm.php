<?php

namespace App\Filament\Resources\Chapters\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ChapterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('lesson_id')
                    ->relationship('lesson', 'title')
                    ->searchable()
                    ->required(),
                Radio::make('type')
                    ->options([
                        'video' => 'Video',
                        'markdown' => 'Markdown',
                        'assessment' => 'Assessment',
                    ])
                    ->afterStateHydrated(function ($state, callable $set, $record) {
                        if (! $record) {
                            return;
                        }
                        $type = null;

                        if ($record->video_id) {
                            $type = 'video';
                        } elseif ($record->markdown_id) {
                            $type = 'markdown';
                        } elseif ($record->assessment_id) {
                            $type = 'assessment';
                        }

                        $set('type', $type);
                    })
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === 'video') {
                            $set('markdown_id', null);
                            $set('assessment_id', null);
                        }

                        if ($state === 'markdown') {
                            $set('video_id', null);
                            $set('assessment_id', null);
                        }

                        if ($state === 'assessment') {
                            $set('video_id', null);
                            $set('markdown_id', null);
                        }
                    })
                    ->dehydrated(false)
                    ->required(),
                Select::make('video_id')
                    ->relationship('video', 'title')
                    ->searchable()
                    ->visible(fn (Get $get) => $get('type') === 'video')
                    ->required(fn (Get $get) => $get('type') === 'video')
                    ->dehydrateStateUsing(fn ($state, Get $get) => $get('type') === 'video' ? $state : null)
                    ->dehydrated(true),
                Select::make('markdown_id')
                    ->relationship('markdown', 'title')
                    ->searchable()
                    ->visible(fn (Get $get) => $get('type') === 'markdown')
                    ->required(fn (Get $get) => $get('type') === 'markdown')
                    ->dehydrateStateUsing(fn ($state, Get $get) => $get('type') === 'markdown' ? $state : null)
                    ->dehydrated(true),
                Select::make('assessment_id')
                    ->relationship('assessment', 'title')
                    ->searchable()
                    ->visible(fn (Get $get) => $get('type') === 'assessment')
                    ->required(fn (Get $get) => $get('type') === 'assessment')
                    ->dehydrateStateUsing(fn ($state, Get $get) => $get('type') === 'assessment' ? $state : null)
                    ->dehydrated(true),
                TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('created_by')
                    ->label('Created By')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($record) => $record?->creator?->username),
            ]);
    }
}
