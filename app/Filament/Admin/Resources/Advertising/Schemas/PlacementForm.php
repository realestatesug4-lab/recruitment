<?php

namespace App\Filament\Admin\Resources\Advertising\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Schemas\Schema;

class PlacementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Placement Details')
                ->description('Basic placement configuration')
                ->columns(2)
                ->components([
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
                ]),

            Section::make('Revive Adserver Integration')
                ->description('Connect this placement to Revive Adserver zones')
                ->components([
                    TextInput::make('revive_zone_id')
                        ->numeric()
                        ->nullable()
                        ->helperText('Zone ID from Revive Adserver'),
                ]),

            Section::make('Targeting')
                ->description('Advanced targeting configuration')
                ->columns(1)
                ->components([
                    Textarea::make('device_targeting')
                        ->rows(3)
                        ->helperText('JSON device targeting rules (desktop, mobile, tablet)'),
                    Textarea::make('audience')
                        ->rows(3)
                        ->helperText('JSON audience targeting rules'),
                ]),

            Section::make('Status & Order')
                ->columns(2)
                ->components([
                    Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'paused' => 'Paused',
                            'disabled' => 'Disabled',
                        ])
                        ->required(),
                    TextInput::make('sort_order')->numeric()->default(100),
                ]),
        ]);
    }
}
