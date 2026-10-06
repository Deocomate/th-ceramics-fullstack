<?php

use App\Domains\Content\Http\Admin\AwardAchievementController;
use App\Domains\Content\Http\Admin\CoreValueController;
use Illuminate\Support\Facades\Route;

// Cấu hình section chung
Route::prefix('gia-tri-vuot-troi')->name('gia-tri-vuot-troi.')->group(function () {
    Route::get('/', [CoreValueController::class, 'index'])->name('index');
    Route::post('/', [CoreValueController::class, 'store'])->name('store');
    Route::put('/{id}', [CoreValueController::class, 'update'])->name('update');
    Route::delete('/{id}', [CoreValueController::class, 'destroy'])->name('destroy');
});

Route::prefix('giai-thuong-thanh-tuu')->name('giai-thuong-thanh-tuu.')->group(function () {
    Route::get('/', [AwardAchievementController::class, 'index'])->name('index');
    Route::post('/', [AwardAchievementController::class, 'store'])->name('store');
    Route::put('/{id}', [AwardAchievementController::class, 'update'])->name('update');
    Route::delete('/{id}', [AwardAchievementController::class, 'destroy'])->name('destroy');
});
