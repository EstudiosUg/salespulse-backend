# Email Templates Usage Guide

This guide explains how to use the email templates in the SalesPulse application.

## Available Email Templates

All email templates are professionally designed without emojis, fully responsive, and follow SalesPulse branding.

### 1. Welcome User Email
**File:** `resources/views/emails/welcome-user.blade.php`  
**Mail Class:** `App\Mail\WelcomeUserMail`  
**Purpose:** Sent when a new user registers

**Usage:**
```php
use App\Mail\WelcomeUserMail;
use Illuminate\Support\Facades\Mail;

Mail::to($user->email)->send(new WelcomeUserMail($user));
```

---

### 2. New User Notification Email (Admin)
**File:** `resources/views/emails/new-user-notification.blade.php`  
**Mail Class:** `App\Mail\NewUserNotificationMail`  
**Purpose:** Notifies admins when a new user registers

**Usage:**
```php
use App\Mail\NewUserNotificationMail;
use Illuminate\Support\Facades\Mail;

$adminEmail = config('mail.admin_email', 'admin@estudios.ug');
Mail::to($adminEmail)->send(new NewUserNotificationMail($user));
```

---

### 3. Password Reset Email
**File:** `resources/views/emails/password-reset.blade.php`  
**Mail Class:** `App\Mail\PasswordResetMail`  
**Purpose:** Sent when a user requests a password reset

**Usage:**
```php
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;

// Generate a random reset code
$resetCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

// Store the code in database (create a password_resets table)
DB::table('password_resets')->insert([
    'email' => $user->email,
    'token' => $resetCode,
    'created_at' => now(),
]);

// Send the email
Mail::to($user->email)->send(new PasswordResetMail($user, $resetCode));
```

**Example Controller Method:**
```php
public function sendPasswordReset(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->email)->first();

    // Generate 6-digit reset code
    $resetCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // Store in database with expiration
    DB::table('password_resets')->updateOrInsert(
        ['email' => $user->email],
        [
            'token' => Hash::make($resetCode),
            'created_at' => now(),
        ]
    );

    // Send email
    Mail::to($user->email)->send(new PasswordResetMail($user, $resetCode));

    return response()->json([
        'message' => 'Password reset code sent to your email',
    ]);
}
```

---

### 4. Password Reset Success Email
**File:** `resources/views/emails/password-reset-success.blade.php`  
**Mail Class:** `App\Mail\PasswordResetSuccessMail`  
**Purpose:** Sent after a user successfully resets their password

**Usage:**
```php
use App\Mail\PasswordResetSuccessMail;
use Illuminate\Support\Facades\Mail;

// Basic usage
Mail::to($user->email)->send(new PasswordResetSuccessMail($user));

// With IP address and device info (recommended for security)
$ipAddress = $request->ip();
$device = $request->userAgent();
Mail::to($user->email)->send(new PasswordResetSuccessMail($user, $ipAddress, $device));
```

**Example in Password Reset Flow:**
```php
public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'code' => 'required|string|size:6',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Verify reset code (see verify method below)
    $user = User::where('email', $request->email)->first();
    
    // Update password
    $user->update([
        'password' => Hash::make($request->password),
    ]);

    // Send success confirmation email
    Mail::to($user->email)->send(
        new PasswordResetSuccessMail($user, $request->ip(), $request->userAgent())
    );

    return response()->json([
        'message' => 'Password reset successfully',
    ]);
}
```

---

### 5. Account Deleted Email
**File:** `resources/views/emails/account-deleted.blade.php`  
**Mail Class:** `App\Mail\AccountDeletedMail`  
**Purpose:** Sent when a user's account is deleted

**Usage:**
```php
use App\Mail\AccountDeletedMail;
use Illuminate\Support\Facades\Mail;

// Default restore period is 30 days
Mail::to($user->email)->send(new AccountDeletedMail($user));

// Or specify a custom restore period
Mail::to($user->email)->send(new AccountDeletedMail($user, 15)); // 15 days
```

**Example Controller Method:**
```php
public function deleteAccount(Request $request)
{
    $user = $request->user();

    // Send confirmation email before deletion
    Mail::to($user->email)->send(new AccountDeletedMail($user, 30));

    // Soft delete the user (requires soft deletes in User model)
    $user->delete();

    // Or mark for deletion
    $user->update([
        'deleted_at' => now(),
        'restore_deadline' => now()->addDays(30),
    ]);

    return response()->json([
        'message' => 'Account deleted successfully. You have 30 days to restore it.',
    ]);
}
```

---

## Database Migration for Password Resets

Create a migration for the password_resets table:

```php
php artisan make:migration create_password_resets_table
```

