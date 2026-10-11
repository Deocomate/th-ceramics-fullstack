<?php

use App\Domains\Content\Http\Admin\AboutPageConfigController;
use App\Domains\Content\Http\Admin\CatalogController;
use App\Domains\Content\Http\Admin\ContactPageController;
use App\Domains\Content\Http\Admin\FactoryPageController;
use App\Domains\Content\Http\Admin\FaqController;
use App\Domains\Content\Http\Admin\FaqPageController;
use App\Domains\Content\Http\Admin\HomePageConfigController;
use App\Domains\Content\Http\Admin\InstallationGuideController;
use App\Domains\Content\Http\Admin\NewsArticleController;
use App\Domains\Content\Http\Admin\NewsCategoryController;
use App\Domains\Content\Http\Admin\ProjectCategoryController;
use App\Domains\Content\Http\Admin\ProjectController;
use App\Domains\Content\Http\Admin\ProjectPageConfigController;
use Illuminate\Support\Facades\Route;

// ── Trang Chủ Configuration ─────────────────────────────────────────────
Route::get('trang-chu', [HomePageConfigController::class, 'edit'])->name('trang_chu.edit');
Route::put('trang-chu', [HomePageConfigController::class, 'update'])->name('trang_chu.update');

// ── Page Configuration: single-page config panels ──────────────────────
Route::prefix('pages')->name('pages.')->group(function () {
    Route::get('ve-chung-toi', [AboutPageConfigController::class, 'edit'])->name('ve_chung_toi.edit');
    Route::put('ve-chung-toi', [AboutPageConfigController::class, 'update'])->name('ve_chung_toi.update');

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
    Route::get('/', [ProjectCategoryController::class, 'index'])->name('index');
    Route::post('/', [ProjectCategoryController::class, 'store'])->name('store');
    Route::put('/{id}', [ProjectCategoryController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProjectCategoryController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [ProjectCategoryController::class, 'restore'])->name('restore');
});

// ── Dự Án ────────────────────────────────────────────────────────
Route::prefix('trang-du-an')->name('trang-du-an.')->group(function () {
    Route::get('/', [ProjectPageConfigController::class, 'index'])->name('index');
    Route::put('/', [ProjectPageConfigController::class, 'update'])->name('update');
});

Route::prefix('du-an')->name('du-an.')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/create', [ProjectController::class, 'create'])->name('create');
    Route::post('/', [ProjectController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ProjectController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProjectController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProjectController::class, 'destroy'])->name('destroy');
    Route::delete('/{id}/image', [ProjectController::class, 'destroyImage'])->name('image.destroy');
});

// ── Danh Mục Tin Tức ────────────────────────────────────────────
Route::prefix('danh-muc-tin-tuc')->name('danh-muc-tin-tuc.')->group(function () {
    Route::get('/', [NewsCategoryController::class, 'index'])->name('index');
    Route::post('/', [NewsCategoryController::class, 'store'])->name('store');
    Route::put('/{id}', [NewsCategoryController::class, 'update'])->name('update');
    Route::delete('/{id}', [NewsCategoryController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [NewsCategoryController::class, 'restore'])->name('restore');
});

// ── Tin Tức ──────────────────────────────────────────────────────
Route::prefix('tin-tuc')->name('tin-tuc.')->group(function () {
    Route::get('/', [NewsArticleController::class, 'index'])->name('index');
    Route::get('/create', [NewsArticleController::class, 'create'])->name('create');
    Route::post('/', [NewsArticleController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [NewsArticleController::class, 'edit'])->name('edit');
    Route::put('/{id}', [NewsArticleController::class, 'update'])->name('update');
    Route::delete('/{id}', [NewsArticleController::class, 'destroy'])->name('destroy');
});

Route::prefix('thi-cong')->name('thi-cong.')->group(function () {
    Route::get('/', [InstallationGuideController::class, 'index'])->name('index');
    Route::post('/', [InstallationGuideController::class, 'store'])->name('store');
    Route::put('/{id}', [InstallationGuideController::class, 'update'])->name('update');
    Route::delete('/{id}', [InstallationGuideController::class, 'destroy'])->name('destroy');
});

Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/', [CatalogController::class, 'index'])->name('index');
    Route::post('/', [CatalogController::class, 'store'])->name('store');
    Route::put('/{id}', [CatalogController::class, 'update'])->name('update');
    Route::delete('/{id}', [CatalogController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/file', [CatalogController::class, 'file'])->name('file');
    Route::post('/{id}/pages', [CatalogController::class, 'storePage'])->name('pages.store');
    Route::post('/{id}/pages/finalize', [CatalogController::class, 'finalizePages'])->name('pages.finalize');
});
