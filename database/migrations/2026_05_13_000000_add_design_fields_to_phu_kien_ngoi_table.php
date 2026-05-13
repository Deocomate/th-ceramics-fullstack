<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phu_kien_ngoi', function (Blueprint $table) {
            $table->string('banner_text_1')->nullable()->after('thumbnail_main');
            $table->string('banner_text_2')->nullable()->after('banner_text_1');
            $table->string('sec1_title')->nullable()->after('banner_text_2');
            $table->string('sec1_image')->nullable()->after('sec1_title');
            $table->string('sec2_title')->nullable()->after('sec1_image');
            $table->string('sec2_image')->nullable()->after('sec2_title');
        });
    }

    public function down(): void
    {
        Schema::table('phu_kien_ngoi', function (Blueprint $table) {
            $table->dropColumn([
                'banner_text_1',
                'banner_text_2',
                'sec1_title',
                'sec1_image',
                'sec2_title',
                'sec2_image',
            ]);
        });
    }
};
