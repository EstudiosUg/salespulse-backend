<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SalesController;
use App\Http\Controllers\Api\ExpensesController;
use App\Http\Controllers\Api\SuppliersController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\GoogleAuthController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Google Authentication
Route::post('/auth/google', [GoogleAuthController::class, 'login']);
Route::post('/auth/google/verify', [GoogleAuthController::class, 'verifyToken']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);

    // User profile and settings
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::post('/profile/avatar', [UserController::class, 'uploadAvatar']);
    Route::get('/settings', [UserController::class, 'getSettings']);
    Route::put('/settings', [UserController::class, 'updateSettings']);
    Route::post('/export-data', [UserController::class, 'exportData']);

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index']); // Complete dashboard in one call
    Route::get('/dashboard/overview', [DashboardController::class, 'overview']);
    Route::get('/dashboard/unpaid-commissions', [DashboardController::class, 'unpaidCommissions']);
    Route::get('/dashboard/history', [DashboardController::class, 'salesExpenseHistory']);
    Route::get('/dashboard/monthly-stats', [DashboardController::class, 'monthlyStats']);

    // Sales routes
    Route::apiResource('sales', SalesController::class);
    Route::patch('/sales/{sale}/mark-commission-paid', [SalesController::class, 'markCommissionPaid']);
    Route::post('/sales/mark-multiple-commissions-paid', [SalesController::class, 'markMultipleCommissionsPaid']);
    Route::patch('/sales/supplier/{supplierId}/mark-commissions-paid', [SalesController::class, 'markSupplierCommissionsPaid']);

    // Expenses routes
    Route::apiResource('expenses', ExpensesController::class);

    // Suppliers routes
    Route::apiResource('suppliers', SuppliersController::class);
});
