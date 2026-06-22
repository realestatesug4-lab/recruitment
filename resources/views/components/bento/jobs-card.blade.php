@props(['jobs' => []])

<div class="bento-card card-jobs glass">
    <div class="card-tag text-xs font-semibold uppercase tracking-wide text-sage mb-3 flex items-center gap-1.5">
        <span class="w-1 h-1 rounded-full bg-sage"></span>
        Latest Openings
    </div>
    <div class="card-title font-syne font-bold text-deep mb-4">Hot jobs right now</div>
    <div class="job-list space-y-2.5">
        @foreach($jobs as $job)
            @php
                $company = $job['company'] ?? '';
                $logoBg = $job['logo_bg'] ?? 'rgba(82,183,136,0.1)';
                $logoColor = $job['logo_color'] ?? '#1B4332';
                $logoStyle = "background: {$logoBg}; color: {$logoColor};";
            @endphp

            <div class="job-item bg-white/60 border border-white/80 rounded-md p-3.5 flex items-center gap-3.5 transition-all duration-200 cursor-pointer hover:bg-white/85 hover:border-mint/30 hover:translate-x-1">
                <div
                    class="job-logo w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 font-syne font-bold text-sm"
                    style="{{ $logoStyle }}"
                >
                    {{ $job['initial'] ?? substr($company, 0, 1) }}
                </div>
                <div class="job-info flex-1 min-w-0">
                    <div class="job-title-text text-sm font-semibold text-deep truncate">{{ $job['title'] ?? '' }}</div>
                    <div class="job-meta text-xs text-text-light">{{ $job['company'] ?? '' }} &middot; {{ $job['location'] ?? '' }}</div>
                </div>
                <span class="job-badge text-xs px-2.5 py-1 rounded-full font-medium whitespace-nowrap {{ $job['badge_class'] ?? 'badge-green' }}">
                    {{ $job['type'] ?? '' }}
                </span>
            </div>
        @endforeach
    </div>
    <a href="{{ route('jobs.index') }}" class="view-all-link flex items-center gap-1.5 mt-3.5 text-sm font-medium text-sage hover:gap-2.5 transition-all duration-200">
        View all 12,400+ jobs &rarr;
    </a>
</div>
