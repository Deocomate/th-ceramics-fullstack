// database/migrations/2026_04_26_034128_create_tables_product_type.php
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
        // ==========================================
        // 1. NGÓI ÂM DƯƠNG
        // ==========================================
        Schema::create('ngoi_am_duong', function (Blueprint $table) {
            $table->id('ngoi_am_duong_id');
            $table->string('thumbnail_main');
            $table->string('thumbnail1');
            $table->string('thumbnail2');
            $table->string('video')->nullable();
            $table->timestamps();
        });

        // ==========================================
        // SECTION GIÁ TRỊ VƯỢT TRỘI
        // ==========================================
        Schema::create('gia_tri_vuot_troi', function (Blueprint $table) {
            $table->id('gia_tri_vuot_troi_id');
            $table->string('title', 50);
            $table->longText('desscription'); 
            $table->string('image');
            $table->timestamps();
        });

        // ==========================================
        // 2. NGÓI HÀI VĂN MIẾU
        // ==========================================
        Schema::create('ngoi_hai_van_mieu', function (Blueprint $table) {
            $table->id('ngoi_hai_van_mieu_id');
            $table->string('thumbnail_main');
            $table->string('title1', 50);
            $table->string('thumbnail1');
            $table->string('title2', 50);
            $table->string('thumbnail2');
            $table->string('title3', 50);
            $table->string('thumbnail3');
            $table->string('video');
            $table->timestamps();
        });
        // ==========================================
        // 3. GẠCH HOA THÔNG GIÓ
        // ==========================================
        Schema::create('gach_hoa_thong_gio', function (Blueprint $table) {
            $table->id('gach_hoa_thong_gio_id');
            $table->string('image');
            $table->string('video')->nullable();
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
        // ==========================================
        // 4. PHỤ KIỆN NGÓI
        // ==========================================
        Schema::create('phu_kien_ngoi', function (Blueprint $table) {
            $table->id('phu_kien_ngoi_id');
            $table->string('thumbnail_main');
            $table->json('images')->nullable();
            $table->timestamps();
        });
        // ==========================================
        // 5. GẠCH TRANG TRÍ
        // ==========================================
        Schema::create('gach_trang_tri', function (Blueprint $table) {
            $table->id('gach_trang_tri_id');
            $table->string('thumbnail_main');
            $table->string('video')->nullable();
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
        // ==========================================
        // 6. LAN CAN GỐM SỨ
        // ==========================================
        Schema::create('lan_can_gom_xu', function (Blueprint $table) {
            $table->id('lan_can_gom_xu_id');
            $table->string('thumbnail_main');
            $table->string('video')->nullable();
            $table->timestamps();
        });
        // ==========================================
        // 7. GẠCH CỔ BÁT TRÀNG
        // ==========================================
        Schema::create('gach_co_bat_trang', function (Blueprint $table) {
            $table->id('gach_co_bat_trang_id');
            $table->string('thumbnail_main');
            $table->string('video')->nullable();
            $table->timestamps();
        });
        Schema::create('gach_co_bat_trang_anh', function (Blueprint $table) {
            $table->id('gach_co_bat_trang_anh_id');
            $table->string('image');
            $table->foreignId('gach_co_bat_trang_id')->constrained('gach_co_bat_trang', 'gach_co_bat_trang_id')->cascadeOnDelete();
            $table->timestamps();
        });
        // ==========================================
        // 8. LINH VẬT PHONG THỦY
        // ==========================================
        Schema::create('linh_vat_phong_thuy', function (Blueprint $table) {
            $table->id('linh_vat_phong_thuy_id');
            $table->string('thumbnail_main');
            $table->string('video')->nullable();
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
        // ==========================================
        // 9. ĐÈN GỐM SỨ
        // ==========================================
        Schema::create('den_gom_su', function (Blueprint $table) {
            $table->id('den_gom_su_id');
            $table->string('thumbnail_main');
            $table->string('video')->nullable();
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('den_gom_su_anh');
        Schema::dropIfExists('den_gom_su');
        Schema::dropIfExists('linh_vat_phong_thuy_anh');
        Schema::dropIfExists('linh_vat');
        Schema::dropIfExists('linh_vat_phong_thuy');
        Schema::dropIfExists('gach_co_bat_trang_anh');
        Schema::dropIfExists('gach_co_bat_trang');
        Schema::dropIfExists('lan_can_gom_xu');
        Schema::dropIfExists('dau_an_gach_trang_tri');
        Schema::dropIfExists('gach_trang_tri');
        Schema::dropIfExists('phu_kien_ngoi');
        Schema::dropIfExists('gia_tri_gach_hoa_thong_gio');
        Schema::dropIfExists('gach_hoa_thong_gio_anh');
        Schema::dropIfExists('gach_hoa_thong_gio');
        Schema::dropIfExists('ngoi_hai_van_mieu');
        Schema::dropIfExists('gia_tri_vuot_troi');
        Schema::dropIfExists('ngoi_am_duong');
    }
};