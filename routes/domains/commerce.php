<?php

use App\Http\Controllers\Admin\ConsultationRequestController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

        // ── Quản lý mã giảm giá ──────────────────────────────────────────
        Route::resource('coupons', CouponController::class)
            ->except(['show']);
        Route::post('/coupons/{coupon}/restore', [CouponController::class, 'restore'])
            ->name('coupons.restore');

        // ── Quản lý đơn hàng ──────────────────────────────────────────
        Route::resource('orders', OrderController::class)
            ->only(['index', 'show', 'update']);

        Route::get('yeu-cau-tu-van', [ConsultationRequestController::class, 'index'])->name('consultation-requests.index');
        Route::get('yeu-cau-tu-van/{consultationRequest}', [ConsultationRequestController::class, 'show'])->name('consultation-requests.show');
        Route::patch('yeu-cau-tu-van/{consultationRequest}/trang-thai', [ConsultationRequestController::class, 'updateStatus'])->name('consultation-requests.update-status');
        Route::delete('yeu-cau-tu-van/{consultationRequest}', [ConsultationRequestController::class, 'destroy'])->name('consultation-requests.destroy');
