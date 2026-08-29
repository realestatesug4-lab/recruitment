<?php

namespace App\Filament\Admin\Resources\EmployerProfiles\Pages;

use App\Filament\Admin\Resources\EmployerProfiles\EmployerProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployerProfiles extends ListRecords
{
    protected static string $resource = EmployerProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
