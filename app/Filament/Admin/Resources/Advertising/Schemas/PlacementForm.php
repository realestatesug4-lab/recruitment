<?php

namespace App\Filament\Admin\Resources\Advertising\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PlacementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('slug')->required()->maxLength(255),
            Select::make('context')
                ->options([
                    'job_list' => 'Job List',
                    'search_results' => 'Search Results',
                    'company_page' => 'Company Page',
                    'quick_links' => 'Quick Links',
                    'homepage' => 'Homepage',
                ])
                ->required(),
            Select::make('position')
                ->options([
                    'top' => 'Top',
                    'sidebar' => 'Sidebar',
                    'inline' => 'Inline',
                    'footer' => 'Footer',
                ])
                ->required(),
            Textarea::make('device_targeting')->rows(3)->helperText('JSON device targeting rules'),
            Textarea::make('audience')->rows(3)->helperText('JSON audience targeting rules'),
            TextInput::make('revive_zone_id')->numeric()->nullable(),
            Select::make('status')
                ->options([
                    'active' => 'Active',
                    'paused' => 'Paused',
                    'disabled' => 'Disabled',
                ])
                ->required(),
            TextInput::make('sort_order')->numeric()->default(100),
        ]);
    }
}
