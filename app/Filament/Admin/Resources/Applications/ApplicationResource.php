<?php

namespace App\Filament\Admin\Resources\Applications;

use App\Filament\Admin\Resources\Applications\Pages\CreateApplication;
use App\Filament\Admin\Resources\Applications\Pages\EditApplication;
use App\Filament\Admin\Resources\Applications\Pages\ListApplications;
use App\Filament\Admin\Resources\Applications\Pages\ViewApplication;
use App\Filament\Admin\Resources\Applications\Schemas\ApplicationForm;
use App\Filament\Admin\Resources\Applications\Schemas\ApplicationInfolist;
use App\Filament\Admin\Resources\Applications\Tables\ApplicationsTable;
use App\Models\Application;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ApplicationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ApplicationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApplicationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApplications::route('/'),
            'create' => CreateApplication::route('/create'),
            'view' => ViewApplication::route('/{record}'),
            'edit' => EditApplication::route('/{record}/edit'),
        ];
    }
}
