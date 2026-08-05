<?php

namespace App\Filament\Admin\Resources\EmployerProfiles\Pages;

use App\Filament\Admin\Resources\EmployerProfiles\EmployerProfileResource;
use Filament\Resources\Pages\ListRecords;

class ListEmployerProfiles extends ListRecords
{
    protected static string $resource = EmployerProfileResource::class;
}
