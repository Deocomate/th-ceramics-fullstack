<?php

use App\Domains\Catalog\Http\Admin\AncientFishScaleRoofTileAdminController;
use App\Domains\Catalog\Http\Admin\BatTrangAntiqueBrickAdminController;
use App\Domains\Catalog\Http\Admin\BatTrangAntiqueBrickController;
use App\Domains\Catalog\Http\Admin\BreezeBlockAdminController;
use App\Domains\Catalog\Http\Admin\BreezeBlockController;
use App\Domains\Catalog\Http\Admin\CategoryCeramicBalustradeAdminController;
use App\Domains\Catalog\Http\Admin\CategoryGardenCeramicLampAdminController;
use App\Domains\Catalog\Http\Admin\CategoryRoofTileAccessoryAdminController;
use App\Domains\Catalog\Http\Admin\CeramicBalustradeAdminController;
use App\Domains\Catalog\Http\Admin\CeramicBalustradeController;
use App\Domains\Catalog\Http\Admin\CeramicLampController;
use App\Domains\Catalog\Http\Admin\ColorOptionAncientFishScaleRoofTileAdminController;
use App\Domains\Catalog\Http\Admin\ColorOptionVanMieuFishScaleRoofTileAdminController;
use App\Domains\Catalog\Http\Admin\ColorOptionYinYangRoofTileAdminController;
use App\Domains\Catalog\Http\Admin\DecorativeTileAdminController;
use App\Domains\Catalog\Http\Admin\DecorativeTileController;
use App\Domains\Catalog\Http\Admin\FengShuiCreatureAdminController;
use App\Domains\Catalog\Http\Admin\FengShuiCreatureController;
use App\Domains\Catalog\Http\Admin\GardenCeramicLampAdminController;
use App\Domains\Catalog\Http\Admin\ProductBulkRenameController;
use App\Domains\Catalog\Http\Admin\ProductCopyController;
use App\Domains\Catalog\Http\Admin\ProductPriorityController;
use App\Domains\Catalog\Http\Admin\RoofTileAccessoryAdminController;
use App\Domains\Catalog\Http\Admin\RoofTileAccessoryController;
use App\Domains\Catalog\Http\Admin\UsageNormAncientFishScaleRoofTileController;
use App\Domains\Catalog\Http\Admin\UsageNormBatTrangAntiqueBrickController;
use App\Domains\Catalog\Http\Admin\UsageNormBreezeBlockController;
use App\Domains\Catalog\Http\Admin\UsageNormDecorativeTileController;
use App\Domains\Catalog\Http\Admin\UsageNormVanMieuFishScaleRoofTileController;
use App\Domains\Catalog\Http\Admin\UsageNormYinYangRoofTileController;
use App\Domains\Catalog\Http\Admin\VanMieuFishScaleRoofTileAdminController;
use App\Domains\Catalog\Http\Admin\VanMieuFishScaleRoofTileController;
use App\Domains\Catalog\Http\Admin\YinYangRoofTileAdminController;
use App\Domains\Catalog\Http\Admin\YinYangRoofTileController;
use Illuminate\Support\Facades\Route;

// ── Product Copy API Routes ──────────────────────────────────────────────
Route::prefix('api/product-copy')->name('product-copy.')->group(function () {
    Route::get('list', [ProductCopyController::class, 'list'])->name('list');
    Route::get('detail/{type}/{id}', [ProductCopyController::class, 'detail'])->name('detail');
});

Route::post('api/products/{type}/bulk-rename', [ProductBulkRenameController::class, 'update'])
    ->name('products.bulk-rename');
Route::put('api/products/{type}/priority', [ProductPriorityController::class, 'update'])
    ->name('products.priority.update');

// ── Product Types Routes ────────────────────────────────────────────────
// 1. Ngói Âm Dương
Route::prefix('ngoi-am-duong')->name('ngoi-am-duong.')->group(function () {
    Route::get('/', [YinYangRoofTileController::class, 'index'])->name('index');
    Route::put('/', [YinYangRoofTileController::class, 'update'])->name('update');
});

