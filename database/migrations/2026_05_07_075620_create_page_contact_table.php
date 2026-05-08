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
        Schema::create('page_contact', function (Blueprint $table) {
            $table->id('page_contact_id');
            $table->string('map_image', 500)->nullable();
            $table->string('hotline', 50)->nullable();
            $table->string('zalo_link', 500)->nullable();
            $table->string('form_title', 500)->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_contact');
    }
};
