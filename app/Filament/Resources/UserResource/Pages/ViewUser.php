<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\ArticleResource\Actions\MakePublicAction;
use App\Filament\Resources\UserResource;
use App\Forms\Components\SelectRole;
use App\Models\Article;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn (User $record) => $record->email == 'admin@gmail.com'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn (User $record) => $record->email == 'admin@gmail.com'),
                DateTimePicker::make('email_verified_at')
                    ->disabled(fn (User $record) => $record->email == 'admin@gmail.com'),
                SelectRole::make('roles')
                    ->disabled(fn (User $record) => $record->email == 'admin@gmail.com')
                    ->columnSpanFull()
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->hidden(fn (User $record) => $record->email == 'admin@gmail.com'),
        ];
    }
}
