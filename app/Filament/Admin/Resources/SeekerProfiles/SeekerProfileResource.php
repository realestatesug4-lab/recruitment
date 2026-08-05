<?php

namespace App\Filament\Admin\Resources\SeekerProfiles;

use App\Models\SeekerProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeekerProfileResource extends Resource
{
    protected static ?string $model = \App\Domain\Users\Models\SeekerProfile::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return \App\Filament\Admin\Resources\SeekerProfiles\Schemas\SeekerProfileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return \App\Filament\Admin\Resources\SeekerProfiles\Schemas\SeekerProfileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Admin\Resources\SeekerProfiles\Tables\SeekerProfilesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeekerProfiles::route('/'),
            'create' => Pages\CreateSeekerProfile::route('/create'),
            'view' => Pages\ViewSeekerProfile::route('/{record}'),
            'edit' => Pages\EditSeekerProfile::route('/{record}/edit'),
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
