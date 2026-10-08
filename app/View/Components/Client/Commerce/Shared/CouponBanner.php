<?php

namespace App\View\Components\Client\Commerce\Shared;

use App\Domains\Commerce\Infrastructure\Models\Coupon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class CouponBanner extends Component
{
    public Collection $bannerCoupons;

    public function __construct(mixed $bannerCoupons = null)
    {
        $this->bannerCoupons = $bannerCoupons !== null
            ? collect($bannerCoupons)
            : Coupon::query()
                ->where('show_banner', true)
                ->where('is_active', true)
                ->where('is_delete', 0)
                ->where('start_date', '<=', now())
                ->where(function ($query): void {
                    $query->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->orderByDesc('created_at')
                ->get();
    }

    public function render(): View
    {
        return view('components.client.commerce.shared.coupon-banner');
    }
}
