@props(['job'])

<a href="{{ route('jobs.show', $job->slug) }}"
   class="job-card glass rounded-xl sm:rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 group hover:-translate-y-0.5 hover:shadow-xl transition-all duration-200 block no-underline active:scale-[0.99]"
>
    <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl flex-shrink-0 flex items-center justify-center font-syne font-bold text-white text-base sm:text-lg"
             style="background: {{ $job->company->color ?? '#1B4332' }}">
            {{ strtoupper(substr($job->company->name, 0, 1)) }}
        </div>

        <div class="flex-1 min-w-0">
            <div class="font-semibold text-deep text-sm sm:text-base group-hover:text-forest transition-colors truncate">
                {{ $job->title }}
            </div>
            <div class="text-xs text-text-light mt-0.5 truncate">
                {{ $job->company->name }} · {{ $job->location }}
            </div>
            @if($job->salary_min)
            <div class="text-xs text-sage font-medium mt-1">
                UGX {{ number_format($job->salary_min / 1000) }}K – {{ number_format($job->salary_max / 1000) }}K/mo
            </div>
            @endif
        </div>
    </div>

    <div class="flex items-center justify-between sm:justify-end gap-2 sm:gap-3 pl-14 sm:pl-0 flex-shrink-0">
        <span @class([
            'text-xs px-3 py-1 rounded-full font-medium whitespace-nowrap',
            'bg-mint/10 text-sage'     => $job->type === 'Full-time',
            'bg-amber/10 text-amber-700' => $job->type === 'Contract',
            'bg-blue-100 text-blue-700' => $job->type === 'Remote',
            'bg-forest/10 text-forest'  => !in_array($job->type, ['Full-time','Contract','Remote']),
        ])>{{ $job->type }}</span>
        <span class="text-xs text-text-light whitespace-nowrap">{{ $job->created_at->diffForHumans() }}</span>
        <svg class="hidden sm:block job-arrow w-4 h-4 text-text-light transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
    </div>
</a>
