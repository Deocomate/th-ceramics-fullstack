<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lan_can_gom_xu', function (Blueprint $table) {
            $table->string('section_1_image')->nullable()->after('video');
            $table->string('section_1_title')->nullable()->after('section_1_image');
            $table->json('section_1_products')->nullable()->after('section_1_title');
            $table->string('section_2_image')->nullable()->after('section_1_products');
            $table->string('section_2_title')->nullable()->after('section_2_image');
            $table->json('section_2_products')->nullable()->after('section_2_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lan_can_gom_xu', function (Blueprint $table) {
            $table->dropColumn([
                'section_1_image', 'section_1_title', 'section_1_products',
                'section_2_image', 'section_2_title', 'section_2_products',
            ]);
        });
    }
};
