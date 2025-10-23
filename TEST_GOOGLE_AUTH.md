# Testing Google Authentication - Complete Guide

## ✅ Backend Implementation Status

**ALL COMPLETE!** ✓

- ✅ Google API Client installed (v2.18.4)
- ✅ Database migration created and executed
- ✅ User model updated with `google_id`
- ✅ GoogleAuthController created
- ✅ API routes registered and verified
- ✅ Configuration files updated

## 🔧 Before Testing

### 1. Configure Environment Variables

Add these to your `.env` file:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=https://your-domain.com/auth/google/callback
```

### 2. Verify Routes

Run this command to confirm routes are registered:

```bash
php artisan route:list | findstr "auth/google"
```

Expected output:
```
POST  api/auth/google ................ GoogleAuthController@login
POST  api/auth/google/verify ......... GoogleAuthController@verifyToken
```

### 3. Verify Database

Check that the `google_id` column exists:

```sql
DESCRIBE users;
```

You should see:
```
google_id | varchar(255) | YES | UNI | NULL
```

## 🧪 Testing Methods

### Method 1: Using cURL (Command Line)

**Test Google Login:**

```bash
curl -X POST http://localhost:8000/api/auth/google \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"id_token\":\"YOUR_GOOGLE_ID_TOKEN\",\"access_token\":\"YOUR_ACCESS_TOKEN\"}"
```

**Test Token Verification:**

```bash
curl -X POST http://localhost:8000/api/auth/google/verify \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{\"id_token\":\"YOUR_GOOGLE_ID_TOKEN\"}"
```

### Method 2: Using PowerShell

**Test Google Login:**

```powershell
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
}

