<?php

namespace App\Domains\Content\Domain;

final class FaqCategory
{
    /** @var array<string, string> */
    public const ALL = [
        'sản-phẩm' => 'Sản phẩm',
        'báo-giá' => 'Giá cả & Đặt hàng',
        'vận-chuyển' => 'Vận chuyển & Lắp đặt',
        'lắp-đặt' => 'Lắp đặt & Bảo trì',
        'đổi-trả' => 'Đổi trả',
    ];

    public static function isValid(string $category): bool
    {
        return array_key_exists($category, self::ALL);
    }

    public static function labelFor(string $category): string
    {
        return self::ALL[$category] ?? $category;
    }
}
