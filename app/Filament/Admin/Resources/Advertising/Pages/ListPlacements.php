<?php

namespace App\Filament\Admin\Resources\Advertising\Pages;

use App\Filament\Admin\Resources\Advertising\PlacementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlacements extends ListRecords
{
    protected static string $resource = PlacementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
