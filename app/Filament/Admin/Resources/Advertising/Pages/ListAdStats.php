<?php

namespace App\Filament\Admin\Resources\Advertising\Pages;

use App\Filament\Admin\Resources\Advertising\AdStatResource;
use Filament\Resources\Pages\ListRecords;

class ListAdStats extends ListRecords
{
    protected static string $resource = AdStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Add sync stats action
            \Filament\Actions\Action::make('sync_stats')
                ->label('Sync from Revive')
                ->icon('heroicon-o-arrow-path')
                ->action('syncStats')
                ->visible(fn () => config('services.revive.enabled')),
        ];
    }

    public function syncStats()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('revive:sync-stats', ['--days' => 30]);
            $this->notify('success', 'Statistics synced from Revive Adserver');
            $this->dispatch('refresh');
        } catch (\Exception $e) {
            $this->notify('danger', 'Failed to sync statistics: ' . $e->getMessage());
        }
    }
}
