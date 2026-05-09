<x-layouts.client title="Chi tiết Phụ Kiện Ngói" data-page="products" main-class="bg-background-secondary pb-14 md:pb-20" :hide-newsletter="true">

@push('styles')
<style>
    @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");
</style>
@endpush

@php
    $productId = $product->ngoi_bo_noc_ct_id ?? $product->bo_noc_chu_van_ct_id ?? 0;
@endphp

<!-- Top Banner for Detail -->
<section class="hidden md:flex relative w-full h-[180px] md:h-[210px] items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        @php $detailBanner = (!empty($product->images) && is_array($product->images)) ? $product->images[0] : null; @endphp
        <img src="{{ $detailBanner ? asset('storage/' . $detailBanner) : asset('assets/images/detail-banner.png') }}" alt="Phụ Kiện Ngói Banner" class="w-full h-full object-cover" />
    </div>
    <div class="relative z-10 text-center text-white px-4 pt-4">
        <h1 class="text-2xl md:text-3xl font-bold mb-2.5 uppercase">
            {{ $product->name }}
        </h1>
        <p class="text-xs md:text-sm text-white/80">
            <a href="{{ route('client.home') }}" class="hover:text-white transition-colors">Trang chủ</a>
            <svg class="w-2.5 h-2.5 inline-block mx-2 fill-current opacity-80" viewBox="0 0 35 35" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.5797 31.4214C11.1695 31.0111 10.9391 30.4548 10.9391 29.8747C10.9391 29.2946 11.1695 28.7383 11.5797 28.3281L22.4078 17.5L11.5797 6.67184C11.1937 6.25726 10.9836 5.70915 10.9936 5.14283C11.0036 4.5765 11.2328 4.03612 11.6331 3.63539C12.0334 3.23465 12.5735 3.0048 13.1399 2.99421C13.7062 2.98361 14.2545 3.1931 14.6695 3.57858L27.046 15.9516C27.4561 16.3618 27.6865 16.9182 27.6865 17.4983C27.6865 18.0783 27.4561 18.6347 27.046 19.0449L14.6729 31.4214C14.2627 31.8315 13.7064 32.0619 13.1263 32.0619C12.5462 32.0619 11.9899 31.8315 11.5797 31.4214Z"
                    fill="currentColor" />
            </svg>
            <a href="{{ route('client.products.phu-kien-ngoi.index') }}" class="hover:text-white transition-colors">Phụ kiện ngói</a>
        </p>
    </div>
</section>

<!-- Sub Breadcrumb -->
<div class="hidden md:block w-[85%] max-w-[1320px] mx-auto py-8">
    <x-products.breadcrumb current-label="{{ $product->name }}" parent-label="Phụ Kiện Ngói" parent-href="{{ route('client.products.phu-kien-ngoi.index') }}" />
    <hr class="border-t border-black/10 mt-4 w-full" />
</div>

<!-- Product Detail Container -->
<x-products.product-detail-container
    title="{!! $product->name !!}"
    price="Liên hệ"
    sku="{{ $type === 'bo_noc' ? 'BN-' . $productId : 'CV-' . $productId }}"
    :features="$product->des && is_array($product->des) ? $product->des : null"
    productType="phu_kien_ngoi"
    productId="{{ $productId }}"
/>

