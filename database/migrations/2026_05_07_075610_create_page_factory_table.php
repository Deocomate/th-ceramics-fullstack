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
        Schema::create('page_factory', function (Blueprint $table) {
            $table->id('page_factory_id');
            $table->string('hero_banner_desktop', 500)->nullable();
            $table->string('hero_banner_mobile', 500)->nullable();
            $table->string('intro_title', 500)->nullable();
            $table->string('intro_subtitle', 500)->nullable();
            $table->text('intro_description')->nullable();
            $table->json('gallery_1')->nullable();
            $table->string('process_title', 500)->nullable();
            $table->text('process_description')->nullable();
            $table->json('process_slider')->nullable();
            $table->string('process_bottom_title', 500)->nullable();
            $table->text('process_bottom_desc')->nullable();
            $table->string('process_bottom_image', 500)->nullable();
            $table->json('material_slider')->nullable();
            $table->json('material_steps')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_factory');
    }
};
