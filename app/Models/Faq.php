<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faqs';

    protected $primaryKey = 'faq_id';

    public const CATEGORIES = [
        'sản-phẩm' => 'Sản phẩm',
        'báo-giá' => 'Giá cả & Đặt hàng',
        'vận-chuyển' => 'Vận chuyển & Lắp đặt',
        'lắp-đặt' => 'Lắp đặt & Bảo trì',
        'đổi-trả' => 'Đổi trả',
    ];

    protected $fillable = [
        'category',
        'question',
        'answer',
        'sort_order',
        'is_active',
        'is_delete',
    ];

    public function getRouteKeyName(): string
    {
        return 'faq_id';
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
