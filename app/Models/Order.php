<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'customer_name',
        'phone',
        'email',
        'address',
        'note',
        'subtotal',
        'shipping_fee',
        'discount',
        'total_amount',
        'payment_method',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
            'payment_method' => 'string',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateOrderCode(): string
    {
        do {
            $code = 'THC-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        } while (static::where('order_code', $code)->exists());

        return $code;
    }
}
