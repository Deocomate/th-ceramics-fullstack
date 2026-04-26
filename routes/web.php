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

        // ── Product Types Routes ────────────────────────────────────────────────
        
        // 1. Ngói Âm Dương
        Route::prefix('ngoi-am-duong')->name('ngoi-am-duong.')->group(function () {
            Route::get('/',[\App\Http\Controllers\Admin\NgoiAmDuongController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\NgoiAmDuongController::class, 'update'])->name('update');
            Route::post('gia-tri',[\App\Http\Controllers\Admin\NgoiAmDuongController::class, 'storeGiaTri'])->name('gia-tri.store');
            Route::put('gia-tri/{giaTri}',[\App\Http\Controllers\Admin\NgoiAmDuongController::class, 'updateGiaTri'])->name('gia-tri.update');
            Route::delete('gia-tri/{giaTri}',[\App\Http\Controllers\Admin\NgoiAmDuongController::class, 'destroyGiaTri'])->name('gia-tri.destroy');
        });

        // 2. Ngói Hài Văn Miếu
        Route::prefix('ngoi-hai-van-mieu')->name('ngoi-hai-van-mieu.')->group(function () {
            Route::get('/',[\App\Http\Controllers\Admin\NgoiHaiVanMieuController::class, 'index'])->name('index');
            Route::put('/',[\App\Http\Controllers\Admin\NgoiHaiVanMieuController::class, 'update'])->name('update');
        });


        // 3. Gạch Hoa Thông Gió
        Route::prefix('gach-hoa-thong-gio')->name('gach-hoa-thong-gio.')->group(function () {
            Route::get('/',[\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'index'])->name('index');
            Route::put('/', [\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'update'])->name('update');
            // Thư viện ảnh
            Route::post('anh',[\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'storeAnh'])->name('anh.store');
            Route::delete('anh/{anh}',[\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'destroyAnh'])->name('anh.destroy');
            // Giá trị nổi bật
            Route::post('gia-tri',[\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'storeGiaTri'])->name('gia-tri.store');
            Route::put('gia-tri/{giaTri}',[\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'updateGiaTri'])->name('gia-tri.update');
            Route::delete('gia-tri/{giaTri}',[\App\Http\Controllers\Admin\GachHoaThongGioController::class, 'destroyGiaTri'])->name('gia-tri.destroy');
        });

        // 4. Phụ Kiện Ngói
        Route::prefix('phu-kien-ngoi')->name('phu-kien-ngoi.')->group(function () {
            Route::get('/',[\App\Http\Controllers\Admin\PhuKienNgoiController::class, 'index'])->name('index');
            Route::put('/',[\App\Http\Controllers\Admin\PhuKienNgoiController::class, 'update'])->name('update');
            Route::delete('image',[\App\Http\Controllers\Admin\PhuKienNgoiController::class, 'destroyImage'])->name('image.destroy');
        });

        // 5. Gạch Trang Trí
        Route::resource('gach-trang-tri', \App\Http\Controllers\Admin\GachTrangTriController::class)
            ->parameters(['gach-trang-tri' => 'gachTrangTri']);
        Route::post('gach-trang-tri/{gachTrangTri}/dau-an',[\App\Http\Controllers\Admin\GachTrangTriController::class, 'storeDauAn'])->name('gach-trang-tri.dau-an.store');
        Route::put('gach-trang-tri/dau-an/{dauAn}',[\App\Http\Controllers\Admin\GachTrangTriController::class, 'updateDauAn'])->name('gach-trang-tri.dau-an.update');
        Route::delete('gach-trang-tri/dau-an/{dauAn}',[\App\Http\Controllers\Admin\GachTrangTriController::class, 'destroyDauAn'])->name('gach-trang-tri.dau-an.destroy');

        // 6. Lan Can Gốm Sứ
        Route::resource('lan-can-gom-xu', \App\Http\Controllers\Admin\LanCanGomXuController::class)
            ->parameters(['lan-can-gom-xu' => 'lanCanGomXu']);
        Route::post('lan-can-gom-xu/{lanCanGomXu}/gia-tri',[\App\Http\Controllers\Admin\LanCanGomXuController::class, 'storeGiaTri'])->name('lan-can-gom-xu.gia-tri.store');
        Route::put('lan-can-gom-xu/gia-tri/{giaTri}',[\App\Http\Controllers\Admin\LanCanGomXuController::class, 'updateGiaTri'])->name('lan-can-gom-xu.gia-tri.update');
        Route::delete('lan-can-gom-xu/gia-tri/{giaTri}',[\App\Http\Controllers\Admin\LanCanGomXuController::class, 'destroyGiaTri'])->name('lan-can-gom-xu.gia-tri.destroy');

        // 7. Gạch Cổ Bát Tràng
        Route::resource('gach-co-bat-trang', \App\Http\Controllers\Admin\GachCoBatTrangController::class)
            ->parameters(['gach-co-bat-trang' => 'gachCoBatTrang']);
        Route::post('gach-co-bat-trang/{gachCoBatTrang}/anh',[\App\Http\Controllers\Admin\GachCoBatTrangController::class, 'storeAnh'])->name('gach-co-bat-trang.anh.store');
        Route::delete('gach-co-bat-trang/anh/{anh}', [\App\Http\Controllers\Admin\GachCoBatTrangController::class, 'destroyAnh'])->name('gach-co-bat-trang.anh.destroy');

        // 8. Linh Vật Phong Thủy
        Route::resource('linh-vat-phong-thuy', \App\Http\Controllers\Admin\LinhVatPhongThuyController::class)
            ->parameters(['linh-vat-phong-thuy' => 'linhVatPhongThuy']);
        Route::post('linh-vat-phong-thuy/{linhVatPhongThuy}/linh-vat',[\App\Http\Controllers\Admin\LinhVatPhongThuyController::class, 'storeLinhVat'])->name('linh-vat-phong-thuy.linh-vat.store');
        Route::put('linh-vat-phong-thuy/linh-vat/{linhVat}',[\App\Http\Controllers\Admin\LinhVatPhongThuyController::class, 'updateLinhVat'])->name('linh-vat-phong-thuy.linh-vat.update');
        Route::delete('linh-vat-phong-thuy/linh-vat/{linhVat}',[\App\Http\Controllers\Admin\LinhVatPhongThuyController::class, 'destroyLinhVat'])->name('linh-vat-phong-thuy.linh-vat.destroy');
        Route::post('linh-vat-phong-thuy/{linhVatPhongThuy}/anh',[\App\Http\Controllers\Admin\LinhVatPhongThuyController::class, 'storeAnh'])->name('linh-vat-phong-thuy.anh.store');
        Route::delete('linh-vat-phong-thuy/anh/{anh}',[\App\Http\Controllers\Admin\LinhVatPhongThuyController::class, 'destroyAnh'])->name('linh-vat-phong-thuy.anh.destroy');

        // 9. Đèn Gốm Sứ
        Route::resource('den-gom-su', \App\Http\Controllers\Admin\DenGomSuController::class)
            ->parameters(['den-gom-su' => 'denGomSu']);
        Route::post('den-gom-su/{denGomSu}/anh',[\App\Http\Controllers\Admin\DenGomSuController::class, 'storeAnh'])->name('den-gom-su.anh.store');
        Route::delete('den-gom-su/anh/{anh}',[\App\Http\Controllers\Admin\DenGomSuController::class, 'destroyAnh'])->name('den-gom-su.anh.destroy');
        
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
