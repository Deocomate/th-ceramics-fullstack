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
        Schema::create('ngoi_am_duong_ct', function (Blueprint $table) {
            $table->id('ngoi_am_duong_ct_id');
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->json('images');
            $table->integer('price');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
        Schema::create('mau_sac_ngoi_am_duong_ct', function (Blueprint $table) {
            $table->id('mau_sac_ngoi_am_duong_ct_id');
            $table->string('name');
            $table->string('image');
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
        Schema::create('ngoi_hai_co_ct', function (Blueprint $table) {
            $table->id('ngoi_hai_co_ct_id');
            $table->string('name');
            $table->json('images');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
        Schema::create('mau_sac_ngoi_hai_co_ct', function (Blueprint $table) {
            $table->id('mau_sac_ngoi_hai_co_ct_id');
            $table->string('name');
            $table->string('image');
            $table->string('code', 50)->unique();
            $table->integer('price');
            $table->foreignId('ngoi_hai_co_ct_id')->constrained('ngoi_hai_co_ct', 'ngoi_hai_co_ct_id')->cascadeOnDelete();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
        Schema::create('dinh_muc_ngoi_hai_co', function (Blueprint $table) {
            $table->id('dinh_muc_ngoi_hai_co_id');
            $table->string('roof_type')->unique();
            $table->integer('ngoi_tren_mai_go');
            $table->integer('ngoi_tren_mai_be_tong');
            $table->timestamps();
        });
        Schema::create('ngoi_hai_van_mieu_ct', function (Blueprint $table) {
            $table->id('ngoi_hai_van_mieu_ct_id');
            $table->string('name');
            $table->json('images');
            $table->integer('price');
            $table->json('des')->nullable();
            $table->unsignedBigInteger('mau_sac_id');
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
        Schema::create('mau_sac_ngoi_hai_van_mieu_ct', function (Blueprint $table) {
            $table->id('mau_sac_ngoi_hai_van_mieu_ct_id');
            $table->string('name');
            $table->string('image');
            $table->string('code', 50)->unique();
            $table->integer('price');
            $table->foreignId('ngoi_hai_van_mieu_ct_id')->constrained('ngoi_hai_van_mieu_ct', 'ngoi_hai_van_mieu_ct_id')->cascadeOnDelete();
            $table->boolean('is_delete')->default(0);
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
        Schema::create('gach_hoa_thong_gio_ct', function (Blueprint $table) {
            $table->id('gach_hoa_thong_gio_ct_id');
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->json('images');
            $table->integer('price');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
        Schema::create('dinh_muc_gach_hoa_thong_gio', function (Blueprint $table) {
            $table->id('dinh_muc_gach_hoa_thong_gio_id');
            $table->string('brick_type')->unique();
            $table->integer('value')->nullable();
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
            $table->string('title');
            $table->string('location');
            $table->string('description');
            $table->foreignId('gach_trang_tri_id')->constrained('gach_trang_tri', 'gach_trang_tri_id')->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('gach_trang_tri_ct', function (Blueprint $table) {
            $table->id('gach_trang_tri_ct_id');
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->json('images');
            $table->integer('price');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
        Schema::create('dinh_muc_gach_trang_tri', function (Blueprint $table) {
            $table->id('dinh_muc_gach_trang_tri_id');
            $table->string('brick_type')->unique();
            $table->integer('value')->nullable();
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
        Schema::create('gach_co_bat_trang_ct', function (Blueprint $table) {
            $table->id('gach_co_bat_trang_ct_id');
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->json('images');
            $table->integer('price');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
        Schema::create('dinh_muc_gach_co_bat_trang', function (Blueprint $table) {
            $table->id('dinh_muc_gach_co_bat_trang_id');
            $table->string('brick_type')->unique();
            $table->integer('value')->nullable();
            $table->timestamps();
        });

        // ================== CATEGORY: LINH VAT ==================
        Schema::create('linh_vat_phong_thuy', function (Blueprint $table) {
            $table->id('linh_vat_phong_thuy_id');
            $table->string('thumbnail_main');
            $table->longText('video')->nullable();
            $table->timestamps();
        });
        Schema::create('linh_vat', function (Blueprint $table) {
            $table->id('linh_vat_id');
            $table->string('image');
            $table->string('title', 50);
            $table->text('description');
            $table->foreignId('linh_vat_phong_thuy_id')->constrained('linh_vat_phong_thuy', 'linh_vat_phong_thuy_id')->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('linh_vat_phong_thuy_anh', function (Blueprint $table) {
            $table->id('linh_vat_phong_thuy_anh_id');
            $table->string('image');
            $table->foreignId('linh_vat_phong_thuy_id')->constrained('linh_vat_phong_thuy', 'linh_vat_phong_thuy_id')->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('linh_vat_phong_thuy_ct', function (Blueprint $table) {
            $table->id('linh_vat_phong_thuy_ct_id');
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->json('images');
            $table->integer('price');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->json('size_des')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });

        // ================== CATEGORY: PHU KIEN NGOI & BO NOC ==================
        Schema::create('phu_kien_ngoi', function (Blueprint $table) {
            $table->id('phu_kien_ngoi_id');
            $table->string('thumbnail_main');
            $table->json('images')->nullable();
            $table->timestamps();
        });
        Schema::create('ngoi_bo_noc_ct', function (Blueprint $table) {
            $table->id('ngoi_bo_noc_ct_id');
            $table->string('name');
            $table->json('images');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->json('size_des')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
        Schema::create('phan_loai_ngoi_bo_noc_ct', function (Blueprint $table) {
            $table->id('phan_loai_ngoi_bo_noc_ct_id');
            $table->string('name')->unique();
            $table->string('code', 50)->unique();
            $table->integer('price');
            $table->foreignId('ngoi_bo_noc_ct_id')->constrained('ngoi_bo_noc_ct', 'ngoi_bo_noc_ct_id')->cascadeOnDelete();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
        Schema::create('bo_noc_chu_van_ct', function (Blueprint $table) {
            $table->id('bo_noc_chu_van_ct_id');
            $table->string('name');
            $table->json('images');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->json('size_des')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
        Schema::create('phan_loai_bo_noc_chu_van_ct', function (Blueprint $table) {
            $table->id('phan_loai_bo_noc_chu_van_ct_id');
            $table->string('name')->unique();
            $table->string('code', 50)->unique();
            $table->integer('price');
            $table->foreignId('bo_noc_chu_van_ct_id')->constrained('bo_noc_chu_van_ct', 'bo_noc_chu_van_ct_id')->cascadeOnDelete();
            $table->boolean('is_delete')->default(0);
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
        // Drop in any order since constraints are disabled
        $tables = [
            'phan_loai_bo_noc_chu_van_ct', 'bo_noc_chu_van_ct', 'phan_loai_ngoi_bo_noc_ct', 'ngoi_bo_noc_ct', 'phu_kien_ngoi',
            'linh_vat_phong_thuy_ct', 'linh_vat_phong_thuy_anh', 'linh_vat', 'linh_vat_phong_thuy',
            'dinh_muc_gach_co_bat_trang', 'gach_co_bat_trang_ct', 'gach_co_bat_trang_anh', 'gach_co_bat_trang',
            'dinh_muc_gach_trang_tri', 'gach_trang_tri_ct', 'dau_an_gach_trang_tri', 'gach_trang_tri',
            'dinh_muc_gach_hoa_thong_gio', 'gach_hoa_thong_gio_ct', 'gia_tri_gach_hoa_thong_gio', 'gach_hoa_thong_gio_anh', 'gach_hoa_thong_gio',
            'dinh_muc_ngoi_hai_van_mieu', 'mau_sac_ngoi_hai_van_mieu_ct', 'ngoi_hai_van_mieu_ct',
            'dinh_muc_ngoi_hai_co', 'mau_sac_ngoi_hai_co_ct', 'ngoi_hai_co_ct', 'ngoi_hai_van_mieu',
            'dinh_muc_ngoi_am_duong', 'mau_sac_ngoi_am_duong_ct', 'ngoi_am_duong_ct', 'ngoi_am_duong',
            'lan_can_gom_xu', 'den_gom_su_anh', 'den_gom_su',
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
        Schema::enableForeignKeyConstraints();
    }
};
