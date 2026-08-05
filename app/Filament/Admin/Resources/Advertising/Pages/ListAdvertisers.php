<?php

namespace App\Filament\Admin\Resources\Advertising\Pages;

use App\Filament\Admin\Resources\Advertising\AdvertiserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdvertisers extends ListRecords
{
    protected static string $resource = AdvertiserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
