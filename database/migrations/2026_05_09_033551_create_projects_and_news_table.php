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
        // 1. Tạo bảng Danh Mục Dự Án (Bảng cha)
        Schema::create('danh_muc_du_an', function (Blueprint $table) {
            $table->increments('danh_muc_du_an_id');
            $table->string('ten_danh_muc')->unique();
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
        });

        // 2. Tạo bảng Dự Án (Bảng con - chứa khóa ngoại liên kết với danh_muc_du_an)
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

            // Khóa ngoại
            $table->foreign('danh_muc_du_an_id')
                  ->references('danh_muc_du_an_id')
                  ->on('danh_muc_du_an')
                  ->onDelete('cascade');
        });

        // 3. Tạo bảng Danh Mục Tin Tức (Bảng cha)
        Schema::create('danh_muc_tin_tuc', function (Blueprint $table) {
            $table->increments('danh_muc_tin_tuc_id');
            $table->string('ten_danh_muc')->unique();
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
        });

        // 4. Tạo bảng Tin Tức (Bảng con - chứa khóa ngoại liên kết với danh_muc_tin_tuc)
        Schema::create('tin_tuc', function (Blueprint $table) {
            $table->increments('tin_tuc_id');
            $table->unsignedInteger('danh_muc_tin_tuc_id');
            
            // Thông tin cơ bản
            $table->string('tieu_de');
            $table->string('slug')->unique();
            $table->string('anh_dai_dien')->nullable();
            $table->text('mo_ta_ngan');
            $table->string('the_loai')->nullable();
            
            // Nội dung chi tiết
            $table->json('noi_dung_blocks')->nullable();
            
            // Trạng thái & Theo dõi
            $table->string('trang_thai')->default('draft');
            $table->timestamp('ngay_dang')->nullable();
            
            $table->timestamps(); // Đã bao gồm created_at và updated_at

            // Khóa ngoại
            $table->foreign('danh_muc_tin_tuc_id')
                  ->references('danh_muc_tin_tuc_id')
                  ->on('danh_muc_tin_tuc')
                  ->onDelete('cascade');
        });

        // 5. Tạo bảng Tác Giả (Bảng độc lập)
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // QUAN TRỌNG: Phải xóa bảng con trước, bảng cha sau để tránh lỗi khóa ngoại
        Schema::dropIfExists('tac_gia');
        Schema::dropIfExists('tin_tuc');
        Schema::dropIfExists('danh_muc_tin_tuc');
        Schema::dropIfExists('du_an');
        Schema::dropIfExists('danh_muc_du_an');
    }
};