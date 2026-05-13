<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gach_co_bat_trang', function (Blueprint $table) {
            if (! Schema::hasColumn('gach_co_bat_trang', 'section_bat')) {
                $table->json('section_bat')->nullable()->after('images');
            }

            if (! Schema::hasColumn('gach_co_bat_trang', 'section_that')) {
                $table->json('section_that')->nullable()->after('section_bat');
            }

            if (! Schema::hasColumn('gach_co_bat_trang', 'section_the')) {
                $table->json('section_the')->nullable()->after('section_that');
            }
        });

        Schema::table('gach_co_bat_trang_ct', function (Blueprint $table) {
            if (! Schema::hasColumn('gach_co_bat_trang_ct', 'category_type')) {
                $table->string('category_type', 20)->default('bat')->after('name')->index();
            }

            if (! Schema::hasColumn('gach_co_bat_trang_ct', 'dinh_muc')) {
                $table->string('dinh_muc', 50)->nullable()->after('size');
            }

            if (! Schema::hasColumn('gach_co_bat_trang_ct', 'weight')) {
                $table->string('weight', 50)->nullable()->after('dinh_muc');
            }
        });

        DB::table('gach_co_bat_trang_ct')
            ->whereNull('category_type')
            ->orWhere('category_type', '')
            ->update(['category_type' => 'bat']);
    }

    public function down(): void
    {
        Schema::table('gach_co_bat_trang_ct', function (Blueprint $table) {
            if (Schema::hasColumn('gach_co_bat_trang_ct', 'category_type')) {
                $table->dropIndex(['category_type']);
                $table->dropColumn('category_type');
            }

            if (Schema::hasColumn('gach_co_bat_trang_ct', 'weight')) {
                $table->dropColumn('weight');
            }

            if (Schema::hasColumn('gach_co_bat_trang_ct', 'dinh_muc')) {
                $table->dropColumn('dinh_muc');
            }
        });

        Schema::table('gach_co_bat_trang', function (Blueprint $table) {
            foreach (['section_the', 'section_that', 'section_bat'] as $column) {
                if (Schema::hasColumn('gach_co_bat_trang', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
