# Troubleshooting 404 Errors

## Error Messages
```
error loading sales: exception: error updating sale: failed to update sale: 404
error loading Expense: exception: error updating sale: failed to update Expense: 404
error loading supplier: exception: error updating sale: failed to update supplier: 404
```

## What Was Fixed

### 1. **Simplified ApiOnly Middleware**
The middleware was simplified to prevent route matching issues:
- Now allows any request with `Accept: application/json` header
- Allows any request with Bearer token
- Allows any request with `X-App-Identifier` header

**Location**: `app/Http/Middleware/ApiOnly.php`

### 2. **Updated .htaccess**
Added support for the custom `X-App-Identifier` header to ensure it's properly passed to the application.

**Location**: `public/.htaccess`

## Steps to Fix on Production Server

### Step 1: Upload Updated Files
Upload these modified files to your production server:
- `app/Http/Middleware/ApiOnly.php`
- `public/.htaccess`
- `bootstrap/app.php`
- `lib/services/api_service.dart` (Flutter app)

### Step 2: Clear All Caches

**On Linux/Mac:**
```bash
cd /path/to/salespulse-backend
chmod +x clear-cache.sh
./clear-cache.sh
```

**On Windows (Laragon/XAMPP):**
```cmd
cd C:\path\to\salespulse-backend
clear-cache.bat
```

**Or manually:**
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Then rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 3: Verify Apache Modules (Production Server)

Ensure these Apache modules are enabled:
```bash
sudo a2enmod rewrite
sudo a2enmod headers
sudo systemctl restart apache2
```

### Step 4: Check File Permissions

Ensure proper permissions on production:
```bash
cd /path/to/salespulse-backend
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache
```

### Step 5: Verify Virtual Host Configuration

Your Apache virtual host should point to the `public` directory:

```apache
<VirtualHost *:443>
    ServerName salespulse.estudios.ug
    DocumentRoot /path/to/salespulse-backend/public

    <Directory /path/to/salespulse-backend/public>
        AllowOverride All
        Require all granted
    </Directory>

    SSLEngine on
    SSLCertificateFile /path/to/cert.pem
    SSLCertificateKeyFile /path/to/key.pem
</VirtualHost>
```

## Testing the API

### Test 1: Check if API is accessible
```bash
curl -X GET https://salespulse.estudios.ug/api/login \
     -H "Accept: application/json"
```

**Expected**: 405 Method Not Allowed (because login requires POST)

### Test 2: Test with proper headers
```bash
curl -X POST https://salespulse.estudios.ug/api/login \
     -H "Accept: application/json" \
     -H "Content-Type: application/json" \
     -H "X-App-Identifier: SalesPulse-Mobile-App-2025-Secret-Key" \
     -d '{"login":"test@example.com","password":"password"}'
```

**Expected**: 200 or 401/422 (not 404)

### Test 3: Test authenticated route
```bash
curl -X GET https://salespulse.estudios.ug/api/sales \
     -H "Accept: application/json" \
     -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     -H "X-App-Identifier: SalesPulse-Mobile-App-2025-Secret-Key"
```

**Expected**: 200 with sales data (not 404)

## Common Issues and Solutions

### Issue 1: Still Getting 404 Errors

**Solution A**: Check Laravel logs
```bash
tail -f storage/logs/laravel.log
```

**Solution B**: Enable debug mode temporarily in `.env`:
```env
APP_DEBUG=true
```
(Remember to set it back to `false` after debugging)

### Issue 2: 403 Forbidden on All Routes

**Cause**: Apache/Nginx doesn't have permission to access files

**Solution**:
```bash
sudo chmod -R 755 /path/to/salespulse-backend
sudo chmod -R 775 storage bootstrap/cache
```

### Issue 3: Routes Work Locally But Not in Production

**Possible Causes**:
1. Route cache is outdated
2. `.htaccess` not being read (check `AllowOverride All` in virtual host)
3. `mod_rewrite` not enabled in Apache

**Solution**:
```bash
# Clear caches
php artisan route:clear
php artisan config:clear

# Enable mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Issue 4: PUT/PATCH/DELETE Methods Return 404

**Cause**: Some servers don't handle these methods properly through forms

**Solution**: Laravel already handles this via `_method` parameter. Ensure `.htaccess` is working:
```bash
# Test if mod_rewrite is working
curl -I https://salespulse.estudios.ug/api/test
# Should return Laravel 404 page, not Apache 404
```

## Debugging in Flutter App

Add this to your `api_service.dart` to log requests:

```dart
Future<Sale> updateSale(String id, Sale sale) async {
  try {
    final url = '$baseUrl/sales/$id';
    print('🔵 UPDATE SALE Request: $url');
    print('Headers: $_headers');
    print('Body: ${json.encode(sale.toJson())}');
    
    final response = await http.put(
      Uri.parse(url),
      headers: _headers,
      body: json.encode(sale.toJson()),
    );
    
    print('Response Status: ${response.statusCode}');
    print('Response Body: ${response.body}');
    
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      return Sale.fromJson(data['data']);
    } else {
      throw Exception('Failed to update sale: ${response.statusCode}');
    }
  } catch (e) {
    print('❌ Error updating sale: $e');
    throw Exception('Error updating sale: $e');
  }
}
```

## If Nothing Works

### Nuclear Option: Temporarily Disable Middleware

Edit `bootstrap/app.php` and comment out the middleware:

```php
->withMiddleware(function (Middleware $middleware): void {
    // Temporarily disabled for debugging
    // $middleware->alias([
    //     'api.only' => \App\Http\Middleware\ApiOnly::class,
    // ]);
    
    // $middleware->group('api', [
    //     \App\Http\Middleware\ApiOnly::class,
    // ]);
})
```

Then:
```bash
php artisan config:clear
php artisan route:clear
```

If this fixes the 404s, the issue was with the middleware. If 404s persist, the issue is with routing or server configuration.

## Production Environment Checklist

- [ ] All files uploaded to production
- [ ] Route cache cleared (`php artisan route:clear`)
- [ ] Config cache cleared (`php artisan config:clear`)
- [ ] Apache `mod_rewrite` enabled
- [ ] Apache `mod_headers` enabled
- [ ] Virtual host points to `public` directory
- [ ] Virtual host has `AllowOverride All`
- [ ] `.htaccess` file exists in `public` directory
- [ ] File permissions are correct (755/775)
- [ ] `.env` has correct `APP_URL`
- [ ] SSL certificate is valid
- [ ] Flutter app has production URL configured

## Contact Support

If issues persist after following all steps:
- **Email**: info@estudios.ug
- **Check Laravel logs**: `storage/logs/laravel.log`
- **Check Apache logs**: `/var/log/apache2/error.log`

---

**Last Updated**: October 18, 2025
