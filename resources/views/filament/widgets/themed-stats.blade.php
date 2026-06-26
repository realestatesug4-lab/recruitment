<div class="p-6 bg-gradient-to-br from-forest/5 via-white to-mint/5 rounded-lg">
    <div class="grid grid-cols-3 gap-4">
        <div class="glass rounded-2xl p-4 border border-mint/10">
            <div class="text-xs font-semibold uppercase text-text-light">Users</div>
            <div class="mt-2 text-2xl font-syne font-bold text-deep">{{ number_format($this->users) }}</div>
            <div class="mt-1 text-sm text-sage">Registered seekers & employers</div>
        </div>

        <div class="glass rounded-2xl p-4 border border-mint/10">
            <div class="text-xs font-semibold uppercase text-text-light">Published jobs</div>
            <div class="mt-2 text-2xl font-syne font-bold text-deep">{{ number_format($this->jobs) }}</div>
            <div class="mt-1 text-sm text-sage">Active job listings</div>
        </div>

        <div class="glass rounded-2xl p-4 border border-mint/10">
            <div class="text-xs font-semibold uppercase text-text-light">Applications</div>
            <div class="mt-2 text-2xl font-syne font-bold text-deep">{{ number_format($this->applications) }}</div>
            <div class="mt-1 text-sm text-sage">Total applications</div>
        </div>
    </div>
</div>
