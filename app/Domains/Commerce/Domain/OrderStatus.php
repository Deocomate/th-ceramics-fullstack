<?php

namespace App\Domains\Commerce\Domain;

final class OrderStatus
{
    private const LABELS = [
        'pending_payment' => 'Chờ thanh toán',
        'processing' => 'Đang xử lý',
        'shipping' => 'Đang giao hàng',
        'completed' => 'Hoàn tất',
        'canceled' => 'Đã hủy',
        'returned' => 'Đổi trả',
    ];

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }

    public static function label(string $status): string
    {
        return self::LABELS[$status] ?? $status;
    }
}
