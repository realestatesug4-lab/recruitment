<?php

namespace App\Filament\Admin\Resources\Applications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uuid')
                    ->label('UUID'),
                TextColumn::make('job.title')
                    ->searchable(),
                TextColumn::make('seeker_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('resume_path')
                    ->searchable(),
                TextColumn::make('match_score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('applied_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('reviewed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('shortlisted_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('rejected_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
