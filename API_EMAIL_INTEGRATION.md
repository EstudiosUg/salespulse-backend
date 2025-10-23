# API Email Integration Guide

This guide shows how the email templates are integrated with the SalesPulse API endpoints.

## 📧 Email Flow Integration

All email templates are now fully integrated with the backend API. Emails are sent automatically when users perform specific actions.

---

## 1. User Registration

**Endpoint:** `POST /api/register`

**Request Body:**
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone_number": "+256700123456",
    "password": "SecurePass123",
    "password_confirmation": "SecurePass123"
}
```

**Emails Sent:**
1. ✅ **Welcome Email** to user (`welcome-user.blade.php`)
2. ✅ **Admin Notification** to manager (`new-user-notification.blade.php`)

**Response:**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": { ... },
        "token": "...",
        "token_type": "Bearer"
    }
}
```

---

## 2. Forgot Password (Request Reset Code)

**Endpoint:** `POST /api/forgot-password`

**Request Body:**
```json
{
    "email": "john@example.com"
}
```

**Emails Sent:**
1. ✅ **Password Reset Email** with 6-digit code (`password-reset.blade.php`)
   - Code expires in 60 minutes
   - Professional template with security warnings

**Response:**
```json
{
    "success": true,
    "message": "Password reset code sent to your email"
}
```

**Frontend Flow:**
1. User enters email
2. User receives email with 6-digit code
3. User enters code and new password in app
4. User submits to `/api/reset-password`

---

## 3. Reset Password (Verify Code & Change Password)

**Endpoint:** `POST /api/reset-password`

**Request Body:**
```json
{
    "email": "john@example.com",
    "code": "123456",
    "password": "NewSecurePass123",
    "password_confirmation": "NewSecurePass123"
}
```

**Emails Sent:**
1. ✅ **Password Reset Success** confirmation (`password-reset-success.blade.php`)
   - Includes IP address and device info
   - Security alert if not initiated by user

**Response:**
```json
{
    "success": true,
    "message": "Password reset successfully"
}
```

---

## 4. Change Password (Authenticated)

**Endpoint:** `POST /api/change-password`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "current_password": "OldPassword123",
    "new_password": "NewSecurePass123",
    "new_password_confirmation": "NewSecurePass123"
}
```

**Emails Sent:**
1. ✅ **Password Reset Success** confirmation (`password-reset-success.blade.php`)
   - Same template as reset password
   - Includes IP address and device info

**Response:**
```json
{
    "success": true,
    "message": "Password changed successfully"
}
```

---

## 5. Delete Account

**Endpoint:** `DELETE /api/delete-account`

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "password": "CurrentPassword123"
}
```

**Emails Sent:**
1. ✅ **Account Deleted** confirmation (`account-deleted.blade.php`)
   - Sent BEFORE account deletion
   - Includes 30-day restore period information
   - Lists all data that will be deleted

**Response:**
```json
{
    "success": true,
    "message": "Account deleted successfully. You have 30 days to restore it if you change your mind."
}
```

---

## 📱 Frontend Implementation Guide

### 1. Registration Flow

```dart
Future<void> register() async {
  final response = await http.post(
    Uri.parse('$baseUrl/api/register'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'first_name': firstName,
      'last_name': lastName,
      'email': email,
      'phone_number': phoneNumber,
      'password': password,
      'password_confirmation': passwordConfirmation,
    }),
  );
  
  if (response.statusCode == 201) {
    // User registered successfully
    // Welcome email sent automatically
    final data = jsonDecode(response.body);
    saveToken(data['data']['token']);
  }
}
```

---

### 2. Forgot Password Flow

**Step 1: Request Reset Code**

```dart
Future<void> requestPasswordReset(String email) async {
  final response = await http.post(
    Uri.parse('$baseUrl/api/forgot-password'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({'email': email}),
  );
  
  if (response.statusCode == 200) {
    // Reset code sent to email
    // Show code input screen
    navigateToCodeVerification(email);
  }
}
```

**Step 2: Reset Password with Code**

