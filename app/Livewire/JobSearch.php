<?php

namespace App\Livewire;

use App\Models\Job;
use Livewire\Component;
use Livewire\WithPagination;

class JobSearch extends Component
{
    use WithPagination;

    public string $query    = '';
    public string $location = '';
    public array  $types    = [];
    public string $sort     = 'relevance';
    public ?int   $salaryMax = null;

    protected $queryString = [
        'query'     => ['except' => ''],
        'location'  => ['except' => ''],
        'types'     => ['except' => []],
        'sort'      => ['except' => 'relevance'],
        'salaryMax' => ['except' => null],
    ];

    public function updatedQuery()    { $this->resetPage(); }
    public function updatedLocation() { $this->resetPage(); }
    public function updatedTypes()    { $this->resetPage(); }

    public function render()
    {
        $jobs = Job::query()
            ->when($this->query,    fn($q) => $q->search($this->query))
            ->when($this->location, fn($q) => $q->where('location', 'like', "%{$this->location}%"))
            ->when($this->types,    fn($q) => $q->whereIn('type', $this->types))
            ->when($this->salaryMax,fn($q) => $q->where('salary_max', '<=', $this->salaryMax))
            ->when($this->sort === 'date',        fn($q) => $q->latest())
            ->when($this->sort === 'salary_desc', fn($q) => $q->orderByDesc('salary_max'))
            ->paginate(15);

        return view('livewire.job-search', compact('jobs'));
    }
}
