<?php

namespace App\Filament\Admin\Resources\EmployerProfiles\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployerProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('id')->label('ID')->sortable(),
            TextColumn::make('user.name')->label('User')->searchable(),
            TextColumn::make('company.name')->label('Company')->searchable(),
            TextColumn::make('title')->searchable(),
        ]);
    }
}
