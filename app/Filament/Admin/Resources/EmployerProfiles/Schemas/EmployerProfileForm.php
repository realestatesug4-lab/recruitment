<?php

namespace App\Filament\Admin\Resources\EmployerProfiles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployerProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required(),
            Select::make('company_id')
                ->relationship('company', 'name')
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('title')
                ->label('Profile title')
                ->maxLength(255)
                ->nullable(),
            TextInput::make('job_title')
                ->label('Job title')
                ->maxLength(255)
                ->nullable(),
            TextInput::make('phone')
                ->label('Phone')
                ->tel()
                ->maxLength(255)
                ->nullable(),
            Textarea::make('bio')
                ->label('Bio')
                ->rows(6)
                ->columnSpanFull(),
        ]);
    }
}
