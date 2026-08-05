<?php

namespace App\Filament\Admin\Resources\EmployerProfiles\Pages;

use App\Filament\Admin\Resources\EmployerProfiles\EmployerProfileResource;
use Filament\Resources\Pages\EditRecord;

class EditEmployerProfile extends EditRecord
{
    protected static string $resource = EmployerProfileResource::class;
}
