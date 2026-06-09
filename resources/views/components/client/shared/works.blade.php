@php
  $fallbackImage = 'assets/images/work-01.jpg';
  $worksData = $works->map(function ($work) use ($fallbackImage) {
      $firstImage = collect($work->images ?? [])->first();

      return [
          'title' => mb_strtoupper($work->ten_du_an ?? '', 'UTF-8'),
          'location' => $work->dia_diem ?? '',
          'product' => $work->san_pham ?? '',
          'link' => route('client.projects.detail', $work->slug),
          'image' => \App\Support\AssetPath::url($firstImage, $fallbackImage),
      ];
  })->values();

  $count = count($worksData);
  // Clone 3 items at the end to prepend
  $prepends = $count >= 3 
      ? [$worksData[$count - 3], $worksData[$count - 2], $worksData[$count - 1]] 
      : $worksData->toArray();
  
  // Clone 3 items at the beginning to append
  $appends = $count >= 3 
      ? [$worksData[0], $worksData[1], $worksData[2]] 
      : $worksData->toArray();
@endphp

<section
  class="w-full bg-background-secondary pb-12 md:pb-16 relative overflow-hidden animate-fade-in-up xl:min-h-[760px]"
  data-aos="fade-up"
>
  @push('styles')
  <style>
    .works-viewport {
      width: 100%;
      overflow: hidden;
      position: relative;
    }

    .works-track {
      display: flex;
      align-items: flex-end;
      gap: 16px;
      will-change: transform;
      transform: translate3d(0, 0, 0);
      touch-action: pan-y;
      user-select: none;
      -webkit-user-select: none;
    }

    .works-slide {
      width: 85vw;
      aspect-ratio: 4 / 3;
      opacity: 0.55;
      cursor: pointer;
      overflow: hidden;
      border-radius: 4px;
      transition: opacity 400ms cubic-bezier(0.25, 1, 0.5, 1);
      will-change: opacity;
      transform: translate3d(0, 0, 0);
      backface-visibility: hidden;
      -webkit-backface-visibility: hidden;
    }

    .works-slide.active {
      opacity: 1;
    }

    .slide-text-transition {
      transition-property: opacity, transform !important;
      transition-duration: 300ms !important;
      transition-timing-function: ease-in-out !important;
      will-change: opacity, transform;
    }

    @media (min-width: 1280px) {
      .works-viewport {
        height: 545px;
      }

      .works-track {
        height: 545px;
        gap: 30px;
      }

      .works-slide {
        width: 370px !important;
        height: 265px !important;
        aspect-ratio: auto !important;
        opacity: 1;
        transition: width 400ms cubic-bezier(0.25, 1, 0.5, 1), height 400ms cubic-bezier(0.25, 1, 0.5, 1);
        will-change: width, height;
      }

      .works-slide.active {
        width: 721px !important;
        height: 545px !important;
      }
    }
  </style>
  @endpush

  <div class="text-center mb-8 md:mb-16">
    <h2
      class="text-[20px] md:text-[32px] md:leading-[45px] font-archivo font-semibold text-secondary uppercase drop-shadow-sm"
    >
      Dấu ấn trên những công trình
    </h2>
  </div>

  <div class="works-container w-full relative select-none">
    <!-- Viewport that clips the horizontal strip -->
    <div class="works-viewport">
      <!-- Horizontal track containing all slides -->
      <div class="works-track">
        <!-- Prepended Clones -->
        @foreach ($prepends as $item)
          <div class="works-slide flex-shrink-0" data-original-title="{{ $item['title'] }}" data-original-location="{{ $item['location'] }}" data-original-product="{{ $item['product'] }}" data-original-link="{{ $item['link'] }}">
            <img src="{{ $item['image'] }}" class="w-full h-full object-cover pointer-events-none" loading="eager" />
          </div>
        @endforeach
        
        <!-- Original Slides -->
        @foreach ($worksData as $index => $item)
          <div class="works-slide flex-shrink-0" data-index="{{ $index }}" data-original-title="{{ $item['title'] }}" data-original-location="{{ $item['location'] }}" data-original-product="{{ $item['product'] }}" data-original-link="{{ $item['link'] }}">
            <img src="{{ $item['image'] }}" class="w-full h-full object-cover pointer-events-none" loading="eager" />
          </div>
        @endforeach
        
        <!-- Appended Clones -->
        @foreach ($appends as $item)
          <div class="works-slide flex-shrink-0" data-original-title="{{ $item['title'] }}" data-original-location="{{ $item['location'] }}" data-original-product="{{ $item['product'] }}" data-original-link="{{ $item['link'] }}">
            <img src="{{ $item['image'] }}" class="w-full h-full object-cover pointer-events-none" loading="eager" />
          </div>
        @endforeach
      </div>
    </div>

    <!-- Desktop Info Block (hidden on mobile, absolute positioned on desktop) -->
    <div class="works-info-block hidden xl:flex flex-col justify-start absolute top-0 z-20 pointer-events-auto" style="width: 370px; height: 214px;">
      <div class="flex justify-between items-center w-full h-[45px]">
        <h3
          id="slide-title"
          class="text-[16px] leading-[45px] font-archivo font-semibold text-[#2E2F2A] uppercase tracking-wide slide-text-transition"
        >
          {{ data_get($worksData->first(), 'title', 'Dự án') }}
        </h3>
        <!-- Navigation arrows inline with active project title -->
        <div class="flex items-center gap-4">
          <button type="button" class="works-prev text-[24px] leading-none text-[#2E2F2A] hover:text-secondary transition-colors cursor-pointer select-none">←</button>
          <button type="button" class="works-next text-[24px] leading-none text-[#2E2F2A] hover:text-secondary transition-colors cursor-pointer select-none">→</button>
        </div>
      </div>

      <div
        class="text-[14px] leading-[20px] text-[#2E2F2A] slide-text-transition flex flex-col gap-1 mt-3"
        id="slide-meta"
      >
        <p class="font-archivo">
          <span class="font-semibold">Địa điểm:</span>
          <span id="slide-location" class="font-normal">{{ data_get($worksData->first(), 'location', '') }}</span>
        </p>
        <p class="font-archivo">
          <span class="font-semibold">Sản phẩm:</span>
          <span id="slide-product" class="font-normal">{{ data_get($worksData->first(), 'product', '') }}</span>
        </p>
      </div>

      <a
        href="{{ data_get($worksData->first(), 'link', '#') }}"
        id="slide-link"
        class="font-archivo font-semibold text-[16px] text-[#2E2F2A] underline hover:text-secondary transition-colors leading-[1.5] slide-text-transition block w-fit mt-[84px]"
      >See more</a>
    </div>

    <!-- Mobile Info Block (hidden on desktop) -->
    <div class="works-info-block-mobile xl:hidden w-full px-4 mt-6">
      <div class="w-full bg-transparent pt-4">
        <div class="flex justify-between items-start pb-4 border-b border-black/10">
          <h3 id="mobile-slide-title" class="text-base font-bold uppercase tracking-wide slide-text-transition">
            {{ data_get($worksData->first(), 'title', 'Dự án') }}
          </h3>
          <div class="flex items-center gap-4">
            <button type="button" class="works-prev-mobile text-[24px] leading-none text-[#2E2F2A] hover:text-secondary transition-colors cursor-pointer select-none">←</button>
            <button type="button" class="works-next-mobile text-[24px] leading-none text-[#2E2F2A] hover:text-secondary transition-colors cursor-pointer select-none">→</button>
          </div>
        </div>

        <div class="space-y-3 text-[15px] mt-[13px] mb-[15px] slide-text-transition" id="mobile-slide-meta">
          <p>
            <span class="font-bold">Địa điểm:</span>
            <span id="mobile-slide-location">{{ data_get($worksData->first(), 'location', '') }}</span>
          </p>
          <p>
            <span class="font-bold">Sản phẩm:</span>
            <span id="mobile-slide-product">{{ data_get($worksData->first(), 'product', '') }}</span>
          </p>
        </div>

        <a
          href="{{ data_get($worksData->first(), 'link', '#') }}"
          id="mobile-slide-link"
          class="font-bold text-black border-b-[1.5px] border-black inline-block hover:text-secondary hover:border-secondary tracking-[0.05em] text-[13px] slide-text-transition"
        >See more</a>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var track = document.querySelector(".works-track");
    var slides = document.querySelectorAll(".works-slide");
    var infoBlock = document.querySelector(".works-info-block");

    if (!track || slides.length === 0) return;

    var worksData = @json($worksData);
    var originalCount = worksData.length;
    var prependsCount = @json(count($prepends));
    
    // Active track index (starts at prependsCount, representing original index 0)
    var activeIndex = prependsCount;
    var isTransitioning = false;

    // Get current screen mode
    function getIsDesktop() {
      return window.innerWidth >= 1280;
    }

    // Force complete any ongoing transition and snap to final state
    function forceCompleteTransition() {
      if (!isTransitioning) return;
      isTransitioning = false;

      // Wrap around prepended clones
      if (activeIndex < prependsCount) {
        activeIndex = activeIndex + originalCount;
      }
      // Wrap around appended clones
      else if (activeIndex >= prependsCount + originalCount) {
        activeIndex = activeIndex - originalCount;
      }
      updateSlider(false); // jump instantly without animation
    }

    // Update dimensions and translation
    function updateSlider(useTransition) {
      var isDesktop = getIsDesktop();
      
      // Clear active class from all slides
      slides.forEach(function (slide, idx) {
        if (idx === activeIndex) {
          slide.classList.add("active");
        } else {
          slide.classList.remove("active");
        }
      });

      // Spacing details
      var slideWidth = isDesktop ? 370 : window.innerWidth * 0.85;
      var activeWidth = isDesktop ? 721 : window.innerWidth * 0.85;
      var gap = isDesktop ? 30 : 16;
      var containerWidth = 1320;

      // Target left edge of active slide
      var L = isDesktop 
        ? Math.max(24, (window.innerWidth - containerWidth) / 2) 
        : (window.innerWidth - activeWidth) / 2;

      // Calculate track translation
      var leftOffset = activeIndex * (slideWidth + gap);
      var translation = L - leftOffset;

      // Position the Desktop Info Block
      if (isDesktop && infoBlock) {
        infoBlock.style.left = (L + activeWidth + gap) + "px";
      }

      // Apply transform with or without transition
      if (useTransition) {
        track.style.transition = "transform 400ms cubic-bezier(0.25, 1, 0.5, 1)";
      } else {
        track.style.transition = "none";
      }

      track.style.transform = "translate3d(" + translation + "px, 0px, 0px)";

      // Update text block details
      var originalIndex = (activeIndex - prependsCount + originalCount) % originalCount;
      updateTextBlock(originalIndex);
    }

    // Slide transition handler
    function goToSlide(index, useTransition) {
      if (useTransition) {
        forceCompleteTransition();
        isTransitioning = true;
      }
      activeIndex = index;
      updateSlider(useTransition);
    }

    // Next / Prev actions
    function nextSlide() {
      forceCompleteTransition();
      goToSlide(activeIndex + 1, true);
    }

    function prevSlide() {
      forceCompleteTransition();
      goToSlide(activeIndex - 1, true);
    }

    // Text update helper
    var titleEl = document.getElementById("slide-title");
    var locationEl = document.getElementById("slide-location");
    var productEl = document.getElementById("slide-product");
    var linkEl = document.getElementById("slide-link");
    var metaEl = document.getElementById("slide-meta");

    var mobileTitleEl = document.getElementById("mobile-slide-title");
    var mobileLocationEl = document.getElementById("mobile-slide-location");
    var mobileProductEl = document.getElementById("mobile-slide-product");
    var mobileLinkEl = document.getElementById("mobile-slide-link");
    var mobileMetaEl = document.getElementById("mobile-slide-meta");

    var textRevealTimeout;

    function setTextVisibility(isVisible) {
      var opacity = isVisible ? "1" : "0";
      var transform = isVisible ? "translateY(0)" : "translateY(12px)";
      
      var els = [titleEl, metaEl, linkEl, mobileTitleEl, mobileMetaEl, mobileLinkEl];
      els.forEach(function (el) {
        if (el) {
          el.style.opacity = opacity;
          el.style.transform = transform;
        }
      });
    }

    function updateTextBlock(originalIndex) {
      var currentData = worksData[originalIndex];
      if (!currentData) return;

      setTextVisibility(false);
      if (textRevealTimeout) clearTimeout(textRevealTimeout);
      
      textRevealTimeout = setTimeout(function () {
        if (titleEl) titleEl.textContent = currentData.title || "";
        if (locationEl) locationEl.textContent = currentData.location || "";
        if (productEl) productEl.textContent = currentData.product || "";
        if (linkEl) linkEl.href = currentData.link || "#";

        if (mobileTitleEl) mobileTitleEl.textContent = currentData.title || "";
        if (mobileLocationEl) mobileLocationEl.textContent = currentData.location || "";
        if (mobileProductEl) mobileProductEl.textContent = currentData.product || "";
        if (mobileLinkEl) mobileLinkEl.href = currentData.link || "#";

        requestAnimationFrame(function () {
          setTextVisibility(true);
        });
      }, 150);
    }

    // Transition end loop jump handler
    track.addEventListener("transitionend", function (e) {
      if (e.target !== track) return;
      isTransitioning = false;

      // Wrap around prepended clones
      if (activeIndex < prependsCount) {
        activeIndex = activeIndex + originalCount;
        updateSlider(false); // jump without animation
      }
      // Wrap around appended clones
      else if (activeIndex >= prependsCount + originalCount) {
        activeIndex = activeIndex - originalCount;
        updateSlider(false); // jump without animation
      }
    });

    // Navigation triggers
    var prevBtns = document.querySelectorAll(".works-prev, .works-prev-mobile");
    var nextBtns = document.querySelectorAll(".works-next, .works-next-mobile");

    prevBtns.forEach(function (btn) {
      btn.addEventListener("click", function(e) {
        e.preventDefault();
        prevSlide();
      });
    });

    nextBtns.forEach(function (btn) {
      btn.addEventListener("click", function(e) {
        e.preventDefault();
        nextSlide();
      });
    });

    // Drag/swipe state
    var startX = 0;
    var currentX = 0;
    var isDragging = false;
    var wasDragged = false;
    var dragThreshold = 10;
    var pointerId = null;

    // Slide clicks
    slides.forEach(function (slide, idx) {
      slide.addEventListener("click", function (e) {
        if (wasDragged) {
          e.preventDefault();
          e.stopPropagation();
          return;
        }
        goToSlide(idx, true);
      });
    });

    // Window resize handling
    window.addEventListener("resize", function () {
      updateSlider(false);
    });

    // Pointer events for drag/swipe on both desktop and mobile
    track.addEventListener("pointerdown", function (e) {
      if (e.button !== undefined && e.button !== 0) return;
      forceCompleteTransition();
      startX = e.clientX;
      currentX = e.clientX;
      isDragging = true;
      wasDragged = false;
      pointerId = e.pointerId;
      track.style.transition = "none";
    });

    window.addEventListener("pointermove", function (e) {
      if (!isDragging || pointerId !== e.pointerId) return;
      currentX = e.clientX;
      var diffX = currentX - startX;
      
      if (Math.abs(diffX) > dragThreshold) {
        wasDragged = true;
      }
      
      var isDesktop = getIsDesktop();
      var slideWidth = isDesktop ? 370 : window.innerWidth * 0.85;
      var activeWidth = isDesktop ? 721 : window.innerWidth * 0.85;
      var gap = isDesktop ? 30 : 16;
      var containerWidth = 1320;

      // Target left edge of active slide
      var L = isDesktop 
        ? Math.max(24, (window.innerWidth - containerWidth) / 2) 
        : (window.innerWidth - activeWidth) / 2;
        
      var leftOffset = activeIndex * (slideWidth + gap);
      var baseTranslation = L - leftOffset;
      
      track.style.transform = "translate3d(" + (baseTranslation + diffX) + "px, 0, 0)";
    });

    function handleDragEnd(e) {
      if (!isDragging || pointerId !== e.pointerId) return;
      isDragging = false;
      pointerId = null;
      
      var diffX = currentX - startX;
      var isDesktop = getIsDesktop();
      var slideWidth = isDesktop ? 370 : window.innerWidth * 0.85;
      var threshold = slideWidth / 5; // threshold for changing slide

      if (diffX < -threshold) {
        nextSlide();
      } else if (diffX > threshold) {
        prevSlide();
      } else {
        goToSlide(activeIndex, true); // Snap back
      }
      
      // Keep wasDragged true for a tiny delay to prevent slide click trigger
      setTimeout(function () {
        wasDragged = false;
      }, 50);
    }

    window.addEventListener("pointerup", handleDragEnd);
    window.addEventListener("pointercancel", handleDragEnd);

    // Initial setup
    updateSlider(false);
  });
</script>
@endpush
