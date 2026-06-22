<x-guest-layout>
    <div class="mb-6">
        <h1 class="font-syne text-3xl font-bold text-deep">{{ __('Welcome back') }}</h1>
        <p class="mt-2 text-sm text-text-mid">{{ __('Sign in to continue to your JobsUG workspace.') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-white/80 text-forest shadow-sm focus:ring-mint" name="remember">
                <span class="ms-2 text-sm text-text-mid">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6 flex items-center justify-between gap-4">
            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-sage hover:text-forest" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <p class="mt-6 text-center text-sm text-text-mid">
            {{ __('New to JobsUG?') }}
            <a href="{{ route('register') }}" class="font-semibold text-sage hover:text-forest">{{ __('Create an account') }}</a>
        </p>
    </form>
</x-guest-layout>
