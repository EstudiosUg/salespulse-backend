<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeUserMail;
use App\Mail\NewUserNotificationMail;
use App\Mail\PasswordResetMail;
use App\Mail\PasswordResetSuccessMail;
use App\Mail\AccountDeletedMail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Send welcome email to the new user
        try {
            Mail::to($user->email)->send(new WelcomeUserMail($user));
        } catch (\Exception $e) {
            // Log the error but don't fail the registration
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        // Send notification email to the manager
        $managerEmail = env('MANAGER_EMAIL', 'admin@salespulse.com');
        if ($managerEmail) {
            try {
                Mail::to($managerEmail)->send(new NewUserNotificationMail($user));
            } catch (\Exception $e) {
                // Log the error but don't fail the registration
                \Log::error('Failed to send manager notification: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        
        $user = User::where($loginField, $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Account is deactivated'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $request->user()
            ]
        ]);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Send password change confirmation email
        try {
            Mail::to($user->email)->send(
                new PasswordResetSuccessMail($user, $request->ip(), $request->userAgent())
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send password change confirmation email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    public function deleteAccount(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password is incorrect'
            ], 400);
        }

        // Send account deletion confirmation email before deleting
        try {
            Mail::to($user->email)->send(new AccountDeletedMail($user, 30)); // 30 days restore period
        } catch (\Exception $e) {
            \Log::error('Failed to send account deletion email: ' . $e->getMessage());
        }

        // Delete all user tokens
        $user->tokens()->delete();
        
        // Delete user account
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully. You have 30 days to restore it if you change your mind.'
        ]);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found with that email address'
            ], 404);
        }

        // Generate a 6-digit reset code
        $resetCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $token = Hash::make($resetCode);

        // Store reset token in database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]
        );

        // Send email with reset code using professional template
        try {
            Mail::to($user->email)->send(new PasswordResetMail($user, $resetCode));
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reset email. Please try again later.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password reset code sent to your email'
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if reset token exists and is not expired (60 minutes)
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired reset code'
            ], 400);
        }

        // Check if token is expired (60 minutes)
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return response()->json([
                'success' => false,
                'message' => 'Reset code has expired. Please request a new one.'
            ], 400);
        }

        // Verify reset code
        if (!Hash::check($request->code, $resetRecord->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reset code'
            ], 400);
        }

        // Update password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete reset token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Send password reset success email
        try {
            Mail::to($user->email)->send(
                new PasswordResetSuccessMail($user, $request->ip(), $request->userAgent())
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset success email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ]);
    }
}