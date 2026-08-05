<?php

namespace App\Filament\Admin\Resources\EmployerProfiles\Pages;

use App\Filament\Admin\Resources\EmployerProfiles\EmployerProfileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployerProfile extends CreateRecord
{
    protected static string $resource = EmployerProfileResource::class;
}
