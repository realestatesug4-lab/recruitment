{{-- Notification bell with dropdown --}}
<div x-data="{ open: false }" class="relative">
    <button
        @click="open = !open"
        class="relative flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition"
        aria-label="Notifications"
    >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>

        {{-- Unread badge --}}
        @auth
            @php $unreadCount = auth()->user()->unreadNotifications()->count(); @endphp
            @if($unreadCount > 0)
                <span class="absolute -right-0.5 -top-0.5 flex h-4 min-w-[16px] items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            @endif
        @endauth
    </button>

    {{-- Dropdown --}}
    <div
        x-show="open"
        x-cloak
        @click.outside="open = false"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 z-50 mt-2 w-80 origin-top-right rounded-xl border border-gray-200 bg-white shadow-xl"
    >
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
            <h3 class="text-sm font-semibold text-deep">Notifications</h3>
            @auth
                @if($unreadCount > 0)
                    <a href="{{ route('notifications.mark-all-read') }}" class="text-xs font-medium text-forest hover:text-sage">Mark all read</a>
                @endif
            @endauth
        </div>

        <div class="max-h-80 overflow-y-auto">
            @auth
                @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                    <a href="{{ $notification->data['url'] ?? '#' }}"
                       class="flex items-start gap-3 px-4 py-3 transition hover:bg-gray-50 {{ is_null($notification->read_at) ? 'bg-forest/[0.02]' : '' }}">
                        <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full
                            {{ match($notification->data['type'] ?? '') {
                                'new_application' => 'bg-blue-100 text-blue-600',
                                'application_submitted' => 'bg-emerald-100 text-emerald-600',
                                'status_changed' => 'bg-purple-100 text-purple-600',
                                default => 'bg-gray-100 text-gray-600',
                            } }}">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-deep {{ is_null($notification->read_at) ? 'font-semibold' : '' }}">{{ $notification->data['message'] ?? 'Notification' }}</p>
                            <p class="mt-0.5 text-xs text-text-light">{{ $notification->created_at?->diffForHumans() }}</p>
                        </div>
                        @if(is_null($notification->read_at))
                            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-forest"></span>
                        @endif
                    </a>
                @empty
                    <div class="px-4 py-8 text-center text-sm text-text-mid">No notifications yet</div>
                @endforelse
            @endauth
        </div>
    </div>
</div>
