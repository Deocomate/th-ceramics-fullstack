<?php

use App\Http\Controllers\Client\AboutController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\CustomerServiceController;
use App\Http\Controllers\Client\FactoryController;
use App\Http\Controllers\Client\FaqController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\NewsController;
use App\Http\Controllers\Client\ProductPages\DenGomSuController;
use App\Http\Controllers\Client\ProductPages\GachCoBatTrangController;
use App\Http\Controllers\Client\ProductPages\GachHoaThongGioController;
use App\Http\Controllers\Client\ProductPages\GachTrangTriController;
use App\Http\Controllers\Client\ProductPages\LanCanGomSuController;
use App\Http\Controllers\Client\ProductPages\LinhVatPhongThuyController;
use App\Http\Controllers\Client\ProductPages\NgoiAmDuongController;
use App\Http\Controllers\Client\ProductPages\NgoiHaiVanMieuController;
use App\Http\Controllers\Client\ProductPages\PhuKienNgoiController;
use App\Http\Controllers\Client\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CLIENT ROUTES (SEO Vietnamese URLs)
|--------------------------------------------------------------------------
*/
Route::name('client.')->group(function () {

    // Static & Main Pages
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/ve-chung-toi', [AboutController::class, 'index'])->name('about');
    Route::get('/xuong-san-xuat', [FactoryController::class, 'index'])->name('factory');
    Route::get('/lien-he', [ContactController::class, 'index'])->name('contact');
    Route::get('/cau-hoi-thuong-gap', [FaqController::class, 'index'])->name('faq');

    // Showroom
    Route::view('/showroom', 'clients.showroom.index')->name('showroom');

    // Tin tức (News)
    Route::prefix('tin-tuc')->name('news.')->group(function () {
        Route::get('/', [NewsController::class, 'index'])->name('index');
        Route::get('/{slug}', [NewsController::class, 'detail'])->name('detail');
    });

    // Dự án (Projects)
    Route::prefix('du-an')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/{slug}', [ProjectController::class, 'detail'])->name('detail');
    });

    // Giỏ hàng / Thanh toán
    Route::get('/gio-hang', [CartController::class, 'cart'])->name('cart.index');
    Route::get('/thanh-toan', [CartController::class, 'checkout'])->name('cart.checkout');

    // Chính sách & Dịch vụ khách hàng
    Route::prefix('dich-vu')->name('customer-service.')->group(function () {
        Route::get('/{page}', [CustomerServiceController::class, 'show'])->name('show');
    });

    // Sản phẩm (Products)
    Route::prefix('san-pham')->name('products.')->group(function () {
        // Ngói Âm Dương
        Route::get('/ngoi-am-duong', [NgoiAmDuongController::class, 'index'])->name('ngoi-am-duong.index');
        Route::get('/ngoi-am-duong/{id}', [NgoiAmDuongController::class, 'detail'])->name('ngoi-am-duong.detail');

        // Ngói Hài Văn Miếu
        Route::get('/ngoi-hai-van-mieu', [NgoiHaiVanMieuController::class, 'index'])->name('ngoi-hai-van-mieu.index');
        Route::get('/ngoi-hai-van-mieu/{id}', [NgoiHaiVanMieuController::class, 'detail'])->name('ngoi-hai-van-mieu.detail');

        // Gạch Hoa Thông Gió
        Route::get('/gach-hoa-thong-gio', [GachHoaThongGioController::class, 'index'])->name('gach-hoa-thong-gio.index');
        Route::get('/gach-hoa-thong-gio/{id}', [GachHoaThongGioController::class, 'detail'])->name('gach-hoa-thong-gio.detail');

        // Gạch Trang Trí
        Route::get('/gach-trang-tri', [GachTrangTriController::class, 'index'])->name('gach-trang-tri.index');
        Route::get('/gach-trang-tri/{id}', [GachTrangTriController::class, 'detail'])->name('gach-trang-tri.detail');

        // Lan Can Gốm Sứ
        Route::get('/lan-can-gom-su', [LanCanGomSuController::class, 'index'])->name('lan-can-gom-su.index');
        Route::get('/lan-can-gom-su/{id}', [LanCanGomSuController::class, 'detail'])->name('lan-can-gom-su.detail');

        // Gạch Cổ Bát Tràng
        Route::get('/gach-co-bat-trang', [GachCoBatTrangController::class, 'index'])->name('gach-co-bat-trang.index');
        Route::get('/gach-co-bat-trang/{id}', [GachCoBatTrangController::class, 'detail'])->name('gach-co-bat-trang.detail');

        // Linh Vật Phong Thủy
        Route::get('/linh-vat-phong-thuy', [LinhVatPhongThuyController::class, 'index'])->name('linh-vat-phong-thuy.index');
        Route::get('/linh-vat-phong-thuy/{id}', [LinhVatPhongThuyController::class, 'detail'])->name('linh-vat-phong-thuy.detail');

        // Đèn Gốm Sứ
        Route::get('/den-gom-su', [DenGomSuController::class, 'index'])->name('den-gom-su.index');
        Route::get('/den-gom-su/{id}', [DenGomSuController::class, 'detail'])->name('den-gom-su.detail');

        // Phụ Kiện Ngói
        Route::get('/phu-kien-ngoi', [PhuKienNgoiController::class, 'index'])->name('phu-kien-ngoi.index');
        Route::get('/phu-kien-ngoi/{id}', [PhuKienNgoiController::class, 'detail'])->name('phu-kien-ngoi.detail');
    });
});

