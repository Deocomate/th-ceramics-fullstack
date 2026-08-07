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
                        <div
                            class="relative w-full h-full bg-black"
                            data-product-video-shell
                            data-embed-src="{{ $item['embed_url'] }}"
                            data-video-thumb="{{ $item['thumb_url'] }}"
                        >
                            <button
                                type="button"
                                data-product-video-play
                                class="absolute inset-0 z-10 w-full h-full cursor-pointer group/video"
                                aria-label="Phát video sản phẩm {{ $index + 1 }}"
                            >
                                <img
                                    src="{{ $item['thumb_url'] }}"
                                    alt="Video sản phẩm {{ $index + 1 }}"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                    referrerpolicy="no-referrer"
                                />
                                <span class="absolute inset-0 bg-black/25 transition-colors group-hover/video:bg-black/35"></span>
                                <span class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <span class="w-16 h-16 rounded-full bg-[#A31D1D] shadow-lg flex items-center justify-center">
                                        <svg class="w-7 h-7 text-white ml-1" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </span>
                                </span>
                            </button>
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
                    <div class="swiper-slide aspect-square cursor-pointer shadow-sm transition-all duration-200 {{ $thumbBg ?: 'bg-white' }} relative" data-gallery-type="video">
                        <img src="{{ $item['thumb_url'] }}" alt="Video thu nhỏ {{ $index + 1 }}" class="w-full h-full {{ $thumbImgClass }}" loading="lazy" referrerpolicy="no-referrer" />
                        <span class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="w-8 h-8 rounded-full bg-black/60 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white ml-0.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </span>
                        </span>
                    </div>
                @else
                    <div class="swiper-slide aspect-square cursor-pointer shadow-sm transition-all duration-200 {{ $thumbBg ?: 'bg-white' }}" data-gallery-type="image">
                        <img src="{{ \App\Support\AssetPath::url($item['path'] ?? null, 'assets/images/gach-bat-detail-1.png') }}" alt="Ảnh thu nhỏ {{ $index + 1 }}" class="w-full h-full {{ $thumbImgClass }}" />
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

@push('styles')
    <style>
        .product-main-swiper [data-product-video-shell] iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
            z-index: 20;
        }

        .product-thumb-swiper .swiper-slide {
            opacity: 0.55;
            box-sizing: border-box;
        }

        .product-thumb-swiper .swiper-slide:hover {
            opacity: 0.85;
        }

        .product-thumb-swiper .swiper-slide-thumb-active {
            opacity: 1;
            outline: 2px solid #A31D1D;
            outline-offset: 2px;
        }
    </style>
@endpush
