<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('den_vuon_gom_su_ct', function (Blueprint $table) {
            $table->string('category_type', 20)->default('den_gom')->after('name')->index();
        });

        DB::table('den_vuon_gom_su_ct')
            ->whereRaw('den_vuon_gom_su_ct_id % 2 = 0')
            ->update(['category_type' => 'den_su']);

        DB::table('den_vuon_gom_su_ct')
            ->whereRaw('den_vuon_gom_su_ct_id % 2 = 1')
            ->update(['category_type' => 'den_gom']);
    }

    public function down(): void
    {
        Schema::table('den_vuon_gom_su_ct', function (Blueprint $table) {
            $table->dropIndex(['category_type']);
            $table->dropColumn('category_type');
        });
    }
};
