<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'google_id',
        'name',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'avatar',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'is_premium',
        'premium_expires_at',
        'theme',
        'is_active',
        'email_verified_at',
        'phone_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_enabled' => 'boolean',
        'two_factor_recovery_codes' => 'array',
        'two_factor_confirmed_at' => 'datetime',
        'is_premium' => 'boolean',
        'premium_expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(UserSetting::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name) ?: $this->name;
    }

    public function isPremium(): bool
    {
        return $this->is_premium && 
               $this->premium_expires_at && 
               $this->premium_expires_at->isFuture();
    }

    public function canExportData(): bool
    {
        return $this->isPremium();
    }

    /**
     * Get the full avatar URL
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) {
            return null;
        }

        // If avatar already contains full URL, return as is
        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }

        // Otherwise, construct the full URL
        return url('storage/avatars/' . $this->avatar);
    }

    /**
     * Append avatar_url to JSON responses
     */
    protected $appends = ['avatar_url'];
}