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
        // 1. Table ngoi_am_duong_ct
        Schema::create('ngoi_am_duong_ct', function (Blueprint $table) {
            $table->id('ngoi_am_duong_ct_id');
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->json('images');
            $table->integer('price');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->timestamps();
        });

        // 2. Table mau_sac_ngoi_am_duong_ct
        Schema::create('mau_sac_ngoi_am_duong_ct', function (Blueprint $table) {
            $table->id('mau_sac_ngoi_am_duong_ct_id');
            $table->string('name');
            $table->string('image');
            $table->timestamps();
        });

        // 3. Table dinh_muc_ngoi_am_duong
        Schema::create('dinh_muc_ngoi_am_duong', function (Blueprint $table) {
            $table->id('dinh_muc_ngoi_am_duong_id');
            $table->string('roof_type');
            $table->string('tile_type');
            $table->integer('ngoi_am');
            $table->integer('ngoi_duong');
            $table->integer('diem');
            $table->timestamps();
            
            // Indexes
            $table->unique(['roof_type', 'tile_type']);
        });

        // 4. Table ngoi_hai_co_ct
        Schema::create('ngoi_hai_co_ct', function (Blueprint $table) {
            $table->id('ngoi_hai_co_ct_id');
            $table->string('name');
            $table->json('images');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->timestamps();
        });

        // 5. Table dinh_muc_ngoi_hai_co (MỚI)
        Schema::create('dinh_muc_ngoi_hai_co', function (Blueprint $table) {
            $table->id('dinh_muc_ngoi_hai_co_id');
            $table->string('roof_type')->unique();
            $table->integer('ngoi_tren_mai_go');
            $table->integer('ngoi_tren_mai_be_tong');
            $table->timestamps();
        });

        // 6. Table mau_sac_ngoi_hai_co_ct
        Schema::create('mau_sac_ngoi_hai_co_ct', function (Blueprint $table) {
            $table->id('mau_sac_ngoi_hai_co_ct_id');
            $table->string('name');
            $table->string('image');
            $table->string('code', 50)->unique();
            $table->integer('price');
            $table->unsignedBigInteger('ngoi_hai_co_ct_id');
            $table->timestamps();

            // Foreign Key
            $table->foreign('ngoi_hai_co_ct_id')->references('ngoi_hai_co_ct_id')->on('ngoi_hai_co_ct')->onDelete('cascade');
        });

        // 7. Table ngoi_hai_van_mieu_ct
        Schema::create('ngoi_hai_van_mieu_ct', function (Blueprint $table) {
            $table->id('ngoi_hai_van_mieu_ct_id');
            $table->string('name');
            $table->json('images');
            $table->integer('price');
            $table->json('des')->nullable();
            $table->unsignedBigInteger('mau_sac_id'); 
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->timestamps();
        });

        // 8. Table dinh_muc_ngoi_hai_van_mieu (MỚI)
        Schema::create('dinh_muc_ngoi_hai_van_mieu', function (Blueprint $table) {
            $table->id('dinh_muc_ngoi_hai_van_mieu_id'); // Đã sửa tên ID cho chuẩn
            $table->string('roof_type');
            $table->integer('ngoi_tren_mai_go');
            $table->integer('ngoi_tren_mai_be_tong');
            $table->timestamps();
        });

        // 9. Table mau_sac_ngoi_hai_van_mieu_ct
        Schema::create('mau_sac_ngoi_hai_van_mieu_ct', function (Blueprint $table) {
            $table->id('mau_sac_ngoi_hai_van_mieu_ct_id');
            $table->string('name');
            $table->string('image');
            $table->string('code', 50)->unique();
            $table->integer('price');
            $table->unsignedBigInteger('ngoi_hai_van_mieu_ct_id');
            $table->timestamps();

            // Foreign Key
            $table->foreign('ngoi_hai_van_mieu_ct_id', 'fk_mau_sac_van_mieu')->references('ngoi_hai_van_mieu_ct_id')->on('ngoi_hai_van_mieu_ct')->onDelete('cascade');
        });

        // 10. Table gach_hoa_thong_gio_ct
        Schema::create('gach_hoa_thong_gio_ct', function (Blueprint $table) {
            $table->id('gach_hoa_thong_gio_ct_id');
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->json('images');
            $table->integer('price');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->timestamps();
        });

        // 11. Table dinh_muc_gach_hoa_thong_gio (MỚI)
        Schema::create('dinh_muc_gach_hoa_thong_gio', function (Blueprint $table) {
            $table->id('dinh_muc_gach_hoa_thong_gio_id');
            $table->string('brick_type')->unique();
            $table->integer('value')->nullable(); // Không có [not null] nên để nullable
            $table->timestamps();
        });

        // 12. Table gach_trang_tri_ct
        Schema::create('gach_trang_tri_ct', function (Blueprint $table) {
            $table->id('gach_trang_tri_ct_id');
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->json('images');
            $table->integer('price');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->timestamps();
        });

        // 13. Table dinh_muc_gach_trang_tri (MỚI)
        Schema::create('dinh_muc_gach_trang_tri', function (Blueprint $table) {
            $table->id('dinh_muc_gach_trang_tri_id');
            $table->string('brick_type')->unique();
            $table->integer('value')->nullable();
            $table->timestamps();
        });

        // 14. Table gach_co_bat_trang_ct
        Schema::create('gach_co_bat_trang_ct', function (Blueprint $table) {
            $table->id('gach_co_bat_trang_ct_id');
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->json('images');
            $table->integer('price');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->timestamps();
        });

        // 15. Table dinh_muc_gach_co_bat_trang (MỚI)
        Schema::create('dinh_muc_gach_co_bat_trang', function (Blueprint $table) {
            $table->id('dinh_muc_gach_co_bat_trang_id');
            $table->string('brick_type')->unique();
            $table->integer('value')->nullable();
            $table->timestamps();
        });

        // 16. Table linh_vat_phong_thuy_ct
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
            $table->timestamps();
        });

        // 17. Table ngoi_bo_noc_ct
        Schema::create('ngoi_bo_noc_ct', function (Blueprint $table) {
            $table->id('ngoi_bo_noc_ct_id');
            $table->string('name');
            $table->json('images');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->json('size_des')->nullable();
            $table->timestamps();
        });

        // 18. Table phan_loai_ngoi_bo_noc_ct
        Schema::create('phan_loai_ngoi_bo_noc_ct', function (Blueprint $table) {
            $table->id('phan_loai_ngoi_bo_noc_ct_id');
            $table->string('name')->unique();
            $table->string('code', 50)->unique();
            $table->integer('price');
            $table->unsignedBigInteger('ngoi_bo_noc_ct_id');
            $table->timestamps();

            // Foreign Key
            $table->foreign('ngoi_bo_noc_ct_id', 'fk_phan_loai_bo_noc')->references('ngoi_bo_noc_ct_id')->on('ngoi_bo_noc_ct')->onDelete('cascade');
        });

        // 19. Table bo_noc_chu_van_ct
        Schema::create('bo_noc_chu_van_ct', function (Blueprint $table) {
            $table->id('bo_noc_chu_van_ct_id');
            $table->string('name');
            $table->json('images');
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->json('size_des')->nullable();
            $table->timestamps();
        });

        // 20. Table phan_loai_bo_noc_chu_van_ct
        Schema::create('phan_loai_bo_noc_chu_van_ct', function (Blueprint $table) {
            $table->id('phan_loai_bo_noc_chu_van_ct_id');
            $table->string('name')->unique();
            $table->string('code', 50)->unique();
            $table->integer('price');
            $table->unsignedBigInteger('bo_noc_chu_van_ct_id');
            $table->timestamps();

            // Foreign Key
            $table->foreign('bo_noc_chu_van_ct_id', 'fk_phan_loai_chu_van')->references('bo_noc_chu_van_ct_id')->on('bo_noc_chu_van_ct')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('phan_loai_bo_noc_chu_van_ct');
        Schema::dropIfExists('bo_noc_chu_van_ct');
        Schema::dropIfExists('phan_loai_ngoi_bo_noc_ct');
        Schema::dropIfExists('ngoi_bo_noc_ct');
        Schema::dropIfExists('linh_vat_phong_thuy_ct');
        
        Schema::dropIfExists('dinh_muc_gach_co_bat_trang');
        Schema::dropIfExists('gach_co_bat_trang_ct');
        
        Schema::dropIfExists('dinh_muc_gach_trang_tri');
        Schema::dropIfExists('gach_trang_tri_ct');
        
        Schema::dropIfExists('dinh_muc_gach_hoa_thong_gio');
        Schema::dropIfExists('gach_hoa_thong_gio_ct');
        
        Schema::dropIfExists('mau_sac_ngoi_hai_van_mieu_ct');
        Schema::dropIfExists('dinh_muc_ngoi_hai_van_mieu');
        Schema::dropIfExists('ngoi_hai_van_mieu_ct');
        
        Schema::dropIfExists('mau_sac_ngoi_hai_co_ct');
        Schema::dropIfExists('dinh_muc_ngoi_hai_co');
        Schema::dropIfExists('ngoi_hai_co_ct');
        
        Schema::dropIfExists('dinh_muc_ngoi_am_duong');
        Schema::dropIfExists('mau_sac_ngoi_am_duong_ct');
        Schema::dropIfExists('ngoi_am_duong_ct');

        Schema::enableForeignKeyConstraints();
    }
};