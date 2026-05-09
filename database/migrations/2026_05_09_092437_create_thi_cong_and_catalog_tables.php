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
        // 1. Tạo bảng thi_cong
        Schema::create('thi_cong', function (Blueprint $table) {
            // Khóa chính kiểu int, tự tăng theo yêu cầu
            $table->increments('thi_cong'); 
            
            // Các cột not null
            $table->string('tieu_de');
            $table->string('anh');
            
            // Cột không yêu cầu not null nên cho phép nullable()
            $table->string('link_youtube')->nullable();
            
            // Tạo thêm cột created_at và updated_at (Chuẩn thường dùng trong Laravel)
            $table->timestamps(); 
        });

        // 2. Tạo bảng catalog
        Schema::create('catalog', function (Blueprint $table) {
            // Khóa chính kiểu int, tự tăng
            $table->increments('catalog_id');
            
            // Các cột không ghi rõ [not null] nên được thiết lập nullable()
            $table->string('tieu_de')->nullable();
            $table->string('anh_dai_dien')->nullable();
            $table->text('file')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa bảng theo thứ tự ngược lại (để tránh lỗi khóa ngoại nếu sau này có)
        Schema::dropIfExists('catalog');
        Schema::dropIfExists('thi_cong');
    }
};