<?php

use App\Domains\Catalog\Http\Admin\NgoiHaiCoCtController;
use App\Domains\Catalog\Http\Admin\GachCoBatTrangCtController;
use App\Domains\Catalog\Http\Admin\GachCoBatTrangController;
use App\Domains\Catalog\Http\Admin\GachHoaThongGioCtController;
use App\Domains\Catalog\Http\Admin\GachHoaThongGioController;
use App\Domains\Catalog\Http\Admin\PhanLoaiLanCanGomSuCtController;
use App\Domains\Catalog\Http\Admin\PhanLoaiDenVuonGomSuCtController;
use App\Domains\Catalog\Http\Admin\PhanLoaiPhuKienNgoiCtController;
use App\Domains\Catalog\Http\Admin\LanCanGomSuCtController;
use App\Domains\Catalog\Http\Admin\LanCanGomSuController;
use App\Domains\Catalog\Http\Admin\DenGomSuController;
use App\Domains\Catalog\Http\Admin\MauSacNgoiHaiCoCtController;
use App\Domains\Catalog\Http\Admin\MauSacNgoiHaiVanMieuCtController;
use App\Domains\Catalog\Http\Admin\MauSacNgoiAmDuongCtController;
use App\Domains\Catalog\Http\Admin\GachTrangTriCtController;
use App\Domains\Catalog\Http\Admin\GachTrangTriController;
use App\Domains\Catalog\Http\Admin\LinhVatPhongThuyCtController;
use App\Domains\Catalog\Http\Admin\LinhVatPhongThuyController;
use App\Domains\Catalog\Http\Admin\DenVuonGomSuCtController;
use App\Domains\Catalog\Http\Admin\ProductBulkRenameController;
use App\Domains\Catalog\Http\Admin\ProductCopyController;
use App\Domains\Catalog\Http\Admin\ProductPriorityController;
use App\Domains\Catalog\Http\Admin\PhuKienNgoiCtController;
use App\Domains\Catalog\Http\Admin\PhuKienNgoiController;
use App\Domains\Catalog\Http\Admin\DinhMucNgoiHaiCoController;
use App\Domains\Catalog\Http\Admin\DinhMucGachCoBatTrangController;
use App\Domains\Catalog\Http\Admin\DinhMucGachHoaThongGioController;
use App\Domains\Catalog\Http\Admin\DinhMucGachTrangTriController;
use App\Domains\Catalog\Http\Admin\DinhMucNgoiHaiVanMieuController;
use App\Domains\Catalog\Http\Admin\DinhMucNgoiAmDuongController;
use App\Domains\Catalog\Http\Admin\NgoiHaiVanMieuCtController;
use App\Domains\Catalog\Http\Admin\NgoiHaiVanMieuController;
use App\Domains\Catalog\Http\Admin\NgoiAmDuongCtController;
use App\Domains\Catalog\Http\Admin\NgoiAmDuongController;
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
    Route::get('/', [NgoiAmDuongController::class, 'index'])->name('index');
    Route::put('/', [NgoiAmDuongController::class, 'update'])->name('update');
});