/*
|--------------------------------------------------------------------------
| SEO 301 REDIRECTS (Old URLs → New Vietnamese URLs)
|--------------------------------------------------------------------------
| These redirects preserve SEO rankings for URLs already indexed by Google.
| Must be 301 (permanent) — not 302.
*/
Route::redirect('/about', '/ve-chung-toi', 301);
Route::redirect('/factory', '/xuong-san-xuat', 301);
Route::redirect('/contact', '/lien-he', 301);
Route::redirect('/products/ngoi-am-duong', '/san-pham/ngoi-am-duong', 301);
Route::redirect('/products/ngoi-hai-van-mieu', '/san-pham/ngoi-hai-van-mieu', 301);
Route::redirect('/products/gach-hoa-thong-gio', '/san-pham/gach-hoa-thong-gio', 301);
Route::redirect('/products/gach-trang-tri', '/san-pham/gach-trang-tri', 301);
Route::redirect('/products/lan-can-gom-su', '/san-pham/lan-can-gom-su', 301);
Route::redirect('/products/gach-co-bat-trang', '/san-pham/gach-co-bat-trang', 301);
Route::redirect('/products/linh-vat-phong-thuy', '/san-pham/linh-vat-phong-thuy', 301);
Route::redirect('/products/den-gom-su', '/san-pham/den-gom-su', 301);
Route::redirect('/products/phu-kien-ngoi', '/san-pham/phu-kien-ngoi', 301);
Route::redirect('/news', '/tin-tuc', 301);
Route::redirect('/projects', '/du-an', 301);

// Customer service .html URLs → clean Vietnamese URLs
Route::redirect('/dich-vu-khach-hang/trang-thai-don-hang.html', '/dich-vu/trang-thai-don-hang', 301);
Route::redirect('/dich-vu-khach-hang/tai-khoan-cua-toi.html', '/dich-vu/tai-khoan-cua-toi', 301);
Route::redirect('/dich-vu-khach-hang/tai-catalog.html', '/dich-vu/tai-catalog', 301);
Route::redirect('/dich-vu-khach-hang/quy-trinh-dat-hang.html', '/dich-vu/quy-trinh-dat-hang', 301);
Route::redirect('/dich-vu-khach-hang/huong-dan-thi-cong.html', '/dich-vu/huong-dan-thi-cong', 301);
Route::redirect('/dich-vu-khach-hang/chinh-sach-van-chuyen.html', '/dich-vu/chinh-sach-van-chuyen', 301);
Route::redirect('/dich-vu-khach-hang/chinh-sach-doi-tra.html', '/dich-vu/chinh-sach-doi-tra', 301);
Route::redirect('/dich-vu-khach-hang/bao-mat-thong-tin.html', '/dich-vu/bao-mat-thong-tin', 301);
Route::redirect('/faq/index.html', '/cau-hoi-thuong-gap', 301);
Route::redirect('/cart/gio-hang', '/gio-hang', 301);
Route::redirect('/cart/thanh-toan.html', '/thanh-toan', 301);
