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
            Select::make('user_id')->relationship('user', 'name')->required(),
            TextInput::make('headline')->maxLength(255)->nullable(),
            TextInput::make('location')->maxLength(255)->nullable(),
            TextInput::make('experience_level')->maxLength(255)->nullable(),
            Textarea::make('bio')->columnSpanFull()->nullable(),
            TextInput::make('resume_url')->url()->nullable(),
        ]);
    }
}
