<section class="bg-primary py-[28px] lg:py-16 relative">
  <div class="w-[85%] mx-auto max-w-[1320px] relative">
    <h2
      class="text-secondary text-2xl lg:text-4xl font-bold uppercase mb-[10px] lg:mb-12"
      data-aos="fade-up"
    >
      Khách hàng & đối tác
    </h2>

    @php
      $partners = collect($trangChu?->khach_hang_doi_tac ?? [])->filter()->values();
      $renderPartners = $partners;

      while ($renderPartners->count() > 0 && $renderPartners->count() < 10) {
          $renderPartners = $renderPartners->merge($partners);
      }
    @endphp

    @if($renderPartners->isNotEmpty())
    <div
      class="relative -mx-[8%] lg:mx-0"
      data-aos="fade-up"
      data-aos-delay="200"
    >
      <div class="swiper partner-swiper overflow-hidden">
        <div class="swiper-wrapper">
          @foreach($renderPartners as $logo)
          <div class="swiper-slide partner-slide">
            <div class="partner-card-item">
              <img
                src="{{ Str::startsWith($logo, 'assets/') ? asset($logo) : asset('storage/' . $logo) }}"
                alt="Partner"
                class="partner-image"
              />
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

    @if($renderPartners->count() > 5)
    <button
      class="partner-prev hidden lg:flex absolute -left-16 top-[60%] -translate-y-1/2 z-20 w-10 h-10 border border-white/50 rounded-full items-center justify-center text-white/70 hover:border-white hover:text-white transition-all focus:outline-none"
      aria-label="Previous partner"
    >
      <svg
        class="w-5 h-5"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M15 19l-7-7 7-7"
        ></path>
      </svg>
    </button>

    <button
      class="partner-next hidden lg:flex absolute -right-16 top-[60%] -translate-y-1/2 z-20 w-10 h-10 border border-white/50 rounded-full items-center justify-center text-white/70 hover:border-white hover:text-white transition-all focus:outline-none"
      aria-label="Next partner"
    >
      <svg
        class="w-5 h-5"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M9 5l7 7-7 7"
        ></path>
      </svg>
    </button>
    @endif
  </div>
</section>

@push('styles')
<style>
  .partner-slide {
    height: auto;
  }

  .partner-card-item {
    width: 100%;
    height: 84px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 12px;
  }

  .partner-image {
    max-width: 110px;
    max-height: 100%;
    object-fit: contain;
  }

  @media (min-width: 1024px) {
    .partner-card-item {
      height: 10rem;
      padding: 0;
    }

    .partner-image {
      max-width: 100%;
    }
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const swiperEl = document.querySelector(".partner-swiper");

    if (!swiperEl || swiperEl.dataset.partnerInitDone === "true") return;
    if (typeof Swiper === "undefined") return;

    const section = swiperEl.closest("section");
    const slideCount = swiperEl.querySelectorAll(".swiper-slide").length;

    new Swiper(swiperEl, {
      loop: slideCount > 5,
      speed: 700,
      grabCursor: true,
      slidesPerView: 2,
      spaceBetween: 24,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      navigation: {
        nextEl: section?.querySelector(".partner-next"),
        prevEl: section?.querySelector(".partner-prev"),
      },
      breakpoints: {
        640: {
          slidesPerView: 3,
        },
        1024: {
          slidesPerView: 5,
          spaceBetween: 24,
        },
      },
    });

    swiperEl.dataset.partnerInitDone = "true";
  });
</script>
@endpush
