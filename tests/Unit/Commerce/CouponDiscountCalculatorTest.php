<?php

use App\Domains\Commerce\Domain\CouponDiscountCalculator;

test('coupon calculator applies product scope minimum and discount cap', function () {
    $coupon = [
        'is_active' => true,
        'is_delete' => false,
        'start_date' => '2026-01-01 00:00:00',
        'end_date' => null,
        'usage_limit' => 20,
        'used_count' => 3,
        'applicable_product_types' => ['linh_vat_phong_thuy_ct'],
        'min_order_value' => 1000,
        'discount_type' => 'percent',
        'discount_value' => 25,
        'max_discount_amount' => 400,
    ];

    $result = (new CouponDiscountCalculator)->calculate($coupon, [
        ['product_type' => 'linh_vat_phong_thuy_ct', 'price' => 1000, 'quantity' => 2],
        ['product_type' => 'gach_trang_tri_ct', 'price' => 10000, 'quantity' => 1],
    ], new DateTimeImmutable('2026-09-29 12:00:00'));

    expect($result)->toMatchArray([
        'valid' => true,
        'discount' => 400,
        'message' => 'Áp dụng mã giảm giá thành công.',
    ]);
});

test('coupon calculator rejects exhausted usage', function () {
    $coupon = [
        'is_active' => true,
        'is_delete' => false,
        'start_date' => '2026-01-01 00:00:00',
        'end_date' => null,
        'usage_limit' => 1,
        'used_count' => 1,
        'applicable_product_types' => null,
        'min_order_value' => 0,
        'discount_type' => 'fixed',
        'discount_value' => 100,
        'max_discount_amount' => null,
    ];

    expect((new CouponDiscountCalculator)->calculate($coupon, [], new DateTimeImmutable('2026-09-29 12:00:00')))
        ->toMatchArray(['valid' => false, 'discount' => 0, 'message' => 'Mã giảm giá đã hết lượt sử dụng.']);
});
