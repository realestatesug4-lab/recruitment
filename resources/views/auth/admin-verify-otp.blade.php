<x-guest-layout>
    <div class="mb-8 text-center">
        <div class="mb-4 flex justify-center">
            <div class="rounded-lg bg-mint/10 p-3">
                <svg class="h-8 w-8 text-mint" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1V3a1 1 0 011-1h5a1 1 0 011 1v1h1V3a1 1 0 011 1v2h2a2 2 0 012 2v2h1a1 1 0 110 2h-1v1h1a1 1 0 110 2h-1v1h1a1 1 0 110 2h-1v5a2 2 0 01-2 2H4a2 2 0 01-2-2V7a2 2 0 012-2h2V3a1 1 0 011-1zm0 5a1 1 0 000 2h12a1 1 0 100-2H5z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <h1 class="font-syne text-3xl font-bold text-deep">{{ __('Verify Your OTP') }}</h1>
        <p class="mt-2 text-sm text-text-mid">
            {{ __('Enter the 6-digit code sent to') }}
            <br>
            <span class="font-semibold text-deep">{{ $email }}</span>
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4">
            @foreach ($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if ($type === 'registration')
        <form method="POST" action="{{ route('admin.verify-register-otp') }}" id="otpForm">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="admin_code" value="{{ $admin_code ?? '' }}">

            <!-- OTP Code -->
            <div class="mb-6">
                <x-input-label for="code" :value="__('One-Time Password')" />
                <input
                    id="code"
                    type="text"
                    name="code"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    maxlength="6"
                    class="block mt-2 w-full text-center text-4xl tracking-widest font-mono rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-deep outline-none focus:border-mint focus:ring-2 focus:ring-mint/30"
                    placeholder="000000"
                    required
                    autofocus>
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            <!-- Full Name (for registration only) -->
            @if ($type === 'registration')
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Full Name')" />
                    <x-text-input
                        id="name"
                        class="block mt-2 w-full"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autocomplete="name"
                        placeholder="John Doe" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input
                        id="password"
                        class="block mt-2 w-full"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input
                        id="password_confirmation"
                        class="block mt-2 w-full"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            @endif

            <div class="mt-8 flex items-center justify-between gap-4">
                <div class="text-sm">
                    <p class="text-text-mid">{{ __('Didn\'t receive the code?') }}</p>
                    <button type="button" onclick="document.getElementById('resendOtpForm').submit()" class="mt-1 font-semibold text-sage hover:text-forest">
                        {{ __('Resend OTP') }}
                    </button>
                </div>

                <x-primary-button type="submit">
                    @if ($type === 'registration')
                        {{ __('Complete Registration') }}
                    @else
                        {{ __('Verify & Login') }}
                    @endif
                </x-primary-button>
            </div>
        </form>
    @else
        <form method="POST" action="{{ route('admin.verify-otp') }}" id="otpForm">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">

            <!-- OTP Code -->
            <div class="mb-6">
                <x-input-label for="code" :value="__('One-Time Password')" />
                <input
                    id="code"
                    type="text"
                    name="code"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    maxlength="6"
                    class="block mt-2 w-full text-center text-4xl tracking-widest font-mono rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-deep outline-none focus:border-mint focus:ring-2 focus:ring-mint/30"
                    placeholder="000000"
                    required
                    autofocus>
                <x-input-error :messages="$errors->get('code')" class="mt-2" />
            </div>

            <div class="mt-8 flex items-center justify-between gap-4">
                <div class="text-sm">
                    <p class="text-text-mid">{{ __('Didn\'t receive the code?') }}</p>
                    <button type="button" onclick="document.getElementById('resendOtpForm').submit()" class="mt-1 font-semibold text-sage hover:text-forest">
                        {{ __('Resend OTP') }}
                    </button>
                </div>

                <x-primary-button type="submit">
                    {{ __('Verify & Login') }}
                </x-primary-button>
            </div>
        </form>
    @endif

    <form method="POST" action="{{ route('admin.resend-otp') }}" id="resendOtpForm" class="hidden">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
    </form>

    <div class="mt-8 rounded-lg border border-amber-200 bg-amber-50 p-4">
        <div class="flex gap-3">
            <svg class="h-5 w-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div class="text-xs text-amber-800">
                <p class="font-semibold mb-1">{{ __('OTP Expires Soon') }}</p>
                <p>{{ __('This code expires in 10 minutes. Make sure to enter it correctly.') }}</p>
            </div>
        </div>
    </div>

    <script>
        // Auto-format OTP input
        document.getElementById('code').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);

            // Auto-submit when 6 digits entered
            if (this.value.length === 6) {
                // Uncomment to auto-submit:
                // document.getElementById('otpForm').submit();
            }
        });
    </script>
</x-guest-layout>
