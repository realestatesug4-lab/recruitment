<?php

namespace App\Filament\Admin\Resources\Jobs\Schemas;

use App\Models\Job;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class JobInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('uuid')
                    ->label('UUID'),
                TextEntry::make('title'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Job $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('company.name')
                    ->label('Company')
                    ->placeholder('-'),
                TextEntry::make('slug')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('job_type')
                    ->badge(),
                TextEntry::make('experience_level')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('location')
                    ->placeholder('-'),
                TextEntry::make('salary_min')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('salary_max')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deadline')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
