<?php

namespace App\Filament\Admin\Resources\EmployerProfiles;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployerProfileResource extends Resource
{
    protected static ?string $model = \App\Domain\Users\Models\EmployerProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $recordTitleAttribute = 'job_title';

    public static function form(Schema $schema): Schema
    {
        return \App\Filament\Admin\Resources\EmployerProfiles\Schemas\EmployerProfileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return \App\Filament\Admin\Resources\EmployerProfiles\Schemas\EmployerProfileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Admin\Resources\EmployerProfiles\Tables\EmployerProfilesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployerProfiles::route('/'),
            'create' => Pages\CreateEmployerProfile::route('/create'),
            'view' => Pages\ViewEmployerProfile::route('/{record}'),
            'edit' => Pages\EditEmployerProfile::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
