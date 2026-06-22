<?php

namespace App\Filament\Admin\Resources\Jobs\Schemas;

use App\Domain\Jobs\Enums\ExperienceLevel;
use App\Domain\Jobs\Enums\JobStatus;
use App\Domain\Jobs\Enums\JobType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class JobForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Select::make('company_id')
                    ->relationship('company', 'name'),
                TextInput::make('slug'),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('job_type')
                    ->options(JobType::class)
                    ->default('full-time')
                    ->required(),
                Select::make('experience_level')
                    ->options(ExperienceLevel::class),
                TextInput::make('location'),
                TextInput::make('salary_min')
                    ->numeric(),
                TextInput::make('salary_max')
                    ->numeric(),
                Select::make('status')
                    ->options(JobStatus::class)
                    ->default('draft')
                    ->required(),
                DateTimePicker::make('published_at'),
                DateTimePicker::make('deadline'),
            ]);
    }
}
