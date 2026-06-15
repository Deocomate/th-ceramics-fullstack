<?php

use App\Support\ClientProductType;

test('resolves product type from detail route', function () {
    expect(ClientProductType::fromDetailRoute('client.products.lan-can-gom-su.detail'))
        ->toBe('lan_can_gom_su_ct');
});

test('resolves product id from model pk field', function () {
    $product = (object) ['lan_can_gom_su_ct_id' => 42];

    expect(ClientProductType::resolveProductId($product, 'lan_can_gom_su_ct'))->toBe(42);
});

test('resolves first variant from phan loai relation', function () {
    $product = (object) [
        'phanLoais' => collect([
            (object) ['phan_loai_lan_can_gom_su_ct_id' => 5, 'price' => 100000, 'is_delete' => 0],
            (object) ['phan_loai_lan_can_gom_su_ct_id' => 8, 'price' => 80000, 'is_delete' => 0],
        ]),
    ];

    expect(ClientProductType::resolveFirstVariantId($product, 'lan_can_gom_su_ct'))->toBe(8);
});
