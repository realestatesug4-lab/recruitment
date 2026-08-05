<?php

namespace App\Filament\Admin\Resources\Advertising;

use App\Domain\Advertising\Models\Placement;
use App\Filament\Admin\Resources\Advertising\Schemas\PlacementForm;
use App\Filament\Admin\Resources\Advertising\Tables\PlacementsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PlacementResource extends Resource
{
    protected static ?string $model = Placement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PlacementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlacementsTable::configure($table);
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
            'index' => Pages\ListPlacements::route('/'),
            'create' => Pages\CreatePlacement::route('/create'),
            'edit' => Pages\EditPlacement::route('/{record}/edit'),
        ];
    }
}
