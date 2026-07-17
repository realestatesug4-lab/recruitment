<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-4">
    <div class="p-4 bg-white rounded-lg shadow">
        <div class="text-sm text-gray-500">Total jobs</div>
        <div class="text-2xl font-semibold">{{ $this->stats['total_jobs'] ?? 0 }}</div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow">
        <div class="text-sm text-gray-500">Published jobs</div>
        <div class="text-2xl font-semibold">{{ $this->stats['published_jobs'] ?? 0 }}</div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow">
        <div class="text-sm text-gray-500">Companies</div>
        <div class="text-2xl font-semibold">{{ $this->stats['companies'] ?? 0 }}</div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow">
        <div class="text-sm text-gray-500">Users</div>
        <div class="text-2xl font-semibold">{{ $this->stats['users'] ?? 0 }}</div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow">
        <div class="text-sm text-gray-500">Applications</div>
        <div class="text-2xl font-semibold">{{ $this->stats['applications'] ?? 0 }}</div>
    </div>

    <div class="p-4 bg-white rounded-lg shadow">
        <div class="text-sm text-gray-500">Hired</div>
        <div class="text-2xl font-semibold">{{ $this->stats['hired_applications'] ?? 0 }}</div>
    </div>
</div>
