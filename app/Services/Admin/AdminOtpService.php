<?php

namespace App\Services\Admin;

use App\Models\AdminOtp;
use App\Notifications\AdminOtpNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class AdminOtpService
{
    /**
     * Generate and send OTP to admin email
     */
    public function generateAndSendOtp(string $email, string $type = 'login'): AdminOtp
    {
        // Invalidate previous OTPs for this email
        AdminOtp::where('email', $email)
            ->where('type', $type)
            ->where('used_at', null)
            ->update(['expires_at' => now()]);

        $code = AdminOtp::generateCode();
        $expiresAt = now()->addMinutes(10);

        $otp = AdminOtp::create([
            'email' => $email,
            'code' => $code,
            'type' => $type,
            'expires_at' => $expiresAt,
        ]);

        // Send OTP via email
        try {
            Notification::route('mail', $email)
                ->notify(new AdminOtpNotification($code, $type));
        } catch (\Throwable $e) {
            Log::error('Failed to send admin OTP', ['email' => $email, 'error' => $e->getMessage()]);
        }

        return $otp;
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(string $email, string $code, string $type = 'login'): ?AdminOtp
    {
        $otp = AdminOtp::where('email', $email)
            ->where('code', $code)
            ->where('type', $type)
            ->latest()
            ->first();

        if (!$otp || !$otp->isValid()) {
            return null;
        }

        return $otp;
    }

    /**
     * Resend OTP (with rate limiting)
     */
    public function resendOtp(string $email, string $type = 'login'): AdminOtp
    {
        // Check if user already has a valid OTP sent in the last 2 minutes
        $recent = AdminOtp::where('email', $email)
            ->where('type', $type)
            ->where('used_at', null)
            ->where('created_at', '>=', now()->subMinutes(2))
            ->exists();

        if ($recent) {
            throw new \Exception('Please wait before requesting another OTP.');
        }

        return $this->generateAndSendOtp($email, $type);
    }
}
