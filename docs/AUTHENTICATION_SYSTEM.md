# Cranelinks Authentication System - Complete Guide

## Overview

The authentication system has been completely redesigned with:

1. **Enhanced Seeker/Employer Registration** - Clear role selection with visual cards
2. **OTP-Based Admin Authentication** - Secure admin login and registration with one-time passwords
3. **Admin Gating Middleware** - Easy role-based access control for admin endpoints
4. **Unified `/admin` Routes** - Centralized admin authentication flow

---

## User Roles

### Seeker
- Browse jobs
- Apply for positions
- Manage applications
- Update profile

### Employer
- Post jobs
- Manage applications
- View candidates
- Employer dashboard

### Admin
- Manage all users
- Manage advertising
- View platform statistics
- Configure system settings

---

## Registration Flows

### Regular Registration (Seekers/Employers)
```
/register → User selects role (with visual cards) → Creates account
```

**Route**: `GET/POST /register`
**Controller**: `RegisteredUserController`
**View**: `resources/views/auth/register.blade.php`

**Features**:
- Clear role selection (Job Seeker vs Recruiter)
- Visual cards with icons and descriptions
- Terms agreement checkbox
- Link to admin login
- Validates email uniqueness
- Auto-redirects to onboarding/dashboard

### Admin Registration (OTP-Based)
```
/admin/register → Enter admin code + email → OTP sent → Enter OTP → Create account
```

**Routes**:
1. `GET /admin/register` - Show registration form
2. `POST /admin/register` - Request OTP verification
3. `GET /admin/register/verify` - Show OTP verification form
4. `POST /admin/register/verify` - Verify OTP and create account

**Controller**: `AdminAuthController`
**Views**: 
- `resources/views/auth/admin-register.blade.php` (initial form)
- `resources/views/auth/admin-verify-otp.blade.php` (OTP verification)

**Features**:
- Admin registration code validation
- Email verification via OTP
- OTP expires in 10 minutes
- Password confirmation
- Auto-assigns admin role
- Redirects to Filament admin dashboard

---

## Login Flows

### Regular Login (Seekers/Employers)
```
/login → Email + password → Dashboard/Onboarding
```

**Route**: `GET/POST /login`
**Controller**: `AuthenticatedSessionController`
**View**: `resources/views/auth/login.blade.php`

### Admin Login (OTP-Based)
```
/admin/login → Email → OTP sent → Enter OTP → Redirect to admin dashboard
```

**Routes**:
1. `GET /admin/login` - Show email form
2. `POST /admin/login` - Request OTP
3. `GET /admin/login/verify` - Show OTP verification form
4. `POST /admin/login/verify` - Verify OTP and login
5. `POST /admin/login/resend` - Resend OTP (rate-limited)

**Controller**: `AdminAuthController`
**Views**:
- `resources/views/auth/admin-login.blade.php` (email form)
- `resources/views/auth/admin-verify-otp.blade.php` (OTP verification)

**Features**:
- Email-based authentication (no password needed)
- OTP sent via email
- OTP expires in 10 minutes
- Rate-limited resend (max 1 per 2 minutes)
- Auto-remembers login for 12 months
- Secure session handling

---

## OTP System

### AdminOtpService

Located at: `app/Services/Admin/AdminOtpService.php`

**Methods**:
```php
// Generate OTP and send via email
generateAndSendOtp(string $email, string $type = 'login'): AdminOtp

// Verify OTP code
verifyOtp(string $email, string $code, string $type = 'login'): ?AdminOtp

// Resend OTP (with rate limiting)
resendOtp(string $email, string $type = 'login'): AdminOtp

// Get OTP details
getOtpDetails(string $email, string $type = 'login'): ?array
```

### AdminOtp Model

Located at: `app/Models/AdminOtp.php`

**Fields**:
- `user_id` - Associated user (nullable, null for registration OTPs)
- `email` - OTP recipient email
- `code` - 6-digit OTP code
- `type` - 'login' or 'registration'
- `expires_at` - Expiration timestamp (10 minutes)
- `used_at` - When OTP was used

**Methods**:
```php
static generateCode(): string      // Generate random 6-digit code
isExpired(): bool                  // Check if OTP expired
isUsed(): bool                     // Check if OTP already used
isValid(): bool                    // Check if OTP is still usable
markAsUsed(): void                 // Mark OTP as consumed
```

