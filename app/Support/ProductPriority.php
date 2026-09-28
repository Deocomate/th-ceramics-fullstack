<?php

namespace App\Support;

use App\Domains\Catalog\ProductTypeRegistry;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductPriority
{
    public static function groups(): array
    {
        $groups = [];
        foreach (array_keys(ProductTypeRegistry::all()) as $type) {
            $groups[str_replace('_', '-', $type)] = [
                'type_key' => $type,
                'category' => in_array($type, ['gach_co_bat_trang_ct', 'phu_kien_ngoi_ct', 'den_vuon_gom_su_ct'], true)
                    ? 'category_type' : null,
            ];
        }

        return $groups;
    }

    public static function reorder(string $type, array $ids, ?string $categoryType = null): void
    {
        $group = self::groups()[$type] ?? null;

        if ($group === null || count($ids) !== count(array_unique($ids))) {
            throw ValidationException::withMessages(['ids' => 'Danh sách thứ tự sản phẩm không hợp lệ.']);
        }

        $normalizedIds = array_map('intval', $ids);
        $typeKey = $group['type_key'];

        DB::transaction(function () use ($group, $typeKey, $normalizedIds, $categoryType): void {
            $query = DB::table('products')
                ->leftJoin('product_public_ids', function ($join) use ($typeKey) {
                    $join->on('product_public_ids.product_id', '=', 'products.id')
                        ->where('product_public_ids.type_key', '=', $typeKey);
                })
                ->where('products.type_key', $typeKey)
                ->where('products.is_delete', false)
                ->lockForUpdate();

            self::applyCategory($query, $group['category'], $categoryType, 'products.');

            $rows = $query->get(['products.id as db_id', 'product_public_ids.public_id']);
            if ($rows->contains(fn ($row) => $row->public_id === null)) {
                throw ValidationException::withMessages(['ids' => 'Sản phẩm thiếu ID công khai. Hãy tải lại trang rồi thử lại.']);
            }

            $expected = $rows->map(fn ($row) => (int) $row->public_id)->all();
            $submitted = $normalizedIds;
            sort($expected);
            sort($submitted);

            if ($expected !== $submitted) {
                throw ValidationException::withMessages(['ids' => 'Danh sách sản phẩm đã thay đổi. Hãy tải lại trang rồi thử lại.']);
            }

            $dbIdsByPublicId = $rows->mapWithKeys(fn ($row) => [(int) $row->public_id => (int) $row->db_id]);
            foreach ($normalizedIds as $index => $publicId) {
                DB::table('products')
                    ->where('id', $dbIdsByPublicId[$publicId])
                    ->update(['priority' => count($normalizedIds) - $index]);
            }
        });
    }

    private static function applyCategory(Builder $query, ?string $categoryColumn, ?string $categoryType, string $prefix = ''): void
    {
        if ($categoryColumn === null) {
            if ($categoryType !== null) {
                throw ValidationException::withMessages(['category_type' => 'Nhóm sản phẩm không hợp lệ.']);
            }

            return;
        }

        if ($categoryType === null || $categoryType === '') {
            throw ValidationException::withMessages(['category_type' => 'Vui lòng chọn nhóm sản phẩm.']);
        }

        $query->where($prefix . $categoryColumn, $categoryType);
    }
}
