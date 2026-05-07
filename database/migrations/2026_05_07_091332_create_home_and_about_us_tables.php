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
        // ---------------------------------------------------------
        // 1. Tạo bảng trang_chu
        // ---------------------------------------------------------
        Schema::create('trang_chu', function (Blueprint $table) {
            $table->id('trang_chu_id');
            $table->longText('banner');
            $table->longText('khach_hang_doi_tac')->nullable();
            $table->json('loi_tri_an');
            $table->string('loi_tri_an_anh');
            $table->longText('ve_chung_toi_logo');
            $table->string('video')->nullable();
            $table->json('nhung_con_so')->nullable();
            $table->longText('showroom_images');
            $table->longText('showroom_noidung')->nullable();
            
            $table->timestamps();
        });

        // ---------------------------------------------------------
        // 2. Tạo bảng ve_chung_toi
        // ---------------------------------------------------------
        Schema::create('ve_chung_toi', function (Blueprint $table) {
            $table->id('ve_chung_toi_id');
            
            $table->string('banner'); 
            
            $table->string('header_banner');
            $table->string('body_banner');
            
            $table->json('gs_head');
            $table->json('gs_gia_tri');
            $table->json('gs_hanh_trinh');
            $table->string('gs_nguoi_sang_lap_anh');
            $table->longText('gs_nguoi_sang_lap_noi_dung');
            $table->json('gs_giai_thuong');
            
            $table->string('nt_head');
            $table->longText('nt_body');
            $table->json('nt_ngon_ngu');
            $table->string('nt_che_tac_head');
            $table->longText('nt_che_tac_body');
            $table->string('nt_che_tac_anh');
            
            $table->string('nt_luyen_dat_head');
            $table->longText('nt_luyen_dat_body');
            $table->json('nt_luyen_dat_item');
            
            $table->string('nt_dun_lo_head');
            $table->longText('nt_dun_lo_body');
            $table->string('nt_dun_lo_anh');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop bảng theo thứ tự ngược lại
        Schema::dropIfExists('ve_chung_toi');
        Schema::dropIfExists('trang_chu');
    }
};