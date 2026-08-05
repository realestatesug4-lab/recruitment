<?php

namespace App\Filament\Admin\Resources\SeekerProfiles\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SeekerProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('ID')->sortable(),
            TextColumn::make('user.name')->label('User')->searchable(),
            TextColumn::make('name')->searchable(),
            TextColumn::make('location')->sortable(),
        ]);
    }
}
