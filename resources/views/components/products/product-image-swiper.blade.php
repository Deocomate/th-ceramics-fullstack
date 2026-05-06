@props(['images' => []])

<div class="flex flex-col md:gap-5 lg:col-span-3">
    <div class="w-full aspect-square bg-white md:shadow-lg relative overflow-hidden group swiper product-main-swiper">
        <div class="swiper-wrapper">
            @if(!empty($images) && is_array($images))
                @foreach($images as $index => $image)
                <div class="swiper-slide w-full h-full">
                    <img src="{{ Storage::url($image) }}" alt="Ảnh sản phẩm {{ $index + 1 }}"
                        class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110" />
                </div>
                @endforeach
            @else
                @for ($i = 0; $i < 7; $i++)
                <div class="swiper-slide w-full h-full">
                    <img src="{{ asset('assets/images/gach-bat-detail-1.png') }}" alt="Ảnh sản phẩm {{ $i + 1 }}"
                        class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-110" />
                </div>
                @endfor
            @endif
        </div>
    </div>

    <div class="md:hidden flex justify-center mt-5">
        <div class="product-main-pagination flex justify-center gap-[7px]"></div>
    </div>

    <div class="hidden md:block w-full mt-2 overflow-hidden swiper product-thumb-swiper">
        <div class="swiper-wrapper">
            @if(!empty($images) && is_array($images))
                @foreach($images as $image)
                <div class="swiper-slide aspect-square cursor-pointer shadow-sm hover:opacity-80 transition-opacity">
                    <img src="{{ Storage::url($image) }}" alt="" class="w-full h-full object-cover" />
                </div>
                @endforeach
            @else
                @for ($i = 0; $i < 7; $i++)
                <div class="swiper-slide aspect-square bg-[#5C2321] cursor-pointer shadow-sm hover:opacity-80 transition-opacity"></div>
                @endfor
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        var thumbSwiper = new Swiper(".product-thumb-swiper", {
            spaceBetween: 20,
            slidesPerView: 7,
            freeMode: true,
            watchSlidesProgress: true,
        });

        new Swiper(".product-main-swiper", {
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
                el: ".product-main-pagination",
                clickable: true,
            },
            thumbs: {
                swiper: thumbSwiper,
            },
        });
    })();
</script>
@endpush