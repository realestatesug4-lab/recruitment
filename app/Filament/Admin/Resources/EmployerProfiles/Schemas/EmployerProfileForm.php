<?php

namespace App\Filament\Admin\Resources\EmployerProfiles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EmployerProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('uuid')->label('UUID'),
            Select::make('user_id')->relationship('user', 'name'),
            TextInput::make('company_id'),
            TextInput::make('title'),
            TextInput::make('website'),
            Textarea::make('bio')->columnSpanFull(),
        ]);
    }
}
