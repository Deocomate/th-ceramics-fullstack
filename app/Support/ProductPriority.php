<?php

namespace App\Support;

use App\Products\ProductTypeRegistry;
use App\Services\ProductBackfillService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductPriority
{
    public static function groups(): array
    {
        $groups = [];
        foreach (ProductTypeRegistry::all() as $table => $config) {
            $groups[str_replace('_', '-', $table)] = [
                'table' => $table,
                'key' => $config['pk'],
                'category' => in_array($table, ['gach_co_bat_trang_ct', 'phu_kien_ngoi_ct', 'den_vuon_gom_su_ct'], true)
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

        DB::transaction(function () use ($group, $normalizedIds, $categoryType): void {
            $query = DB::table($group['table'])->where('is_delete', 0)->lockForUpdate();
            self::applyCategory($query, $group['category'], $categoryType);
            $currentIds = $query->orderBy($group['key'])->pluck($group['key'])->map(fn ($id) => (int) $id)->all();
            $expected = $currentIds;
            $submitted = $normalizedIds;
            sort($expected);
            sort($submitted);

            if ($expected !== $submitted) {
                throw ValidationException::withMessages(['ids' => 'Danh sách sản phẩm đã thay đổi. Hãy tải lại trang rồi thử lại.']);
            }

            foreach ($normalizedIds as $index => $id) {
                DB::table($group['table'])
                    ->where($group['key'], $id)
                    ->where('is_delete', 0)
                    ->update(['priority' => count($normalizedIds) - $index]);
                if (config('product_catalog.shadow_write')) {
                    app(ProductBackfillService::class)->sync($group['table'], $id);
                }
            }
        });
    }

    private static function applyCategory(Builder $query, ?string $categoryColumn, ?string $categoryType): void
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

        $query->where($categoryColumn, $categoryType);
    }
}
