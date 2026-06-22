@props(['job'])

<a href="{{ route('jobs.show', $job->slug) }}"
   class="glass rounded-2xl p-5 flex items-center gap-4 group hover:-translate-y-1 hover:shadow-xl transition-all duration-200 block no-underline"
   x-data
   @mouseenter="$el.querySelector('.job-arrow')?.classList.add('translate-x-1')"
   @mouseleave="$el.querySelector('.job-arrow')?.classList.remove('translate-x-1')"
>
    {{-- Company logo --}}
    <div class="w-12 h-12 rounded-xl flex-shrink-0 flex items-center justify-center font-syne font-bold text-white text-lg"
         style="background: {{ $job->company->color ?? '#1B4332' }}">
        {{ strtoupper(substr($job->company->name, 0, 1)) }}
    </div>

    <div class="flex-1 min-w-0">
        <div class="font-semibold text-deep text-sm group-hover:text-forest transition-colors">
            {{ $job->title }}
        </div>
        <div class="text-xs text-text-light mt-0.5">
            {{ $job->company->name }} · {{ $job->location }}
        </div>
        @if($job->salary_min)
        <div class="text-xs text-sage font-medium mt-1">
            UGX {{ number_format($job->salary_min / 1000) }}K – {{ number_format($job->salary_max / 1000) }}K/mo
        </div>
        @endif
    </div>

    <div class="flex flex-col items-end gap-2 flex-shrink-0">
        <span @class([
            'text-xs px-3 py-1 rounded-full font-medium',
            'bg-mint/10 text-sage'     => $job->type === 'Full-time',
            'bg-amber/10 text-amber-700' => $job->type === 'Contract',
            'bg-blue-100 text-blue-700' => $job->type === 'Remote',
            'bg-forest/10 text-forest'  => !in_array($job->type, ['Full-time','Contract','Remote']),
        ])>{{ $job->type }}</span>
        <span class="text-xs text-text-light">{{ $job->created_at->diffForHumans() }}</span>
    </div>

    <svg class="job-arrow w-4 h-4 text-text-light transition-transform duration-200" ...></svg>
</a>