Migration file:
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            
            $table->index(['email', 'token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_resets');
    }
};
```

---

## Verify Password Reset Code

```php
public function verifyResetCode(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'code' => 'required|string|size:6',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Find the reset request
    $reset = DB::table('password_resets')
        ->where('email', $request->email)
        ->first();

    if (!$reset) {
        return response()->json([
            'message' => 'Invalid reset code',
        ], 400);
    }

    // Check if code is expired (60 minutes)
    if (now()->diffInMinutes($reset->created_at) > 60) {
        DB::table('password_resets')->where('email', $request->email)->delete();
        
        return response()->json([
            'message' => 'Reset code has expired',
        ], 400);
    }

    // Verify the code
    if (!Hash::check($request->code, $reset->token)) {
        return response()->json([
            'message' => 'Invalid reset code',
        ], 400);
    }

    // Update the password
    $user = User::where('email', $request->email)->first();
    $user->update([
        'password' => Hash::make($request->password),
    ]);

    // Delete the reset code
    DB::table('password_resets')->where('email', $request->email)->delete();

    return response()->json([
        'message' => 'Password reset successfully',
    ]);
}
```

---

## Email Configuration

Make sure your `.env` file has the correct mail configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@estudios.ug
MAIL_FROM_NAME="SalesPulse"
```

---

## Testing Emails

You can test emails using Laravel's testing features:

```php
use Illuminate\Support\Facades\Mail;

// Prevent actual email sending
Mail::fake();

// Your code that sends emails
Mail::to($user->email)->send(new PasswordResetMail($user, '123456'));

// Assert the email was sent
Mail::assertSent(PasswordResetMail::class, function ($mail) use ($user) {
    return $mail->user->id === $user->id;
});
```

---

## Queue Emails (Optional)

For better performance, you can queue emails:

1. Add `ShouldQueue` to the Mail class:
```php
class PasswordResetMail extends Mailable implements ShouldQueue
```

2. Send using queue:
```php
Mail::to($user->email)->queue(new PasswordResetMail($user, $resetCode));
```

3. Run the queue worker:
```bash
php artisan queue:work
```

---

## Complete Password Reset Flow

Here's the complete implementation of the password reset flow:

```php
// Step 1: User requests password reset
public function sendPasswordReset(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->email)->first();

    // Generate 6-digit reset code
    $resetCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // Store in database with expiration
    DB::table('password_resets')->updateOrInsert(
        ['email' => $user->email],
        [
            'token' => Hash::make($resetCode),
            'created_at' => now(),
        ]
    );

    // Send email with reset code
    Mail::to($user->email)->send(new PasswordResetMail($user, $resetCode));

    return response()->json([
        'message' => 'Password reset code sent to your email',
    ]);
}

// Step 2: User submits new password with code
public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'code' => 'required|string|size:6',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Find the reset request
    $reset = DB::table('password_resets')
        ->where('email', $request->email)
        ->first();

    if (!$reset) {
        return response()->json(['message' => 'Invalid reset code'], 400);
    }

    // Check if code is expired (60 minutes)
    if (now()->diffInMinutes($reset->created_at) > 60) {
        DB::table('password_resets')->where('email', $request->email)->delete();
        return response()->json(['message' => 'Reset code has expired'], 400);
    }

    // Verify the code
    if (!Hash::check($request->code, $reset->token)) {
        return response()->json(['message' => 'Invalid reset code'], 400);
    }

    // Update the password
    $user = User::where('email', $request->email)->first();
    $user->update([
        'password' => Hash::make($request->password),
    ]);

    // Delete the reset code
    DB::table('password_resets')->where('email', $request->email)->delete();

    // Send success confirmation email
    Mail::to($user->email)->send(
        new PasswordResetSuccessMail($user, $request->ip(), $request->userAgent())
    );

    return response()->json([
        'message' => 'Password reset successfully',
    ]);
}
```

---

## Email Summary

| Email Template | Purpose | Mail Class |
|---|---|---|
| `welcome-user.blade.php` | New user registration | `WelcomeUserMail` |
| `new-user-notification.blade.php` | Admin notification for new users | `NewUserNotificationMail` |
| `password-reset.blade.php` | Send reset code | `PasswordResetMail` |
| `password-reset-success.blade.php` | Confirm password changed | `PasswordResetSuccessMail` |
| `account-deleted.blade.php` | Account deletion confirmation | `AccountDeletedMail` |

---

## Design Features

All email templates feature:
- ✅ No emojis (professional design)
- ✅ Responsive layout (mobile-friendly)
- ✅ Modern gradients and colors
- ✅ Clean typography
- ✅ Professional structure
- ✅ Consistent branding
- ✅ Security-focused messaging
- ✅ Clear call-to-actions

---

## Support

For questions or issues, contact: support@estudios.ug