// 1.1 Chi tiết Ngói Âm Dương
Route::prefix('ngoi-am-duong-ct')->name('ngoi-am-duong-ct.')->group(function () {
    Route::get('/', [NgoiAmDuongCtController::class, 'index'])->name('index');
    Route::get('/create', [NgoiAmDuongCtController::class, 'create'])->name('create');
    Route::post('/', [NgoiAmDuongCtController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [NgoiAmDuongCtController::class, 'edit'])->name('edit');
    Route::put('/{id}', [NgoiAmDuongCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [NgoiAmDuongCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [NgoiAmDuongCtController::class, 'restore'])->name('restore');

    Route::delete('/{id}/image', [NgoiAmDuongCtController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [NgoiAmDuongCtController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [NgoiAmDuongCtController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 1.2 Màu sắc Ngói Âm Dương
Route::prefix('mau-sac-ngoi-am-duong-ct')->name('mau-sac-ngoi-am-duong-ct.')->group(function () {
    Route::get('/', [MauSacNgoiAmDuongCtController::class, 'index'])->name('index');
    Route::post('/', [MauSacNgoiAmDuongCtController::class, 'store'])->name('store');
    Route::put('/{id}', [MauSacNgoiAmDuongCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [MauSacNgoiAmDuongCtController::class, 'destroy'])->name('destroy');
});

// 1.3 Định mức Ngói Âm Dương
Route::prefix('dinh-muc-ngoi-am-duong')->name('dinh-muc-ngoi-am-duong.')->group(function () {
    Route::get('/', [DinhMucNgoiAmDuongController::class, 'index'])->name('index');
    Route::post('/', [DinhMucNgoiAmDuongController::class, 'store'])->name('store');
    Route::put('/{id}', [DinhMucNgoiAmDuongController::class, 'update'])->name('update');
    Route::delete('/{id}', [DinhMucNgoiAmDuongController::class, 'destroy'])->name('destroy');
});

// 2. Ngói Hài Văn Miếu
Route::prefix('ngoi-hai-van-mieu')->name('ngoi-hai-van-mieu.')->group(function () {
    Route::get('/', [NgoiHaiVanMieuController::class, 'index'])->name('index');
    Route::put('/', [NgoiHaiVanMieuController::class, 'update'])->name('update');
    Route::delete('cong-doan-image', [NgoiHaiVanMieuController::class, 'destroyCongDoanImage'])->name('cong-doan-image.destroy');
});

// 2.1 Chi tiết Ngói Hài Cổ
Route::prefix('ngoi-hai-co-ct')->name('ngoi-hai-co-ct.')->group(function () {
    Route::get('/', [NgoiHaiCoCtController::class, 'index'])->name('index');
    Route::get('/create', [NgoiHaiCoCtController::class, 'create'])->name('create');
    Route::post('/', [NgoiHaiCoCtController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [NgoiHaiCoCtController::class, 'edit'])->name('edit');
    Route::put('/{id}', [NgoiHaiCoCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [NgoiHaiCoCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [NgoiHaiCoCtController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [NgoiHaiCoCtController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [NgoiHaiCoCtController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [NgoiHaiCoCtController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 2.2 Màu sắc Ngói Hài Cổ
Route::prefix('mau-sac-ngoi-hai-co-ct')->name('mau-sac-ngoi-hai-co-ct.')->group(function () {
    Route::get('/', [MauSacNgoiHaiCoCtController::class, 'index'])->name('index');
    Route::post('/', [MauSacNgoiHaiCoCtController::class, 'store'])->name('store');
    Route::put('/{id}', [MauSacNgoiHaiCoCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [MauSacNgoiHaiCoCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [MauSacNgoiHaiCoCtController::class, 'restore'])->name('restore');
});

// 2.3 Định mức Ngói Hài Cổ
Route::prefix('dinh-muc-ngoi-hai-co')->name('dinh-muc-ngoi-hai-co.')->group(function () {
    Route::get('/', [DinhMucNgoiHaiCoController::class, 'index'])->name('index');
    Route::post('/', [DinhMucNgoiHaiCoController::class, 'store'])->name('store');
    Route::put('/{id}', [DinhMucNgoiHaiCoController::class, 'update'])->name('update');
    Route::delete('/{id}', [DinhMucNgoiHaiCoController::class, 'destroy'])->name('destroy');
});

// 2.4 Chi tiết Ngói Hài Văn Miếu
Route::prefix('ngoi-hai-van-mieu-ct')->name('ngoi-hai-van-mieu-ct.')->group(function () {
    Route::get('/', [NgoiHaiVanMieuCtController::class, 'index'])->name('index');
    Route::get('/create', [NgoiHaiVanMieuCtController::class, 'create'])->name('create');
    Route::post('/', [NgoiHaiVanMieuCtController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [NgoiHaiVanMieuCtController::class, 'edit'])->name('edit');
    Route::put('/{id}', [NgoiHaiVanMieuCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [NgoiHaiVanMieuCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [NgoiHaiVanMieuCtController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [NgoiHaiVanMieuCtController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [NgoiHaiVanMieuCtController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [NgoiHaiVanMieuCtController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 2.5 Màu sắc Ngói Hài Văn Miếu
Route::prefix('mau-sac-ngoi-hai-van-mieu-ct')->name('mau-sac-ngoi-hai-van-mieu-ct.')->group(function () {
    Route::get('/', [MauSacNgoiHaiVanMieuCtController::class, 'index'])->name('index');
    Route::post('/', [MauSacNgoiHaiVanMieuCtController::class, 'store'])->name('store');
    Route::put('/{id}', [MauSacNgoiHaiVanMieuCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [MauSacNgoiHaiVanMieuCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [MauSacNgoiHaiVanMieuCtController::class, 'restore'])->name('restore');
});

// 2.6 Định mức Ngói Hài Văn Miếu
Route::prefix('dinh-muc-ngoi-hai-van-mieu')->name('dinh-muc-ngoi-hai-van-mieu.')->group(function () {
    Route::get('/', [DinhMucNgoiHaiVanMieuController::class, 'index'])->name('index');
    Route::post('/', [DinhMucNgoiHaiVanMieuController::class, 'store'])->name('store');
    Route::put('/{id}', [DinhMucNgoiHaiVanMieuController::class, 'update'])->name('update');
    Route::delete('/{id}', [DinhMucNgoiHaiVanMieuController::class, 'destroy'])->name('destroy');
});

// 3. Gạch Hoa Thông Gió
Route::prefix('gach-hoa-thong-gio')->name('gach-hoa-thong-gio.')->group(function () {
    Route::get('/', [GachHoaThongGioController::class, 'index'])->name('index');
    Route::put('/', [GachHoaThongGioController::class, 'update'])->name('update');
    // Thư viện ảnh
    Route::post('anh', [GachHoaThongGioController::class, 'storeAnh'])->name('anh.store');
    Route::delete('anh/{anh}', [GachHoaThongGioController::class, 'destroyAnh'])->name('anh.destroy');
    // Giá trị nổi bật
    Route::post('gia-tri', [GachHoaThongGioController::class, 'storeGiaTri'])->name('gia-tri.store');
    Route::put('gia-tri/{giaTri}', [GachHoaThongGioController::class, 'updateGiaTri'])->name('gia-tri.update');
    Route::delete('gia-tri/{giaTri}', [GachHoaThongGioController::class, 'destroyGiaTri'])->name('gia-tri.destroy');
    Route::delete('process-image', [GachHoaThongGioController::class, 'destroyProcessImage'])->name('process-image.destroy');
});

// 3.1 Chi tiết Gạch Hoa Thông Gió
Route::prefix('gach-hoa-thong-gio-ct')->name('gach-hoa-thong-gio-ct.')->group(function () {
    Route::get('/', [GachHoaThongGioCtController::class, 'index'])->name('index');
    Route::get('/create', [GachHoaThongGioCtController::class, 'create'])->name('create');
    Route::post('/', [GachHoaThongGioCtController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [GachHoaThongGioCtController::class, 'edit'])->name('edit');
    Route::put('/{id}', [GachHoaThongGioCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [GachHoaThongGioCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [GachHoaThongGioCtController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [GachHoaThongGioCtController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [GachHoaThongGioCtController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [GachHoaThongGioCtController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 3.2 Định mức Gạch Hoa Thông Gió
Route::prefix('dinh-muc-gach-hoa-thong-gio')->name('dinh-muc-gach-hoa-thong-gio.')->group(function () {
    Route::get('/', [DinhMucGachHoaThongGioController::class, 'index'])->name('index');
    Route::post('/', [DinhMucGachHoaThongGioController::class, 'store'])->name('store');
    Route::put('/{id}', [DinhMucGachHoaThongGioController::class, 'update'])->name('update');
    Route::delete('/{id}', [DinhMucGachHoaThongGioController::class, 'destroy'])->name('destroy');
});

// 3.3 Chi tiết Gạch Trang Trí
Route::prefix('gach-trang-tri-ct')->name('gach-trang-tri-ct.')->group(function () {
    Route::get('/', [GachTrangTriCtController::class, 'index'])->name('index');
    Route::get('/create', [GachTrangTriCtController::class, 'create'])->name('create');
    Route::post('/', [GachTrangTriCtController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [GachTrangTriCtController::class, 'edit'])->name('edit');
    Route::put('/{id}', [GachTrangTriCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [GachTrangTriCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [GachTrangTriCtController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [GachTrangTriCtController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [GachTrangTriCtController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [GachTrangTriCtController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 3.4 Định mức Gạch Trang Trí
Route::prefix('dinh-muc-gach-trang-tri')->name('dinh-muc-gach-trang-tri.')->group(function () {
    Route::get('/', [DinhMucGachTrangTriController::class, 'index'])->name('index');
    Route::post('/', [DinhMucGachTrangTriController::class, 'store'])->name('store');
    Route::put('/{id}', [DinhMucGachTrangTriController::class, 'update'])->name('update');
    Route::delete('/{id}', [DinhMucGachTrangTriController::class, 'destroy'])->name('destroy');
});

// 4. Phụ Kiện Ngói
Route::prefix('phu-kien-ngoi')->name('phu-kien-ngoi.')->group(function () {
    Route::get('/', [PhuKienNgoiController::class, 'index'])->name('index');
    Route::put('/', [PhuKienNgoiController::class, 'update'])->name('update');
    Route::delete('image', [PhuKienNgoiController::class, 'destroyImage'])->name('image.destroy');
});

// 4.1 Chi tiết Phụ Kiện Ngói
Route::prefix('phu-kien-ngoi-ct')->name('phu-kien-ngoi-ct.')->group(function () {
    Route::get('/', [PhuKienNgoiCtController::class, 'index'])->name('index');
    Route::get('/create', [PhuKienNgoiCtController::class, 'create'])->name('create');
    Route::post('/', [PhuKienNgoiCtController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [PhuKienNgoiCtController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PhuKienNgoiCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [PhuKienNgoiCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [PhuKienNgoiCtController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [PhuKienNgoiCtController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [PhuKienNgoiCtController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [PhuKienNgoiCtController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 4.2 Phân loại Phụ Kiện Ngói
Route::prefix('phan-loai-phu-kien-ngoi-ct')->name('phan-loai-phu-kien-ngoi-ct.')->group(function () {
    Route::get('/', [PhanLoaiPhuKienNgoiCtController::class, 'index'])->name('index');
    Route::post('/', [PhanLoaiPhuKienNgoiCtController::class, 'store'])->name('store');
    Route::put('/{id}', [PhanLoaiPhuKienNgoiCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [PhanLoaiPhuKienNgoiCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [PhanLoaiPhuKienNgoiCtController::class, 'restore'])->name('restore');
});

// 5. Gạch Trang Trí (Cấu hình)
Route::prefix('gach-trang-tri')->name('gach-trang-tri.')->group(function () {
    Route::get('/', [GachTrangTriController::class, 'index'])->name('index');
    Route::put('/', [GachTrangTriController::class, 'update'])->name('update');
    Route::delete('cong-doan-image', [GachTrangTriController::class, 'destroyCongDoanImage'])->name('cong-doan-image.destroy');
});

// 5.1 Chi tiết Gạch Cổ Bát Tràng
Route::prefix('gach-co-bat-trang-ct')->name('gach-co-bat-trang-ct.')->group(function () {
    Route::get('/', [GachCoBatTrangCtController::class, 'index'])->name('index');
    Route::get('/create', [GachCoBatTrangCtController::class, 'create'])->name('create');
    Route::post('/', [GachCoBatTrangCtController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [GachCoBatTrangCtController::class, 'edit'])->name('edit');
    Route::put('/{id}', [GachCoBatTrangCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [GachCoBatTrangCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [GachCoBatTrangCtController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [GachCoBatTrangCtController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [GachCoBatTrangCtController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [GachCoBatTrangCtController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 5.2 Định mức Gạch Cổ Bát Tràng
Route::prefix('dinh-muc-gach-co-bat-trang')->name('dinh-muc-gach-co-bat-trang.')->group(function () {
    Route::get('/', [DinhMucGachCoBatTrangController::class, 'index'])->name('index');
    Route::post('/', [DinhMucGachCoBatTrangController::class, 'store'])->name('store');
    Route::put('/{id}', [DinhMucGachCoBatTrangController::class, 'update'])->name('update');
    Route::delete('/{id}', [DinhMucGachCoBatTrangController::class, 'destroy'])->name('destroy');
});

// 6. Lan Can Gốm Sứ (Cấu hình)
Route::prefix('lan-can-gom-xu')->name('lan-can-gom-xu.')->group(function () {
    Route::get('/', [LanCanGomSuController::class, 'index'])->name('index');
    Route::put('/', [LanCanGomSuController::class, 'update'])->name('update');
});

// 7. Gạch Cổ Bát Tràng (Cấu hình)
Route::prefix('gach-co-bat-trang')->name('gach-co-bat-trang.')->group(function () {
    Route::get('/', [GachCoBatTrangController::class, 'index'])->name('index');
    Route::put('/', [GachCoBatTrangController::class, 'update'])->name('update');
    Route::delete('anh/{anh}', [GachCoBatTrangController::class, 'destroyAnh'])->name('anh.destroy');
    Route::delete('cong-doan-image', [GachCoBatTrangController::class, 'destroyCongDoanImage'])->name('cong-doan-image.destroy');
    Route::delete('section-image', [GachCoBatTrangController::class, 'destroySectionImage'])->name('section-image.destroy');
});

// 8. Linh Vật Phong Thủy (Cấu hình)
Route::prefix('linh-vat-phong-thuy')->name('linh-vat-phong-thuy.')->group(function () {
    Route::get('/', [LinhVatPhongThuyController::class, 'index'])->name('index');
    Route::put('/', [LinhVatPhongThuyController::class, 'update'])->name('update');
    Route::post('linh-vat', [LinhVatPhongThuyController::class, 'storeLinhVat'])->name('linh-vat.store');
    Route::put('linh-vat/{linhVat}', [LinhVatPhongThuyController::class, 'updateLinhVat'])->name('linh-vat.update');
    Route::delete('linh-vat/{linhVat}', [LinhVatPhongThuyController::class, 'destroyLinhVat'])->name('linh-vat.destroy');
    Route::delete('anh/{anh}', [LinhVatPhongThuyController::class, 'destroyAnh'])->name('anh.destroy');
});

// 8.1 Chi tiết Linh Vật Phong Thủy
Route::prefix('linh-vat-phong-thuy-ct')->name('linh-vat-phong-thuy-ct.')->group(function () {
    Route::get('/', [LinhVatPhongThuyCtController::class, 'index'])->name('index');
    Route::get('/create', [LinhVatPhongThuyCtController::class, 'create'])->name('create');
    Route::post('/', [LinhVatPhongThuyCtController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [LinhVatPhongThuyCtController::class, 'edit'])->name('edit');
    Route::put('/{id}', [LinhVatPhongThuyCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [LinhVatPhongThuyCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [LinhVatPhongThuyCtController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [LinhVatPhongThuyCtController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [LinhVatPhongThuyCtController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [LinhVatPhongThuyCtController::class, 'reorderGallery'])->name('gallery.reorder');
});

// 9. Đèn Gốm Sứ (Cấu hình)
Route::prefix('den-gom-su')->name('den-gom-su.')->group(function () {
    Route::get('/', [DenGomSuController::class, 'index'])->name('index');
    Route::put('/', [DenGomSuController::class, 'update'])->name('update');
    Route::delete('anh/{anh}', [DenGomSuController::class, 'destroyAnh'])->name('anh.destroy');
});

// 10. Lan Can Gốm Sứ (Chi tiết & Phân loại)
Route::prefix('lan-can-gom-su-ct')->name('lan-can-gom-su-ct.')->group(function () {
    Route::get('/', [LanCanGomSuCtController::class, 'index'])->name('index');
    Route::get('/create', [LanCanGomSuCtController::class, 'create'])->name('create');
    Route::post('/', [LanCanGomSuCtController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [LanCanGomSuCtController::class, 'edit'])->name('edit');
    Route::put('/{id}', [LanCanGomSuCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [LanCanGomSuCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [LanCanGomSuCtController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [LanCanGomSuCtController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [LanCanGomSuCtController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [LanCanGomSuCtController::class, 'reorderGallery'])->name('gallery.reorder');
});
Route::prefix('phan-loai-lan-can-gom-su-ct')->name('phan-loai-lan-can-gom-su-ct.')->group(function () {
    Route::get('/', [PhanLoaiLanCanGomSuCtController::class, 'index'])->name('index');
    Route::post('/', [PhanLoaiLanCanGomSuCtController::class, 'store'])->name('store');
    Route::put('/{id}', [PhanLoaiLanCanGomSuCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [PhanLoaiLanCanGomSuCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [PhanLoaiLanCanGomSuCtController::class, 'restore'])->name('restore');
});

// 11. Đèn Vườn Gốm Sứ (Chi tiết & Phân loại)
Route::prefix('den-vuon-gom-su-ct')->name('den-vuon-gom-su-ct.')->group(function () {
    Route::get('/', [DenVuonGomSuCtController::class, 'index'])->name('index');
    Route::get('/create', [DenVuonGomSuCtController::class, 'create'])->name('create');
    Route::post('/', [DenVuonGomSuCtController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [DenVuonGomSuCtController::class, 'edit'])->name('edit');
    Route::put('/{id}', [DenVuonGomSuCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [DenVuonGomSuCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [DenVuonGomSuCtController::class, 'restore'])->name('restore');
    Route::delete('/{id}/image', [DenVuonGomSuCtController::class, 'destroyImage'])->name('image.destroy');
    Route::post('/{id}/images', [DenVuonGomSuCtController::class, 'storeImages'])->name('image.store');
    Route::put('/{id}/gallery-order', [DenVuonGomSuCtController::class, 'reorderGallery'])->name('gallery.reorder');
});
Route::prefix('phan-loai-den-vuon-gom-su-ct')->name('phan-loai-den-vuon-gom-su-ct.')->group(function () {
    Route::get('/', [PhanLoaiDenVuonGomSuCtController::class, 'index'])->name('index');
    Route::post('/', [PhanLoaiDenVuonGomSuCtController::class, 'store'])->name('store');
    Route::put('/{id}', [PhanLoaiDenVuonGomSuCtController::class, 'update'])->name('update');
    Route::delete('/{id}', [PhanLoaiDenVuonGomSuCtController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/restore', [PhanLoaiDenVuonGomSuCtController::class, 'restore'])->name('restore');
});
