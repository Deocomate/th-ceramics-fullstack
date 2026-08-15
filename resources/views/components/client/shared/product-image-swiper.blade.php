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
    <div class="relative w-full aspect-square overflow-hidden shadow-none border-0 ring-0">
    <div class="absolute inset-0 swiper product-main-swiper group" data-product-main-swiper>
        <div class="swiper-wrapper">
            @foreach($mediaItems as $index => $item)
                @if(($item['type'] ?? '') === 'video')
                    @php $isFileVideo = \App\Support\ProductGallery::isFileVideo($item); @endphp
                    <div class="swiper-slide w-full h-full {{ $mainBg }}" data-gallery-type="video">
                        <div
                            class="relative w-full h-full bg-black"
                            data-product-video-shell
                            @if($isFileVideo)
                                data-video-src="{{ $item['display_url'] ?? '' }}"
                            @else
                                data-embed-src="{{ $item['embed_url'] ?? '' }}"
                                data-youtube-id="{{ $item['youtube_id'] ?? '' }}"
                                data-video-thumb="{{ $item['thumb_url'] ?? '' }}"
                            @endif
                        >
                            <button
                                type="button"
                                data-product-video-play
                                class="absolute inset-0 z-10 w-full h-full cursor-pointer group/video"
                                aria-label="Phát video sản phẩm {{ $index + 1 }}"
                            >
                                @if($isFileVideo)
                                    <video src="{{ $item['display_url'] ?? '' }}" class="w-full h-full object-cover" muted preload="metadata" playsinline></video>
                                @else
                                    <img
                                        src="{{ $item['thumb_url'] }}"
                                        alt="Video sản phẩm {{ $index + 1 }}"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                        referrerpolicy="no-referrer"
                                    />
                                @endif
                                <span class="absolute inset-0 bg-black/25 transition-colors group-hover/video:bg-black/35"></span>
                                <span class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <span class="w-16 h-16 rounded-full bg-[#A31D1D] shadow-lg flex items-center justify-center">
                                        <svg class="w-7 h-7 text-white ml-1" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </span>
                                </span>
                            </button>
                            @if($isFileVideo)
                                <div data-file-player-host class="absolute inset-0 z-20 w-full h-full bg-black hidden"></div>
                            @else
                                <div data-yt-player-host class="absolute inset-0 z-20 w-full h-full bg-black hidden"></div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="swiper-slide w-full h-full {{ $mainBg }}" data-gallery-type="image">
                        <img src="{{ \App\Support\AssetPath::url($item['path'] ?? null, 'assets/images/gach-bat-detail-1.png') }}" alt="Ảnh sản phẩm {{ $index + 1 }}"
                            class="w-full h-full {{ $mainImgClass }} object-center" />
                    </div>
                @endif
            @endforeach
        </div>

        <button
            type="button"
            class="product-main-prev absolute left-2 md:left-3 top-1/2 -translate-y-1/2 z-30 w-9 h-9 md:w-10 md:h-10 rounded-full bg-white/90 border border-black/10 shadow-md flex items-center justify-center text-primary hover:bg-white hover:border-secondary transition-all focus:outline-none"
            data-product-main-prev
            aria-label="Ảnh trước"
        >
            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button
            type="button"
            class="product-main-next absolute right-2 md:right-3 top-1/2 -translate-y-1/2 z-30 w-9 h-9 md:w-10 md:h-10 rounded-full bg-white/90 border border-black/10 shadow-md flex items-center justify-center text-primary hover:bg-white hover:border-secondary transition-all focus:outline-none"
            data-product-main-next
            aria-label="Ảnh tiếp theo"
        >
            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
    </div>

    <div class="md:hidden flex justify-center mt-5">
        <div class="product-main-pagination flex justify-center gap-[7px]" data-product-main-pagination></div>
    </div>

    <div class="hidden md:block w-full overflow-x-hidden overflow-y-visible py-1 swiper product-thumb-swiper" data-product-thumb-swiper>
        <div class="swiper-wrapper">
            @foreach($mediaItems as $index => $item)
                @if(($item['type'] ?? '') === 'video')
                    @php $isFileVideo = \App\Support\ProductGallery::isFileVideo($item); @endphp
                    <div class="swiper-slide aspect-square cursor-pointer shadow-sm transition-all duration-200 {{ $thumbBg ?: 'bg-white' }} relative" data-gallery-type="video">
                        @if($isFileVideo)
                            <video src="{{ $item['display_url'] ?? '' }}" class="w-full h-full {{ $thumbImgClass }} object-cover" muted preload="metadata" playsinline></video>
                        @else
                            <img src="{{ $item['thumb_url'] }}" alt="Video thu nhỏ {{ $index + 1 }}" class="w-full h-full {{ $thumbImgClass }}" loading="lazy" referrerpolicy="no-referrer" />
                        @endif
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
        .product-main-swiper,
        .product-main-swiper .swiper-wrapper,
        .product-main-swiper .swiper-slide {
            width: 100%;
            height: 100%;
            box-shadow: none;
            border: 0;
            outline: none;
        }

        .product-main-swiper .swiper-slide {
            overflow: hidden;
        }

        .product-main-swiper .swiper-slide > img,
        .product-main-swiper .swiper-slide > [data-product-video-shell],
        .product-main-swiper [data-product-video-shell] > img,
        .product-main-swiper [data-product-video-shell] video,
        .product-main-swiper [data-product-video-play] img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            box-shadow: none;
            border: 0;
            outline: none;
        }

        .product-main-swiper [data-product-video-shell] iframe,
        .product-main-swiper [data-yt-player-host] iframe,
        .product-main-swiper [data-file-player-host] video {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
            z-index: 20;
            object-fit: cover;
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
        }

        .product-main-swiper .swiper-button-disabled {
            opacity: 0.35;
            cursor: default;
            pointer-events: none;
        }
    </style>
@endpush
