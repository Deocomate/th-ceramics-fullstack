<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ConsultationRequest extends Model
{
    protected $fillable = [
        'product_id',
        'product_type',
        'product_name',
        'variant_name',
        'customer_name',
        'phone',
        'email',
        'note',
        'status',
    ];

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessed(Builder $query): Builder
    {
        return $query->where('status', 'processed');
    }

    public static function statusLabel(string $status): string
    {
        return match ($status) {
            'processed' => 'Đã xử lý',
            default => 'Chờ xử lý',
        };
    }
}
