<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Product CT tables that render journey-video on detail pages.
     *
     * @var list<string>
     */
    private array $tables = [
        'ngoi_am_duong_ct',
        'ngoi_hai_co_ct',
        'ngoi_hai_van_mieu_ct',
        'gach_hoa_thong_gio_ct',
        'gach_trang_tri_ct',
        'gach_co_bat_trang_ct',
        'linh_vat_phong_thuy_ct',
        'lan_can_gom_su_ct',
        'den_vuon_gom_su_ct',
        'phu_kien_ngoi_ct',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (! Schema::hasColumn($tableName, 'video')) {
                    $table->string('video', 500)->nullable()->after('images');
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'video')) {
                    $table->dropColumn('video');
                }
            });
        }
    }
};
