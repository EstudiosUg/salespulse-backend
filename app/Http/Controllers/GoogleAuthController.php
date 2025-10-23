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
                    'name' => $fullName,
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
