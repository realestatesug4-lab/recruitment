<?php

namespace App\Filament\Admin\Resources\Advertising;

use App\Domain\Advertising\Models\Advertiser;
use App\Filament\Admin\Resources\Advertising\Schemas\AdvertiserForm;
use App\Filament\Admin\Resources\Advertising\Tables\AdvertisersTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdvertiserResource extends Resource
{
    protected static ?string $model = Advertiser::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AdvertiserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdvertisersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // Add relationship managers if needed.
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdvertisers::route('/'),
            'create' => Pages\CreateAdvertiser::route('/create'),
            'edit' => Pages\EditAdvertiser::route('/{record}/edit'),
        ];
    }
}
