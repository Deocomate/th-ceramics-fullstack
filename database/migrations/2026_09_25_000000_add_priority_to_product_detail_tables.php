<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $groups = [
        'ngoi_am_duong_ct' => ['ngoi_am_duong_ct_id', null],
        'ngoi_hai_co_ct' => ['ngoi_hai_co_ct_id', null],
        'ngoi_hai_van_mieu_ct' => ['ngoi_hai_van_mieu_ct_id', null],
        'gach_hoa_thong_gio_ct' => ['gach_hoa_thong_gio_ct_id', null],
        'gach_trang_tri_ct' => ['gach_trang_tri_ct_id', null],
        'gach_co_bat_trang_ct' => ['gach_co_bat_trang_ct_id', 'category_type'],
        'linh_vat_phong_thuy_ct' => ['linh_vat_phong_thuy_ct_id', null],
        'phu_kien_ngoi_ct' => ['phu_kien_ngoi_ct_id', 'category_type'],
        'lan_can_gom_su_ct' => ['lan_can_gom_su_ct_id', null],
        'den_vuon_gom_su_ct' => ['den_vuon_gom_su_ct_id', 'category_type'],
    ];

    public function up(): void
    {
        foreach ($this->groups as $table => [$primaryKey]) {
            Schema::table($table, function (Blueprint $table) {
                $table->unsignedInteger('priority')->default(0)->index();
            });
        }

        foreach ($this->groups as $tableName => [$primaryKey, $categoryColumn]) {
            $query = DB::table($tableName)->orderByDesc('created_at')->orderByDesc($primaryKey);
            $rows = $query->get([$primaryKey, ...($categoryColumn ? [$categoryColumn] : [])]);
            $buckets = $categoryColumn ? $rows->groupBy($categoryColumn) : collect(['all' => $rows]);

            foreach ($buckets as $bucket) {
                $priority = $bucket->count();
                foreach ($bucket as $row) {
                    DB::table($tableName)
                        ->where($primaryKey, $row->{$primaryKey})
                        ->update(['priority' => $priority--]);
                }
            }
        }
    }

    public function down(): void
    {
        foreach (array_keys($this->groups) as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('priority');
            });
        }
    }
};
