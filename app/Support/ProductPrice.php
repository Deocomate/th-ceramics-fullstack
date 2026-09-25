<?php

namespace App\Support;

final class ProductPrice
{
    public static function unitForType(?string $productType): string
    {
        return match (true) {
            in_array($productType, ['ngoi_am_duong_ct', 'ngoi_hai_van_mieu_ct'], true) => 'm²',
            str_starts_with($productType ?? '', 'gach_') => 'viên',
            default => 'chiếc',
        };
    }

    public static function withUnit(string $displayPrice, ?string $productType): string
    {
        // Keep labels such as "Giá:" and "Từ", and leave "Liên hệ" untouched.
        return preg_replace(
            '~\s*(?:đ|₫|VNĐ)(?:\s*/\s*(?:m²|m2|viên|chiếc))?\s*$~iu',
            ' đ/'.self::unitForType($productType),
            $displayPrice,
        ) ?? $displayPrice;
    }

    public static function formatAmount(float $price, ?string $productType): string
    {
        return $price > 0
            ? number_format($price, 0, ',', '.').' đ/'.self::unitForType($productType)
            : 'Liên hệ';
    }
}