### OTP Notification

Located at: `app/Notifications/AdminOtpNotification.php`

Sends OTP via email with:
- 6-digit code
- Expiration time
- Type of OTP (login/registration)
- Security reminder

---

## Middleware & Access Control

### AdminGate Middleware

Located at: `app/Http/Middleware/AdminGate.php`

**Purpose**: Protect routes that require admin access

**Usage**:
```php
// In routes/web.php or routes/admin.php
Route::middleware('admin.gate')->group(function () {
    Route::get('/admin/dashboard', ...);
    // ... other admin routes
});
```

**Behavior**:
- Redirects unauthenticated users to `/admin/login`
- Denies access to non-admin users (403)
- Logs access attempts

### User Model Methods

```php
$user->isAdmin(): bool                    // Check if admin
$user->isSuperAdmin(): bool               // Check if super admin
$user->hasRole('admin'): bool             // Check spatie role
$user->canAccessPanel(Panel $panel): bool // Check panel access
```

---

## Database

### admin_otps Table
```
id               - Primary key
user_id          - Foreign key to users (nullable)
email            - Recipient email
code             - 6-digit OTP
type             - 'login' or 'registration'
expires_at       - Expiration time
used_at          - When OTP was verified
created_at       - Creation timestamp
updated_at       - Update timestamp

Indexes:
- [email, type]
- [code]
- [expires_at]
```

**Migration**: `database/migrations/2026_08_14_create_admin_otps_table.php`

---

## Configuration

### Environment Variables

Add to `.env`:
```env
# Admin registration code (change in production!)
ADMIN_REGISTRATION_CODE=ADMIN-SECRET-CODE-12345

# Mail configuration for OTP emails
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@cranelinks.com
MAIL_FROM_NAME="Cranelinks Admin"
```

### config/app.php

```php
'admin_registration_code' => env('ADMIN_REGISTRATION_CODE', ''),
```

---

## Routes

### Public Routes (Guest Middleware)

```php
// Regular Authentication
GET  /login                      - Login form
POST /login                      - Process login
GET  /register                   - Registration form (role selection)
POST /register                   - Create account

// Admin Authentication (OTP-based)
GET  /admin/login                - Admin email form
POST /admin/login                - Request OTP
GET  /admin/login/verify         - OTP verification form
POST /admin/login/verify         - Verify OTP and login
POST /admin/login/resend         - Resend OTP

GET  /admin/register             - Admin registration form
POST /admin/register             - Request registration OTP
GET  /admin/register/verify      - Registration OTP form
POST /admin/register/verify      - Verify OTP and create admin

// Password Reset
GET  /forgot-password            - Forgot password form
POST /forgot-password            - Send reset link
GET  /reset-password/{token}     - Reset form
POST /reset-password             - Process reset
```

### Protected Routes (Auth Middleware)

```php
GET  /verify-email               - Email verification prompt
GET  /verify-email/{id}/{hash}   - Verify email
POST /email/verification-notification - Send verification email
```

---

## Redirects

After registration/login, users are redirected based on their role:

```php
// Seekers
POST /register → /seeker/dashboard

// Employers
POST /register → /employer/onboarding

// Admins
POST /admin/register/verify → /admin (Filament dashboard)
POST /admin/login/verify → /admin (Filament dashboard)
```

---

## Usage Examples

### Checking Admin Access

```php
// In controller
if (auth()->user()->isAdmin()) {
    // Show admin features
}

// In Blade template
@admin
    <!-- Admin only content -->
@endadmin

// In middleware
Route::middleware(['auth', 'admin.gate'])->group(function () {
    // Admin only routes
});
```

### Sending OTP Programmatically

```php
use App\Services\Admin\AdminOtpService;

$service = app(AdminOtpService::class);

// Send login OTP
$otp = $service->generateAndSendOtp('admin@cranelinks.com', 'login');

// Verify OTP
$verified = $service->verifyOtp('admin@cranelinks.com', '123456', 'login');

if ($verified && $verified->isValid()) {
    // Login user
    $verified->markAsUsed();
}
```

### Customizing Email Template

Edit: `app/Notifications/AdminOtpNotification.php`

