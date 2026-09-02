<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{ Auth, Hash, Log };
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminAuthController extends Controller
{

    /**
     * Show admin login form
     */
    public function loginShow(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Send OTP to admin email after validating the password
     */
    public function requestOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if (! $user || ! $user->isAdmin() || ! Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Invalid admin credentials.',
            ]);
        }

        try {
            $user->sendOneTimePassword();

            $request->session()->put('admin_otp_email', $user->email);

            return redirect()
                ->route('admin.login.verify')
                ->with('email', $user->email)
                ->with('success', 'A verification code has been sent to your email.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again.']);
        }
    }

    /**
     * Show OTP verification form
     */
    public function verifyShow(Request $request): View|RedirectResponse
    {
        $email = $request->query('email') ?? session('email') ?? session('admin_otp_email');

        if (! $email) {
            return redirect()->route('admin.login');
        }

        return view('auth.admin-verify-otp', [
            'email' => $email,
            'type' => 'login',
        ]);
    }

    /**
     * Verify OTP and log in
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if (! $user || ! $user->isAdmin()) {
            throw ValidationException::withMessages([
                'email' => 'Unauthorized access.',
            ]);
        }

        $result = $user->attemptLoginUsingOneTimePassword($validated['code'], remember: true);

        if (! $result->isOk()) {
            throw ValidationException::withMessages([
                'code' => $result->validationMessage(),
            ]);
        }

        $request->session()->forget('admin_otp_email');
        $request->session()->regenerate();

        return redirect()->intended(route('filament.admin.pages.dashboard'));
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if (! $user || ! $user->isAdmin()) {
            throw ValidationException::withMessages([
                'email' => 'Invalid admin credentials.',
            ]);
        }

        try {
            $user->sendOneTimePassword();

            return back()->with('success', 'A new verification code has been sent to your email.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to resend OTP. Please try again.']);
        }
    }

    /**
     * Show admin registration form
     */
    public function registerShow(): View
    {
        return view('auth.admin-register');
    }

    /**
     * Send OTP for registration
     */
    public function requestRegistrationOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'admin_code' => ['required', 'string'],
        ]);

        $adminCode = config('app.admin_registration_code');
        if (blank($adminCode) || ! hash_equals($adminCode, $validated['admin_code'])) {
            throw ValidationException::withMessages([
                'admin_code' => 'The admin registration code is invalid.',
            ]);
        }

        try {
            $user = new User([
                'email' => $validated['email'],
            ]);

            $user->sendOneTimePassword();

            return redirect()
                ->route('admin.register.verify')
                ->with([
                    'email' => $validated['email'],
                    'admin_code' => $validated['admin_code'],
                ])
                ->with('success', 'A verification code has been sent to your email.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again.']);
        }
    }

    /**
     * Show registration OTP verification form
     */
    public function registerVerifyShow(Request $request): View|RedirectResponse
    {
        $email = $request->query('email') ?? session('email');
        $adminCode = session('admin_code');

        if (!$email || !$adminCode) {
            return redirect()->route('admin.register');
        }

        return view('auth.admin-verify-otp', [
            'email' => $email,
            'type' => 'registration',
            'admin_code' => $adminCode,
        ]);
    }

    /**
     * Complete registration with OTP verification
     */
    public function verifyRegistrationOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'admin_code' => ['required', 'string'],
        ]);

        $adminCode = config('app.admin_registration_code');
        if (blank($adminCode) || ! hash_equals($adminCode, $validated['admin_code'])) {
            throw ValidationException::withMessages([
                'admin_code' => 'The admin registration code is invalid.',
            ]);
        }

        $user = User::query()->where('email', $validated['email'])->first();
        if (! $user) {
            $user = new User([
                'email' => $validated['email'],
            ]);
        }

        $result = $user->consumeOneTimePassword($validated['code']);

        if (! $result->isOk()) {
            throw ValidationException::withMessages([
                'code' => $result->validationMessage(),
            ]);
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'admin',
            'password' => $validated['password'],
        ]);

        $user->save();

        if (method_exists($user, 'assignRole')) {
            try {
                $user->assignRole('admin');
            } catch (\Throwable $e) {
                Log::warning('Failed to assign admin role', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        }

        event(new Registered($user));

        $request->session()->regenerate();

        Auth::login($user);

        return redirect()->intended(route('filament.admin.pages.dashboard'))
            ->with('success', 'Admin account created successfully!');
    }
}
