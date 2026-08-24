<x-guest-layout>
    <div class="mb-8">
        <h1 class="font-syne text-4xl font-bold text-deep">{{ __('Join Cranelinks') }}</h1>
        <p class="mt-2 text-sm text-text-mid">{{ __('Choose your role to get started. You can create an account in seconds.') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- Role Selection - Visual Cards -->
        <div class="mb-8">
            <label class="block text-sm font-semibold text-deep mb-4">{{ __('I am joining as') }}</label>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <!-- Seeker Card -->
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="seeker" @checked(old('role', 'seeker') === 'seeker') class="sr-only peer">
                    <div class="peer-checked:border-mint peer-checked:bg-mint/10 peer-checked:ring-2 peer-checked:ring-mint/30 transition-all p-4 rounded-lg border-2 border-white/50 hover:border-mint/50">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-sage peer-checked:text-mint" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-deep">{{ __('Job Seeker') }}</p>
                                <p class="text-xs text-text-mid">{{ __('Find and apply for jobs') }}</p>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Employer Card -->
                <label class="relative cursor-pointer">
                    <input type="radio" name="role" value="employer" @checked(old('role') === 'employer') class="sr-only peer">
                    <div class="peer-checked:border-mint peer-checked:bg-mint/10 peer-checked:ring-2 peer-checked:ring-mint/30 transition-all p-4 rounded-lg border-2 border-white/50 hover:border-mint/50">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-sage peer-checked:text-mint" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-deep">{{ __('Recruiter') }}</p>
                                <p class="text-xs text-text-mid">{{ __('Post jobs and hire talent') }}</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" 
                            placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <p class="mt-1 text-xs text-text-mid">{{ __('At least 8 characters with a mix of uppercase, lowercase, and numbers') }}</p>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" 
                            placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms Agreement -->
        <div class="mt-6 flex items-start gap-2">
            <input type="checkbox" id="agree" name="agree" class="mt-1 rounded border-white/80 text-forest focus:ring-mint" required>
            <label for="agree" class="text-xs text-text-mid">
                {{ __('I agree to the') }}
                <a href="#" class="font-semibold text-sage hover:text-forest">{{ __('Terms of Service') }}</a>
                {{ __('and') }}
                <a href="#" class="font-semibold text-sage hover:text-forest">{{ __('Privacy Policy') }}</a>
            </label>
        </div>

        <div class="mt-8 flex items-center justify-between gap-4">
            <div class="text-sm text-text-mid">
                {{ __('Already have an account?') }}
                <a class="font-semibold text-sage hover:text-forest" href="{{ route('login') }}">
                    {{ __('Log in') }}
                </a>
            </div>

            <x-primary-button type="submit">
                {{ __('Create Account') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-8 pt-8 border-t border-white/20">
        <p class="text-center text-xs text-text-mid">
            {{ __('Looking to manage ads or access the admin panel?') }}
            <a href="{{ route('admin.login') }}" class="font-semibold text-sage hover:text-forest">
                {{ __('Admin Login') }}
            </a>
        </p>
    </div>
</x-guest-layout>
