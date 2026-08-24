{{-- Seeker sidebar navigation --}}
@php
    $seekerNav = [
        [
            'label' => 'Dashboard',
            'route' => 'seeker.dashboard',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
        ],
        [
            'label' => 'Browse Jobs',
            'route' => 'jobs.index',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />',
        ],
        [
            'label' => 'My Profile',
            'route' => auth()->user()->seekerProfile ? 'seeker.profile.show' : 'seeker.profile.create',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />',
        ],
        [
            'label' => 'Applications',
            'route' => 'seeker.applications.progress',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />',
        ],
        [
            'label' => 'Saved Jobs',
            'route' => 'seeker.saved-jobs',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />',
        ],
        [
            'label' => 'AI Career Tools',
            'route' => 'seeker.ai-tools',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />',
        ],
    ];
@endphp

<div class="space-y-1">
    <p x-show="!sidebarCollapsed" class="px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-gray-400">Job Seeker</p>

    @foreach($seekerNav as $item)
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
