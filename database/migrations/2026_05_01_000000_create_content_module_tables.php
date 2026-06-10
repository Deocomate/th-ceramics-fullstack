<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Danh muc & Du an
        Schema::create('danh_muc_du_an', function (Blueprint $table) {
            $table->increments('danh_muc_du_an_id');
            $table->string('ten_danh_muc')->unique();
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
        });
        Schema::create('du_an', function (Blueprint $table) {
            $table->increments('du_an_id');
            $table->string('ten_du_an');
            $table->string('dia_diem');
            $table->string('san_pham');
            $table->integer('nam')->nullable();
            $table->longText('images');
            $table->unsignedInteger('danh_muc_du_an_id');
            $table->string('slug')->unique();
            $table->timestamps();
            $table->foreign('danh_muc_du_an_id')->references('danh_muc_du_an_id')->on('danh_muc_du_an')->onDelete('cascade');
        });

        // Danh muc & Tin tuc
        Schema::create('danh_muc_tin_tuc', function (Blueprint $table) {
            $table->increments('danh_muc_tin_tuc_id');
            $table->string('ten_danh_muc')->unique();
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
        });
        Schema::create('tin_tuc', function (Blueprint $table) {
            $table->increments('tin_tuc_id');
            $table->unsignedInteger('danh_muc_tin_tuc_id');
            $table->string('tieu_de');
            $table->string('slug')->unique();
            $table->string('anh_dai_dien')->nullable();
            $table->text('mo_ta_ngan');
            $table->string('the_loai')->nullable();
            $table->json('noi_dung_blocks')->nullable();
            $table->string('trang_thai')->default('draft');
            $table->timestamp('ngay_dang')->nullable();
            $table->timestamps();
            $table->foreign('danh_muc_tin_tuc_id')->references('danh_muc_tin_tuc_id')->on('danh_muc_tin_tuc')->onDelete('cascade');
        });

        // Thi cong
        Schema::create('thi_cong', function (Blueprint $table) {
            $table->increments('thi_cong');
            $table->string('tieu_de');
            $table->string('anh');
            $table->string('link_youtube')->nullable();
            $table->timestamps();
        });

        // Catalog
        Schema::create('catalog', function (Blueprint $table) {
            $table->increments('catalog_id');
            $table->string('tieu_de')->nullable();
            $table->string('anh_dai_dien')->nullable();
            $table->text('file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog');
        Schema::dropIfExists('thi_cong');
        Schema::dropIfExists('tin_tuc');
        Schema::dropIfExists('danh_muc_tin_tuc');
        Schema::dropIfExists('du_an');
        Schema::dropIfExists('danh_muc_du_an');
    }
};
