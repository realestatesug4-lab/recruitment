<?php

namespace App\Filament\Admin\Resources\Advertising\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CampaignsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('advertiser.name')->label('Advertiser')->sortable()->searchable(),
                TextColumn::make('type')->sortable(),
                TextColumn::make('budget_total')->money('USD')->sortable(),
                BadgeColumn::make('status')->enum([
                    'active' => 'Active',
                    'paused' => 'Paused',
                    'ended' => 'Ended',
                ])->colors([
                    'success' => 'active',
                    'warning' => 'paused',
                    'danger' => 'ended',
                ]),
                TextColumn::make('start_at')->dateTime()->sortable(),
                TextColumn::make('end_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'active' => 'Active',
                    'paused' => 'Paused',
                    'ended' => 'Ended',
                ]),
            ]);
    }
}