```dart
Future<void> resetPassword(String email, String code, String newPassword) async {
  final response = await http.post(
    Uri.parse('$baseUrl/api/reset-password'),
    headers: {'Content-Type': 'application/json'},
    body: jsonEncode({
      'email': email,
      'code': code,
      'password': newPassword,
      'password_confirmation': newPassword,
    }),
  );
  
  if (response.statusCode == 200) {
    // Password reset successful
    // Success email sent automatically
    navigateToLogin();
  }
}
```

---

### 3. Change Password (Authenticated)

```dart
Future<void> changePassword(String currentPassword, String newPassword) async {
  final response = await http.post(
    Uri.parse('$baseUrl/api/change-password'),
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer $token',
    },
    body: jsonEncode({
      'current_password': currentPassword,
      'new_password': newPassword,
      'new_password_confirmation': newPassword,
    }),
  );
  
  if (response.statusCode == 200) {
    // Password changed successfully
    // Confirmation email sent automatically
    showSuccessMessage('Password changed successfully');
  }
}
```

---

### 4. Delete Account

```dart
Future<void> deleteAccount(String password) async {
  final response = await http.delete(
    Uri.parse('$baseUrl/api/delete-account'),
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer $token',
    },
    body: jsonEncode({'password': password}),
  );
  
  if (response.statusCode == 200) {
    // Account deleted successfully
    // Deletion email sent automatically
    clearLocalData();
    navigateToWelcomeScreen();
  }
}
```

---

## 🧪 Testing the Email Integration

### Using cURL

**1. Register User:**
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Test",
    "last_name": "User",
    "email": "test@example.com",
    "phone_number": "+256700123456",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**2. Request Password Reset:**
```bash
curl -X POST http://localhost:8000/api/forgot-password \
  -H "Content-Type: application/json" \
  -d '{"email": "test@example.com"}'
```

**3. Reset Password:**
```bash
curl -X POST http://localhost:8000/api/reset-password \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "code": "123456",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
  }'
```

**4. Change Password (Authenticated):**
```bash
curl -X POST http://localhost:8000/api/change-password \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "current_password": "password123",
    "new_password": "newpassword456",
    "new_password_confirmation": "newpassword456"
  }'
```

**5. Delete Account:**
```bash
curl -X DELETE http://localhost:8000/api/delete-account \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"password": "password123"}'
```

---

## ⚙️ Configuration

### Environment Variables

Add to `.env` file:

```env
# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@estudios.ug
MAIL_FROM_NAME="SalesPulse"

# Manager/Admin Email for Notifications
MANAGER_EMAIL=admin@estudios.ug
```

---

## 📊 Email Summary

| Action | Endpoint | Email Template | Recipient |
|--------|----------|----------------|-----------|
| User Registration | `POST /api/register` | `welcome-user.blade.php` | User |
| User Registration | `POST /api/register` | `new-user-notification.blade.php` | Admin |
| Forgot Password | `POST /api/forgot-password` | `password-reset.blade.php` | User |
| Reset Password | `POST /api/reset-password` | `password-reset-success.blade.php` | User |
| Change Password | `POST /api/change-password` | `password-reset-success.blade.php` | User |
| Delete Account | `DELETE /api/delete-account` | `account-deleted.blade.php` | User |

---

## 🔍 Error Handling

All email sending is wrapped in try-catch blocks. If an email fails to send:
- The error is logged
- The API operation continues successfully
- The user is not notified of the email failure

This ensures that critical operations (like password reset) aren't blocked by email delivery issues.

---

## 🚀 Next Steps

1. **Test in Development:** Use a service like [Mailtrap](https://mailtrap.io/) to test emails in development
2. **Configure Production Email:** Set up proper SMTP credentials for production
3. **Monitor Email Delivery:** Check Laravel logs for any email sending errors
4. **Queue Emails (Optional):** For better performance, set up Laravel queues for email sending

---

## 📝 Notes

- All emails are sent asynchronously and won't block API responses
- Email failures are logged but don't cause API errors
- Reset codes expire after 60 minutes
- Account deletion includes a 30-day grace period message
- IP address and device info are included in password change emails for security

For more details, see `EMAIL_TEMPLATES_USAGE.md`

