<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier_id',
        'product_name',
        'price',
        'quantity',
        'commission',
        'feedback',
        'commission_paid',
        'sale_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'commission' => 'decimal:2',
        'commission_paid' => 'boolean',
        'sale_date' => 'date',
    ];

    protected $appends = ['total_amount'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getTotalAmountAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    public function scopeUnpaidCommission($query)
    {
        return $query->where('commission_paid', false);
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('sale_date', $month)
                    ->whereYear('sale_date', $year);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('sale_date', [$startDate, $endDate]);
    }
}
