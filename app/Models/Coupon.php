<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'title',
        'description',
        'code',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'min_order_value',
        'applicable_product_types',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'banner_image',
        'show_banner',
        'is_active',
        'is_delete',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'float',
            'max_discount_amount' => 'integer',
            'min_order_value' => 'integer',
            'applicable_product_types' => 'array',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'show_banner' => 'boolean',
            'is_active' => 'boolean',
            'is_delete' => 'boolean',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function isValid(): bool
    {
        if (! $this->is_active || $this->is_delete) {
            return false;
        }

        $now = now();

        if ($now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date !== null && $now->gt($this->end_date)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }
}
