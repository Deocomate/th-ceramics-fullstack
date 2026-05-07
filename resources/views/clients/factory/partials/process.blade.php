@php
    $processSlider = is_string($factory->process_slider) ? json_decode($factory->process_slider, true) : $factory->process_slider;
    $processSlider = is_array($processSlider) ? $processSlider : [];
@endphp
<section class="bg-background-secondary relative overflow-hidden text-primary">
  <!-- Grid Overlay Blueprint Lines -->
  <div class="grid-overlay absolute inset-0 z-0 pointer-events-none">
    <div class="w-[85%] max-w-[1320px] mx-auto h-full relative">
      <!-- Line 1: Far Left Vertical -->
      <div
        class="line line-v hidden md:block absolute bg-black/10 w-px"
        style="left: -10vw; top: -5%; height: 60%"
      ></div>

      <!-- Line 2: Horizontal Intersect Top -->
      <div
        class="line line-h hidden md:block absolute bg-black/10 h-px"
        style="top: 4%; left: -22vw; width: 40%"
      ></div>

      <!-- Line 3: Middle Vertical -->
      <div
        class="line line-v hidden md:block absolute bg-black/10 w-px"
        style="left: 25%; top: 45%; height: 18%"
      ></div>

      <!-- Line 4: Horizontal Intersect Bottom -->
      <div
        class="line line-h hidden md:block absolute bg-black/10 h-px"
        style="top: 49%; left: -20vw; width: 58%"
      ></div>
    </div>
  </div>

  <!-- Content Container -->
  <div class="relative z-10 w-[85%] max-w-[1320px] mx-auto pt-2 md:pt-16">
    <!-- Top Row -->
    <div class="flex flex-col md:flex-row">
      <!-- Left col: Text -->
      <div
        class="w-full md:w-[45%] md:pr-12 lg:pr-20 pt-0 md:pt-24 xl:pt-32"
        data-aos="fade-up"
      >
        <h3 class="text-lg md:text-[20px] font-bold uppercase mb-6">
          {!! $factory->process_title ?? 'QUY TRÌNH <br class="md:hidden">"KHOA HỌC - NGĂN NẮP - TÁCH BIỆT"' !!}
        </h3>
        <div class="rich-text-content text-justify">
          {!! $factory->process_description !!}
        </div>
      </div>

      <!-- Right col: Image Slider -->
      <div
        class="w-full md:w-[55%] relative md:pl-16 lg:pl-24 mt-16 md:mt-0"
        data-aos="fade-up"
        data-aos-delay="100"
      >
        <!-- Navigation Buttons placed above the image aligned right -->
        <div class="justify-end gap-3 mb-6 relative z-20 hidden md:flex">
          <button
            class="section3-prev w-10 h-10 border border-black/20 rounded-full flex items-center justify-center hover:bg-black/5 transition-all focus:outline-none"
          >
            <svg
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
          </button>
          <button
            class="section3-next w-10 h-10 border border-black/20 rounded-full flex items-center justify-center hover:bg-black/5 transition-all focus:outline-none"
          >
            <svg
              width="18"
              height="18"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.5"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
          </button>
        </div>

        <!-- Swiper -->
        <div class="swiper section3-swiper overflow-hidden shadow-lg">
          <div class="swiper-wrapper">
            @if(!empty($processSlider))
              @foreach($processSlider as $image)
                <div class="swiper-slide w-full">
                  <div
                    class="aspect-[3/4] md:aspect-[4/5] object-cover bg-neutral-1"
                  >
                    <img
                      src="{{ asset('storage/' . $image) }}"
                      alt="Khu vực nhà xưởng"
                      class="w-full h-full object-cover transform transition-transform duration-700 hover:scale-105"
                    />
                  </div>
                </div>
              @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Row (Sức mạnh) -->
    <div class="flex flex-col md:flex-row mt-10 md:mt-20">
      <!-- Empty Left -->
      <div class="hidden md:block md:w-[25%]"></div>
      <!-- Text Right -->
      <div
        class="w-full md:w-[75%] md:pl-16 lg:pl-24 md:pr-12 lg:pr-20"
        data-aos="fade-up"
        data-aos-delay="200"
      >
        <h3 class="text-[15px] md:text-base font-bold uppercase mb-8 md:mb-4">
          {!! $factory->process_bottom_title ?? 'SỨC MẠNH CỦA SỰ KẾT HỢP: <br class="md:hidden" />MÁY MÓC HIỆN ĐẠI & BÀN TAY NGHỆ NHÂN' !!}
        </h3>
        <div class="rich-text-content">
          {!! $factory->process_bottom_desc !!}
        </div>
      </div>
    </div>

    <!-- Bottom Image -->
    <div
      class="w-full md:w-[85%] pt-16 lg:pt-20"
      data-aos="fade-up"
      data-aos-delay="200"
    >
      @if(!empty($factory->process_bottom_image))
        <div class="aspect-[2/1] object-cover bg-neutral-1">
          <img
            src="{{ asset('storage/' . $factory->process_bottom_image) }}"
            alt="Gốm sứ Thanh Hải"
            class="w-full h-full object-cover"
          />
        </div>
      @endif
    </div>
  </div>
</section>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    if (typeof Swiper !== "undefined") {
      new Swiper(".section3-swiper", {
        slidesPerView: 1,
        spaceBetween: 24,
        navigation: {
          nextEl: ".section3-next",
          prevEl: ".section3-prev",
        },
        effect: "fade",
        fadeEffect: {
          crossFade: true,
        },
        loop: true,
        autoplay: {
          delay: 4000,
          disableOnInteraction: false,
        },
      });
    }
  });
</script>
@endpush