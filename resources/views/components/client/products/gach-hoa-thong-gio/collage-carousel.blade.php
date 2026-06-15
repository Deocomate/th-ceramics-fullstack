@props([
    'config' => null,
])

<style>
    .product-collage-swiper .swiper-slide>div {
        position: relative;
        width: 100%;
        aspect-ratio: 1469 / 623;
    }

    .product-collage-swiper .swiper-slide .collage-img-0 {
        left: 16.4200%;
        top: 15.7351%;
        width: 20.9557%;
        height: 48.6757%;
    }

    .product-collage-swiper .swiper-slide .collage-img-1 {
        left: 3.5725%;
        top: 36.9117%;
        width: 11.8495%;
        height: 27.4992%;
    }

    .product-collage-swiper .swiper-slide .collage-img-2 {
        left: 64.3294%;
        top: 36.9117%;
        width: 20.4567%;
        height: 48.5296%;
    }

    .product-collage-swiper .swiper-slide .collage-img-3 {
        left: 38.6657%;
        top: 21.5088%;
        width: 24.4383%;
        height: 57.3033%;
    }

    .product-collage-swiper .swiper-slide .collage-img-4 {
        left: 64.3805%;
        top: 0%;
        width: 14.1572%;
        height: 33.5297%;
    }

    .product-collage-swiper .swiper-slide .collage-img-5 {
        left: 85.8407%;
        top: 36.9181%;
        width: 14.1592%;
        height: 36.9181%;
    }

    .product-collage-swiper .swiper-slide .collage-img-6 {
        left: 22.8441%;
        top: 66.4703%;
        width: 14.5316%;
        height: 33.5297%;
    }

    .product-collage-swiper .swiper-slide .collage-img-7 {
        left: 13.8869%;
        top: 66.4526%;
        width: 7.7603%;
        height: 19.4221%;
    }

    /* Squares positioning */
    .product-collage-swiper .swiper-slide .collage-sq-0 {
        background: #B77F57;
        left: 0%;
        top: 57.1428%;
        width: 2.9952%;
        height: 7.2231%;
    }

    .product-collage-swiper .swiper-slide .collage-sq-1 {
        background: #586549;
        left: 79.8475%;
        top: 25.7351%;
        width: 3.3054%;
        height: 7.7940%;
    }

    .product-collage-swiper .swiper-slide .collage-sq-2 {
        background: #C65A3B;
        left: 85.7324%;
        top: 77.6468%;
        width: 3.3054%;
        height: 7.7940%;
    }
</style>

