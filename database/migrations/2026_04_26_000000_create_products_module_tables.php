<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ================== CATEGORY: NGOI AM DUONG ==================
        Schema::create('ngoi_am_duong', function (Blueprint $table) {
            $table->id('ngoi_am_duong_id');
            $table->string('thumbnail_main');
            $table->string('thumbnail1');
            $table->string('thumbnail2');
            $table->longText('video')->nullable();
            $table->timestamps();
        });
        Schema::create('dinh_muc_ngoi_am_duong', function (Blueprint $table) {
            $table->id('dinh_muc_ngoi_am_duong_id');
            $table->string('roof_type');
            $table->string('tile_type');
            $table->integer('ngoi_am');
            $table->integer('ngoi_duong');
            $table->integer('diem');
            $table->timestamps();
            $table->unique(['roof_type', 'tile_type']);
        });

        // ================== CATEGORY: NGOI HAI ==================
        Schema::create('ngoi_hai_van_mieu', function (Blueprint $table) {
            $table->id('ngoi_hai_van_mieu_id');
            $table->string('thumbnail_main');
            $table->string('title1', 50);
            $table->string('thumbnail1');
            $table->string('title2', 50);
            $table->string('thumbnail2');
            $table->string('title3', 50);
            $table->string('thumbnail3');
            $table->longText('video')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
        });
        Schema::create('dinh_muc_ngoi_hai_co', function (Blueprint $table) {
            $table->id('dinh_muc_ngoi_hai_co_id');
            $table->string('roof_type')->unique();
            $table->integer('ngoi_tren_mai_go');
            $table->integer('ngoi_tren_mai_be_tong');
            $table->timestamps();
        });
        Schema::create('dinh_muc_ngoi_hai_van_mieu', function (Blueprint $table) {
            $table->id('dinh_muc_ngoi_hai_van_mieu_id');
            $table->string('roof_type');
            $table->integer('ngoi_tren_mai_go');
            $table->integer('ngoi_tren_mai_be_tong');
            $table->timestamps();
        });

        // ================== CATEGORY: GACH HOA THONG GIO ==================
        Schema::create('gach_hoa_thong_gio', function (Blueprint $table) {
            $table->id('gach_hoa_thong_gio_id');
            $table->string('video_thumbnail');
            $table->longText('video_url')->nullable();
            $table->json('process_images')->nullable();
            $table->timestamps();
        });
        Schema::create('gach_hoa_thong_gio_anh', function (Blueprint $table) {
            $table->id('gach_hoa_thong_gio_anh_id');
            $table->string('image');
            $table->foreignId('gach_hoa_thong_gio_id')->constrained('gach_hoa_thong_gio', 'gach_hoa_thong_gio_id')->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('gia_tri_gach_hoa_thong_gio', function (Blueprint $table) {
            $table->id('gia_tri_gach_hoa_thong_gio_id');
            $table->string('background');
            $table->string('image');
            $table->string('title', 50);
            $table->longText('desscription');
            $table->foreignId('gach_hoa_thong_gio_id')->constrained('gach_hoa_thong_gio', 'gach_hoa_thong_gio_id')->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('dinh_muc_gach_hoa_thong_gio', function (Blueprint $table) {
            $table->id('dinh_muc_gach_hoa_thong_gio_id');
            $table->string('brick_type')->unique();
            $table->integer('value');
            $table->timestamps();
        });

        // ================== CATEGORY: GACH TRANG TRI ==================
        Schema::create('gach_trang_tri', function (Blueprint $table) {
            $table->id('gach_trang_tri_id');
            $table->string('thumbnail_main');
            $table->longText('video')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
        });
        Schema::create('dau_an_gach_trang_tri', function (Blueprint $table) {
            $table->id('dau_an_gach_trang_tri_id');
            $table->string('background');
            $table->string('image');
            $table->string('title', 50);
            $table->string('desscription', 255);
            $table->foreignId('gach_trang_tri_id')->constrained('gach_trang_tri', 'gach_trang_tri_id')->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('dinh_muc_gach_trang_tri', function (Blueprint $table) {
            $table->id('dinh_muc_gach_trang_tri_id');
            $table->string('brick_type')->unique();
            $table->integer('value');
            $table->timestamps();
        });

        // ================== CATEGORY: GACH CO BAT TRANG ==================
        Schema::create('gach_co_bat_trang', function (Blueprint $table) {
            $table->id('gach_co_bat_trang_id');
            $table->string('thumbnail_main');
            $table->longText('video')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
        });
        Schema::create('gach_co_bat_trang_anh', function (Blueprint $table) {
            $table->id('gach_co_bat_trang_anh_id');
            $table->string('image');
            $table->foreignId('gach_co_bat_trang_id')->constrained('gach_co_bat_trang', 'gach_co_bat_trang_id')->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('dinh_muc_gach_co_bat_trang', function (Blueprint $table) {
            $table->id('dinh_muc_gach_co_bat_trang_id');
            $table->string('brick_type')->unique();
            $table->integer('value');
            $table->timestamps();
        });

        // ================== CATEGORY: LINH VAT PHONG THUY ==================
        Schema::create('linh_vat_phong_thuy', function (Blueprint $table) {
            $table->id('linh_vat_phong_thuy_id');
            $table->string('thumbnail_main');
            $table->longText('video')->nullable();
            $table->timestamps();
        });
        Schema::create('linh_vat', function (Blueprint $table) {
            $table->id('linh_vat_id');
            $table->string('title');
            $table->string('image');
            $table->longText('description')->nullable();
            $table->foreignId('linh_vat_phong_thuy_id')->constrained('linh_vat_phong_thuy', 'linh_vat_phong_thuy_id')->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('linh_vat_phong_thuy_anh', function (Blueprint $table) {
            $table->id('linh_vat_phong_thuy_anh_id');
            $table->string('image');
            $table->foreignId('linh_vat_phong_thuy_id')->constrained('linh_vat_phong_thuy', 'linh_vat_phong_thuy_id')->cascadeOnDelete();
            $table->timestamps();
        });

        // ================== CATEGORY: PHU KIEN NGOI ==================
        Schema::create('phu_kien_ngoi', function (Blueprint $table) {
            $table->id('phu_kien_ngoi_id');
            $table->string('thumbnail_main');
            $table->longText('video')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();
        });

        // ================== CAC DANH MUC KHAC ==================
        Schema::create('lan_can_gom_xu', function (Blueprint $table) {
            $table->id('lan_can_gom_xu_id');
            $table->string('thumbnail_main');
            $table->longText('video')->nullable();
            $table->timestamps();
        });
        Schema::create('den_gom_su', function (Blueprint $table) {
            $table->id('den_gom_su_id');
            $table->string('thumbnail_main');
            $table->longText('video')->nullable();
            $table->string('image1');
            $table->string('image2');
            $table->string('title2', 30)->nullable();
            $table->string('image3');
            $table->string('title3', 30)->nullable();
            $table->string('image4');
            $table->timestamps();
        });
        Schema::create('den_gom_su_anh', function (Blueprint $table) {
            $table->id('den_gom_su_anh_id');
            $table->string('image');
            $table->foreignId('den_gom_su_id')->constrained('den_gom_su', 'den_gom_su_id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        $tables = [
            'den_gom_su_anh', 'den_gom_su', 'lan_can_gom_xu', 'phu_kien_ngoi',
            'linh_vat_phong_thuy_anh', 'linh_vat', 'linh_vat_phong_thuy',
            'dinh_muc_gach_co_bat_trang', 'gach_co_bat_trang_anh', 'gach_co_bat_trang',
            'dinh_muc_gach_trang_tri', 'dau_an_gach_trang_tri', 'gach_trang_tri',
            'dinh_muc_gach_hoa_thong_gio', 'gia_tri_gach_hoa_thong_gio', 'gach_hoa_thong_gio_anh', 'gach_hoa_thong_gio',
            'dinh_muc_ngoi_hai_van_mieu', 'dinh_muc_ngoi_hai_co', 'ngoi_hai_van_mieu',
            'dinh_muc_ngoi_am_duong', 'ngoi_am_duong',
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
        Schema::enableForeignKeyConstraints();
    }
};
