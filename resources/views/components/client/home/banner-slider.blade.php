@props([
    'trangChu' => null,
    'ngoiAmDuongs' => null,
    'ngoiHais' => null,
    'gachHoas' => null,
    'about' => null,
    'factory' => null,
    'showroomImages' => null,
    'showroomContent' => null,
    'news' => null,
    'article' => null,
    'articles' => null,
    'relatedArticles' => null,
    'historyArticles' => null,
    'projects' => null,
    'project' => null,
    'relatedProjects' => null,
    'categories' => null,
    'selectedCategory' => null,
    'currentCategory' => null,
    'config' => null,
    'products' => null,
    'relatedProducts' => null,
    'product' => null,
    'colors' => null,
    'dinhMuc' => null,
    'giaTriVuotTroi' => null,
    'parentConfig' => null,
    'pageLabel' => null,
    'indexRouteName' => null,
    'categoryType' => null,
    'categoryLabel' => null,
    'denGomProducts' => null,
    'denSuProducts' => null,
    'featuredProducts' => null,
    'collectionProducts' => null,
    'ngheProducts' => null,
    'linhVatProducts' => null,
    'bgImage' => null,
    'activeOrder' => false,
    'activeAccount' => false,
    'activeCatalog' => false,
    'activeGuide' => false,
    'activeProcess' => false,
    'activePrivacy' => false,
    'activeReturn' => false,
    'activeShipping' => false,
    'image' => null,
    'label1' => null,
    'rate1' => null,
    'label2' => null,
    'rate2' => null,
    'sectionId' => null,
    'sectionClass' => null,
    'sectionTitle' => null,
    'desktopLinkHref' => null,
    'detailRouteName' => null,
    'wrapperClass' => null,
    'titleClass' => null,
    'title' => null,
    'subtitle' => null,
    'description' => null,
    'items' => null,])
<section
  class="banner-section relative bg-primary overflow-hidden pt-[58px] md:pt-[154px]"
>
  <!-- Decorative background images -->
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <!-- Left decorative element (mirrored) -->
    <img
      src="{{ asset('assets/images/background-decorate.svg') }}"
      alt=""
      class="banner-decor-left absolute top-[44px] lg:top-auto right-[182px] lg:right-auto xl:left-[160px] bottom-0 lg:-bottom-[275px] lg:h-full object-contain opacity-60"
      style="
        transform: scaleX(-1);
        max-width: 440px;
        clip-path: inset(0 1px 43% 0);
      "
    />
    <!-- Right decorative element -->
    <img
      src="{{ asset('assets/images/background-decorate.svg') }}"
      alt=""
      class="banner-decor-right w-[312px] absolute top-[234px] left-[203px] lg:left-auto xl:right-[50px] lg:w-auto lg:-top-[85px] lg:h-full object-contain opacity-60"
      style="max-width: 700px"
    />
  </div>
  <!-- Carousel container centered -->
  <div
    class="banner-carousel relative w-[85%] max-w-[1320px] mx-auto h-[488px] md:h-full lg:rounded-[4px] lg:overflow-hidden lg:shadow-[0px_4px_4px_0px_rgba(0,0,0,0.25)] z-10"
  >
    @if($trangChu && !empty($trangChu->banner))
      @foreach($trangChu->banner as $index => $bg)
      <div
        class="banner-slide {{ $index === 0 ? 'active' : '' }} absolute inset-0 transition-opacity duration-500"
      >
        <img
          src="{{ Str::startsWith($bg, 'assets/') ? asset($bg) : asset('storage/' . $bg) }}"
          alt="Banner {{ $index + 1 }}"
          class="w-full h-full object-cover"
        />
        <div
          class="absolute inset-0 flex items-end justify-center pb-[29px] md:pb-16 lg:pb-[67px]"
        >
          <a href="#" class="banner-cta-btn"> XEM CHI TIẾT </a>
        </div>
      </div>
      @endforeach
    @endif
  </div>

  <!-- Banner Navigation & Dots -->
  <div
    class="relative z-50 w-[85%] max-w-[1320px] mx-auto mt-[25px] md:mt-6 lg:mt-[50px] flex items-center justify-center md:justify-between"
  >
    <!-- Prev Button (desktop/tablet only) -->
    <button
      class="banner-prev relative z-50 hidden md:flex w-10 h-10 border border-white/50 lg:border-neutral-1 rounded-full items-center justify-center text-white/70 lg:text-neutral-1 hover:border-white hover:text-white lg:hover:bg-white/10 transition-all"
      aria-label="Previous slide"
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

    <div class="banner-dots relative z-50 flex items-center gap-[7px] md:gap-3">
      @if($trangChu && !empty($trangChu->banner))
        @foreach($trangChu->banner as $index => $bg)
        <button
          class="banner-dot {{ $index === 0 ? 'active' : '' }} w-2 h-2 md:w-3 md:h-3 rounded-full {{ $index === 0 ? 'bg-secondary' : 'bg-white/40 hover:bg-white/60' }} transition-all"
          data-slide="{{ $index }}"
          aria-label="Slide {{ $index + 1 }}"
        ></button>
        @endforeach
      @endif
    </div>

    <!-- Next Button (desktop/tablet only) -->
    <button
      class="banner-next relative z-50 hidden md:flex w-10 h-10 border border-white/50 lg:border-neutral-1 rounded-full items-center justify-center text-white/70 lg:text-neutral-1 hover:border-white hover:text-white lg:hover:bg-white/10 transition-all"
      aria-label="Next slide"
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
  </div>
