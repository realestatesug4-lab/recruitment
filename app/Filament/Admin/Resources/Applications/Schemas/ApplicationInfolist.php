<?php

namespace App\Filament\Admin\Resources\Applications\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('uuid')
                    ->label('UUID'),
                TextEntry::make('job.title')
                    ->label('Job'),
                TextEntry::make('seeker_id')
                    ->numeric(),
                TextEntry::make('cover_letter')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('resume_path')
                    ->placeholder('-'),
                TextEntry::make('match_score')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('applied_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('reviewed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('shortlisted_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('rejected_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
