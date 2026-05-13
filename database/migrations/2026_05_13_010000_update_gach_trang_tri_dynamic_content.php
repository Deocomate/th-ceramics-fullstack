<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('dau_an_gach_trang_tri');

        Schema::table('gach_trang_tri', function (Blueprint $table) {
            if (! Schema::hasColumn('gach_trang_tri', 'ung_dung_da_dang')) {
                $table->json('ung_dung_da_dang')->nullable()->after('images');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gach_trang_tri', function (Blueprint $table) {
            if (Schema::hasColumn('gach_trang_tri', 'ung_dung_da_dang')) {
                $table->dropColumn('ung_dung_da_dang');
            }
        });

        if (! Schema::hasTable('dau_an_gach_trang_tri')) {
            Schema::create('dau_an_gach_trang_tri', function (Blueprint $table) {
                $table->id('dau_an_gach_trang_tri_id');
                $table->string('background');
                $table->string('title');
                $table->string('location');
                $table->string('description');
                $table->foreignId('gach_trang_tri_id')->constrained('gach_trang_tri', 'gach_trang_tri_id')->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }
};
