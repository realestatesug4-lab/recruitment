<?php

namespace App\Filament\Admin\Resources\Advertising\Pages;

use App\Filament\Admin\Resources\Advertising\CampaignResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCampaign extends CreateRecord
{
    protected static string $resource = CampaignResource::class;
}
