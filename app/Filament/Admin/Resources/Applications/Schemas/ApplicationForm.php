<?php

namespace App\Filament\Admin\Resources\Applications\Schemas;

use App\Domain\Applications\Enums\ApplicationStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                Select::make('job_id')
                    ->relationship('job', 'title')
                    ->required(),
                TextInput::make('seeker_id')
                    ->required()
                    ->numeric(),
                Textarea::make('cover_letter')
                    ->columnSpanFull(),
                TextInput::make('resume_path'),
                TextInput::make('match_score')
                    ->numeric(),
                DateTimePicker::make('applied_at'),
                Select::make('status')
                    ->options(ApplicationStatus::class)
                    ->default('submitted')
                    ->required(),
                DateTimePicker::make('reviewed_at'),
                DateTimePicker::make('shortlisted_at'),
                DateTimePicker::make('rejected_at'),
            ]);
    }
}
