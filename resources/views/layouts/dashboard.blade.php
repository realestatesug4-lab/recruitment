<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'CraneLinks'))</title>

    <link rel="icon" type="image/png" href="{{ asset('images/cranelinks.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=Manrope:wght@200..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="font-sans antialiased text-text-dark bg-gray-50" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
    <div class="flex h-screen overflow-hidden">

        {{-- ── Mobile overlay ── --}}
        <div
            x-show="sidebarOpen"
            x-cloak
            x-transition:enter="transition-opacity ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/30 backdrop-blur-sm lg:hidden"
        ></div>

        {{-- ── Sidebar ── --}}
        <aside
            :class="[
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                sidebarCollapsed ? 'lg:w-20' : 'lg:w-72',
                'fixed inset-y-0 left-0 z-50 flex flex-col border-r border-gray-200/80 bg-white transition-all duration-300 lg:translate-x-0 lg:static'
            ]"
        >
            {{-- Brand --}}
            <div class="flex h-16 items-center gap-3 border-b border-gray-100 px-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="h-9 w-9 rounded-lg overflow-hidden bg-white shadow-sm ring-1 ring-gray-200 transition-transform group-hover:scale-105">
                        <img src="{{ asset('images/cranelinks.png') }}" alt="CraneLinks" class="h-full w-full object-cover">
                    </div>
                    <span x-show="!sidebarCollapsed" class="font-bold text-lg tracking-tight text-forest">CraneLinks</span>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4">
                @auth
                    @if(auth()->user()->role === 'employer' || auth()->user()->employerProfile)
                        @include('layouts.partials.sidebar-employer')
                    @else
                        @include('layouts.partials.sidebar-seeker')
                    @endif
                @endauth
            </nav>

            {{-- Collapse toggle (desktop only) --}}
            <div class="hidden lg:flex items-center justify-center border-t border-gray-100 py-3">
                <button
                    @click="sidebarCollapsed = !sidebarCollapsed"
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition"
                    :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
                >
                    <svg class="h-4 w-4 transition-transform" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>
        </aside>

        {{-- ── Main content area ── --}}
        <div class="flex flex-1 flex-col overflow-hidden">

            {{-- Top bar --}}
            <header class="flex h-16 items-center justify-between border-b border-gray-200/80 bg-white px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    {{-- Mobile hamburger --}}
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{-- Breadcrumbs --}}
                    <div class="hidden sm:flex items-center gap-2 text-sm text-text-mid">
                        @hasSection('breadcrumbs')
                            @yield('breadcrumbs')
                        @else
                            <span class="text-text-light">{{ auth()->user()->role === 'employer' || auth()->user()->employerProfile ? 'Employer' : 'Seeker' }}</span>
                            <svg class="h-3 w-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            <span class="font-medium text-deep">@yield('page_title', 'Dashboard')</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Notifications bell --}}
                    @include('layouts.partials.notification-bell')

                    {{-- User menu --}}
                    @include('layouts.partials.user-menu')
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto">
                {{-- Flash messages --}}
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 translate-y-[-8px]"
                         class="mx-4 sm:mx-6 lg:mx-8 mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 flex items-center justify-between">
                        <span>{{ session('success') }}</span>
                        <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">&times;</button>
                    </div>
                @endif
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 translate-y-[-8px]"
                         class="mx-4 sm:mx-6 lg:mx-8 mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800 flex items-center justify-between">
                        <span>{{ session('error') }}</span>
                        <button @click="show = false" class="text-rose-600 hover:text-rose-800">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
