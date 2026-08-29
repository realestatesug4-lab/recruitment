<?php

namespace App\Filament\Admin\Resources\Advertising\Schemas;

use App\Domain\Advertising\Models\Advertiser;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('advertiser_id')
                ->label('Advertiser')
                ->options(Advertiser::query()->pluck('name', 'id'))
                ->required(),
            TextInput::make('name')->required()->maxLength(255),
            Select::make('type')
                ->options([
                    'CPM' => 'CPM',
                    'CPC' => 'CPC',
                    'CPA' => 'CPA',
                    'flat' => 'Flat Rate',
                    'subscription' => 'Subscription',
                    'featured' => 'Featured',
                ])
                ->required(),
            TextInput::make('objective')->maxLength(255),
            Grid::make(2)->components([
                TextInput::make('budget_total')->numeric()->required(),
                TextInput::make('budget_spent')->numeric()->required()->default(0),
            ]),
            Grid::make(2)->components([
                DateTimePicker::make('start_at')->nullable(),
                DateTimePicker::make('end_at')->nullable(),
            ]),
            Select::make('status')
                ->options([
                    'active' => 'Active',
                    'paused' => 'Paused',
                    'ended' => 'Ended',
                ])
                ->required(),
            TextInput::make('priority')->numeric()->default(50),
            Textarea::make('targeting')->rows(4)->helperText('JSON object for audience targeting'),
            TextInput::make('external_campaign_id')->numeric()->nullable(),
        ]);
    }
}
