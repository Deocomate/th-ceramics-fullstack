<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** Coupon scopes used section keys that no cart item carries; store the product type instead. */
    private const RENAMED = [
        'lan_can_gom_xu' => 'lan_can_gom_su_ct',
        'den_gom_su' => 'den_vuon_gom_su_ct',
    ];

    public function up(): void
    {
        DB::table('coupons')->whereNotNull('applicable_product_types')->orderBy('id')->each(function (object $coupon): void {
            $types = json_decode((string) $coupon->applicable_product_types, true);
            if (! is_array($types)) {
                return;
            }

            $normalized = array_values(array_unique(array_map(
                static fn ($type) => self::RENAMED[$type] ?? $type,
                $types,
            )));

            if ($normalized !== $types) {
                DB::table('coupons')->where('id', $coupon->id)->update([
                    'applicable_product_types' => json_encode($normalized),
                ]);
            }
        });
    }

    public function down(): void
    {
        // The old keys never matched a cart item, so there is nothing to restore.
    }
};
