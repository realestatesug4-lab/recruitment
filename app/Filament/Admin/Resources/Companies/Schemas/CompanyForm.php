<?php

namespace App\Filament\Admin\Resources\Companies\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug'),
                TextInput::make('industry'),
                TextInput::make('website')
                    ->url(),
                TextInput::make('location'),
                Select::make('owner_id')
                    ->relationship('owner', 'name')
                    ->label('Owner'),
                TextInput::make('logo_url')
                    ->label('Logo URL')
                    ->url(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('verification_status')
                    ->label('Verification Status')
                    ->options([
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),
                Checkbox::make('is_featured')
                    ->label('Featured Company'),
            ]);
    }
}
