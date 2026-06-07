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
<section class="bg-primary pt-[20px] lg:pt-20">
  <div class="w-[85%] max-w-[1320px] mx-auto mb-[20px] md:mb-12">
    <div class="flex items-center" data-aos="fade-up">
      <h2 class="text-secondary text-2xl lg:text-4xl font-bold uppercase">
        Công trình ấn tượng
      </h2>
    </div>
  </div>

  <!-- Works Carousel Container: Full width to allow bleed-out -->
  <div class="works-carousel-container relative" data-aos="fade-up" data-aos-delay="200">
    <div class="works-carousel overflow-x-auto scrollbar-hide">
      <!-- padding-left calculated to match the 1320px container's left edge -->
      <div class="flex" style="padding-left: max(7.5%, calc((100% - 1320px) / 2))">
        <div class="works-track flex gap-[10px] lg:gap-6 pr-12">
          @foreach($projects as $project)
            @php
              $firstImage = !empty($project->images) ? $project->images[0] : null;
            @endphp
          <a href="{{ route('client.projects.detail', $project->slug) }}"
            class="works-item flex-shrink-0 w-[151px] lg:w-96 h-[125px] lg:h-80 rounded-sm overflow-hidden group cursor-pointer relative">
            @if($firstImage)
            <img
              src="{{ Str::startsWith($firstImage, 'assets/') ? asset($firstImage) : asset('storage/' . $firstImage) }}"
              alt="{{ $project->ten_du_an }}"
              class="w-full h-full object-cover"
            />
            @endif
            <div
              class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-2 md:p-4 pt-12">
              <p class="works-item-title text-white">
                {{ $project->ten_du_an }}
              </p>
            </div>
          </a>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <!-- Controls Container: Gói gọn trong 1320px -->
  <div
    class="w-[85%] max-w-[1320px] mx-auto mt-[11px] md:mt-12 flex flex-col items-center lg:items-end justify-between gap-8 lg:gap-12">
    <!-- Custom scrollbar -->
    <div class="flex-grow w-full">
      <div class="works-scrollbar-track">
        <div class="works-scrollbar-thumb"></div>
      </div>
    </div>
  </div>
</section>

