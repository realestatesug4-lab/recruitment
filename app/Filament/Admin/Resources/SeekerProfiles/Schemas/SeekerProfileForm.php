<?php

namespace App\Filament\Admin\Resources\SeekerProfiles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class SeekerProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('uuid')->label('UUID'),
            Select::make('user_id')->relationship('user', 'name'),
            TextInput::make('name'),
            TextInput::make('headline'),
            TextInput::make('location'),
            Textarea::make('summary')->columnSpanFull(),
            TextInput::make('resume_url'),
        ]);
    }
}