The email includes:
- Subject line (different for login/registration)
- Main message
- Large 6-digit code
- Expiration notice
- Security disclaimer
- Link to admin login

---

## Security Considerations

1. **OTP Validation**
   - OTPs expire after 10 minutes
   - OTPs can only be used once
   - Invalid OTPs don't reveal whether email exists

2. **Rate Limiting**
   - Resend OTP limited to 1 per 2 minutes per email
   - Prevents OTP enumeration attacks

3. **Session Security**
   - Admin sessions are remembered for 12 months
   - CSRF protection on all forms
   - Email verification required for registration

4. **Access Control**
   - Admin routes protected by middleware
   - Role-based Filament panel access
   - Super admin cannot be downgraded

---

## Troubleshooting

### OTP Not Sending

1. Check mail configuration in `.env`
2. Verify MAIL_FROM_ADDRESS is set
3. Check Laravel logs: `storage/logs/laravel.log`
4. Test email: `php artisan tinker` → `Mail::raw('test', fn($m) => $m->to('admin@test.com'));`

### "Invalid OTP" Error

1. OTP may have expired (10 minute window)
2. OTP may have already been used
3. Check that OTP was entered correctly (case-insensitive)
4. Click "Resend OTP" to get a new code

### "Unauthorized Access"

1. User must be assigned admin role
2. Check `users.role` = 'admin'
3. Check `role_user` table if using spatie/laravel-permission
4. Verify `is_super_admin` flag if needed

### Admin Link Not Working

1. Ensure routes are registered in `routes/auth.php`
2. Check `AdminAuthController` is imported
3. Verify `AdminOtpService` is available in container
4. Check Laravel cache: `php artisan cache:clear`

---

## Next Steps

1. **Run Migrations**
   ```bash
   php artisan migrate
   ```

2. **Set Admin Code in .env**
   ```
   ADMIN_REGISTRATION_CODE=your-secret-code
   ```

3. **Test Admin Login**
   - Visit `/admin/login`
   - Enter admin email
   - Check email for OTP
   - Enter OTP to login

4. **Test Regular Registration**
   - Visit `/register`
   - Select role (Job Seeker or Recruiter)
   - Create account
   - Verify redirect to appropriate dashboard

5. **Configure Email**
   - Set up mail service (SMTP, Mailgun, SendGrid, etc.)
   - Test email sending
   - Customize email template if needed

---

## File Summary

**Controllers**:
- `app/Http/Controllers/Auth/AdminAuthController.php` - OTP admin auth (NEW)
- `app/Http/Controllers/Auth/RegisteredUserController.php` - Regular registration (unchanged)
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php` - Regular login (unchanged)

**Models**:
- `app/Models/AdminOtp.php` - OTP model (NEW)
- `app/Models/User.php` - User model (unchanged)

**Services**:
- `app/Services/Admin/AdminOtpService.php` - OTP service (NEW)

**Notifications**:
- `app/Notifications/AdminOtpNotification.php` - OTP email (NEW)

**Middleware**:
- `app/Http/Middleware/AdminGate.php` - Admin protection (NEW)

**Views**:
- `resources/views/auth/register.blade.php` - Enhanced with role cards (UPDATED)
- `resources/views/auth/admin-login.blade.php` - Admin email form (UPDATED)
- `resources/views/auth/admin-register.blade.php` - Admin registration (UPDATED)
- `resources/views/auth/admin-verify-otp.blade.php` - OTP verification (NEW)
- `resources/views/auth/login.blade.php` - Regular login (unchanged)

**Routes**:
- `routes/auth.php` - Admin OTP routes (UPDATED)

**Migrations**:
- `database/migrations/2026_08_14_create_admin_otps_table.php` - OTP table (NEW)

---

## Quick Reference

| Feature | URL | Method | Notes |
|---------|-----|--------|-------|
| Register (role selection) | `/register` | GET/POST | Visual role cards |
| Regular Login | `/login` | GET/POST | Email + password |
| Admin Login | `/admin/login` | GET/POST | Email → OTP → Login |
| Admin Register | `/admin/register` | GET/POST | Code + Email → OTP → Create |
| OTP Verify | `/admin/*/verify` | GET/POST | 6-digit code entry |
| Resend OTP | `/admin/login/resend` | POST | Rate-limited |

