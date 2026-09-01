<?php

namespace App\Filament\Super\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class GeneratePasswordHash extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'Password Hash Generator';
    protected static ?string $title = 'Generate Password Hash';

    protected string $view = 'filament.super.pages.generate-password-hash';

    public ?string $password = null;
    public ?string $hashedPassword = null;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('password')
                    ->label('Plain Password')
                    ->password()
                    ->revealable()
                    ->required(),
            ])
            ->statePath('data');
    }

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function generate(): void
    {
        $state = $this->form->getState();

        $this->hashedPassword = Hash::make($state['password']);

        Notification::make()
            ->title('Hash generated')
            ->success()
            ->send();
    }

    public function copyHash(): void
    {
        Notification::make()
            ->title('Copied to clipboard')
            ->success()
            ->send();
    }
}
