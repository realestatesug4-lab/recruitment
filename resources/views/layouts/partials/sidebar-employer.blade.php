{{-- Employer sidebar navigation --}}
@php
    $employerNav = [
        [
            'label' => 'Dashboard',
            'route' => 'employer.dashboard',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
        ],
        [
            'label' => 'Post a Job',
            'route' => 'employer.jobs.create',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />',
        ],
        [
            'label' => 'Manage Jobs',
            'route' => 'employer.jobs.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />',
        ],
        [
            'label' => 'ATS Pipeline',
            'route' => 'employer.ats',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />',
        ],
        [
            'label' => 'Applications',
            'route' => 'employer.applications.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />',
        ],
        [
            'label' => 'Company Profile',
            'route' => 'employer.company.edit',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />',
        ],
    ];
@endphp

<div class="space-y-1">
    <p x-show="!sidebarCollapsed" class="px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Employer</p>

    @foreach($employerNav as $item)
        @php
            $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*');
        @endphp
        <a
            href="{{ route($item['route']) }}"
            class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-150
                   {{ $isActive
                       ? 'bg-forest/10 text-forest shadow-sm'
                       : 'text-gray-600 hover:bg-gray-50 hover:text-deep' }}"
            :title="sidebarCollapsed ? '{{ $item['label'] }}' : ''"
        >
            <svg class="h-5 w-5 shrink-0 {{ $isActive ? 'text-forest' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                {!! $item['icon'] !!}
            </svg>
            <span x-show="!sidebarCollapsed" class="truncate">{{ $item['label'] }}</span>
        </a>
    @endforeach
</div>

<div class="mt-6 space-y-1">
    <p x-show="!sidebarCollapsed" class="px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Account</p>

    <a href="{{ route('profile.edit') }}"
       class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-deep transition"
       :title="sidebarCollapsed ? 'Settings' : ''">
        <svg class="h-5 w-5 shrink-0 text-gray-400 group-hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span x-show="!sidebarCollapsed" class="truncate">Settings</span>
    </a>
</div>
