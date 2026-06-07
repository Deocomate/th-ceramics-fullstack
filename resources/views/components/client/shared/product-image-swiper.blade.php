@props(['images' => []])

@php
    $imageItems = collect($images)->filter()->values();
    if ($imageItems->isEmpty()) {
        $imageItems = collect(['assets/images/gach-bat-detail-1.png']);
    }
@endphp

<div class="flex flex-col md:gap-5 lg:col-span-3">
    <div class="w-full aspect-square bg-white md:shadow-lg relative overflow-hidden group swiper product-main-swiper" data-product-main-swiper>
        <div class="swiper-wrapper">
            @foreach($imageItems as $index => $image)
                <div class="swiper-slide w-full h-full">
                    <img src="{{ \App\Support\AssetPath::url($image, 'assets/images/gach-bat-detail-1.png') }}" alt="Ảnh sản phẩm {{ $index + 1 }}"
                        class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110" />
                </div>
            @endforeach
        </div>
    </div>

    <div class="md:hidden flex justify-center mt-5">
        <div class="product-main-pagination flex justify-center gap-[7px]" data-product-main-pagination></div>
    </div>

    <div class="hidden md:block w-full mt-2 overflow-hidden swiper product-thumb-swiper" data-product-thumb-swiper>
        <div class="swiper-wrapper">
            @foreach($imageItems as $index => $image)
                <div class="swiper-slide aspect-square cursor-pointer shadow-sm hover:opacity-80 transition-opacity">
                    <img src="{{ \App\Support\AssetPath::url($image, 'assets/images/gach-bat-detail-1.png') }}" alt="Ảnh thu nhỏ {{ $index + 1 }}" class="w-full h-full object-cover" />
                </div>
            @endforeach
        </div>
    </div>
</div>
