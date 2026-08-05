<?php

namespace App\Filament\Admin\Resources\Advertising\Pages;

use App\Filament\Admin\Resources\Advertising\CreativeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCreatives extends ListRecords
{
    protected static string $resource = CreativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
