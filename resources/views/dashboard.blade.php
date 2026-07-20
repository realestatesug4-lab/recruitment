<x-app-layout>
    <x-slot name="header">
        <div class="page-wrap py-8">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-sage">Workspace</p>
                    <h2 class="mt-2 text-3xl font-syne font-bold text-deep">{{ __('Dashboard') }}</h2>
                    <p class="mt-2 text-sm text-text-mid">A polished overview of your activity, opportunities, and next steps.</p>
                </div>
                <div class="rounded-2xl border border-white/70 bg-white/70 px-4 py-3 text-sm text-text-mid shadow-sm">
                    {{ __('You are logged in and ready to move forward.') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="page-wrap space-y-6">
            <div class="glass rounded-3xl p-6">
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4">
                        <div class="text-sm font-semibold uppercase tracking-[0.2em] text-text-light">Today</div>
                        <div class="mt-2 text-2xl font-syne font-bold text-deep">Status is active</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4">
                        <div class="text-sm font-semibold uppercase tracking-[0.2em] text-text-light">Focus</div>
                        <div class="mt-2 text-2xl font-syne font-bold text-deep">Stay productive</div>
                    </div>
                    <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4">
                        <div class="text-sm font-semibold uppercase tracking-[0.2em] text-text-light">Next step</div>
                        <div class="mt-2 text-2xl font-syne font-bold text-deep">Review opportunities</div>
                    </div>
                </div>
            </div>

            <div class="glass rounded-3xl p-6">
                <div class="text-lg font-semibold text-deep">{{ __('Welcome back') }}</div>
                <p class="mt-2 text-sm text-text-mid">{{ __("You're logged in!") }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
