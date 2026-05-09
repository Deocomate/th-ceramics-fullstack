<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// Admin routes
// ─────────────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // ── Guest routes (unauthenticated only) ──────────────────────────────────
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])
            ->name('auth.login');
        Route::post('login', [AuthController::class, 'login'])
            ->name('auth.login.submit');
        Route::get('forgot-password', [AuthController::class, 'showForgotPassword'])
            ->name('auth.forgot-password');
        Route::post('forgot-password', [AuthController::class, 'forgotPassword'])
            ->name('auth.forgot-password.submit');
        Route::get('reset-password/{token}', [AuthController::class, 'showResetPassword'])
            ->name('auth.reset-password');
        Route::post('reset-password', [AuthController::class, 'resetPassword'])
            ->name('auth.reset-password.submit');
    });

    // ── Authenticated routes ──────────────────────────────────────────────────
    Route::middleware('auth')->group(function () {
        Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('home');
        Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');

        // Cấu hình section chung
        Route::prefix('gia-tri-vuot-troi')->name('gia-tri-vuot-troi.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\GiaTriVuotTroiController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\GiaTriVuotTroiController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\GiaTriVuotTroiController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\GiaTriVuotTroiController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('giai-thuong-thanh-tuu')->name('giai-thuong-thanh-tuu.')->group(function () {
            Route::get('/',[\App\Http\Controllers\Admin\GiaiThuongThanhTuuController::class, 'index'])->name('index');
            Route::post('/',[\App\Http\Controllers\Admin\GiaiThuongThanhTuuController::class, 'store'])->name('store');
            Route::put('/{id}',[\App\Http\Controllers\Admin\GiaiThuongThanhTuuController::class, 'update'])->name('update');
            Route::delete('/{id}',[\App\Http\Controllers\Admin\GiaiThuongThanhTuuController::class, 'destroy'])->name('destroy');
        });

        // ── Product Types Routes ────────────────────────────────────────────────
        // 1. Ngói Âm Dương
        Route::prefix('ngoi-am-duong')->name('ngoi-am-duong.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\NgoiAmDuongController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\NgoiAmDuongController::class, 'update'])->name('update');
        });

        // 1.1 Chi tiết Ngói Âm Dương
        Route::prefix('ngoi-am-duong-ct')->name('ngoi-am-duong-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\NgoiAmDuongCtController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\NgoiAmDuongCtController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\NgoiAmDuongCtController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\NgoiAmDuongCtController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\NgoiAmDuongCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\NgoiAmDuongCtController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [\App\Http\Controllers\Admin\NgoiAmDuongCtController::class, 'restore'])->name('restore');

            Route::delete('/{id}/image', [\App\Http\Controllers\Admin\NgoiAmDuongCtController::class, 'destroyImage'])->name('image.destroy');
        });

        // 1.2 Màu sắc Ngói Âm Dương
        Route::prefix('mau-sac-ngoi-am-duong-ct')->name('mau-sac-ngoi-am-duong-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MauSacNgoiAmDuongCtController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\MauSacNgoiAmDuongCtController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MauSacNgoiAmDuongCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MauSacNgoiAmDuongCtController::class, 'destroy'])->name('destroy');
        });

        // 1.3 Định mức Ngói Âm Dương
        Route::prefix('dinh-muc-ngoi-am-duong')->name('dinh-muc-ngoi-am-duong.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DinhMucNgoiAmDuongController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\DinhMucNgoiAmDuongController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\DinhMucNgoiAmDuongController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\DinhMucNgoiAmDuongController::class, 'destroy'])->name('destroy');
        });

        // 2. Ngói Hài Văn Miếu
        Route::prefix('ngoi-hai-van-mieu')->name('ngoi-hai-van-mieu.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\NgoiHaiVanMieuController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\NgoiHaiVanMieuController::class, 'update'])->name('update');
            Route::delete('cong-doan-image', [\App\Http\Controllers\Admin\NgoiHaiVanMieuController::class, 'destroyCongDoanImage'])->name('cong-doan-image.destroy');
        });

        // 2.1 Chi tiết Ngói Hài Cổ
        Route::prefix('ngoi-hai-co-ct')->name('ngoi-hai-co-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\NgoiHaiCoCtController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\NgoiHaiCoCtController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\NgoiHaiCoCtController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\NgoiHaiCoCtController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\NgoiHaiCoCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\NgoiHaiCoCtController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [\App\Http\Controllers\Admin\NgoiHaiCoCtController::class, 'restore'])->name('restore');
            Route::delete('/{id}/image', [\App\Http\Controllers\Admin\NgoiHaiCoCtController::class, 'destroyImage'])->name('image.destroy');
        });

        // 2.2 Màu sắc Ngói Hài Cổ
        Route::prefix('mau-sac-ngoi-hai-co-ct')->name('mau-sac-ngoi-hai-co-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MauSacNgoiHaiCoCtController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\MauSacNgoiHaiCoCtController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MauSacNgoiHaiCoCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MauSacNgoiHaiCoCtController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [\App\Http\Controllers\Admin\MauSacNgoiHaiCoCtController::class, 'restore'])->name('restore');
        });

        // 2.3 Định mức Ngói Hài Cổ
        Route::prefix('dinh-muc-ngoi-hai-co')->name('dinh-muc-ngoi-hai-co.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DinhMucNgoiHaiCoController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\DinhMucNgoiHaiCoController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\DinhMucNgoiHaiCoController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\DinhMucNgoiHaiCoController::class, 'destroy'])->name('destroy');
        });

        // 2.4 Chi tiết Ngói Hài Văn Miếu (Bạn thêm vào vị trí thích hợp)
        Route::prefix('ngoi-hai-van-mieu-ct')->name('ngoi-hai-van-mieu-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\NgoiHaiVanMieuCtController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\NgoiHaiVanMieuCtController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\NgoiHaiVanMieuCtController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\NgoiHaiVanMieuCtController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\NgoiHaiVanMieuCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\NgoiHaiVanMieuCtController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [\App\Http\Controllers\Admin\NgoiHaiVanMieuCtController::class, 'restore'])->name('restore');
            Route::delete('/{id}/image', [\App\Http\Controllers\Admin\NgoiHaiVanMieuCtController::class, 'destroyImage'])->name('image.destroy');
        });

        // 2.5 Màu sắc Ngói Hài Văn Miếu
        Route::prefix('mau-sac-ngoi-hai-van-mieu-ct')->name('mau-sac-ngoi-hai-van-mieu-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MauSacNgoiHaiVanMieuCtController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\MauSacNgoiHaiVanMieuCtController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MauSacNgoiHaiVanMieuCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MauSacNgoiHaiVanMieuCtController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [\App\Http\Controllers\Admin\MauSacNgoiHaiVanMieuCtController::class, 'restore'])->name('restore');
        });

        // 2.6 Định mức Ngói Hài Văn Miếu
        Route::prefix('dinh-muc-ngoi-hai-van-mieu')->name('dinh-muc-ngoi-hai-van-mieu.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DinhMucNgoiHaiVanMieuController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\DinhMucNgoiHaiVanMieuController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\DinhMucNgoiHaiVanMieuController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\DinhMucNgoiHaiVanMieuController::class, 'destroy'])->name('destroy');
        });

        // 3. Gạch Hoa Thông Gió
        Route::prefix('gach-hoa-thong-gio')->name('gach-hoa-thong-gio.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'update'])->name('update');
            // Thư viện ảnh
            Route::post('anh', [\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'storeAnh'])->name('anh.store');
            Route::delete('anh/{anh}', [\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'destroyAnh'])->name('anh.destroy');
            // Giá trị nổi bật
            Route::post('gia-tri', [\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'storeGiaTri'])->name('gia-tri.store');
            Route::put('gia-tri/{giaTri}', [\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'updateGiaTri'])->name('gia-tri.update');
            Route::delete('gia-tri/{giaTri}', [\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'destroyGiaTri'])->name('gia-tri.destroy');
            Route::delete('cong-doan-image', [\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'destroyCongDoanImage'])->name('cong-doan-image.destroy');
        });

        // 3.1 Chi tiết Gạch Hoa Thông Gió
        Route::prefix('gach-hoa-thong-gio-ct')->name('gach-hoa-thong-gio-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\GachHoaThongGioCtController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\GachHoaThongGioCtController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\GachHoaThongGioCtController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\GachHoaThongGioCtController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\GachHoaThongGioCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\GachHoaThongGioCtController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [\App\Http\Controllers\Admin\GachHoaThongGioCtController::class, 'restore'])->name('restore');
            Route::delete('/{id}/image', [\App\Http\Controllers\Admin\GachHoaThongGioCtController::class, 'destroyImage'])->name('image.destroy');
        });

        // 3.2 Định mức Gạch Hoa Thông Gió
        Route::prefix('dinh-muc-gach-hoa-thong-gio')->name('dinh-muc-gach-hoa-thong-gio.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DinhMucGachHoaThongGioController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\DinhMucGachHoaThongGioController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\DinhMucGachHoaThongGioController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\DinhMucGachHoaThongGioController::class, 'destroy'])->name('destroy');
        });

        // 3.3 Chi tiết Gạch Trang Trí
        Route::prefix('gach-trang-tri-ct')->name('gach-trang-tri-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\GachTrangTriCtController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\GachTrangTriCtController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\GachTrangTriCtController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\GachTrangTriCtController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\GachTrangTriCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\GachTrangTriCtController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [\App\Http\Controllers\Admin\GachTrangTriCtController::class, 'restore'])->name('restore');
            Route::delete('/{id}/image', [\App\Http\Controllers\Admin\GachTrangTriCtController::class, 'destroyImage'])->name('image.destroy');
        });

        // 3.4 Định mức Gạch Trang Trí
        Route::prefix('dinh-muc-gach-trang-tri')->name('dinh-muc-gach-trang-tri.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DinhMucGachTrangTriController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\DinhMucGachTrangTriController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\DinhMucGachTrangTriController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\DinhMucGachTrangTriController::class, 'destroy'])->name('destroy');
        });

        // 4. Phụ Kiện Ngói
        Route::prefix('phu-kien-ngoi')->name('phu-kien-ngoi.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PhuKienNgoiController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\PhuKienNgoiController::class, 'update'])->name('update');
            Route::delete('image', [\App\Http\Controllers\Admin\PhuKienNgoiController::class, 'destroyImage'])->name('image.destroy');
        });

        // 4.1 Chi tiết Ngói Bò Nóc
        Route::prefix('ngoi-bo-noc-ct')->name('ngoi-bo-noc-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\NgoiBoNocCtController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\NgoiBoNocCtController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\NgoiBoNocCtController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\NgoiBoNocCtController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\NgoiBoNocCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\NgoiBoNocCtController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [\App\Http\Controllers\Admin\NgoiBoNocCtController::class, 'restore'])->name('restore');
            Route::delete('/{id}/image', [\App\Http\Controllers\Admin\NgoiBoNocCtController::class, 'destroyImage'])->name('image.destroy');
        });

        // 4.2 Phân loại Ngói Bò Nóc
        Route::prefix('phan-loai-ngoi-bo-noc-ct')->name('phan-loai-ngoi-bo-noc-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PhanLoaiNgoiBoNocCtController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\PhanLoaiNgoiBoNocCtController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\PhanLoaiNgoiBoNocCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\PhanLoaiNgoiBoNocCtController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [\App\Http\Controllers\Admin\PhanLoaiNgoiBoNocCtController::class, 'restore'])->name('restore');
        });

        // 5. Gạch Trang Trí
        Route::prefix('gach-trang-tri')->name('gach-trang-tri.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\GachTrangTriController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\GachTrangTriController::class, 'update'])->name('update');
            Route::post('dau-an', [\App\Http\Controllers\Admin\GachTrangTriController::class, 'storeDauAn'])->name('dau-an.store');
            Route::put('dau-an/{dauAn}', [\App\Http\Controllers\Admin\GachTrangTriController::class, 'updateDauAn'])->name('dau-an.update');
            Route::delete('dau-an/{dauAn}', [\App\Http\Controllers\Admin\GachTrangTriController::class, 'destroyDauAn'])->name('dau-an.destroy');
            Route::delete('cong-doan-image', [\App\Http\Controllers\Admin\GachTrangTriController::class, 'destroyCongDoanImage'])->name('cong-doan-image.destroy');
        });

        // 5.1 Chi tiết Gạch Cổ Bát Tràng
        Route::prefix('gach-co-bat-trang-ct')->name('gach-co-bat-trang-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\GachCoBatTrangCtController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\GachCoBatTrangCtController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\GachCoBatTrangCtController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\GachCoBatTrangCtController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\GachCoBatTrangCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\GachCoBatTrangCtController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [\App\Http\Controllers\Admin\GachCoBatTrangCtController::class, 'restore'])->name('restore');
            Route::delete('/{id}/image', [\App\Http\Controllers\Admin\GachCoBatTrangCtController::class, 'destroyImage'])->name('image.destroy');
        });

        // 5.2 Định mức Gạch Cổ Bát Tràng
        Route::prefix('dinh-muc-gach-co-bat-trang')->name('dinh-muc-gach-co-bat-trang.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DinhMucGachCoBatTrangController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\DinhMucGachCoBatTrangController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\DinhMucGachCoBatTrangController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\DinhMucGachCoBatTrangController::class, 'destroy'])->name('destroy');
        });

        // 6. Lan Can Gốm Sứ
        Route::prefix('lan-can-gom-xu')->name('lan-can-gom-xu.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\LanCanGomXuController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\LanCanGomXuController::class, 'update'])->name('update');
        });

        // 7. Gạch Cổ Bát Tràng
        Route::prefix('gach-co-bat-trang')->name('gach-co-bat-trang.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\GachCoBatTrangController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\GachCoBatTrangController::class, 'update'])->name('update');
            Route::delete('anh/{anh}', [\App\Http\Controllers\Admin\GachCoBatTrangController::class, 'destroyAnh'])->name('anh.destroy');
            Route::delete('cong-doan-image', [\App\Http\Controllers\Admin\GachCoBatTrangController::class, 'destroyCongDoanImage'])->name('cong-doan-image.destroy');
        });

        // 8. Linh Vật Phong Thủy
        Route::prefix('linh-vat-phong-thuy')->name('linh-vat-phong-thuy.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\LinhVatPhongThuyController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\LinhVatPhongThuyController::class, 'update'])->name('update');
            Route::post('linh-vat', [\App\Http\Controllers\Admin\LinhVatPhongThuyController::class, 'storeLinhVat'])->name('linh-vat.store');
            Route::put('linh-vat/{linhVat}', [\App\Http\Controllers\Admin\LinhVatPhongThuyController::class, 'updateLinhVat'])->name('linh-vat.update');
            Route::delete('linh-vat/{linhVat}', [\App\Http\Controllers\Admin\LinhVatPhongThuyController::class, 'destroyLinhVat'])->name('linh-vat.destroy');
            Route::delete('anh/{anh}', [\App\Http\Controllers\Admin\LinhVatPhongThuyController::class, 'destroyAnh'])->name('anh.destroy');
        });

        // 8.1 Chi tiết Linh Vật Phong Thủy
        Route::prefix('linh-vat-phong-thuy-ct')->name('linh-vat-phong-thuy-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\LinhVatPhongThuyCtController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\LinhVatPhongThuyCtController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\LinhVatPhongThuyCtController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\LinhVatPhongThuyCtController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\LinhVatPhongThuyCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\LinhVatPhongThuyCtController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [\App\Http\Controllers\Admin\LinhVatPhongThuyCtController::class, 'restore'])->name('restore');
            Route::delete('/{id}/image', [\App\Http\Controllers\Admin\LinhVatPhongThuyCtController::class, 'destroyImage'])->name('image.destroy');
        });

        // 9. Đèn Gốm Sứ
        Route::prefix('den-gom-su')->name('den-gom-su.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DenGomSuController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\DenGomSuController::class, 'update'])->name('update');
            Route::delete('anh/{anh}', [\App\Http\Controllers\Admin\DenGomSuController::class, 'destroyAnh'])->name('anh.destroy');
        });

        // Chi tiết Bò Nóc Chữ Vạn
        Route::prefix('bo-noc-chu-van-ct')->name('bo-noc-chu-van-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\BoNocChuVanCtController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\BoNocChuVanCtController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\BoNocChuVanCtController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\BoNocChuVanCtController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\BoNocChuVanCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\BoNocChuVanCtController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [\App\Http\Controllers\Admin\BoNocChuVanCtController::class, 'restore'])->name('restore');
            Route::delete('/{id}/image', [\App\Http\Controllers\Admin\BoNocChuVanCtController::class, 'destroyImage'])->name('image.destroy');
        });

        // Phân loại Bò Nóc Chữ Vạn
        Route::prefix('phan-loai-bo-noc-chu-van-ct')->name('phan-loai-bo-noc-chu-van-ct.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PhanLoaiBoNocChuVanCtController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\PhanLoaiBoNocChuVanCtController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\PhanLoaiBoNocChuVanCtController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\PhanLoaiBoNocChuVanCtController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore', [\App\Http\Controllers\Admin\PhanLoaiBoNocChuVanCtController::class, 'restore'])->name('restore');
        });

        Route::get('trang-chu', [\App\Http\Controllers\Admin\TrangChuController::class, 'edit'])->name('trang_chu.edit');
        Route::put('trang-chu', [\App\Http\Controllers\Admin\TrangChuController::class, 'update'])->name('trang_chu.update');

        // ── Page Configuration: single-page config panels ──────────────────────
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('ve-chung-toi',[\App\Http\Controllers\Admin\VeChungToiController::class, 'edit'])->name('ve_chung_toi.edit');
            Route::put('ve-chung-toi',[\App\Http\Controllers\Admin\VeChungToiController::class, 'update'])->name('ve_chung_toi.update');

            Route::get('factory', [\App\Http\Controllers\Admin\FactoryPageController::class, 'edit'])->name('factory.edit');
            Route::put('factory', [\App\Http\Controllers\Admin\FactoryPageController::class, 'update'])->name('factory.update');

            Route::get('contact', [\App\Http\Controllers\Admin\ContactPageController::class, 'edit'])->name('contact.edit');
            Route::put('contact', [\App\Http\Controllers\Admin\ContactPageController::class, 'update'])->name('contact.update');

            Route::get('faq', [\App\Http\Controllers\Admin\FaqPageController::class, 'edit'])->name('faq.edit');
            Route::put('faq', [\App\Http\Controllers\Admin\FaqPageController::class, 'update'])->name('faq.update');
            Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class)->except(['show']);
        });


            // ── Danh Mục Dự Án ──────────────────────────────────────────────
        Route::prefix('danh-muc-du-an')->name('danh-muc-du-an.')->group(function () {
            Route::get('/',[\App\Http\Controllers\Admin\DanhMucDuAnController::class, 'index'])->name('index');
            Route::post('/',[\App\Http\Controllers\Admin\DanhMucDuAnController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\DanhMucDuAnController::class, 'update'])->name('update');
            Route::delete('/{id}',[\App\Http\Controllers\Admin\DanhMucDuAnController::class, 'destroy'])->name('destroy');
            Route::put('/{id}/restore',[\App\Http\Controllers\Admin\DanhMucDuAnController::class, 'restore'])->name('restore');
        });

        // ── Dự Án ────────────────────────────────────────────────────────
        Route::prefix('du-an')->name('du-an.')->group(function () {
            Route::get('/',[\App\Http\Controllers\Admin\DuAnController::class, 'index'])->name('index');
            Route::get('/create',[\App\Http\Controllers\Admin\DuAnController::class, 'create'])->name('create');
            Route::post('/',[\App\Http\Controllers\Admin\DuAnController::class, 'store'])->name('store');
            Route::get('/{id}/edit',[\App\Http\Controllers\Admin\DuAnController::class, 'edit'])->name('edit');
            Route::put('/{id}',[\App\Http\Controllers\Admin\DuAnController::class, 'update'])->name('update');
            Route::delete('/{id}',[\App\Http\Controllers\Admin\DuAnController::class, 'destroy'])->name('destroy');
            Route::delete('/{id}/image',[\App\Http\Controllers\Admin\DuAnController::class, 'destroyImage'])->name('image.destroy');
        });

        // ── Superadmin-only: account management ──────────────────────────────
        Route::middleware('role:superadmin')->group(function () {
            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::get('users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('users', [UserController::class, 'store'])->name('users.store');
            Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });
    });
});
