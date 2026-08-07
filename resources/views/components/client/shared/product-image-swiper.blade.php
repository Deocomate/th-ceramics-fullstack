@props([
    'images' => [],
    'thumbBg' => '',
    'thumbImgClass' => 'object-cover',
    'mainBg' => '',
    'mainImgClass' => 'object-cover',
])

@php
    $mediaItems = \App\Support\ProductGallery::normalize($images);
    if ($mediaItems->isEmpty()) {
        $mediaItems = collect([
            [
                'type' => 'image',
                'path' => 'assets/images/gach-bat-detail-1.png',
            ],
        ]);
    }
@endphp

<div class="flex flex-col md:gap-5 lg:col-span-3">
    <div class="w-full aspect-square bg-white md:shadow-lg relative overflow-hidden group swiper product-main-swiper" data-product-main-swiper>
        <div class="swiper-wrapper">
            @foreach($mediaItems as $index => $item)
                @if(($item['type'] ?? '') === 'video')
                    <div class="swiper-slide w-full h-full {{ $mainBg }}" data-gallery-type="video">
                        <div class="relative w-full h-full bg-black">
                            <iframe
                                class="absolute inset-0 w-full h-full"
                                data-product-video-iframe
                                data-embed-src="{{ $item['embed_url'] }}"
                                title="Video sản phẩm {{ $index + 1 }}"
                                src=""
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                referrerpolicy="strict-origin-when-cross-origin"
                            ></iframe>
                        </div>
                    </div>
                @else
                    <div class="swiper-slide w-full h-full {{ $mainBg }}" data-gallery-type="image">
                        <img src="{{ \App\Support\AssetPath::url($item['path'] ?? null, 'assets/images/gach-bat-detail-1.png') }}" alt="Ảnh sản phẩm {{ $index + 1 }}"
                            class="w-full h-full {{ $mainImgClass }} object-center transition-transform duration-500 group-hover:scale-110" />
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="md:hidden flex justify-center mt-5">
        <div class="product-main-pagination flex justify-center gap-[7px]" data-product-main-pagination></div>
    </div>

    <div class="hidden md:block w-full overflow-hidden swiper product-thumb-swiper" data-product-thumb-swiper>
        <div class="swiper-wrapper">
            @foreach($mediaItems as $index => $item)
                @if(($item['type'] ?? '') === 'video')
                    <div class="swiper-slide aspect-square cursor-pointer shadow-sm hover:opacity-80 transition-opacity {{ $thumbBg ?: 'bg-white' }} relative" data-gallery-type="video">
                        <img src="{{ $item['thumb_url'] }}" alt="Video thu nhỏ {{ $index + 1 }}" class="w-full h-full {{ $thumbImgClass }}" />
                        <span class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="w-8 h-8 rounded-full bg-black/60 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white ml-0.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </span>
                        </span>
                    </div>
                @else
                    <div class="swiper-slide aspect-square cursor-pointer shadow-sm hover:opacity-80 transition-opacity {{ $thumbBg ?: 'bg-white' }}" data-gallery-type="image">
                        <img src="{{ \App\Support\AssetPath::url($item['path'] ?? null, 'assets/images/gach-bat-detail-1.png') }}" alt="Ảnh thu nhỏ {{ $index + 1 }}" class="w-full h-full {{ $thumbImgClass }}" />
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

@push('styles')
    <style>
        @media (min-width: 768px) {
            .product-thumb-swiper .swiper-wrapper {
                display: flex !important;
                transform: none !important;
                justify-content: flex-start !important;
                gap: 20px !important;
            }
            .product-thumb-swiper .swiper-slide {
                width: calc((100% - 6 * 20px) / 7) !important;
                margin-right: 0 !important;
                flex-shrink: 0 !important;
            }
        }
    </style>
@endpush
