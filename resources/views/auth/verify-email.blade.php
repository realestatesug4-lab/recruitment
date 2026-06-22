<x-guest-layout>
    <div class="mb-6">
        <h1 class="font-syne text-3xl font-bold text-deep">{{ __('Verify your email') }}</h1>
        <p class="mt-2 text-sm text-text-mid">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-lg bg-mint/10 px-4 py-3 text-sm font-semibold text-sage">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="text-sm font-semibold text-sage hover:text-forest">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
