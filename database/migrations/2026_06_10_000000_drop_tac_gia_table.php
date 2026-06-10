<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('tac_gia');
    }

    public function down(): void
    {
        Schema::create('tac_gia', function (Blueprint $table) {
            $table->increments('tac_gia_id');
            $table->string('ten_tac_gia');
            $table->text('link_fb')->nullable();
            $table->text('link_linkedin')->nullable();
            $table->text('link_tele')->nullable();
            $table->text('link_sky')->nullable();
            $table->longText('mo_ta');
            $table->string('anh_dai_dien');
            $table->timestamps();
        });
    }
};
