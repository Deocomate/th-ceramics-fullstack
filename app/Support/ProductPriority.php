<?php

namespace App\Support;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductPriority
{
    /** @var array<string, array{table: string, key: string, category: ?string}> */
    private const GROUPS = [
        'ngoi-am-duong-ct' => ['table' => 'ngoi_am_duong_ct', 'key' => 'ngoi_am_duong_ct_id', 'category' => null],
        'ngoi-hai-co-ct' => ['table' => 'ngoi_hai_co_ct', 'key' => 'ngoi_hai_co_ct_id', 'category' => null],
        'ngoi-hai-van-mieu-ct' => ['table' => 'ngoi_hai_van_mieu_ct', 'key' => 'ngoi_hai_van_mieu_ct_id', 'category' => null],
        'gach-hoa-thong-gio-ct' => ['table' => 'gach_hoa_thong_gio_ct', 'key' => 'gach_hoa_thong_gio_ct_id', 'category' => null],
        'gach-trang-tri-ct' => ['table' => 'gach_trang_tri_ct', 'key' => 'gach_trang_tri_ct_id', 'category' => null],
        'gach-co-bat-trang-ct' => ['table' => 'gach_co_bat_trang_ct', 'key' => 'gach_co_bat_trang_ct_id', 'category' => 'category_type'],
        'linh-vat-phong-thuy-ct' => ['table' => 'linh_vat_phong_thuy_ct', 'key' => 'linh_vat_phong_thuy_ct_id', 'category' => null],
        'phu-kien-ngoi-ct' => ['table' => 'phu_kien_ngoi_ct', 'key' => 'phu_kien_ngoi_ct_id', 'category' => 'category_type'],
        'lan-can-gom-su-ct' => ['table' => 'lan_can_gom_su_ct', 'key' => 'lan_can_gom_su_ct_id', 'category' => null],
        'den-vuon-gom-su-ct' => ['table' => 'den_vuon_gom_su_ct', 'key' => 'den_vuon_gom_su_ct_id', 'category' => 'category_type'],
    ];

    public static function groups(): array
    {
        return self::GROUPS;
    }

    public static function reorder(string $type, array $ids, ?string $categoryType = null): void
    {
        $group = self::GROUPS[$type] ?? null;

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
