<?php

use App\Domains\Content\Http\Admin\CatalogController;
use App\Domains\Content\Http\Admin\ContactPageController;
use App\Domains\Content\Http\Admin\DanhMucDuAnController;
use App\Domains\Content\Http\Admin\DanhMucTinTucController;
use App\Domains\Content\Http\Admin\DuAnController;
use App\Domains\Content\Http\Admin\FactoryPageController;
use App\Domains\Content\Http\Admin\FaqController;
use App\Domains\Content\Http\Admin\FaqPageController;
use App\Domains\Content\Http\Admin\ThiCongController;
use App\Domains\Content\Http\Admin\TinTucController;
use App\Domains\Content\Http\Admin\TrangDuAnController;
use App\Domains\Content\Http\Admin\VeChungToiController;
use Illuminate\Support\Facades\Route;

// ── Page Configuration: single-page config panels ──────────────────────
Route::prefix('pages')->name('pages.')->group(function () {
    Route::get('ve-chung-toi', [VeChungToiController::class, 'edit'])->name('ve_chung_toi.edit');
    Route::put('ve-chung-toi', [VeChungToiController::class, 'update'])->name('ve_chung_toi.update');

    Route::get('factory', [FactoryPageController::class, 'edit'])->name('factory.edit');
    Route::put('factory', [FactoryPageController::class, 'update'])->name('factory.update');

    Route::get('contact', [ContactPageController::class, 'edit'])->name('contact.edit');
    Route::put('contact', [ContactPageController::class, 'update'])->name('contact.update');

    Route::get('faq', [FaqPageController::class, 'edit'])->name('faq.edit');
    Route::put('faq', [FaqPageController::class, 'update'])->name('faq.update');
    Route::resource('faqs', FaqController::class)->except(['show']);
});

// ── Danh Mục Dự Án ──────────────────────────────────────────────
Route::prefix('danh-muc-du-an')->name('danh-muc-du-an.')->group(function () {
    Route::get('/', [DanhMucDuAnController::class, 'index'])->name('index');
    Route::post('/', [DanhMucDuAnController::class, 'store'])->name('store');
    Route::put('/{id}', [DanhMucDuAnController::class, 'update'])->name('update');
    Route::delete('/{id}', [DanhMucDuAnController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [DanhMucDuAnController::class, 'restore'])->name('restore');
});

// ── Dự Án ────────────────────────────────────────────────────────
Route::prefix('trang-du-an')->name('trang-du-an.')->group(function () {
    Route::get('/', [TrangDuAnController::class, 'index'])->name('index');
    Route::put('/', [TrangDuAnController::class, 'update'])->name('update');
});

Route::prefix('du-an')->name('du-an.')->group(function () {
    Route::get('/', [DuAnController::class, 'index'])->name('index');
    Route::get('/create', [DuAnController::class, 'create'])->name('create');
    Route::post('/', [DuAnController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [DuAnController::class, 'edit'])->name('edit');
    Route::put('/{id}', [DuAnController::class, 'update'])->name('update');
    Route::delete('/{id}', [DuAnController::class, 'destroy'])->name('destroy');
    Route::delete('/{id}/image', [DuAnController::class, 'destroyImage'])->name('image.destroy');
});

// ── Danh Mục Tin Tức ────────────────────────────────────────────
Route::prefix('danh-muc-tin-tuc')->name('danh-muc-tin-tuc.')->group(function () {
    Route::get('/', [DanhMucTinTucController::class, 'index'])->name('index');
    Route::post('/', [DanhMucTinTucController::class, 'store'])->name('store');
    Route::put('/{id}', [DanhMucTinTucController::class, 'update'])->name('update');
    Route::delete('/{id}', [DanhMucTinTucController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [DanhMucTinTucController::class, 'restore'])->name('restore');
});

// ── Tin Tức ──────────────────────────────────────────────────────
Route::prefix('tin-tuc')->name('tin-tuc.')->group(function () {
    Route::get('/', [TinTucController::class, 'index'])->name('index');
    Route::get('/create', [TinTucController::class, 'create'])->name('create');
    Route::post('/', [TinTucController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [TinTucController::class, 'edit'])->name('edit');
    Route::put('/{id}', [TinTucController::class, 'update'])->name('update');
    Route::delete('/{id}', [TinTucController::class, 'destroy'])->name('destroy');
});

Route::prefix('thi-cong')->name('thi-cong.')->group(function () {
    Route::get('/', [ThiCongController::class, 'index'])->name('index');
    Route::post('/', [ThiCongController::class, 'store'])->name('store');
    Route::put('/{id}', [ThiCongController::class, 'update'])->name('update');
    Route::delete('/{id}', [ThiCongController::class, 'destroy'])->name('destroy');
});

Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/', [CatalogController::class, 'index'])->name('index');
    Route::post('/', [CatalogController::class, 'store'])->name('store');
    Route::put('/{id}', [CatalogController::class, 'update'])->name('update');
    Route::delete('/{id}', [CatalogController::class, 'destroy'])->name('destroy');
});
