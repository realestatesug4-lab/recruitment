<?php

namespace App\Filament\Admin\Resources\Advertising\Schemas;

use App\Domain\Advertising\Models\Campaign;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CreativeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('campaign_id')
                ->label('Campaign')
                ->options(Campaign::query()->pluck('name', 'id'))
                ->required(),
            TextInput::make('name')->required()->maxLength(255),
            Select::make('type')
                ->options([
                    'banner' => 'Banner',
                    'native' => 'Native',
                    'quick_link' => 'Quick Link',
                    'sponsored_card' => 'Sponsored Card',
                ])
                ->required(),
            Select::make('format')
                ->options([
                    'image' => 'Image',
                    'html' => 'HTML',
                    'text' => 'Text',
                    'video' => 'Video',
                ])
                ->required(),
            TextInput::make('title')->maxLength(255),
            Textarea::make('body')->rows(3),
            TextInput::make('image_url')->url()->maxLength(255),
            TextInput::make('click_url')->url()->maxLength(255),
            TextInput::make('cta_text')->maxLength(100),
            Textarea::make('html')->rows(4),
            TextInput::make('width')->numeric()->nullable(),
            TextInput::make('height')->numeric()->nullable(),
            Select::make('status')
                ->options([
                    'active' => 'Active',
                    'paused' => 'Paused',
                    'archived' => 'Archived',
                ])
                ->required(),
            TextInput::make('external_banner_id')->numeric()->nullable(),
            TextInput::make('weight')->numeric()->default(10),
            Textarea::make('metadata')->rows(3)->helperText('JSON metadata for this creative'),
        ]);
    }
}
