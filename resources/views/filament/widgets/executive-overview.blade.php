<div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
    <div class="text-sm font-semibold uppercase tracking-[0.24em] text-sage">Executive overview</div>
    <div class="mt-3 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach(($this->data['kpis'] ?? []) as $kpi)
            <div class="rounded-2xl border border-slate-200/70 bg-slate-50 p-4">
                <div class="text-sm text-text-light">{{ $kpi['label'] }}</div>
                <div class="mt-2 text-2xl font-syne font-bold text-deep">{{ $kpi['value'] }}</div>
            </div>
        @endforeach
    </div>
</div>
