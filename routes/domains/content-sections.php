<?php

use App\Domains\Content\Http\Admin\GiaiThuongThanhTuuController;
use App\Domains\Content\Http\Admin\GiaTriVuotTroiController;
use Illuminate\Support\Facades\Route;

// Cấu hình section chung
Route::prefix('gia-tri-vuot-troi')->name('gia-tri-vuot-troi.')->group(function () {
    Route::get('/', [GiaTriVuotTroiController::class, 'index'])->name('index');
    Route::post('/', [GiaTriVuotTroiController::class, 'store'])->name('store');
    Route::put('/{id}', [GiaTriVuotTroiController::class, 'update'])->name('update');
    Route::delete('/{id}', [GiaTriVuotTroiController::class, 'destroy'])->name('destroy');
});

Route::prefix('giai-thuong-thanh-tuu')->name('giai-thuong-thanh-tuu.')->group(function () {
    Route::get('/', [GiaiThuongThanhTuuController::class, 'index'])->name('index');
    Route::post('/', [GiaiThuongThanhTuuController::class, 'store'])->name('store');
    Route::put('/{id}', [GiaiThuongThanhTuuController::class, 'update'])->name('update');
    Route::delete('/{id}', [GiaiThuongThanhTuuController::class, 'destroy'])->name('destroy');
});
