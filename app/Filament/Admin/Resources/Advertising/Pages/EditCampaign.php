<?php

namespace App\Filament\Admin\Resources\Advertising\Pages;

use App\Filament\Admin\Resources\Advertising\CampaignResource;
use Filament\Resources\Pages\EditRecord;

class EditCampaign extends EditRecord
{
    protected static string $resource = CampaignResource::class;
}
