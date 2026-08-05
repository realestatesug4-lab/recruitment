<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BaseDashboard
{
    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?string $title = 'Dashboard';

    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'lg' => 2,
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return '';
    }
}
