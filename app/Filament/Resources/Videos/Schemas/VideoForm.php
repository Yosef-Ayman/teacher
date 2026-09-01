<?php

namespace App\Filament\Resources\Videos\Schemas;

use App\Enums\VideoProvider;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class VideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Select::make('provider')
                    ->options([
                        VideoProvider::Youtube->value => 'Youtube',
                        VideoProvider::VIMEO->value => 'Vimeo',
                        VideoProvider::STREAMABLE->value => 'Streamable',
                    ])
                    ->required()
                    ->default('youtube'),
                TextInput::make('external_id')
                    ->required(),
                Toggle::make('hidden')
                    ->disabled()
                    ->required(),
                TextInput::make('created_by')
                    ->label('Created By')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($record) => $record?->creator?->username),
                Section::make('Video Preview')
                    ->schema([
                        Placeholder::make('video_preview')
                            ->label('Video Preview')
                            ->content(function ($record) {
                                if (! $record || ! $record->getEmbedUrl()) {
                                    return 'No video URL provided.';
                                }

                                return new HtmlString("
                                <div style='width: 100%; max-width: 560px; margin: 0 auto;'>
                                    <iframe
                                        width='100%'
                                        height='315'
                                        src='{$record->getEmbedUrl()}'
                                        title='Video preview'
                                        frameborder='0'
                                        allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture'
                                        allowfullscreen
                                        style='border-radius: 8px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);'
                                    ></iframe>
                                </div>
                                ");
                            }),
                    ])
                    ->collapsible(),

            ]);
    }
}
