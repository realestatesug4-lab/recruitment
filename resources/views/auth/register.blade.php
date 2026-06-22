<x-guest-layout>
    <div class="mb-6">
        <h1 class="font-syne text-3xl font-bold text-deep">{{ __('Create your account') }}</h1>
        <p class="mt-2 text-sm text-text-mid">{{ __('Join JobsUG to apply for roles or manage hiring activity.') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="role" :value="__('I am joining as')" />
            <select id="role" name="role" class="mt-1 block w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep outline-none focus:border-mint focus:ring-2 focus:ring-mint/30">
                <option value="seeker" @selected(old('role', 'seeker') === 'seeker')>Job seeker</option>
                <option value="employer" @selected(old('role') === 'employer')>Company recruiter</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6 flex items-center justify-between gap-4">
            <a class="text-sm font-semibold text-sage hover:text-forest" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button>
                {{ __('Create account') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
