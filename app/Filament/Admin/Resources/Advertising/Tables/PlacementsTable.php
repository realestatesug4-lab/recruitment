<?php

namespace App\Filament\Admin\Resources\Advertising\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PlacementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('context')->sortable(),
                TextColumn::make('position')->sortable(),
                TextColumn::make('revive_zone_id')->label('Revive Zone')->sortable(),
                BadgeColumn::make('status')->enum([
                    'active' => 'Active',
                    'paused' => 'Paused',
                    'disabled' => 'Disabled',
                ])->colors([
                    'success' => 'active',
                    'warning' => 'paused',
                    'danger' => 'disabled',
                ]),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'active' => 'Active',
                    'paused' => 'Paused',
                    'disabled' => 'Disabled',
                ]),
            ]);
    }
}