<!-- Product Collage Carousel -->
<section class="relative w-full pt-4 pb-8 md:pt-[28px] md:pb-16 mx-auto gach-hoa-collage-section">
    <!-- Background Decoration (Right/Left corner extending down) -->
    <div class="absolute z-[0] right-[-35%] md:right-[-25%] lg:right-[-50%] top-[50%] md:top-[55%] w-[70%] md:w-[70%] lg:w-[85%] pointer-events-none"
        data-aos="fade-up-left">
        <img src="{{ asset('assets/images/gach-hoa-decorate.png') }}" alt=""
            class="w-full origin-center rotate-[-65deg] scale-x-[-1] opacity-10 drop-shadow-sm" />
    </div>
    <div class="absolute z-[0] left-[-35%] md:left-[-25%] lg:left-[-30%] -bottom-[200%] md:-bottom-[230%] w-[70%] md:w-[70%] lg:w-[82%] pointer-events-none"
        data-aos="fade-up-right">
        <img src="{{ asset('assets/images/gach-hoa-decorate.png') }}" alt=""
            class="w-full origin-center rotate-[-55deg] scale-x-[-1] opacity-10 drop-shadow-sm" />
    </div>

    <div class="w-full max-w-[1660px] px-4 md:px-[65px] mx-auto flex flex-col items-center relative z-10"
        data-aos="fade-up">
        <!-- Swiper Container -->
        <div class="swiper product-collage-swiper w-full pb-0">
            <!-- Slides are injected by JS below -->
            <div class="swiper-wrapper" id="collage-swiper-wrapper"></div>
        </div>

        @php
            $galleryImages = $config->anh ?? collect();
            $allGalleryPaths = $galleryImages->pluck('image')->filter()->values();
        @endphp
        <!--
      Asset preload hints
    -->
        <div aria-hidden="true" class="hidden">
            @foreach ($allGalleryPaths as $imgPath)
                <img src="{{ asset('storage/' . $imgPath) }}" alt="" />
            @endforeach
        </div>

        <!-- Custom Page-Number Navigation -->
        <div
            class="flex items-center justify-between gap-6 w-full max-w-[1320px] mx-auto text-textPrimary font-bold text-[17px] z-20">
            <!-- Prev Button -->
            <button id="collage-btn-prev"
                class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <!-- Page Numbers (rendered by JS) -->
            <div id="collage-pagination" class="flex items-center gap-5 select-none"></div>

            <!-- Next Button -->
            <button id="collage-btn-next"
                class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        (function() {
            @php
                $galleryImages = $config->anh ?? collect();
                $allImages = $galleryImages->pluck('image')->filter()->values()->toArray();
                $chunkSize = 8;
                $imageChunks = array_chunk($allImages, $chunkSize);
                $SHADOWS = ['shadow-sm', 'shadow-md', 'shadow-sm', 'shadow-lg focal', 'shadow-sm', 'shadow-sm', 'shadow-md', 'shadow-sm'];
                $ZCLASSES = ['z-20', 'z-20', 'z-20', 'z-30', 'z-20', 'z-20', 'z-20', 'z-20'];
                $slidesArray = [];
                foreach ($imageChunks as $chunkIdx => $chunk) {
                    $slideImages = [];
                    foreach ($chunk as $imgIdx => $imgPath) {
                        $imgUrl = asset('storage/' . $imgPath);
                        $slideImages[] = [
                            'href' => $imgUrl,
                            'src' => $imgUrl,
                            'alt' => 'Image ' . ($imgIdx + 1),
                            'shadow' => $SHADOWS[$imgIdx % count($SHADOWS)],
                            'zClass' => $ZCLASSES[$imgIdx % count($ZCLASSES)],
                        ];
                    }
                    $slidesArray[] = [
                        'gallery' => 'gach-hoa-' . ($chunkIdx + 1),
                        'images' => $slideImages,
                    ];
                }
                if (empty($slidesArray)) {
                    $slidesArray[] = ['gallery' => 'gach-hoa', 'images' => []];
                }
            @endphp
            var SLIDES = @json($slidesArray);

            var wrapper = document.getElementById("collage-swiper-wrapper");
            if (!wrapper) return;

            for (var i = 0; i < SLIDES.length; i++) {
                var slide = SLIDES[i];
                var squaresHtml = "";
                for (var s = 0; s < 3; s++) {
                    squaresHtml += '<div class="absolute collage-sq-' + s + '"></div>';
                }
                var imagesHtml = "";
                for (var j = 0; j < slide.images.length; j++) {
                    var img = slide.images[j];
                    var isFocal = img.zClass === "z-30";
                    var hoverScale = isFocal ? "hover:scale-[1.02]" : "hover:scale-105";
                    var zClass = img.zClass || "z-20";
                    imagesHtml += '<a href="' + img.href + '" class="glightbox absolute ' + img.shadow +
                        ' transition-transform ' + hoverScale + ' ' + zClass + ' collage-img-' + j +
                        '" data-gallery="' + slide.gallery + '">' + '<img src="' + img.src +
                        '" class="w-full h-full object-cover" alt="' + img.alt + '" />' + "</a>";
                }
                var slideEl = document.createElement("div");
                slideEl.className = "swiper-slide w-full";
                slideEl.innerHTML = '<div class="relative w-full aspect-[1469/623]">' +
                    squaresHtml + imagesHtml + "</div>";
                wrapper.appendChild(slideEl);
            }

            var swiper = new Swiper(".product-collage-swiper", {
                slidesPerView: 1,
                speed: 600,
                loop: false,
                allowTouchMove: true,
            });

            var VISIBLE_PAGES = 3;
            var total = SLIDES.length;

            function buildPagination(activeIdx) {
                var pag = document.getElementById("collage-pagination");
                if (!pag) return;
                pag.innerHTML = "";
                var shown = [];
                for (var p = 0; p < total && p < VISIBLE_PAGES; p++) shown.push(p);
                var hasEllipsis = total > VISIBLE_PAGES + 1;
                var showLast = total > VISIBLE_PAGES;

                shown.forEach(function(idx) {
                    var a = document.createElement("a");
                    a.href = "#";
                    a.textContent = idx + 1;
                    if (idx === activeIdx) {
                        a.className = "text-black border-b-[3px] border-black pb-[2px] px-1";
                    } else {
                        a.className = "text-black/40 hover:text-black transition-colors px-1";
                    }
                    a.addEventListener("click", function(e) {
                        e.preventDefault();
                        swiper.slideTo(idx);
                    });
                    pag.appendChild(a);
                });

                if (hasEllipsis) {
                    var dots = document.createElement("span");
                    dots.textContent = "...";
                    dots.className = "text-black/40 tracking-widest px-1";
                    pag.appendChild(dots);
                }

                if (showLast) {
                    var lastIdx = total - 1;
                    var la = document.createElement("a");
                    la.href = "#";
                    la.textContent = total;
                    la.className = lastIdx === activeIdx ? "text-black border-b-[3px] border-black pb-[2px] px-1" :
                        "text-black/40 hover:text-black transition-colors px-1";
                    la.addEventListener("click", function(e) {
                        e.preventDefault();
                        swiper.slideTo(lastIdx);
                    });
                    pag.appendChild(la);
                }
            }

            buildPagination(0);

            swiper.on("slideChange", function() {
                buildPagination(swiper.activeIndex);
            });

            var btnPrev = document.getElementById("collage-btn-prev");
            var btnNext = document.getElementById("collage-btn-next");
            if (btnPrev) btnPrev.addEventListener("click", function() {
                swiper.slidePrev();
            });
            if (btnNext) btnNext.addEventListener("click", function() {
                swiper.slideNext();
            });
        })();
    </script>
@endpush
