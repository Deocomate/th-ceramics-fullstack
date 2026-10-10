<?php

namespace App\Domains\Commerce\Http\Admin;

use App\Domains\Commerce\Application\CouponService;
use App\Domains\Commerce\Http\Requests\CouponRequest;
use App\Http\Controllers\Controller;
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
        $productTypes = $this->couponService->productTypes();

        return view('admin.commerce.coupons.index', compact('coupons', 'deletedCoupons', 'productTypes'));
    }

    public function create(): View
    {
        $productTypes = $this->couponService->productTypes();

        return view('admin.commerce.coupons.create', compact('productTypes'));
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
        $productTypes = $this->couponService->productTypes();

        return view('admin.commerce.coupons.edit', compact('coupon', 'productTypes'));
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
