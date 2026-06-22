@props(['company' => []])

@php
    $accent = $company['accent'] ?? '#1B4332';
@endphp

<div class="bento-card card-company glass h-full rounded-lg p-5">
    <div class="card-tag text-xs font-semibold uppercase tracking-wide text-sage mb-3 flex items-center gap-1.5">
        <span class="w-1 h-1 rounded-full bg-sage"></span>
        Featured Employer
    </div>

    <div
        class="company-logo-big w-13 h-13 rounded-xl flex items-center justify-center font-syne font-extrabold text-white text-lg mb-3.5"
        style="background: {{ $accent }}"
    >
        {{ $company['initial'] ?? 'M' }}
    </div>

    <div class="company-name font-syne font-bold text-deep">{{ $company['name'] ?? 'MTN Uganda' }}</div>
    <div class="company-sub text-sm text-text-light mb-3.5">
        {{ $company['industry'] ?? 'Telecommunications' }} &middot; {{ $company['location'] ?? 'Kampala' }}
    </div>

    <span class="open-roles-badge inline-flex items-center gap-1.5 bg-mint/12 border border-mint/20 text-sage text-xs font-medium px-3 py-1.5 rounded-full">
        <span class="dot w-1.5 h-1.5 rounded-full bg-mint"></span>
        {{ $company['open_roles'] ?? '14' }} open roles
    </span>

    <div class="company-stats-row grid grid-cols-3 gap-3 mt-3.5 pt-3.5 border-t border-forest/7">
        <div class="cstat text-center">
            <div class="cstat-num font-syne font-bold text-deep">{{ $company['employees'] ?? '5K+' }}</div>
            <div class="cstat-lbl text-xs text-text-light">Employees</div>
        </div>
        <div class="cstat text-center">
            <div class="cstat-num font-syne font-bold text-deep">{{ $company['rating'] ?? '4.2' }}</div>
            <div class="cstat-lbl text-xs text-text-light">Rating</div>
        </div>
        <div class="cstat text-center">
            <div class="cstat-num font-syne font-bold text-deep">{{ $company['founded'] ?? "'98" }}</div>
            <div class="cstat-lbl text-xs text-text-light">Founded</div>
        </div>
    </div>
</div>
