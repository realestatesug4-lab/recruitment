<?php

namespace App\Filament\Admin\Resources\Advertising;

use App\Domain\Advertising\Models\AdStat;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class AdStatResource extends Resource
{
    protected static ?string $model = AdStat::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $label = 'Ad Statistics';

    protected static ?string $pluralLabel = 'Ad Statistics';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('placement.name')
                    ->label('Placement')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('impressions')
                    ->label('Impressions')
                    ->sortable()
                    ->summarize(\Filament\Tables\Enums\SummaryType::Sum),
                TextColumn::make('clicks')
                    ->label('Clicks')
                    ->sortable()
                    ->summarize(\Filament\Tables\Enums\SummaryType::Sum),
                TextColumn::make('ctr')
                    ->label('CTR (%)')
                    ->formatStateUsing(fn (string $state): string => round((float)$state, 2) . '%')
                    ->sortable(),
                TextColumn::make('revenue')
                    ->label('Revenue')
                    ->money('usd')
                    ->sortable()
                    ->summarize(\Filament\Tables\Enums\SummaryType::Sum),
                TextColumn::make('ecpm')
                    ->label('eCPM')
                    ->formatStateUsing(fn (string $state): string => '$' . round((float)$state, 2))
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('placement_id')
                    ->relationship('placement', 'name')
                    ->label('Placement'),
                Filter::make('date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('date_from')
                            ->label('From Date'),
                        \Filament\Forms\Components\DatePicker::make('date_until')
                            ->label('To Date'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn ($query, $date) => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'],
                                fn ($query, $date) => $query->whereDate('date', '<=', $date),
                            );
                    }),
            ])
            ->defaultSort('date', 'desc')
            ->paginated([25, 50, 100]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\Advertising\Pages\ListAdStats::route('/'),
        ];
    }
}
