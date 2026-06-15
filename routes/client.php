<?php

use App\Http\Controllers\Client\AboutController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\CustomerServiceController;
use App\Http\Controllers\Client\DichVuKhachHang\BaoMatThongTinController;
use App\Http\Controllers\Client\DichVuKhachHang\CatalogController;
use App\Http\Controllers\Client\DichVuKhachHang\ChinhSachDoiTraController;
use App\Http\Controllers\Client\DichVuKhachHang\ChinhSachVanChuyenController;
use App\Http\Controllers\Client\DichVuKhachHang\HuongDanThiCongController;
use App\Http\Controllers\Client\DichVuKhachHang\QuyTrinhDatHangController;
use App\Http\Controllers\Client\DichVuKhachHang\TaiKhoanCuaToiController;
use App\Http\Controllers\Client\DichVuKhachHang\TrangThaiDonHangController;
use App\Http\Controllers\Client\FactoryController;
use App\Http\Controllers\Client\FaqController;
use App\Http\Controllers\Client\GlobalSearchController;
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
use App\Http\Controllers\Client\ShowroomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CLIENT ROUTES (SEO Vietnamese URLs)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/tai-khoan/xac-thuc-email', [AuthController::class, 'verifyNotice'])
        ->name('verification.notice');
    Route::get('/tai-khoan/xac-thuc-email/trang-thai', [AuthController::class, 'verificationStatus'])
        ->name('verification.status');
    Route::get('/tai-khoan/xac-thuc-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('/tai-khoan/xac-thuc-email/gui-lai', [AuthController::class, 'resendVerification'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::name('client.')->group(function () {

    // Static & Main Pages
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/ve-chung-toi', [AboutController::class, 'index'])->name('about');
    Route::get('/xuong-san-xuat', [FactoryController::class, 'index'])->name('factory');
    Route::get('/lien-he', [ContactController::class, 'index'])->name('contact');
    Route::post('/lien-he', [ContactController::class, 'submit'])->middleware('throttle:3,1')->name('contact.submit');
    Route::get('/cau-hoi-thuong-gap', [FaqController::class, 'index'])->name('faq');
    Route::get('/tim-kiem-nhanh', GlobalSearchController::class)->name('search.quick');

    // Showroom
    Route::get('/showroom', [ShowroomController::class, 'index'])->name('showroom');

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
    Route::get('/gio-hang/san-pham-options', [CartController::class, 'productOptions'])->name('cart.product-options');
    Route::get('/gio-hang/mini', [CartController::class, 'mini'])->name('cart.mini');
    Route::get('/thanh-toan', [CartController::class, 'checkout'])->middleware(['auth', 'verified'])->name('cart.checkout');
    Route::post('/gio-hang/them', [CartController::class, 'add'])->name('cart.add');
    Route::post('/gio-hang/cap-nhat', [CartController::class, 'update'])->name('cart.update');
    Route::post('/gio-hang/xoa', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/thanh-toan/xu-ly', [CartController::class, 'processCheckout'])->middleware(['auth', 'verified'])->name('cart.checkout.process');
    Route::post('/thanh-toan/ap-dung-ma', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
    Route::post('/thanh-toan/go-ma', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

    // Chính sách & Dịch vụ khách hàng — route tĩnh phải khai báo TRƯỚC catch-all `/{page}`
    Route::prefix('dich-vu')->name('dich-vu.')->group(function () {
        Route::get('/trang-thai-don-hang', [TrangThaiDonHangController::class, 'index'])->name('trang-thai-don-hang');
        Route::get('/tai-catalog', [CatalogController::class, 'index'])->name('tai-catalog');
        Route::get('/tai-catalog/doc/{id}', [CatalogController::class, 'read'])->name('tai-catalog.read');
        Route::get('/quy-trinh-dat-hang', [QuyTrinhDatHangController::class, 'index'])->name('quy-trinh-dat-hang');
        Route::get('/huong-dan-thi-cong', [HuongDanThiCongController::class, 'index'])->name('huong-dan-thi-cong');
        Route::get('/chinh-sach-van-chuyen', [ChinhSachVanChuyenController::class, 'index'])->name('chinh-sach-van-chuyen');
        Route::get('/chinh-sach-doi-tra', [ChinhSachDoiTraController::class, 'index'])->name('chinh-sach-doi-tra');
        Route::get('/bao-mat-thong-tin', [BaoMatThongTinController::class, 'index'])->name('bao-mat-thong-tin');
    });

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
        Route::get('/ngoi-hai-co/{id}', [NgoiHaiVanMieuController::class, 'detailNgoiHaiCo'])->name('ngoi-hai-co.detail');

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
        Route::get('/phu-kien-ngoi/ngoi-bo-noc/{id}', [PhuKienNgoiController::class, 'detailNgoiBoNoc'])->name('phu-kien-ngoi.ngoi-bo-noc.detail');
        Route::get('/phu-kien-ngoi/bo-noc-chu-van/{id}', [PhuKienNgoiController::class, 'detailBoNocChuVan'])->name('phu-kien-ngoi.bo-noc-chu-van.detail');
        Route::get('/phu-kien-ngoi/{id}', [PhuKienNgoiController::class, 'legacyDetailRedirect'])->name('phu-kien-ngoi.detail');
    });

    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATION ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('tai-khoan')->name('auth.')->group(function () {
        // Only for guests
        Route::middleware('guest')->group(function () {
            // Login
            Route::get('/dang-nhap', [AuthController::class, 'showLogin'])->name('login');
            Route::post('/dang-nhap', [AuthController::class, 'login'])->name('login.post');

            // Register
            Route::get('/dang-ky', [AuthController::class, 'showRegister'])->name('register');
            Route::post('/dang-ky', [AuthController::class, 'register'])->name('register.post');

            // Forgot Password
            Route::get('/quen-mat-khau', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
            Route::post('/quen-mat-khau', [AuthController::class, 'sendResetLink'])->name('forgot-password.post');

            // Reset Password
            Route::get('/dat-lai-mat-khau/{token}', [AuthController::class, 'showResetPassword'])->name('reset-password');
            Route::post('/dat-lai-mat-khau', [AuthController::class, 'resetPassword'])->name('reset-password.post');

            // Google OAuth
            Route::get('/google', [AuthController::class, 'redirectToGoogle'])->name('google');
            Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
            Route::get('/google/hoan-tat-dang-ky', [AuthController::class, 'showCompleteGoogleRegistration'])->name('google.complete');
            Route::post('/google/hoan-tat-dang-ky', [AuthController::class, 'submitCompleteGoogleRegistration'])->name('google.complete.post');
        });

        // Only for authenticated users
        Route::middleware('auth')->group(function () {
            Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');
        });

        // Only for verified authenticated users
        Route::middleware(['auth', 'verified'])->group(function () {
            // --- THÊM MỚI ROUTE CHO TRANG TÀI KHOẢN CỦA TÔI ---
            Route::get('/cua-toi', [TaiKhoanCuaToiController::class, 'index'])->name('profile');
            Route::post('/cua-toi/cap-nhat-thong-tin', [TaiKhoanCuaToiController::class, 'updateProfile'])->name('profile.update');
            Route::post('/cua-toi/doi-mat-khau', [TaiKhoanCuaToiController::class, 'updatePassword'])->name('password.update');
            Route::post('/cua-toi/cap-nhat-anh', [TaiKhoanCuaToiController::class, 'updateAvatar'])->name('profile.update-avatar');
        });
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

Route::redirect('/faq/index.html', '/cau-hoi-thuong-gap', 301);

Route::redirect('/cart/gio-hang', '/gio-hang', 301);
Route::redirect('/cart/thanh-toan.html', '/thanh-toan', 301);
