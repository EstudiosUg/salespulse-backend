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
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone_number')->unique()->nullable()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            $table->string('avatar')->nullable()->after('password');
            $table->boolean('two_factor_enabled')->default(false)->after('avatar');
            $table->string('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->json('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            $table->boolean('is_premium')->default(false)->after('two_factor_confirmed_at');
            $table->timestamp('premium_expires_at')->nullable()->after('is_premium');
            $table->string('theme')->default('light')->after('premium_expires_at');
            $table->boolean('is_active')->default(true)->after('theme');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name', 
                'phone_number',
                'phone_verified_at',
                'avatar',
                'two_factor_enabled',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'is_premium',
                'premium_expires_at',
                'theme',
                'is_active'
            ]);
        });
    }
};
