<?php

namespace App\Filament\Admin\Resources\Advertising;

use App\Domain\Advertising\Models\Creative;
use App\Filament\Admin\Resources\Advertising\Schemas\CreativeForm;
use App\Filament\Admin\Resources\Advertising\Tables\CreativesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CreativeResource extends Resource
{
    protected static ?string $model = Creative::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CreativeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CreativesTable::configure($table);
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
            'index' => Pages\ListCreatives::route('/'),
            'create' => Pages\CreateCreative::route('/create'),
            'edit' => Pages\EditCreative::route('/{record}/edit'),
        ];
    }
}