@push('styles')
<style>
  .works-carousel {
    scroll-behavior: smooth;
    scrollbar-width: none;
    -ms-overflow-style: none;
    cursor: grab;
    touch-action: pan-y;
    user-select: none;
  }

  .works-carousel::-webkit-scrollbar {
    display: none;
  }

  .works-carousel.is-dragging {
    cursor: grabbing;
    scroll-behavior: auto;
  }

  .works-scrollbar-track {
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
    position: relative;
  }

  .works-scrollbar-thumb {
    height: 100%;
    background: #c76e00;
    border-radius: 2px;
    min-width: 40px;
    position: absolute;
    top: 0;
    left: 0;
    transition: width 0.1s ease;
    cursor: pointer;
    touch-action: none;
  }

  .works-scrollbar-thumb.is-dragging {
    transition: none;
  }

  .works-item-title {
    text-align: center;
    font-family: Archivo, sans-serif;
    font-size: 10px;
    font-style: normal;
    font-weight: 600;
  }

  @media (min-width: 1024px) {
    .works-item-title {
      font-size: 18px;
    }
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const worksSection = document
      .querySelector(".works-carousel-container")
      ?.closest("section");
    if (!worksSection) return;

    const worksCarousel = worksSection.querySelector(".works-carousel");
    const worksTrack = worksSection.querySelector(".works-track");
    const scrollbarThumb = worksSection.querySelector(".works-scrollbar-thumb");
    const scrollbarTrack = worksSection.querySelector(".works-scrollbar-track");

    if (!worksCarousel || !worksTrack) return;

    const updateScrollbar = () => {
      if (!scrollbarThumb || !scrollbarTrack) return;

      const scrollWidth = worksCarousel.scrollWidth;
      const clientWidth = worksCarousel.clientWidth;
      const scrollLeft = worksCarousel.scrollLeft;
      const trackWidth = scrollbarTrack.clientWidth;

      if (scrollWidth <= clientWidth || trackWidth <= 0) {
        scrollbarThumb.style.width = "100%";
        scrollbarThumb.style.left = "0";
        return;
      }

      const thumbWidth = Math.max((clientWidth / scrollWidth) * trackWidth, 40);
      const thumbLeft =
        (scrollLeft / (scrollWidth - clientWidth)) * (trackWidth - thumbWidth);

      scrollbarThumb.style.width = `${thumbWidth}px`;
      scrollbarThumb.style.left = `${thumbLeft}px`;
    };

    let carouselPointerId = null;
    let carouselStartX = 0;
    let carouselStartScrollLeft = 0;
    let carouselDidDrag = false;

    const endCarouselDrag = () => {
      if (carouselPointerId === null) return;

      worksCarousel.classList.remove("is-dragging");
      carouselPointerId = null;

      window.setTimeout(() => {
        carouselDidDrag = false;
      }, 0);
    };

    worksCarousel.addEventListener("pointerdown", (event) => {
      if (event.button !== undefined && event.button !== 0) return;

      carouselPointerId = event.pointerId;
      carouselStartX = event.clientX;
      carouselStartScrollLeft = worksCarousel.scrollLeft;
      carouselDidDrag = false;
      worksCarousel.classList.add("is-dragging");
      worksCarousel.setPointerCapture?.(event.pointerId);
    });

    worksCarousel.addEventListener("pointermove", (event) => {
      if (carouselPointerId !== event.pointerId) return;

      const deltaX = event.clientX - carouselStartX;
      if (Math.abs(deltaX) > 4) {
        carouselDidDrag = true;
      }

      worksCarousel.scrollLeft = carouselStartScrollLeft - deltaX;
    });

    worksCarousel.addEventListener("pointerup", endCarouselDrag);
    worksCarousel.addEventListener("pointercancel", endCarouselDrag);
    worksCarousel.addEventListener("pointerleave", endCarouselDrag);

    worksCarousel.addEventListener(
      "click",
      (event) => {
        if (!carouselDidDrag) return;

        event.preventDefault();
        event.stopPropagation();
      },
      true,
    );

    if (scrollbarThumb && scrollbarTrack) {
      let thumbPointerId = null;
      let thumbStartX = 0;
      let thumbStartScrollLeft = 0;

      const endThumbDrag = () => {
        if (thumbPointerId === null) return;

        scrollbarThumb.classList.remove("is-dragging");
        thumbPointerId = null;
        document.body.style.userSelect = "";
      };

      scrollbarThumb.addEventListener("pointerdown", (event) => {
        if (event.button !== undefined && event.button !== 0) return;

        event.preventDefault();
        event.stopPropagation();

        thumbPointerId = event.pointerId;
        thumbStartX = event.clientX;
        thumbStartScrollLeft = worksCarousel.scrollLeft;
        scrollbarThumb.classList.add("is-dragging");
        scrollbarThumb.setPointerCapture?.(event.pointerId);
        document.body.style.userSelect = "none";
      });

      scrollbarThumb.addEventListener("pointermove", (event) => {
        if (thumbPointerId !== event.pointerId) return;

        const scrollableWidth = worksCarousel.scrollWidth - worksCarousel.clientWidth;
        const thumbWidth = scrollbarThumb.offsetWidth;
        const draggableWidth = scrollbarTrack.clientWidth - thumbWidth;

        if (scrollableWidth <= 0 || draggableWidth <= 0) return;

        const deltaX = event.clientX - thumbStartX;
        worksCarousel.scrollLeft =
          thumbStartScrollLeft + (deltaX / draggableWidth) * scrollableWidth;
      });

      scrollbarThumb.addEventListener("pointerup", endThumbDrag);
      scrollbarThumb.addEventListener("pointercancel", endThumbDrag);
      scrollbarThumb.addEventListener("lostpointercapture", endThumbDrag);
    }

    worksCarousel.addEventListener("scroll", updateScrollbar);
    window.addEventListener("resize", updateScrollbar);
    updateScrollbar();
  });
</script>
@endpush
