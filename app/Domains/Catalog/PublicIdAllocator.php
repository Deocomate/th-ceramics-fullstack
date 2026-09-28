<?php

namespace App\Domains\Catalog;

use App\Domains\Catalog\Models\Product;
use App\Domains\Catalog\Models\ProductVariant;
use DomainException;
use Illuminate\Support\Facades\DB;

class PublicIdAllocator
{
    public function reconcileSequences(): void
    {
        $types = DB::table('product_public_ids')->select('type_key')
            ->union(DB::table('variant_public_ids')->select('type_key'))
            ->pluck('type_key');
        foreach ($types as $type) {
            DB::table('catalog_public_id_sequences')->insertOrIgnore([
                'type_key' => $type,
                'next_product_id' => 1,
                'next_variant_id' => 1,
            ]);
            $current = DB::table('catalog_public_id_sequences')->where('type_key', $type)->lockForUpdate()->first();
            DB::table('catalog_public_id_sequences')->where('type_key', $type)->update([
                'next_product_id' => max((int) $current->next_product_id, (int) DB::table('product_public_ids')->where('type_key', $type)->max('public_id') + 1),
                'next_variant_id' => max((int) $current->next_variant_id, (int) DB::table('variant_public_ids')->where('type_key', $type)->max('public_id') + 1),
            ]);
        }
    }

    public function product(Product $product, ?int $preferred = null): int
    {
        return $this->assign('product_public_ids', 'product_id', $product->id, $product->type_key, 'next_product_id', $preferred);
    }

    public function variant(ProductVariant $variant, ?int $preferred = null): int
    {
        $type = $variant->product?->type_key;
        if (! $type) {
            throw new DomainException('Biến thể chưa gắn với sản phẩm.');
        }

        return $this->assign('variant_public_ids', 'product_variant_id', $variant->id, $type, 'next_variant_id', $preferred);
    }

    private function assign(string $table, string $ownerColumn, int $ownerId, string $type, string $sequenceColumn, ?int $preferred): int
    {
        if ($preferred !== null && $preferred < 1) {
            throw new DomainException('ID công khai phải là số dương.');
        }

        return DB::transaction(function () use ($table, $ownerColumn, $ownerId, $type, $sequenceColumn, $preferred): int {
            $existing = DB::table($table)->where($ownerColumn, $ownerId)->first();
            if ($existing) {
                if ($existing->type_key !== $type || ($preferred !== null && (int) $existing->public_id !== $preferred)) {
                    throw new DomainException('ID công khai của bản ghi không khớp.');
                }

                return (int) $existing->public_id;
            }

            DB::table('catalog_public_id_sequences')->insertOrIgnore([
                'type_key' => $type,
                'next_product_id' => 1,
                'next_variant_id' => 1,
            ]);
            $sequence = DB::table('catalog_public_id_sequences')->where('type_key', $type)->lockForUpdate()->first();
            $id = $preferred ?? (int) $sequence->{$sequenceColumn};
            if (DB::table($table)->where('type_key', $type)->where('public_id', $id)->exists()) {
                throw new DomainException("ID công khai {$type}/{$id} đã được sử dụng.");
            }
            DB::table($table)->insert(['type_key' => $type, 'public_id' => $id, $ownerColumn => $ownerId]);
            DB::table('catalog_public_id_sequences')->where('type_key', $type)->update([
                $sequenceColumn => max((int) $sequence->{$sequenceColumn}, $id + 1),
            ]);

            return $id;
        });
    }
}
