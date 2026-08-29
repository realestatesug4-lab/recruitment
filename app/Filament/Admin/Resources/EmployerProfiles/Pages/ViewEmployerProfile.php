<?php

namespace App\Filament\Admin\Resources\EmployerProfiles\Pages;

use App\Filament\Admin\Resources\EmployerProfiles\EmployerProfileResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployerProfile extends ViewRecord
{
    protected static string $resource = EmployerProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
