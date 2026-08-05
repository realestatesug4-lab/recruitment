<?php

namespace App\Filament\Admin\Resources\Advertising\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AdvertiserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('slug')
                ->required()
                ->maxLength(255),
            TextInput::make('website')
                ->url()
                ->maxLength(255),
            TextInput::make('contact_name')
                ->maxLength(255),
            TextInput::make('contact_email')
                ->email()
                ->maxLength(255),
            TextInput::make('status')
                ->required()
                ->default('active')
                ->maxLength(50),
            Textarea::make('notes')->rows(4),
            TextInput::make('external_advertiser_id')->numeric()->nullable(),
        ]);
    }
}
