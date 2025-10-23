# Google Authentication Implementation Guide (Backend)

This guide provides the complete backend implementation for Google Sign-In authentication in the SalesPulse Laravel API.

## 📦 Dependencies

Add the Google API Client library to your `composer.json`:

```bash
composer require google/apiclient:"^2.15"
```

## ⚙️ Configuration

### 1. Update `.env`

Add your Google OAuth credentials:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your-google-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=https://your-domain.com/auth/google/callback
```

### 2. Update `config/services.php`

Add Google service configuration:

```php
return [
    // ... other services

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],
];
```

## 🛣️ Routes

Update `routes/api.php`:

```php
use App\Http\Controllers\GoogleAuthController;

// Public routes
Route::post('/auth/google', [GoogleAuthController::class, 'login']);
Route::post('/auth/google/verify', [GoogleAuthController::class, 'verifyToken']);
```

## 🎮 Controller

Create a new controller:

```bash
php artisan make:controller GoogleAuthController
```

### `app/Http/Controllers/GoogleAuthController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Google_Client;

class GoogleAuthController extends Controller
{
    /**
     * Handle Google Sign-In
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
            'access_token' => 'nullable|string',
        ]);

        try {
            // Initialize Google Client
            $client = new Google_Client([
                'client_id' => config('services.google.client_id')
            ]);
            
            // Verify the ID token
            $payload = $client->verifyIdToken($request->id_token);
            
            if (!$payload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Google ID token'
                ], 401);
            }

            // Extract user information from the payload
            $googleId = $payload['sub'];
            $email = $payload['email'];
            $emailVerified = $payload['email_verified'] ?? false;
            $firstName = $payload['given_name'] ?? '';
            $lastName = $payload['family_name'] ?? '';
            $fullName = $payload['name'] ?? '';
            $avatar = $payload['picture'] ?? null;

            // Check if email is verified
            if (!$emailVerified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Google account email is not verified'
                ], 403);
            }

            // Find or create user
            $user = User::where('email', $email)->first();

            if ($user) {
                // Update existing user with Google info if not already set
                if (empty($user->google_id)) {
                    $user->google_id = $googleId;
                }
                
                if (empty($user->avatar) && $avatar) {
                    $user->avatar = $avatar;
                }

                if (empty($user->email_verified_at)) {
                    $user->email_verified_at = now();
                }

                $user->save();
            } else {
                // Create new user
                $user = User::create([
                    'google_id' => $googleId,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'email_verified_at' => now(),
                    'avatar' => $avatar,
                    'password' => Hash::make(Str::random(32)), // Random password
                    'phone_number' => null, // Will be set later if needed
                ]);
            }

            // Delete old tokens (optional - keeps only one active session)
            // $user->tokens()->delete();

            // Create new auth token
            $token = $user->createToken('google_auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Google authentication successful',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'phone_number' => $user->phone_number,
                        'avatar' => $user->avatar,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]
                ]
            ], 200);

        } catch (\Google\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Google authentication failed: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify Google token (alternative method)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyToken(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        try {
            $client = new Google_Client([
                'client_id' => config('services.google.client_id')
            ]);
            
            $payload = $client->verifyIdToken($request->id_token);
            
            if (!$payload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid token'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'google_id' => $payload['sub'],
                    'email' => $payload['email'],
                    'email_verified' => $payload['email_verified'] ?? false,
                    'name' => $payload['name'] ?? '',
                    'picture' => $payload['picture'] ?? null,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token verification failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
```

## 🗄️ Database Migration

Add `google_id` column to users table:

```bash
php artisan make:migration add_google_id_to_users_table
```

### Migration file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('id');
            $table->index('google_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['google_id']);
            $table->dropColumn('google_id');
        });
    }
};
```

Run the migration:

```bash
php artisan migrate
```

## 📝 Update User Model

Update `app/Models/User.php`:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'google_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'avatar',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google_id', // Hide from API responses
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
```

## 🧪 Testing

### Test with cURL:

```bash
curl -X POST http://your-api-url/api/auth/google \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "id_token": "eyJhbGciOiJSUzI1NiIsImtpZCI6...",
    "access_token": "ya29.a0AfH6SMBx..."
  }'
```

### Test with Postman:

1. Create a new POST request
2. URL: `http://your-api-url/api/auth/google`
3. Headers:
   - `Content-Type: application/json`
   - `Accept: application/json`
4. Body (raw JSON):
   ```json
   {
     "id_token": "your-google-id-token",
     "access_token": "your-google-access-token"
   }
   ```

### Expected Response:

```json
{
    "success": true,
    "message": "Google authentication successful",
    "data": {
        "token": "1|abc123...",
        "user": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "phone_number": null,
            "avatar": "https://lh3.googleusercontent.com/...",
            "created_at": "2025-10-23T12:00:00.000000Z",
            "updated_at": "2025-10-23T12:00:00.000000Z"
        }
    }
}
```

## 🔒 Security Considerations

1. **Token Verification**: Always verify the ID token on the server side
2. **HTTPS**: Use HTTPS in production
3. **Token Expiry**: Google ID tokens expire after 1 hour
4. **Rate Limiting**: Add rate limiting to prevent abuse
5. **Email Verification**: Check `email_verified` claim
6. **Audience Verification**: Verify the token's `aud` claim matches your client ID

### Add Rate Limiting:

In `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'api' => [
        'throttle:api',
        // ...
    ],
];
```

In `routes/api.php`:

```php
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/auth/google', [GoogleAuthController::class, 'login']);
});
```

## 📧 Email Notifications (Optional)

Send a welcome email when a new user signs up via Google:

```php
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

// In GoogleAuthController@login, after creating new user:
if (!$user->wasRecentlyCreated) {
    Mail::to($user->email)->send(new WelcomeEmail($user));
}
```

## 🔍 Logging

Add logging for security and debugging:

```php
use Illuminate\Support\Facades\Log;

// In GoogleAuthController@login:
Log::info('Google authentication attempt', [
    'email' => $email,
    'google_id' => $googleId,
]);

// On success:
Log::info('User authenticated via Google', [
    'user_id' => $user->id,
    'email' => $user->email,
]);

// On failure:
Log::warning('Google authentication failed', [
    'error' => $e->getMessage(),
]);
```

## ⚠️ Error Handling

Common errors and solutions:

### "Invalid ID token"
- Token may have expired (1-hour lifetime)
- Client ID mismatch
- Token from wrong Google project

### "Email not verified"
- User must verify their Google account email
- Return appropriate error message to client

### "Database error"
- Check migrations are run
- Verify database connection
- Check user table structure

## 🚀 Production Checklist

- [ ] Install Google API client library
- [ ] Add `google_id` column to users table
- [ ] Configure `.env` with Google credentials
- [ ] Update routes
- [ ] Create GoogleAuthController
- [ ] Test authentication flow
- [ ] Add rate limiting
- [ ] Enable HTTPS
- [ ] Set up logging
- [ ] Test error scenarios

## 📚 Resources

- [Google Identity Platform](https://developers.google.com/identity)
- [Verifying ID Tokens](https://developers.google.com/identity/sign-in/web/backend-auth)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Google API Client PHP](https://github.com/googleapis/google-api-php-client)

