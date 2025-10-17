# Forgot Password Implementation Guide

## Overview
A complete forgot password feature has been implemented for the SalesPulse app, allowing users to reset their password via email using a 6-digit verification code.

## Implementation Details

### Backend Implementation

#### 1. Database Schema
The `password_reset_tokens` table already exists in the database with the following structure:
- `email` (primary key)
- `token` (hashed reset code)
- `created_at` (timestamp for expiration check)

#### 2. API Endpoints

**Forgot Password Endpoint**
- **URL**: `/api/forgot-password`
- **Method**: POST
- **Access**: Public (no authentication required)
- **Request Body**:
  ```json
  {
    "email": "user@example.com"
  }
  ```
- **Response**: 
  ```json
  {
    "success": true,
    "message": "Password reset code sent to your email"
  }
  ```
- **Functionality**:
  - Validates the email exists in the system
  - Generates a random 6-digit code
  - Stores hashed code in database with timestamp
  - Sends code to user's email
  - Code expires after 60 minutes

**Reset Password Endpoint**
- **URL**: `/api/reset-password`
- **Method**: POST
- **Access**: Public (no authentication required)
- **Request Body**:
  ```json
  {
    "email": "user@example.com",
    "code": "123456",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
  }
  ```
- **Response**: 
  ```json
  {
    "success": true,
    "message": "Password reset successfully"
  }
  ```
- **Functionality**:
  - Validates the reset code matches the email
  - Checks code hasn't expired (60 minutes)
  - Validates new password meets requirements (min 8 characters)
  - Updates user password
  - Deletes reset token from database

#### 3. AuthController Methods

**`forgotPassword(Request $request)`**
```php
- Validates email format
- Checks if user exists
- Generates 6-digit code
- Stores hashed token in password_reset_tokens table
- Sends email with plain code
- Returns success message
```

**`resetPassword(Request $request)`**
```php
- Validates email, code, and new password
- Checks reset token exists and hasn't expired
- Verifies code matches stored hash
- Updates user password
- Deletes reset token
- Returns success message
```

### Frontend Implementation (Flutter)

#### 1. API Service Methods

**Location**: `salespulse/lib/services/api_service.dart`

**`forgotPassword(String email)`**
- Sends POST request to `/api/forgot-password`
- Returns void on success
- Throws exception with server message on failure

**`resetPassword({email, code, password, passwordConfirmation})`**
- Sends POST request to `/api/reset-password`
- Returns void on success
- Throws exception with server message on failure

#### 2. Login Screen UI

**Location**: `salespulse/lib/screens/login_screen.dart`

**Forgot Password Link**
- Positioned below password field, aligned right
- Opens forgot password dialog when tapped

**Forgot Password Dialog (`_showForgotPasswordDialog()`)**
- Prompts user to enter their email
- Validates email format
- Calls `forgotPassword` API
- Shows success message
- Automatically opens reset password dialog

**Reset Password Dialog (`_showResetPasswordDialog(String email)`)**
- Shows email address for reference
- Collects 6-digit verification code
- Collects new password and confirmation
- Validates:
  - Code is 6 digits
  - Password is at least 8 characters
  - Passwords match
- Calls `resetPassword` API
- Shows success message prompting user to login
- Non-dismissible (user must complete or cancel)

## User Flow

1. **User clicks "Forgot Password?" on login screen**
2. **Email Entry Dialog appears**
   - User enters their email address
   - Clicks "Send Code"
3. **Backend sends 6-digit code to email**
4. **Reset Password Dialog appears**
   - User enters the 6-digit code from email
   - User enters new password
   - User confirms new password
   - Clicks "Reset Password"
5. **Password is reset successfully**
   - Success message displayed
   - User can now login with new password

## Security Features

1. **Code Hashing**: Reset codes are hashed before storage
2. **Time Expiration**: Codes expire after 60 minutes
3. **One-Time Use**: Tokens are deleted after successful password reset
4. **Email Verification**: Only users with verified email addresses can reset
5. **Password Validation**: New passwords must be at least 8 characters
6. **Throttling**: Built-in Laravel throttling prevents abuse (60 seconds between requests)

## Email Configuration

Ensure your Laravel `.env` file has proper mail configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@salespulse.com
MAIL_FROM_NAME="SalesPulse"
```

For production, configure with actual SMTP provider (SendGrid, Mailgun, AWS SES, etc.)

## Testing the Feature

### Backend Testing (via cURL)

**1. Request Reset Code:**
```bash
curl -X POST http://your-api-url/api/forgot-password \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email": "user@example.com"}'
```

**2. Reset Password:**
```bash
curl -X POST http://your-api-url/api/reset-password \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "user@example.com",
    "code": "123456",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
  }'
```

### Frontend Testing

1. Open the app and navigate to login screen
2. Click "Forgot Password?" link
3. Enter a valid email and click "Send Code"
4. Check email inbox for 6-digit code
5. Enter the code in reset password dialog
6. Enter and confirm new password
7. Click "Reset Password"
8. Verify success message
9. Login with new password

## Error Handling

The implementation handles various error scenarios:

- ❌ **Email not found**: "No account found with that email address"
- ❌ **Invalid code**: "Invalid reset code"
- ❌ **Expired code**: "Reset code has expired. Please request a new one."
- ❌ **Password mismatch**: "Passwords do not match"
- ❌ **Short password**: "Password must be at least 8 characters"
- ❌ **Email send failure**: "Failed to send reset email. Please try again later."

## Files Modified

### Backend
1. `app/Http/Controllers/Api/AuthController.php` - Added forgotPassword and resetPassword methods
2. `routes/api.php` - Added forgot/reset password routes

### Frontend
1. `salespulse/lib/services/api_service.dart` - Added forgotPassword and resetPassword API methods
2. `salespulse/lib/screens/login_screen.dart` - Added forgot password UI and dialogs

## Future Enhancements

Potential improvements for future versions:

1. **Email Templates**: Use Laravel's Mailable classes for prettier emails
2. **SMS Option**: Allow password reset via SMS for phone-based accounts
3. **Rate Limiting UI**: Show countdown timer if user requests too many codes
4. **Resend Code**: Add option to resend code if not received
5. **Security Questions**: Optional security questions as additional verification
6. **Account Lock**: Lock account after multiple failed reset attempts

## Troubleshooting

**Issue**: Emails not being sent
- **Solution**: Check mail configuration in `.env` file
- **Solution**: Use `php artisan queue:work` if using queued mail
- **Solution**: Check Laravel logs: `storage/logs/laravel.log`

**Issue**: Code always invalid
- **Solution**: Verify code is being entered correctly (no spaces)
- **Solution**: Check system time is synchronized (for expiration check)

**Issue**: User not found error
- **Solution**: Ensure email exists in users table
- **Solution**: Check email is exactly as registered (case-sensitive)

## Support

For issues or questions about the forgot password feature, please refer to:
- Laravel Password Reset Documentation
- Flutter Form Validation Best Practices
- API_ENDPOINTS_GUIDE.md for full API documentation

