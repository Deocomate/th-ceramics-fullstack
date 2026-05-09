<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Services\CouponService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function __construct(
        private readonly CouponService $couponService
    ) {}

    public function index(): View
    {
        $coupons = $this->couponService->getAll();
        $deletedCoupons = $this->couponService->getDeleted();
        $productTypes = CouponService::productTypes();

        return view('admin.coupons.index', compact('coupons', 'deletedCoupons', 'productTypes'));
    }

    public function create(): View
    {
        $productTypes = CouponService::productTypes();

        return view('admin.coupons.create', compact('productTypes'));
    }

    public function store(CouponRequest $request): RedirectResponse
    {
        $this->couponService->store($request->validated());

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Đã thêm mã giảm giá mới.');
    }

    public function edit(int $id): View
    {
        $coupon = $this->couponService->findById($id);
        $productTypes = CouponService::productTypes();

        return view('admin.coupons.edit', compact('coupon', 'productTypes'));
    }

    public function update(CouponRequest $request, int $id): RedirectResponse
    {
        $this->couponService->update($id, $request->validated());

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Đã cập nhật mã giảm giá.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->couponService->destroy($id);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Đã xóa mã giảm giá.');
    }

    public function restore(int $id): RedirectResponse
    {
        $this->couponService->restore($id);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Đã khôi phục mã giảm giá.');
    }
}
