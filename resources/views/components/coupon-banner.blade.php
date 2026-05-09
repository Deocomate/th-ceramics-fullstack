@php
    use App\Models\Coupon;

    $bannerCoupons = Coupon::query()
        ->where('show_banner', true)
        ->where('is_active', true)
        ->where('is_delete', 0)
        ->where('start_date', '<=', now())
        ->where(function ($query) {
            $query->whereNull('end_date')->orWhere('end_date', '>=', now());
        })
        ->orderBy('created_at', 'desc')
        ->get();
@endphp

@if($bannerCoupons->isNotEmpty())
<div class="bg-gradient-to-r from-red-50 to-yellow-50 py-3">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-4 overflow-x-auto">
            <span class="text-sm font-semibold text-red-700 whitespace-nowrap font-archivo">
                <i class="fas fa-ticket-alt mr-1"></i>Ưu đãi:
            </span>
            @foreach($bannerCoupons as $coupon)
            <div class="flex items-center gap-2 bg-white rounded-lg px-3 py-2 shadow-sm whitespace-nowrap">
                @if($coupon->banner_image)
                <img src="{{ asset('storage/' . $coupon->banner_image) }}"
                     alt="{{ $coupon->title }}" class="h-8 w-12 object-cover rounded">
                @endif
                <div>
                    <p class="text-xs font-medium text-gray-800 font-archivo">{{ $coupon->title }}</p>
                    <p class="text-xs text-red-600 font-bold font-archivo">{{ $coupon->code }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
