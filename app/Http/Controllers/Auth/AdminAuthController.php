<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\AdminOtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{ Auth, Log };
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    private AdminOtpService $otpService;

    public function __construct(AdminOtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Show admin login form
     */
    public function loginShow(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Send OTP to admin email
     */
    public function requestOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // Verify the user is admin
        $user = User::query()->where('email', $validated['email'])->firstOrFail();
        if (!$user->isAdmin()) {
            throw ValidationException::withMessages([
                'email' => 'This email is not associated with an admin account.',
            ]);
        }

        try {
            $this->otpService->generateAndSendOtp($validated['email'], 'login');

            return redirect()
                ->route('admin.login.verify')
                ->with('email', $validated['email'])
                ->with('success', 'OTP sent to your email. It expires in 10 minutes.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again.']);
        }
    }

    /**
     * Show OTP verification form
     */
    public function verifyShow(Request $request): View|RedirectResponse
    {
        $email = $request->query('email') ?? session('email');

        if (!$email) {
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

        $otp = $this->otpService->verifyOtp($validated['email'], $validated['code'], 'login');

        if (!$otp) {
            throw ValidationException::withMessages([
                'code' => 'Invalid or expired OTP. Please try again.',
            ]);
        }

        $user = User::query()->where('email', $validated['email'])->firstOrFail();

        if (!$user->isAdmin()) {
            throw ValidationException::withMessages([
                'email' => 'Unauthorized access.',
            ]);
        }

        // Mark OTP as used
        $otp->markAsUsed();

        // Regenerate session to prevent fixation attacks
        $request->session()->regenerate();

        // Log the user in
        Auth::login($user, remember: true);

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

        try {
            $this->otpService->resendOtp($validated['email'], 'login');
            return back()->with('success', 'OTP resent to your email.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()]);
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

        // Verify admin code (fail closed: registration is disabled when no code is configured)
        $adminCode = config('app.admin_registration_code');
        if (blank($adminCode) || !hash_equals($adminCode, $validated['admin_code'])) {
            throw ValidationException::withMessages([
                'admin_code' => 'The admin registration code is invalid.',
            ]);
        }

        try {
            $this->otpService->generateAndSendOtp($validated['email'], 'registration');

            return redirect()
                ->route('admin.register.verify')
                ->with([
                    'email' => $validated['email'],
                    'admin_code' => $validated['admin_code'],
                ])
                ->with('success', 'OTP sent to your email. It expires in 10 minutes.');
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

        // Verify admin code again (fail closed: registration is disabled when no code is configured)
        $adminCode = config('app.admin_registration_code');
        if (blank($adminCode) || !hash_equals($adminCode, $validated['admin_code'])) {
            throw ValidationException::withMessages([
                'admin_code' => 'The admin registration code is invalid.',
            ]);
        }

        // Verify OTP
        $otp = $this->otpService->verifyOtp($validated['email'], $validated['code'], 'registration');
        if (!$otp) {
            throw ValidationException::withMessages([
                'code' => 'Invalid or expired OTP. Please try again.',
            ]);
        }

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => 'admin',
            'password' => Hash::make($validated['password']),
        ]);

        // Assign admin role if using spatie
        if (method_exists($user, 'assignRole')) {
            try {
                $user->assignRole('admin');
            } catch (\Throwable $e) {
                Log::warning('Failed to assign admin role', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        }

        // Mark OTP as used
        $otp->markAsUsed();

        event(new Registered($user));

        // Regenerate session to prevent fixation attacks
        $request->session()->regenerate();

        // Log the user in
        Auth::login($user);

        return redirect()->intended(route('filament.admin.pages.dashboard'))
            ->with('success', 'Admin account created successfully!');
    }
}
