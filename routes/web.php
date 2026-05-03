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
        Route::get('/', fn() => redirect()->route('admin.dashboard'))->name('home');
        Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');

        // Cấu hình section chung
        Route::prefix('gia-tri-vuot-troi')->name('gia-tri-vuot-troi.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\GiaTriVuotTroiController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\GiaTriVuotTroiController::class, 'store'])->name('store');
            Route::put('/{id}', [\App\Http\Controllers\Admin\GiaTriVuotTroiController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\GiaTriVuotTroiController::class, 'destroy'])->name('destroy');
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

        // 2. Ngói Hài Văn Miếu
        Route::prefix('ngoi-hai-van-mieu')->name('ngoi-hai-van-mieu.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\NgoiHaiVanMieuController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\NgoiHaiVanMieuController::class, 'update'])->name('update');
            Route::delete('cong-doan-image', [\App\Http\Controllers\Admin\NgoiHaiVanMieuController::class, 'destroyCongDoanImage'])->name('cong-doan-image.destroy');
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

        // 4. Phụ Kiện Ngói
        Route::prefix('phu-kien-ngoi')->name('phu-kien-ngoi.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PhuKienNgoiController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\PhuKienNgoiController::class, 'update'])->name('update');
            Route::delete('image', [\App\Http\Controllers\Admin\PhuKienNgoiController::class, 'destroyImage'])->name('image.destroy');
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

        // 9. Đèn Gốm Sứ
        Route::prefix('den-gom-su')->name('den-gom-su.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DenGomSuController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\DenGomSuController::class, 'update'])->name('update');
            Route::delete('anh/{anh}', [\App\Http\Controllers\Admin\DenGomSuController::class, 'destroyAnh'])->name('anh.destroy');
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
