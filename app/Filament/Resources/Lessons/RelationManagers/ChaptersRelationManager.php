<?php

namespace App\Filament\Resources\Lessons\RelationManagers;

use App\Filament\Resources\Lessons\LessonResource;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChaptersRelationManager extends RelationManager
{
    protected static string $relationship = 'chapters';

    protected static ?string $title = 'Chapters';

    protected static ?string $modelLabel = 'Chapter';

    protected static ?string $pluralModelLabel = 'Chapters';

    protected static ?string $relatedResource = LessonResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                    ->numeric()
                    ->default(0)
                    ->required(),
                TextInput::make('created_by')
                    ->label('Created By')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($record) => $record?->creator?->username),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('video.title')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('markdown.title')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('assessment.title')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('position')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('position')
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New Chapter'),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit chapter'),
                DeleteAction::make()
                    ->modalHeading('Delete chapters'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
