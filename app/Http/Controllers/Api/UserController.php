<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSetting;
use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserDataExport;

class UserController extends Controller
{
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->load('settings');

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $request->user()->id,
            'phone_number' => 'sometimes|required|string|unique:users,phone_number,' . $request->user()->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $user->update($request->only([
            'first_name',
            'last_name',
            'email',
            'phone_number',
        ]));

        // Update name field
        if ($request->has('first_name') || $request->has('last_name')) {
            $user->update([
                'name' => ($request->first_name ?? $user->first_name) . ' ' . ($request->last_name ?? $user->last_name)
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user->fresh()
        ]);
    }

    public function uploadAvatar(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Delete old avatar if exists
        if ($user->avatar && Storage::exists('public/avatars/' . $user->avatar)) {
            Storage::delete('public/avatars/' . $user->avatar);
        }

        // Store new avatar
        $file = $request->file('avatar');
        $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/avatars', $filename);

        $user->update(['avatar' => $filename]);

        return response()->json([
            'success' => true,
            'message' => 'Avatar updated successfully',
            'data' => [
                'avatar_url' => Storage::url('avatars/' . $filename)
            ]
        ]);
    }

    public function getSettings(Request $request): JsonResponse
    {
        $settings = UserSetting::where('user_id', $request->user()->id)->get();
        
        $formattedSettings = $settings->mapWithKeys(function ($setting) {
            return [$setting->key => $setting->value];
        });

        return response()->json([
            'success' => true,
            'data' => $formattedSettings
        ]);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        $user = $request->user();
        $settings = $request->all();

        foreach ($settings as $key => $value) {
            UserSetting::updateOrCreate(
                ['user_id' => $user->id, 'key' => $key],
                [
                    'value' => $value,
                    'type' => $this->getValueType($value)
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully'
        ]);
    }

    public function exportData(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->canExportData()) {
            return response()->json([
                'success' => false,
                'message' => 'Premium subscription required for data export'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'include_sales' => 'boolean',
            'include_expenses' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [];

        if ($request->boolean('include_sales', true)) {
            $sales = Sale::with('supplier')
                ->where('user_id', $user->id)
                ->byDateRange($request->start_date, $request->end_date)
                ->get();
            $data['sales'] = $sales;
        }

        if ($request->boolean('include_expenses', true)) {
            $expenses = Expense::where('user_id', $user->id)
                ->byDateRange($request->start_date, $request->end_date)
                ->get();
            $data['expenses'] = $expenses;
        }

        // Generate filename
        $filename = 'data_export_' . $user->id . '_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        
        // Store the export file
        Excel::store(new UserDataExport($data), 'exports/' . $filename, 'public');
        
        // Get the full file path for email attachment
        $filePath = storage_path('app/public/exports/' . $filename);
        
        // Send email with attachment
        $emailSent = false;
        try {
            $startDate = date('Y-m-d', strtotime($request->start_date));
            $endDate = date('Y-m-d', strtotime($request->end_date));
            
            Mail::raw(
                "Hello {$user->name},\n\n" .
                "Your data export is ready!\n\n" .
                "Export Details:\n" .
                "- Date Range: {$startDate} to {$endDate}\n" .
                "- Sales Data: " . ($request->boolean('include_sales', true) ? 'Included' : 'Not included') . "\n" .
                "- Expenses Data: " . ($request->boolean('include_expenses', true) ? 'Included' : 'Not included') . "\n\n" .
                "Please find your exported data attached to this email.\n\n" .
                "Best regards,\n" .
                "SalesPulse Team",
                function ($message) use ($user, $filePath, $filename) {
                    $message->to($user->email)
                        ->subject('Your Data Export - SalesPulse')
                        ->attach($filePath, [
                            'as' => $filename,
                            'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ]);
                }
            );
            $emailSent = true;
        } catch (\Exception $e) {
            // Log the error but don't fail the request
            \Log::error('Failed to send export email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Data exported successfully',
            'data' => [
                'download_url' => url(Storage::url('exports/' . $filename)),
                'filename' => $filename,
                'email_sent' => $emailSent,
                'file_name' => $filename
            ]
        ]);
    }

    private function getValueType($value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        }
        if (is_int($value)) {
            return 'integer';
        }
        if (is_float($value)) {
            return 'float';
        }
        if (is_array($value)) {
            return 'array';
        }
        return 'string';
    }
}
