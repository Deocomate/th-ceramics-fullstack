<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trang_du_an', function (Blueprint $table) {
            $table->id('trang_du_an_id');
            $table->text('promo_title');
            $table->string('promo_image');
            $table->string('promo_cta_label')->default('XEM CATALOG');
            $table->string('promo_cta_url')->nullable();
            $table->boolean('promo_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trang_du_an');
    }
};