$body = @{
    id_token = "YOUR_GOOGLE_ID_TOKEN"
    access_token = "YOUR_ACCESS_TOKEN"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/api/auth/google" `
    -Method POST `
    -Headers $headers `
    -Body $body
```

### Method 3: Using Postman

1. **Create New Request**
   - Method: `POST`
   - URL: `http://localhost:8000/api/auth/google`

2. **Set Headers**
   - `Content-Type: application/json`
   - `Accept: application/json`

3. **Set Body (raw JSON)**
   ```json
   {
     "id_token": "YOUR_GOOGLE_ID_TOKEN",
     "access_token": "YOUR_ACCESS_TOKEN"
   }
   ```

4. **Send Request**

### Method 4: Test with Flutter App

This is the **recommended** way to test since it provides real Google tokens:

1. Configure Google Cloud Console with your app's SHA-1
2. Run the Flutter app
3. Tap "Continue with Google"
4. App will automatically send tokens to backend
5. Check backend response in Flutter

## 📊 Expected Responses

### Success Response (200 OK)

```json
{
  "success": true,
  "message": "Google authentication successful",
  "data": {
    "token": "1|xyz123abc456def789...",
    "user": {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "email": "john@example.com",
      "phone_number": null,
      "avatar": "https://lh3.googleusercontent.com/a/AAcHTtc...",
      "created_at": "2025-10-23T16:30:00.000000Z",
      "updated_at": "2025-10-23T16:30:00.000000Z"
    }
  }
}
```

### Error Responses

**Invalid Token (401)**
```json
{
  "success": false,
  "message": "Invalid Google ID token"
}
```

**Email Not Verified (403)**
```json
{
  "success": false,
  "message": "Google account email is not verified"
}
```

**Validation Error (422)**
```json
{
  "message": "The id token field is required.",
  "errors": {
    "id_token": [
      "The id token field is required."
    ]
  }
}
```

**Server Error (500)**
```json
{
  "success": false,
  "message": "Authentication error: [error details]"
}
```

## 🔍 How to Get Google ID Token

### Option 1: From Flutter App (Recommended)

The Flutter app automatically gets the token when user signs in. You can log it:

```dart
// In GoogleSignInService
final authentication = await account.authentication;
print('ID Token: ${authentication.idToken}');
print('Access Token: ${authentication.accessToken}');
```

### Option 2: Using Google OAuth Playground

1. Go to: https://developers.google.com/oauthplayground/
2. Select "Google OAuth2 API v2"
3. Select scopes: `email`, `profile`, `openid`
4. Click "Authorize APIs"
5. Sign in with Google
6. Click "Exchange authorization code for tokens"
7. Copy the `id_token`

**Note**: This token will expire in 1 hour!

### Option 3: Manual Testing Script

Create a test PHP script:

```php
<?php
require 'vendor/autoload.php';

$client = new Google_Client(['client_id' => 'YOUR_CLIENT_ID']);

// This would normally come from the Flutter app
$idToken = 'paste-token-here';

$payload = $client->verifyIdToken($idToken);
if ($payload) {
    print_r($payload);
} else {
    echo "Invalid token\n";
}
```

## 🔐 Database Verification

After successful authentication, verify in database:

```sql
-- Check if user was created/updated
SELECT id, google_id, first_name, last_name, email, avatar, email_verified_at 
FROM users 
ORDER BY id DESC 
LIMIT 1;

-- Check if token was created
SELECT id, tokenable_id, name, created_at 
FROM personal_access_tokens 
ORDER BY id DESC 
LIMIT 1;
```

## 🐛 Troubleshooting

### Issue: "Class 'Google_Client' not found"

**Solution:**
```bash
composer dump-autoload
```

### Issue: "Invalid Google ID token"

**Causes:**
- Token expired (1-hour lifetime)
- Wrong Client ID in .env
- Token from different Google project

**Solution:**
- Get a fresh token
- Verify `GOOGLE_CLIENT_ID` in .env matches Google Cloud Console
- Use token from correct Google project

### Issue: "SQLSTATE[42S22]: Column not found: 'google_id'"

**Solution:**
```bash
php artisan migrate:status
php artisan migrate
```

### Issue: Routes not found

**Solution:**
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan route:list | findstr google
```

### Issue: "Connection refused"

**Solution:**
- Make sure Laravel server is running:
  ```bash
  php artisan serve
  ```
- Or if using Laragon, check Apache/Nginx is running

## 📝 Testing Checklist

Before deploying to production, test these scenarios:

- [ ] New user signs in with Google for first time
  - User created in database
  - google_id populated
  - Email verified
  - Token returned

- [ ] Existing user (email/password) signs in with Google
  - google_id added to existing account
  - Other data preserved
  - Token returned

- [ ] Existing Google user signs in again
  - User found by email
  - google_id matches
  - Token returned

- [ ] Invalid token submitted
  - Returns 401 error
  - No user created
  - Proper error message

- [ ] Unverified Google email
  - Returns 403 error
  - No user created
  - Proper error message

- [ ] Missing id_token in request
  - Returns 422 validation error
  - Clear error message

## 🚀 Quick Start Testing

**Fastest way to test:**

1. **Start Laravel server:**
   ```bash
   php artisan serve
   ```

2. **Test with Flutter app:**
   - Make sure `ApiConfig.apiUrl` points to your server
   - Run the app
   - Tap "Continue with Google"
   - Check response

3. **Verify in database:**
   ```sql
   SELECT * FROM users ORDER BY id DESC LIMIT 1;
   ```

## 📊 Success Metrics

You'll know it's working when:

1. ✅ Google login button appears in Flutter app
2. ✅ Clicking it opens Google account selector
3. ✅ After selecting account, user is logged in
4. ✅ User redirected to dashboard
5. ✅ User data saved in database
6. ✅ Token stored in Flutter app
7. ✅ Auto-login works on app restart

## 📚 Useful Commands

```bash
# View all routes
php artisan route:list

# View only Google routes
php artisan route:list | findstr google

# Check migration status
php artisan migrate:status

# View latest users
php artisan tinker
>>> User::latest()->first()

# Clear all caches
php artisan optimize:clear

# Test database connection
php artisan db:show
```

## 🎯 Next Steps

1. ✅ Backend implementation complete
2. ⏳ Get Google OAuth credentials
3. ⏳ Add credentials to .env
4. ⏳ Test with real Google account
5. ⏳ Configure Flutter app with same Client ID
6. ⏳ Test end-to-end flow
7. ⏳ Deploy to production
8. ⏳ Update privacy policy

## 🆘 Need Help?

Check these files for more information:

- `GOOGLE_AUTH_IMPLEMENTATION.md` - Complete implementation guide
- `GOOGLE_BACKEND_IMPLEMENTATION_COMPLETE.md` - Implementation summary
- `google-env-config.txt` - Environment configuration
- `app/Http/Controllers/GoogleAuthController.php` - Controller code
- `routes/api.php` - Routes definition

---

**Status**: ✅ Ready for Testing  
**Last Updated**: October 23, 2025  
**Backend Version**: Laravel 12.35.1

