<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use App\Domain\Applications\Models\Application;

class ApplicationsKanbanWidget extends Widget
{
    protected ?string $heading = 'Applications Kanban';
    protected string $view = 'filament.widgets.applications-kanban';

    public array $columns = [];

    protected function setUp(): void
    {
        parent::setUp();

        $statuses = ['submitted','shortlisted','interview','hired','rejected'];

        foreach ($statuses as $status) {
            $apps = Application::where('status', $status)->with('job','seekerProfile')->latest()->limit(6)->get()->map(fn($a) => [
                'id' => $a->uuid ?? $a->id,
                'title' => $a->job?->title ?? 'Role',
                'candidate' => $a->seekerProfile?->name ?? 'Candidate',
                'when' => $a->created_at?->diffForHumans() ?? '',
                'url' => route('employer.applications.show', $a->uuid ?? $a->id),
            ])->toArray();

            $this->columns[] = ['status' => ucfirst($status), 'items' => $apps];
        }
    }
}
