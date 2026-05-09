<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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

        Schema::create('giai_thuong_thanh_tuu', function (Blueprint $table) {
            $table->id('giai_thuong_thanh_tuu_id');
            $table->string('image');
            $table->longText('des');
            $table->timestamps();
        });

        Schema::create('gia_tri_vuot_troi', function (Blueprint $table) {
            $table->id('gia_tri_vuot_troi_id');
            $table->string('title', 50);
            $table->longText('desscription');
            $table->string('image');
            $table->timestamps();
        });

        Schema::create('page_factory', function (Blueprint $table) {
            $table->id('page_factory_id');
            $table->string('hero_banner_desktop', 500)->nullable();
            $table->string('hero_banner_mobile', 500)->nullable();
            $table->string('intro_title', 500)->nullable();
            $table->string('intro_subtitle', 500)->nullable();
            $table->text('intro_description')->nullable();
            $table->json('gallery_1')->nullable();
            $table->string('process_title', 500)->nullable();
            $table->text('process_description')->nullable();
            $table->json('process_slider')->nullable();
            $table->string('process_bottom_title', 500)->nullable();
            $table->text('process_bottom_desc')->nullable();
            $table->string('process_bottom_image', 500)->nullable();
            $table->json('material_slider')->nullable();
            $table->json('material_steps')->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });

        Schema::create('page_contact', function (Blueprint $table) {
            $table->id('page_contact_id');
            $table->string('map_image', 500)->nullable();
            $table->string('hotline', 50)->nullable();
            $table->string('zalo_link', 500)->nullable();
            $table->string('form_title', 500)->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });

        Schema::create('page_faq', function (Blueprint $table) {
            $table->id('page_faq_id');
            $table->string('banner_image', 500)->nullable();
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id('faq_id');
            $table->string('category', 50);
            $table->string('question', 1000);
            $table->text('answer');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(1);
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('page_faq');
        Schema::dropIfExists('page_contact');
        Schema::dropIfExists('page_factory');
        Schema::dropIfExists('gia_tri_vuot_troi');
        Schema::dropIfExists('giai_thuong_thanh_tuu');
        Schema::dropIfExists('ve_chung_toi');
        Schema::dropIfExists('trang_chu');
    }
};
