<?php

namespace App\Filament\Admin\Resources\Companies\Schemas;

use App\Domain\Companies\Models\Company;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CompanyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('uuid')
                    ->label('UUID'),
                TextEntry::make('name'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Company $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('industry')
                    ->placeholder('-'),
                TextEntry::make('website')
                    ->url()
                    ->placeholder('-'),
                TextEntry::make('location')
                    ->placeholder('-'),
                TextEntry::make('logo_url')
                    ->label('Logo URL')
                    ->url()
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('verification_status')
                    ->badge(),
                TextEntry::make('is_featured')
                    ->label('Featured')
                    ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
            ]);
    }
}
