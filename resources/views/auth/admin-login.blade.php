<x-guest-layout>
    <div class="mb-8 text-center">
        <div class="mb-4 flex justify-center">
            <div class="rounded-lg bg-forest/10 p-3">
                <svg class="h-8 w-8 text-forest" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <h1 class="font-syne text-3xl font-bold text-deep">{{ __('Admin Access') }}</h1>
        <p class="mt-2 text-sm text-text-mid">{{ __('Secure login with one-time password (OTP)') }}</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4">
            <p class="text-sm font-semibold text-red-800">{{ __('Login Failed') }}</p>
            @foreach ($errors->all() as $error)
                <p class="text-xs text-red-700 mt-1">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.request-otp') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Admin Email Address')" />
            <x-text-input
                id="email"
                class="block mt-2 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="email"
                placeholder="admin@cranelinks.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input
                id="password"
                class="block mt-2 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-6 flex items-center justify-between gap-4">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-sage hover:text-forest">
                {{ __('Back to main login') }}
            </a>

            <x-primary-button type="submit">
                {{ __('Send OTP') }}
            </x-primary-button>
        </div>

        <p class="mt-6 text-center text-xs text-text-mid">
            {{ __('Not an admin yet?') }}
            <a href="{{ route('admin.register') }}" class="font-semibold text-sage hover:text-forest">
                {{ __('Create admin account') }}
            </a>
        </p>
    </form>

    <div class="mt-8 rounded-lg border border-mint/20 bg-mint/5 p-4">
        <div class="flex gap-3">
            <svg class="h-5 w-5 text-mint flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 8a1 1 0 000 2h6a1 1 0 100-2H8zm1 5a1 1 0 11-2 0 1 1 0 012 0zm5-1a1 1 0 100 2h1a1 1 0 100-2h-1z" clip-rule="evenodd" />
            </svg>
            <div class="text-xs text-text-mid">
                <p class="font-semibold text-deep mb-1">{{ __('Enhanced Security') }}</p>
                <p>{{ __('We send a one-time password to your email for secure login. Check your email after clicking "Send OTP".') }}</p>
            </div>
        </div>
    </div>
</x-guest-layout>