</section>

@push('styles')
<style>
  .banner-section {
    height: 100%;
  }

  @media (min-width: 768px) {
    .banner-section {
      height: 680px;
      padding: 120px 0;
    }
  }

  @media (min-width: 1024px) {
    .banner-section {
      height: 885px;
      padding: 160px 0 100px;
    }
  }

  .banner-carousel {
    width: 75%;
    animation: banner-slide-in 0.3s ease-in-out;
  }

  @media (min-width: 1024px) {
    .banner-carousel {
      width: 100%;
    }
  }

  .banner-slide {
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
  }

  .banner-slide.active,
  .banner-slide.opacity-100 {
    opacity: 1;
  }

  @keyframes banner-slide-in {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }

  .banner-cta-btn {
    font-family: "Archivo", sans-serif;
    font-style: normal;
    display: inline-block;
    padding: 5px 20px;
    border: 0.5px solid rgba(255, 255, 255, 0.85);
    color: #fff;
    font-weight: 600;
    font-size: 10px;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    text-align: center;
    transition: all 0.3s ease;
    border-radius: 4px;
  }

  @media (min-width: 768px) {
    .banner-cta-btn {
      font-family: inherit;
      padding: 12px 36px;
      border: 1.5px solid rgba(255, 255, 255, 0.85);
      font-size: 14px;
    }
  }

  @media (min-width: 1024px) {
    .banner-cta-btn {
      font-family: "Archivo", sans-serif;
      font-weight: 700;
      font-size: 16px;
      line-height: 25px;
      padding: 10px 23px;
      width: 200px;
      border: 1px solid #efe4de;
      color: #fffaf3;
      border-radius: 4px;
    }
  }

  .banner-cta-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: #fff;
  }

  @media (min-width: 1024px) {
    .banner-cta-btn:hover {
      background: rgba(239, 228, 222, 0.1);
      border-color: #ffffff;
      color: #ffffff;
    }
  }

  .banner-dot {
    background-color: rgba(255, 255, 255, 0.4);
    transition: all 0.3s ease;
  }

  .banner-dot:hover {
    background-color: rgba(255, 255, 255, 0.6);
  }

  .banner-dot.active {
    background-color: #c76e00 !important;
  }

  @media (min-width: 1024px) {
    .banner-dot {
      background-color: rgba(239, 228, 222, 0.4); /* #EFE4DE / neutral-1 with 40% opacity */
    }

    .banner-dot:hover {
      background-color: rgba(239, 228, 222, 0.7);
    }
    
    .banner-dot.bg-white\/40 {
      background-color: rgba(239, 228, 222, 0.4) !important;
    }
  }

  @media (min-width: 1280px) {
    .banner-decor-left {
      left: 12px !important;
      top: 524px !important;
      width: 438px !important;
      height: 271px !important;
      bottom: auto !important;
      right: auto !important;
      opacity: 0.5 !important;
      clip-path: none !important;
    }

    .banner-decor-right {
      right: -114px !important;
      top: 38px !important;
      width: 697px !important;
      height: 674px !important;
      left: auto !important;
      bottom: auto !important;
      opacity: 0.5 !important;
    }
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const bannerSection = document.querySelector(".banner-section");
    if (!bannerSection) return;

    const bannerSlides = Array.from(
      bannerSection.querySelectorAll(".banner-slide"),
    );
    const bannerDots = Array.from(
      bannerSection.querySelectorAll(".banner-dot"),
    );
    const bannerNext = bannerSection.querySelector(".banner-next");
    const bannerPrev = bannerSection.querySelector(".banner-prev");

    if (!bannerSlides.length) return;

    let bannerCurrentSlide = 0;
    const totalBannerSlides = bannerSlides.length;

    const showBannerSlide = (index) => {
      bannerSlides.forEach((slide, slideIndex) => {
        const isActive = slideIndex === index;
        slide.classList.toggle("active", isActive);
        slide.style.opacity = isActive ? "1" : "0";
        slide.style.pointerEvents = isActive ? "auto" : "none";
      });

      bannerDots.forEach((dot, dotIndex) => {
        const isActive = dotIndex === index;
        dot.classList.toggle("active", isActive);
        dot.classList.toggle("bg-secondary", isActive);
        dot.classList.toggle("bg-white/40", !isActive);
      });
    };

    const nextBannerSlide = () => {
      bannerCurrentSlide = (bannerCurrentSlide + 1) % totalBannerSlides;
      showBannerSlide(bannerCurrentSlide);
    };

    const prevBannerSlide = () => {
      bannerCurrentSlide =
        (bannerCurrentSlide - 1 + totalBannerSlides) % totalBannerSlides;
      showBannerSlide(bannerCurrentSlide);
    };

    bannerNext?.addEventListener("click", nextBannerSlide);
    bannerPrev?.addEventListener("click", prevBannerSlide);

    bannerDots.forEach((dot) => {
      dot.addEventListener("click", () => {
        const targetIndex = Number.parseInt(dot.dataset.slide || "0", 10);
        if (Number.isNaN(targetIndex)) return;
        bannerCurrentSlide = targetIndex;
        showBannerSlide(bannerCurrentSlide);
      });
    });

    showBannerSlide(bannerCurrentSlide);
    if (totalBannerSlides > 1) {
      window.setInterval(nextBannerSlide, 5000);
    }
  });
</script>
@endpush