// 1.1 Chi tiết Ngói Âm Dương
Route::prefix('ngoi-am-duong-ct')->name('ngoi-am-duong-ct.')->group(function () {
    Route::get('/', [YinYangRoofTileAdminController::class, 'index'])->name('index');
    Route::get('/create', [YinYangRoofTileAdminController::class, 'create'])->name('create');
    Route::post('/', [YinYangRoofTileAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [YinYangRoofTileAdminController::class, 'edit'])->name('edit');
    Route::put('/{id}', [YinYangRoofTileAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [YinYangRoofTileAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [YinYangRoofTileAdminController::class, 'restore'])->name('restore');

    Route::delete('/{id}/image', [YinYangRoofTileAdminController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [YinYangRoofTileAdminController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [YinYangRoofTileAdminController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 1.2 Màu sắc Ngói Âm Dương
Route::prefix('mau-sac-ngoi-am-duong-ct')->name('mau-sac-ngoi-am-duong-ct.')->group(function () {
    Route::get('/', [ColorOptionYinYangRoofTileAdminController::class, 'index'])->name('index');
    Route::post('/', [ColorOptionYinYangRoofTileAdminController::class, 'store'])->name('store');
    Route::put('/{id}', [ColorOptionYinYangRoofTileAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [ColorOptionYinYangRoofTileAdminController::class, 'destroy'])->name('destroy');
});

// 1.3 Định mức Ngói Âm Dương
Route::prefix('dinh-muc-ngoi-am-duong')->name('dinh-muc-ngoi-am-duong.')->group(function () {
    Route::get('/', [UsageNormYinYangRoofTileController::class, 'index'])->name('index');
    Route::post('/', [UsageNormYinYangRoofTileController::class, 'store'])->name('store');
    Route::put('/{id}', [UsageNormYinYangRoofTileController::class, 'update'])->name('update');
    Route::delete('/{id}', [UsageNormYinYangRoofTileController::class, 'destroy'])->name('destroy');
});

// 2. Ngói Hài Văn Miếu
Route::prefix('ngoi-hai-van-mieu')->name('ngoi-hai-van-mieu.')->group(function () {
    Route::get('/', [VanMieuFishScaleRoofTileController::class, 'index'])->name('index');
    Route::put('/', [VanMieuFishScaleRoofTileController::class, 'update'])->name('update');
    Route::delete('cong-doan-image', [VanMieuFishScaleRoofTileController::class, 'destroyCongDoanImage'])->name('cong-doan-image.destroy');
});

// 2.1 Chi tiết Ngói Hài Cổ
Route::prefix('ngoi-hai-co-ct')->name('ngoi-hai-co-ct.')->group(function () {
    Route::get('/', [AncientFishScaleRoofTileAdminController::class, 'index'])->name('index');
    Route::get('/create', [AncientFishScaleRoofTileAdminController::class, 'create'])->name('create');
    Route::post('/', [AncientFishScaleRoofTileAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AncientFishScaleRoofTileAdminController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AncientFishScaleRoofTileAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [AncientFishScaleRoofTileAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [AncientFishScaleRoofTileAdminController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [AncientFishScaleRoofTileAdminController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [AncientFishScaleRoofTileAdminController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [AncientFishScaleRoofTileAdminController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 2.2 Màu sắc Ngói Hài Cổ
Route::prefix('mau-sac-ngoi-hai-co-ct')->name('mau-sac-ngoi-hai-co-ct.')->group(function () {
    Route::get('/', [ColorOptionAncientFishScaleRoofTileAdminController::class, 'index'])->name('index');
    Route::post('/', [ColorOptionAncientFishScaleRoofTileAdminController::class, 'store'])->name('store');
    Route::put('/{id}', [ColorOptionAncientFishScaleRoofTileAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [ColorOptionAncientFishScaleRoofTileAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [ColorOptionAncientFishScaleRoofTileAdminController::class, 'restore'])->name('restore');
});

// 2.3 Định mức Ngói Hài Cổ
Route::prefix('dinh-muc-ngoi-hai-co')->name('dinh-muc-ngoi-hai-co.')->group(function () {
    Route::get('/', [UsageNormAncientFishScaleRoofTileController::class, 'index'])->name('index');
    Route::post('/', [UsageNormAncientFishScaleRoofTileController::class, 'store'])->name('store');
    Route::put('/{id}', [UsageNormAncientFishScaleRoofTileController::class, 'update'])->name('update');
    Route::delete('/{id}', [UsageNormAncientFishScaleRoofTileController::class, 'destroy'])->name('destroy');
});

// 2.4 Chi tiết Ngói Hài Văn Miếu
Route::prefix('ngoi-hai-van-mieu-ct')->name('ngoi-hai-van-mieu-ct.')->group(function () {
    Route::get('/', [VanMieuFishScaleRoofTileAdminController::class, 'index'])->name('index');
    Route::get('/create', [VanMieuFishScaleRoofTileAdminController::class, 'create'])->name('create');
    Route::post('/', [VanMieuFishScaleRoofTileAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [VanMieuFishScaleRoofTileAdminController::class, 'edit'])->name('edit');
    Route::put('/{id}', [VanMieuFishScaleRoofTileAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [VanMieuFishScaleRoofTileAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [VanMieuFishScaleRoofTileAdminController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [VanMieuFishScaleRoofTileAdminController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [VanMieuFishScaleRoofTileAdminController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [VanMieuFishScaleRoofTileAdminController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 2.5 Màu sắc Ngói Hài Văn Miếu
Route::prefix('mau-sac-ngoi-hai-van-mieu-ct')->name('mau-sac-ngoi-hai-van-mieu-ct.')->group(function () {
    Route::get('/', [ColorOptionVanMieuFishScaleRoofTileAdminController::class, 'index'])->name('index');
    Route::post('/', [ColorOptionVanMieuFishScaleRoofTileAdminController::class, 'store'])->name('store');
    Route::put('/{id}', [ColorOptionVanMieuFishScaleRoofTileAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [ColorOptionVanMieuFishScaleRoofTileAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [ColorOptionVanMieuFishScaleRoofTileAdminController::class, 'restore'])->name('restore');
});

// 2.6 Định mức Ngói Hài Văn Miếu
Route::prefix('dinh-muc-ngoi-hai-van-mieu')->name('dinh-muc-ngoi-hai-van-mieu.')->group(function () {
    Route::get('/', [UsageNormVanMieuFishScaleRoofTileController::class, 'index'])->name('index');
    Route::post('/', [UsageNormVanMieuFishScaleRoofTileController::class, 'store'])->name('store');
    Route::put('/{id}', [UsageNormVanMieuFishScaleRoofTileController::class, 'update'])->name('update');
    Route::delete('/{id}', [UsageNormVanMieuFishScaleRoofTileController::class, 'destroy'])->name('destroy');
});

// 3. Gạch Hoa Thông Gió
Route::prefix('gach-hoa-thong-gio')->name('gach-hoa-thong-gio.')->group(function () {
    Route::get('/', [BreezeBlockController::class, 'index'])->name('index');
    Route::put('/', [BreezeBlockController::class, 'update'])->name('update');
    // Thư viện ảnh
    Route::post('anh', [BreezeBlockController::class, 'storeAnh'])->name('anh.store');
    Route::delete('anh/{anh}', [BreezeBlockController::class, 'destroyAnh'])->name('anh.destroy');
    // Giá trị nổi bật
    Route::post('gia-tri', [BreezeBlockController::class, 'storeGiaTri'])->name('gia-tri.store');
    Route::put('gia-tri/{giaTri}', [BreezeBlockController::class, 'updateGiaTri'])->name('gia-tri.update');
    Route::delete('gia-tri/{giaTri}', [BreezeBlockController::class, 'destroyGiaTri'])->name('gia-tri.destroy');
    Route::delete('process-image', [BreezeBlockController::class, 'destroyProcessImage'])->name('process-image.destroy');
});

// 3.1 Chi tiết Gạch Hoa Thông Gió
Route::prefix('gach-hoa-thong-gio-ct')->name('gach-hoa-thong-gio-ct.')->group(function () {
    Route::get('/', [BreezeBlockAdminController::class, 'index'])->name('index');
    Route::get('/create', [BreezeBlockAdminController::class, 'create'])->name('create');
    Route::post('/', [BreezeBlockAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [BreezeBlockAdminController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BreezeBlockAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [BreezeBlockAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [BreezeBlockAdminController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [BreezeBlockAdminController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [BreezeBlockAdminController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [BreezeBlockAdminController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 3.2 Định mức Gạch Hoa Thông Gió
Route::prefix('dinh-muc-gach-hoa-thong-gio')->name('dinh-muc-gach-hoa-thong-gio.')->group(function () {
    Route::get('/', [UsageNormBreezeBlockController::class, 'index'])->name('index');
    Route::post('/', [UsageNormBreezeBlockController::class, 'store'])->name('store');
    Route::put('/{id}', [UsageNormBreezeBlockController::class, 'update'])->name('update');
    Route::delete('/{id}', [UsageNormBreezeBlockController::class, 'destroy'])->name('destroy');
});

// 3.3 Chi tiết Gạch Trang Trí
Route::prefix('gach-trang-tri-ct')->name('gach-trang-tri-ct.')->group(function () {
    Route::get('/', [DecorativeTileAdminController::class, 'index'])->name('index');
    Route::get('/create', [DecorativeTileAdminController::class, 'create'])->name('create');
    Route::post('/', [DecorativeTileAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [DecorativeTileAdminController::class, 'edit'])->name('edit');
    Route::put('/{id}', [DecorativeTileAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [DecorativeTileAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [DecorativeTileAdminController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [DecorativeTileAdminController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [DecorativeTileAdminController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [DecorativeTileAdminController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 3.4 Định mức Gạch Trang Trí
Route::prefix('dinh-muc-gach-trang-tri')->name('dinh-muc-gach-trang-tri.')->group(function () {
    Route::get('/', [UsageNormDecorativeTileController::class, 'index'])->name('index');
    Route::post('/', [UsageNormDecorativeTileController::class, 'store'])->name('store');
    Route::put('/{id}', [UsageNormDecorativeTileController::class, 'update'])->name('update');
    Route::delete('/{id}', [UsageNormDecorativeTileController::class, 'destroy'])->name('destroy');
});

// 4. Phụ Kiện Ngói
Route::prefix('phu-kien-ngoi')->name('phu-kien-ngoi.')->group(function () {
    Route::get('/', [RoofTileAccessoryController::class, 'index'])->name('index');
    Route::put('/', [RoofTileAccessoryController::class, 'update'])->name('update');
    Route::delete('image', [RoofTileAccessoryController::class, 'destroyImage'])->name('image.destroy');
});

// 4.1 Chi tiết Phụ Kiện Ngói
Route::prefix('phu-kien-ngoi-ct')->name('phu-kien-ngoi-ct.')->group(function () {
    Route::get('/', [RoofTileAccessoryAdminController::class, 'index'])->name('index');
    Route::get('/create', [RoofTileAccessoryAdminController::class, 'create'])->name('create');
    Route::post('/', [RoofTileAccessoryAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [RoofTileAccessoryAdminController::class, 'edit'])->name('edit');
    Route::put('/{id}', [RoofTileAccessoryAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [RoofTileAccessoryAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [RoofTileAccessoryAdminController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [RoofTileAccessoryAdminController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [RoofTileAccessoryAdminController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [RoofTileAccessoryAdminController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 4.2 Phân loại Phụ Kiện Ngói
Route::prefix('phan-loai-phu-kien-ngoi-ct')->name('phan-loai-phu-kien-ngoi-ct.')->group(function () {
    Route::get('/', [CategoryRoofTileAccessoryAdminController::class, 'index'])->name('index');
    Route::post('/', [CategoryRoofTileAccessoryAdminController::class, 'store'])->name('store');
    Route::put('/{id}', [CategoryRoofTileAccessoryAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [CategoryRoofTileAccessoryAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [CategoryRoofTileAccessoryAdminController::class, 'restore'])->name('restore');
});

// 5. Gạch Trang Trí (Cấu hình)
Route::prefix('gach-trang-tri')->name('gach-trang-tri.')->group(function () {
    Route::get('/', [DecorativeTileController::class, 'index'])->name('index');
    Route::put('/', [DecorativeTileController::class, 'update'])->name('update');
    Route::delete('cong-doan-image', [DecorativeTileController::class, 'destroyCongDoanImage'])->name('cong-doan-image.destroy');
});

// 5.1 Chi tiết Gạch Cổ Bát Tràng
Route::prefix('gach-co-bat-trang-ct')->name('gach-co-bat-trang-ct.')->group(function () {
    Route::get('/', [BatTrangAntiqueBrickAdminController::class, 'index'])->name('index');
    Route::get('/create', [BatTrangAntiqueBrickAdminController::class, 'create'])->name('create');
    Route::post('/', [BatTrangAntiqueBrickAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [BatTrangAntiqueBrickAdminController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BatTrangAntiqueBrickAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [BatTrangAntiqueBrickAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [BatTrangAntiqueBrickAdminController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [BatTrangAntiqueBrickAdminController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [BatTrangAntiqueBrickAdminController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [BatTrangAntiqueBrickAdminController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 5.2 Định mức Gạch Cổ Bát Tràng
Route::prefix('dinh-muc-gach-co-bat-trang')->name('dinh-muc-gach-co-bat-trang.')->group(function () {
    Route::get('/', [UsageNormBatTrangAntiqueBrickController::class, 'index'])->name('index');
    Route::post('/', [UsageNormBatTrangAntiqueBrickController::class, 'store'])->name('store');
    Route::put('/{id}', [UsageNormBatTrangAntiqueBrickController::class, 'update'])->name('update');
    Route::delete('/{id}', [UsageNormBatTrangAntiqueBrickController::class, 'destroy'])->name('destroy');
});

// 6. Lan Can Gốm Sứ (Cấu hình)
Route::prefix('lan-can-gom-xu')->name('lan-can-gom-xu.')->group(function () {
    Route::get('/', [CeramicBalustradeController::class, 'index'])->name('index');
    Route::put('/', [CeramicBalustradeController::class, 'update'])->name('update');
});

// 7. Gạch Cổ Bát Tràng (Cấu hình)
Route::prefix('gach-co-bat-trang')->name('gach-co-bat-trang.')->group(function () {
    Route::get('/', [BatTrangAntiqueBrickController::class, 'index'])->name('index');
    Route::put('/', [BatTrangAntiqueBrickController::class, 'update'])->name('update');
    Route::delete('anh/{anh}', [BatTrangAntiqueBrickController::class, 'destroyAnh'])->name('anh.destroy');
    Route::delete('cong-doan-image', [BatTrangAntiqueBrickController::class, 'destroyCongDoanImage'])->name('cong-doan-image.destroy');
    Route::delete('section-image', [BatTrangAntiqueBrickController::class, 'destroySectionImage'])->name('section-image.destroy');
});

// 8. Linh Vật Phong Thủy (Cấu hình)
Route::prefix('linh-vat-phong-thuy')->name('linh-vat-phong-thuy.')->group(function () {
    Route::get('/', [FengShuiCreatureController::class, 'index'])->name('index');
    Route::put('/', [FengShuiCreatureController::class, 'update'])->name('update');
    Route::post('linh-vat', [FengShuiCreatureController::class, 'storeLinhVat'])->name('linh-vat.store');
    Route::put('linh-vat/{linhVat}', [FengShuiCreatureController::class, 'updateLinhVat'])->name('linh-vat.update');
    Route::delete('linh-vat/{linhVat}', [FengShuiCreatureController::class, 'destroyLinhVat'])->name('linh-vat.destroy');
    Route::delete('anh/{anh}', [FengShuiCreatureController::class, 'destroyAnh'])->name('anh.destroy');
});

// 8.1 Chi tiết Linh Vật Phong Thủy
Route::prefix('linh-vat-phong-thuy-ct')->name('linh-vat-phong-thuy-ct.')->group(function () {
    Route::get('/', [FengShuiCreatureAdminController::class, 'index'])->name('index');
    Route::get('/create', [FengShuiCreatureAdminController::class, 'create'])->name('create');
    Route::post('/', [FengShuiCreatureAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [FengShuiCreatureAdminController::class, 'edit'])->name('edit');
    Route::put('/{id}', [FengShuiCreatureAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [FengShuiCreatureAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [FengShuiCreatureAdminController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [FengShuiCreatureAdminController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [FengShuiCreatureAdminController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [FengShuiCreatureAdminController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 9. Đèn Gốm Sứ (Cấu hình)
Route::prefix('den-gom-su')->name('den-gom-su.')->group(function () {
    Route::get('/', [CeramicLampController::class, 'index'])->name('index');
    Route::put('/', [CeramicLampController::class, 'update'])->name('update');
    Route::delete('anh/{anh}', [CeramicLampController::class, 'destroyAnh'])->name('anh.destroy');
});

// 10. Lan Can Gốm Sứ (Chi tiết & Phân loại)
Route::prefix('lan-can-gom-su-ct')->name('lan-can-gom-su-ct.')->group(function () {
    Route::get('/', [CeramicBalustradeAdminController::class, 'index'])->name('index');
    Route::get('/create', [CeramicBalustradeAdminController::class, 'create'])->name('create');
    Route::post('/', [CeramicBalustradeAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CeramicBalustradeAdminController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CeramicBalustradeAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [CeramicBalustradeAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [CeramicBalustradeAdminController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [CeramicBalustradeAdminController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [CeramicBalustradeAdminController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [CeramicBalustradeAdminController::class, 'reorderGallery'])->name('gallery.reorder');
});
Route::prefix('phan-loai-lan-can-gom-su-ct')->name('phan-loai-lan-can-gom-su-ct.')->group(function () {
    Route::get('/', [CategoryCeramicBalustradeAdminController::class, 'index'])->name('index');
    Route::post('/', [CategoryCeramicBalustradeAdminController::class, 'store'])->name('store');
    Route::put('/{id}', [CategoryCeramicBalustradeAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [CategoryCeramicBalustradeAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [CategoryCeramicBalustradeAdminController::class, 'restore'])->name('restore');
});

// 11. Đèn Vườn Gốm Sứ (Chi tiết & Phân loại)
Route::prefix('den-vuon-gom-su-ct')->name('den-vuon-gom-su-ct.')->group(function () {
    Route::get('/', [GardenCeramicLampAdminController::class, 'index'])->name('index');
    Route::get('/create', [GardenCeramicLampAdminController::class, 'create'])->name('create');
    Route::post('/', [GardenCeramicLampAdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [GardenCeramicLampAdminController::class, 'edit'])->name('edit');
    Route::put('/{id}', [GardenCeramicLampAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [GardenCeramicLampAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [GardenCeramicLampAdminController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [GardenCeramicLampAdminController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [GardenCeramicLampAdminController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [GardenCeramicLampAdminController::class, 'reorderGallery'])->name('gallery.reorder');
});
Route::prefix('phan-loai-den-vuon-gom-su-ct')->name('phan-loai-den-vuon-gom-su-ct.')->group(function () {
    Route::get('/', [CategoryGardenCeramicLampAdminController::class, 'index'])->name('index');
    Route::post('/', [CategoryGardenCeramicLampAdminController::class, 'store'])->name('store');
    Route::put('/{id}', [CategoryGardenCeramicLampAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [CategoryGardenCeramicLampAdminController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [CategoryGardenCeramicLampAdminController::class, 'restore'])->name('restore');
});
