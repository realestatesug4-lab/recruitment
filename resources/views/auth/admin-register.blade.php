<x-guest-layout>
    <div class="mb-8 text-center">
        <div class="mb-4 flex justify-center">
            <div class="rounded-lg bg-forest/10 p-3">
                <svg class="h-8 w-8 text-forest" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 7H7v6h6V7z" />
                    <path fill-rule="evenodd" d="M7 2a1 1 0 012 0v1h2V2a1 1 0 112 0v1h2V2a1 1 0 112 0v1h1a2 2 0 012 2v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v2h1a1 1 0 110 2h-1v1a2 2 0 01-2 2h-1v1a1 1 0 11-2 0v-1h-2v1a1 1 0 11-2 0v-1H9a2 2 0 01-2-2v-1H6a1 1 0 110-2h1v-2H6a1 1 0 010-2h1V9H6a1 1 0 010-2h1V6H7a2 2 0 01-2-2V2a1 1 0 012 0v1h2V2z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <h1 class="font-syne text-3xl font-bold text-deep">{{ __('Create Admin Account') }}</h1>
        <p class="mt-2 text-sm text-text-mid">{{ __('Secure registration with email verification') }}</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4">
            @foreach ($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.request-register-otp') }}">
        @csrf

        <!-- Admin Registration Code -->
        <div>
            <x-input-label for="admin_code" :value="__('Admin Registration Code')" />
            <x-text-input 
                id="admin_code" 
                class="block mt-2 w-full" 
                type="password" 
                name="admin_code" 
                required 
                autocomplete="off"
                placeholder="Enter your registration code" />
            <x-input-error :messages="$errors->get('admin_code')" class="mt-2" />
            <p class="mt-1 text-xs text-text-mid">{{ __('You\'ll receive this from your system administrator') }}</p>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input 
                id="email" 
                class="block mt-2 w-full" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autocomplete="email"
                placeholder="admin@cranelinks.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6 flex items-center justify-between gap-4">
            <a href="{{ route('admin.login') }}" class="text-sm font-semibold text-sage hover:text-forest">
                {{ __('Already have an account?') }}
            </a>

            <x-primary-button type="submit">
                {{ __('Send Verification Code') }}
            </x-primary-button>
        </div>

        <p class="mt-6 text-center text-xs text-text-mid">
            {{ __('Not an admin?') }}
            <a href="{{ route('register') }}" class="font-semibold text-sage hover:text-forest">
                {{ __('Create regular account') }}
            </a>
        </p>
    </form>

    <div class="mt-8 rounded-lg border border-blue-200 bg-blue-50 p-4">
        <div class="flex gap-3">
            <svg class="h-5 w-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 8a1 1 0 000 2h6a1 1 0 100-2H8zm1 5a1 1 0 11-2 0 1 1 0 012 0zm5-1a1 1 0 100 2h1a1 1 0 100-2h-1z" clip-rule="evenodd" />
            </svg>
            <div class="text-xs text-blue-800">
                <p class="font-semibold mb-1">{{ __('What is an Admin Registration Code?') }}</p>
                <p>{{ __('Only authorized administrators can create admin accounts. If you don\'t have a registration code, contact your system administrator.') }}</p>
            </div>
        </div>
    </div>
</x-guest-layout>
