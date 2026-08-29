<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Placeholder::make('roles_notice')
                    ->content('Only the configured super admin can assign or change roles.')
                    ->visible(fn () => ! optional(auth()->user())->isSuperAdmin()),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->label('Roles')
                    ->visible(fn () => optional(auth()->user())->isSuperAdmin())
                    ->helperText('Only the configured super admin can assign roles.'),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
            ]);
    }
}
