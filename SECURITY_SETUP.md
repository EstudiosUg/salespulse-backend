# API Security Setup - SalesPulse Backend

This document explains how the SalesPulse API is secured to prevent unauthorized browser access while allowing legitimate mobile app API calls.

## 🔒 Security Implementation

### 1. **API-Only Access Middleware**

Two middleware options are available:

#### Option A: `ApiOnly` Middleware (Currently Active)
- **Location**: `app/Http/Middleware/ApiOnly.php`
- **Blocks**: Direct browser access
- **Allows**: 
  - Requests with Bearer tokens (Sanctum authentication)
  - Requests with `Accept: application/json` header
  - Public endpoints (login, register, password reset)

#### Option B: `RestrictToMobileApp` Middleware (Enhanced Security)
- **Location**: `app/Http/Middleware/RestrictToMobileApp.php`
- **Requires**: Custom `X-App-Identifier` header
- **More Secure**: Only allows requests with the secret app identifier

### 2. **Mobile App Identifier**

A secret identifier is configured to authenticate the mobile app:

**Backend Configuration** (`config/app.php`):
```php
'mobile_app_identifier' => env('MOBILE_APP_IDENTIFIER', 'SalesPulse-Mobile-App-2025-Secret-Key'),
```

**Flutter App Implementation** (`lib/services/api_service.dart`):
```dart
Map<String, String> get _headers => {
  'X-App-Identifier': 'SalesPulse-Mobile-App-2025-Secret-Key',
  // ... other headers
};
```

## 📝 How It Works

### Current Setup (ApiOnly Middleware)

1. **Browser Request** → ❌ Blocked with 403 error
2. **API Request with Token** → ✅ Allowed
3. **API Request with Accept: application/json** → ✅ Allowed
4. **Public Endpoints (login/register)** → ✅ Allowed

### Response for Blocked Requests
```json
{
  "success": false,
  "message": "This endpoint is only accessible via API. Please use the mobile application.",
  "error": "browser_access_forbidden"
}
```

## 🚀 Switching to Enhanced Security

To use the `RestrictToMobileApp` middleware (recommended for production):

1. **Update** `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'api.only' => \App\Http\Middleware\RestrictToMobileApp::class, // Changed
    ]);
    
    $middleware->group('api', [
        \App\Http\Middleware\RestrictToMobileApp::class, // Changed
    ]);
})
```

2. **Add to production `.env`**:
```env
MOBILE_APP_IDENTIFIER=your-custom-secret-key-here-change-this-in-production
```

3. **Update Flutter app** to use the same identifier from step 2

## 🌐 Production Deployment

### Environment Variables

Add to your production `.env` file:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://salespulse.estudios.ug

# Change this to a strong random string in production
MOBILE_APP_IDENTIFIER=your-random-secret-identifier-here
```

### Clear Caches After Deployment

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Web Server Configuration

#### For Apache (.htaccess is already configured)
- Ensure `mod_rewrite` is enabled
- Document root should point to `/public`

#### For Nginx
```nginx
server {
    listen 443 ssl http2;
    server_name salespulse.estudios.ug;
    
    root /path/to/salespulse-backend/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 🛡️ Additional Security Measures

### 1. CORS Configuration
File: `config/cors.php`
- Controls which domains can access your API
- Currently allows all origins (configured for mobile apps)
- Adjust `allowed_origins` for web-specific restrictions

### 2. Rate Limiting
Laravel automatically applies rate limiting to API routes:
- Default: 60 requests per minute per IP

### 3. Sanctum Token Authentication
- All protected routes require valid Bearer tokens
- Tokens are issued on login/register
- Tokens can be revoked on logout

### 4. IP Whitelisting (Optional)
To restrict API access to specific IPs, add to middleware:

```php
public function handle(Request $request, Closure $next): Response
{
    $allowedIps = ['your-server-ip', 'another-ip'];
    
    if (!in_array($request->ip(), $allowedIps)) {
        return response()->json(['error' => 'Unauthorized IP'], 403);
    }
    
    return $next($request);
}
```

## 📋 Testing the Security

### Test Browser Access (Should be Blocked)
```bash
curl https://salespulse.estudios.ug/api/sales
```

**Expected Response**: 403 Forbidden

### Test API Access with Token (Should Work)
```bash
curl -H "Accept: application/json" \
     -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     -H "X-App-Identifier: SalesPulse-Mobile-App-2025-Secret-Key" \
     https://salespulse.estudios.ug/api/sales
```

**Expected Response**: 200 OK with data

### Test Public Endpoints (Should Work)
```bash
curl -X POST https://salespulse.estudios.ug/api/login \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -H "X-App-Identifier: SalesPulse-Mobile-App-2025-Secret-Key" \
     -d '{"login":"user@example.com","password":"password"}'
```

## 🔐 Best Practices

1. **Change the App Identifier**: Use a strong random string in production
2. **Use HTTPS**: Always use SSL/TLS in production
3. **Keep Secrets Secret**: Never commit `.env` file to version control
4. **Rotate Tokens**: Implement token expiration and refresh mechanisms
5. **Monitor Logs**: Check Laravel logs for suspicious activity
6. **Update Dependencies**: Keep Laravel and packages up to date

## 📞 Support

For issues or questions, contact:
- **Email**: info@estudios.ug
- **Developer**: Estudios UG

---

**Last Updated**: October 18, 2025
