<?php

namespace App\Filament\Resources\Markdowns;

use App\Filament\Resources\Markdowns\Pages\CreateMarkdown;
use App\Filament\Resources\Markdowns\Pages\EditMarkdown;
use App\Filament\Resources\Markdowns\Pages\ListMarkdowns;
use App\Filament\Resources\Markdowns\Schemas\MarkdownForm;
use App\Filament\Resources\Markdowns\Tables\MarkdownsTable;
use App\Models\Markdown;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MarkdownResource extends Resource
{
    protected static ?string $model = Markdown::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Pencil;

    protected static ?string $navigationParentItem = 'Courses';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return MarkdownForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MarkdownsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMarkdowns::route('/'),
            'create' => CreateMarkdown::route('/create'),
            'edit' => EditMarkdown::route('/{record}/edit'),
        ];
    }
}
