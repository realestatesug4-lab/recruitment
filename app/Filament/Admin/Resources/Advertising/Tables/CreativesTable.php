<?php

namespace App\Filament\Admin\Resources\Advertising\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CreativesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('campaign.name')->label('Campaign')->sortable()->searchable(),
                TextColumn::make('type')->sortable(),
                TextColumn::make('format')->sortable(),
                BadgeColumn::make('status')->enum([
                    'active' => 'Active',
                    'paused' => 'Paused',
                    'archived' => 'Archived',
                ])->colors([
                    'success' => 'active',
                    'warning' => 'paused',
                    'danger' => 'archived',
                ]),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'active' => 'Active',
                    'paused' => 'Paused',
                    'archived' => 'Archived',
                ]),
            ]);
    }
}
