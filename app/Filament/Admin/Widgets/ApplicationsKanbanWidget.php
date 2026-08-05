<?php

namespace App\Filament\Admin\Widgets;

use App\Domain\Applications\Models\Application;
use Filament\Widgets\Widget;

class ApplicationsKanbanWidget extends Widget
{
    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.admin.widgets.applications-kanban';

    public array $columns = [];

    public function mount(): void
    {
        $statuses = ['submitted', 'shortlisted', 'interview', 'hired', 'rejected'];

        foreach ($statuses as $status) {
            $apps = Application::query()
                ->where('status', $status)
                ->with(['job', 'seekerProfile'])
                ->latest()
                ->limit(6)
                ->get()
                ->map(fn (Application $application) => [
                    'id' => $application->uuid ?? $application->id,
                    'title' => $application->job?->title ?? 'Role',
                    'candidate' => $application->seekerProfile?->name ?? 'Candidate',
                    'when' => $application->created_at?->diffForHumans() ?? '',
                    'url' => $this->safeRoute('employer.applications.show', $application->uuid ?? $application->id),
                ])
                ->toArray();

            $this->columns[] = [
                'status' => ucfirst($status),
                'items' => $apps,
            ];
        }
    }

    protected function safeRoute(string $name, mixed $parameter = null): string
    {
        try {
            return route($name, $parameter);
        } catch (\Throwable) {
            return '#';
        }
    }
}
