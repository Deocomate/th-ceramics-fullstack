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
<!-- Product Collage Carousel -->
<section
  class="relative w-full md:py-16 max-w-[1920px] mx-auto gach-hoa-collage-section"
>
  <!-- Background Decoration (Right/Left corner extending down) -->
  <div
    class="absolute z-[0] right-[-35%] md:right-[-25%] lg:right-[-50%] top-[50%] md:top-[55%] w-[70%] md:w-[70%] lg:w-[85%] pointer-events-none"
    data-aos="fade-up-left"
  >
    <img
      src="{{ asset('assets/images/gach-hoa-decorate.png') }}"
      alt=""
      class="w-full origin-center rotate-[-65deg] scale-x-[-1] opacity-10 drop-shadow-sm"
    />
  </div>
  <div
    class="absolute z-[0] left-[-35%] md:left-[-25%] lg:left-[-30%] -bottom-[200%] md:-bottom-[230%] w-[70%] md:w-[70%] lg:w-[82%] pointer-events-none"
    data-aos="fade-up-right"
  >
    <img
      src="{{ asset('assets/images/gach-hoa-decorate.png') }}"
      alt=""
      class="w-full origin-center rotate-[-55deg] scale-x-[-1] opacity-10 drop-shadow-sm"
    />
  </div>

  <div
    class="w-[85%] md:w-auto mx-auto md:px-4 flex flex-col items-center relative z-10"
    data-aos="fade-up"
  >
    <!-- Swiper Container -->
    <div class="swiper product-collage-swiper w-full pb-0 scale-[1.2]">
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
      @foreach($allGalleryPaths as $imgPath)
      <img src="{{ asset('storage/' . $imgPath) }}" alt="" />
      @endforeach
    </div>

    <!-- Custom Page-Number Navigation -->
    <div
      class="flex items-center justify-between gap-6 md:mt-16 w-full max-w-[1320px] mx-auto text-textPrimary font-bold text-[17px] z-20"
    >
      <!-- Prev Button -->
      <button
        id="collage-btn-prev"
        class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer"
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

      <!-- Page Numbers (rendered by JS) -->
      <div
        id="collage-pagination"
        class="flex items-center gap-5 select-none"
      ></div>

      <!-- Next Button -->
      <button
        id="collage-btn-next"
        class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer"
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
  </div>
</section>

@push('scripts')
<script>
  (function () {
    @php
      $galleryImages = $config->anh ?? collect();
      $allImages = $galleryImages->pluck('image')->filter()->values()->toArray();
      $chunkSize = 8;
      $imageChunks = array_chunk($allImages, $chunkSize);
      $POSITION_STYLES = [
        "left:9%;top:32%;width:12%;height:26%",
        "left:22.5%;top:15%;width:18%;height:38%",
        "left:22.5%;top:55%;width:8%;height:16%",
        "left:31.5%;top:55%;width:9%;height:28%",
        "left:42%;top:18%;width:18%;height:48%",
        "left:61.5%;top:8%;width:12%;height:22%",
        "left:61.5%;top:32%;width:18%;height:40%",
        "left:81%;top:32%;width:12%;height:32%",
      ];
      $SHADOWS = ["shadow-sm","shadow-md","shadow-sm","shadow-sm","shadow-lg focal","shadow-sm","shadow-md","shadow-sm"];
      $ZCLASSES = ["z-20","z-20","z-20","z-20","z-30","z-20","z-20","z-20"];
      $SQUARES = [
        ["color" => "#C0815F", "style" => "left:5.5%;top:52%;width:2.5%;aspect-ratio:1"],
        ["color" => "#5A6A4E", "style" => "left:74.5%;top:24%;width:2.5%;aspect-ratio:1"],
        ["color" => "#C06848", "style" => "left:81%;top:66%;width:2.5%;aspect-ratio:1"],
      ];
      $slidesArray = [];
      foreach ($imageChunks as $chunkIdx => $chunk) {
        $slideImages = [];
        foreach ($chunk as $imgIdx => $imgPath) {
          $imgUrl = asset('storage/' . $imgPath);
          $slideImages[] = [
            "href" => $imgUrl,
            "src" => $imgUrl,
            "alt" => "Image " . ($imgIdx + 1),
            "style" => $POSITION_STYLES[$imgIdx % count($POSITION_STYLES)],
            "shadow" => $SHADOWS[$imgIdx % count($SHADOWS)],
            "zClass" => $ZCLASSES[$imgIdx % count($ZCLASSES)],
          ];
        }
        $slidesArray[] = [
          "gallery" => "gach-hoa-" . ($chunkIdx + 1),
          "images" => $slideImages,
          "squares" => $SQUARES,
        ];
      }
      if (empty($slidesArray)) {
        $slidesArray[] = ["gallery" => "gach-hoa", "images" => [], "squares" => $SQUARES];
      }
    @endphp
    var SLIDES = @json($slidesArray);

    var wrapper = document.getElementById("collage-swiper-wrapper");
    if (!wrapper) return;

    for (var i = 0; i < SLIDES.length; i++) {
      var slide = SLIDES[i];
      var squaresHtml = "";
      for (var s = 0; s < slide.squares.length; s++) {
        var sq = slide.squares[s];
        squaresHtml += '<div class="absolute" style="background:' + sq.color + ";" + sq.style + '"></div>';
      }
      var imagesHtml = "";
      for (var j = 0; j < slide.images.length; j++) {
        var img = slide.images[j];
        var isFocal = img.zClass === "z-30";
        var hoverScale = isFocal ? "hover:scale-[1.02]" : "hover:scale-105";
        var zClass = img.zClass || "z-20";
        imagesHtml += '<a href="' + img.href + '" class="glightbox absolute ' + img.shadow + ' transition-transform ' + hoverScale + ' ' + zClass + '" style="' + img.style + '" data-gallery="' + slide.gallery + '">' + '<img src="' + img.src + '" class="w-full h-full object-cover" alt="' + img.alt + '" />' + "</a>";
      }
      var slideEl = document.createElement("div");
      slideEl.className = "swiper-slide w-full";
      slideEl.innerHTML = '<div class="relative w-full aspect-[4/3] sm:aspect-[16/9] md:aspect-[2.5/1]">' + squaresHtml + imagesHtml + "</div>";
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

      shown.forEach(function (idx) {
        var a = document.createElement("a");
        a.href = "#";
        a.textContent = idx + 1;
        if (idx === activeIdx) {
          a.className = "text-black border-b-[3px] border-black pb-[2px] px-1";
        } else {
          a.className = "text-black/40 hover:text-black transition-colors px-1";
        }
        a.addEventListener("click", function (e) {
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
        la.className = lastIdx === activeIdx ? "text-black border-b-[3px] border-black pb-[2px] px-1" : "text-black/40 hover:text-black transition-colors px-1";
        la.addEventListener("click", function (e) {
          e.preventDefault();
          swiper.slideTo(lastIdx);
        });
        pag.appendChild(la);
      }
    }

    buildPagination(0);

    swiper.on("slideChange", function () {
      buildPagination(swiper.activeIndex);
    });

    var btnPrev = document.getElementById("collage-btn-prev");
    var btnNext = document.getElementById("collage-btn-next");
    if (btnPrev) btnPrev.addEventListener("click", function () { swiper.slidePrev(); });
    if (btnNext) btnNext.addEventListener("click", function () { swiper.slideNext(); });
  })();
</script>
@endpush