<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolicyController;

Route::get('/', function () {
    return view('welcome');
});

// Policy pages for Google Play Store compliance
Route::get('/privacy-policy', [PolicyController::class, 'privacy'])->name('privacy.policy');
Route::get('/terms-of-service', [PolicyController::class, 'terms'])->name('terms.service');
Route::get('/data-safety', [PolicyController::class, 'dataSafety'])->name('data.safety');
Route::get('/app-info', [PolicyController::class, 'appInfo'])->name('app.info');

// Alternative routes for Google Play Store
Route::get('/privacy', [PolicyController::class, 'privacy']);
Route::get('/terms', [PolicyController::class, 'terms']);
Route::get('/safety', [PolicyController::class, 'dataSafety']);
Route::get('/info', [PolicyController::class, 'appInfo']);