<!-- Phan Loai Classifications Table -->
@if($phanLoais && $phanLoais->isNotEmpty())
<section class="w-[85%] max-w-[1320px] mx-auto pb-[40px] md:pb-16" data-aos="fade-up">
    <h2 class="text-[20px] leading-[32px] tracking-[0.6px] md:text-3xl md:leading-normal md:tracking-wide font-semibold text-center text-secondary mb-6 md:mb-12 uppercase break-words">
        Phân loại sản phẩm
    </h2>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-white shadow-md rounded-sm overflow-hidden">
            <thead class="bg-[#C76E00] text-white">
                <tr>
                    <th class="py-3 px-4 md:px-6 text-left text-sm md:text-base uppercase font-semibold">Phân loại</th>
                    <th class="py-3 px-4 md:px-6 text-left text-sm md:text-base uppercase font-semibold">Mã</th>
                    <th class="py-3 px-4 md:px-6 text-right text-sm md:text-base uppercase font-semibold">Giá</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($phanLoais as $phanLoai)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-3 px-4 md:px-6 text-sm md:text-base text-[#2E2F2A] font-medium">{{ $phanLoai->name }}</td>
                    <td class="py-3 px-4 md:px-6 text-sm md:text-base text-gray-500">{{ $phanLoai->code }}</td>
                    <td class="py-3 px-4 md:px-6 text-sm md:text-base text-[#C47526] font-bold text-right">{{ $phanLoai->price > 0 ? number_format($phanLoai->price) . 'đ' : 'Liên hệ' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif

<!-- Product Images Section -->
@if(!empty($product->images) && is_array($product->images))
<section class="w-[85%] max-w-[1320px] mx-auto pb-[40px] md:pb-16" data-aos="fade-up">
    <h2 class="text-[20px] leading-[32px] tracking-[0.6px] md:text-3xl md:leading-normal md:tracking-wide font-semibold text-center text-secondary mb-6 md:mb-12 uppercase break-words">
        Hình ảnh sản phẩm
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        @foreach($product->images as $image)
        <div class="aspect-square overflow-hidden rounded-sm shadow-md">
            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Size Image Section -->
@if(!empty($product->size_image))
<section class="w-[85%] max-w-[1320px] mx-auto pb-[40px] md:pb-16" data-aos="fade-up">
    <h2 class="text-[20px] leading-[32px] tracking-[0.6px] md:text-3xl md:leading-normal md:tracking-wide font-semibold text-center text-secondary mb-6 md:mb-12 uppercase break-words">
        Bảng kích thước
    </h2>
    <div class="size-options-scroll mobile-scroll-visible w-full pb-2 overflow-x-scroll md:overflow-x-hidden">
        <img src="{{ asset('storage/' . $product->size_image) }}" alt="Kích thước {{ $product->name }}"
            class="h-auto object-contain max-w-none w-[200%] md:w-full"
            onload="window.dispatchEvent(new Event('resize'))" />
    </div>
</section>
@endif

<!-- Size Des Section -->
@if(!empty($product->size_des) && is_array($product->size_des))
<section class="w-[85%] max-w-[1320px] mx-auto pb-[40px] md:pb-16" data-aos="fade-up">
    <h2 class="text-[20px] leading-[32px] tracking-[0.6px] md:text-3xl md:leading-normal md:tracking-wide font-semibold text-center text-secondary mb-6 md:mb-12 uppercase break-words">
        Thông tin kích thước
    </h2>
    <ul class="list-disc pl-5 space-y-2 max-w-2xl mx-auto text-[#2E2F2A] text-[14px] md:text-lg leading-relaxed">
        @foreach($product->size_des as $item)
        <li>{{ $item }}</li>
        @endforeach
    </ul>
</section>
@endif

<x-products.journey-video :hide-title="true" />
<x-products.works />
<x-products.recommendations />

<!-- Related Products -->
@if($relatedProducts->isNotEmpty())
<section class="w-[85%] max-w-[1320px] mx-auto pb-[50px] md:pb-24" data-aos="fade-up">
    <h2 class="text-[20px] leading-[32px] tracking-[0.6px] md:text-3xl md:leading-normal md:tracking-wide font-semibold text-center text-secondary mb-6 md:mb-12 uppercase break-words">
        Sản phẩm liên quan
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-6 sm:gap-x-6 sm:gap-y-10">
        @foreach($relatedProducts as $related)
            @php
                $relatedImage = (!empty($related->images) && is_array($related->images)) ? $related->images[0] : null;
                $relatedId = $related->ngoi_bo_noc_ct_id ?? $related->bo_noc_chu_van_ct_id ?? 0;
            @endphp
            <a href="{{ route('client.products.phu-kien-ngoi.detail', $relatedId) }}" class="flex flex-col group cursor-pointer">
                <div class="product-card relative bg-white rounded-sm shadow-lg overflow-hidden mb-4 aspect-square transition-all duration-300 group-hover:-translate-y-1">
                    <img src="{{ $relatedImage ? asset('storage/' . $relatedImage) : asset('assets/images/ngoi-01.jpg') }}" alt="{{ $related->name }}" class="w-full h-full object-cover mix-blend-multiply" />
                    <div class="product-overlay">
                        <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
                        <span>Xem chi tiết</span>
                    </div>
                </div>
                <h3 class="text-primary font-semibold text-sm uppercase mb-2 transition-colors group-hover:text-secondary">
                    {{ $related->name }}
                </h3>
                <p class="text-gray-500 text-[13px] mb-2">MSP: {{ $relatedId }}</p>
                <p class="text-secondary font-bold text-[14px]">Giá: Liên hệ</p>
            </a>
        @endforeach
    </div>
</section>
@endif

<x-products.faq2 />

</x-layouts.client>
