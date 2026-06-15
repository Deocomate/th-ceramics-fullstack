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
@php
  $defaultJourney = [
      ['image' => 'assets/images/about-02.jpg', 'head' => '1985', 'body' => 'Khởi đầu hành trình gìn giữ nghề gốm truyền thống.'],
      ['image' => 'assets/images/about-01.png', 'head' => '1993', 'body' => 'Mở rộng thị trường với các mẫu sản phẩm thủ công đặc trưng.'],
      ['image' => 'assets/images/about-02.jpg', 'head' => '2000', 'body' => 'Chính thức vận hành theo mô hình doanh nghiệp chuyên nghiệp.'],
      ['image' => 'assets/images/about-01.png', 'head' => '2008', 'body' => 'Xây dựng showroom đầu tiên tại Bát Tràng.'],
      ['image' => 'assets/images/about-02.jpg', 'head' => '2024', 'body' => 'Mở showroom thế hệ mới và nâng cấp hệ sinh thái sản phẩm.'],
  ];
  $journeyItems = collect($about->gs_hanh_trinh ?? [])->filter(fn ($item) => is_array($item))->values();
  if ($journeyItems->isEmpty()) {
      $journeyItems = collect($defaultJourney);
  }
@endphp

<!-- Section 4: Our Journey / Hành trình của chúng tôi -->
<div class="md:mt-24 md:mb-16">
  <h3
    class="relative z-20 text-[20px] md:text-4xl font-bold text-center text-textPrimary mb-4 md:mb-0 uppercase tracking-normal"
    data-aos="fade-up"
  >
    HÀNH TRÌNH CỦA CHÚNG TÔI
  </h3>
  <div
    class="relative z-10 mt-4 md:mt-[60px] md:mb-[60px] flex flex-col md:flex-row gap-8 md:gap-[148px]"
  >
    <div
      class="w-full md:flex-1 min-w-0 flex flex-row md:flex-col overflow-x-auto md:overflow-visible snap-x snap-mandatory md:snap-none gap-6 md:gap-0 space-y-0 md:space-y-[50vh] py-4 md:py-[20vh] [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none] items-stretch"
    >
      @foreach ($journeyItems as $index => $item)
      <div
        class="timeline-item flex-shrink-0 w-[85%] md:w-full snap-center flex flex-col items-start md:items-center justify-start md:justify-center text-left md:text-center min-h-min md:min-h-[30vh]"
        data-index="{{ $index }}"
        data-aos="fade-up"
      >
        <div class="md:hidden w-full flex justify-center mb-6">
          <div
            class="aspect-[332/285] md:aspect-[3/4] w-full max-w-sm relative overflow-hidden shadow-lg rounded-sm"
          >
            <img
              src="{{ \App\Support\AssetPath::url(data_get($item, 'image'), 'assets/images/about-02.jpg') }}"
              alt="{{ data_get($item, 'head', 'Cột mốc') }}"
              class="w-full h-full object-cover"
            />
          </div>
        </div>
        <h4
          class="text-[30px] md:text-5xl font-bold text-textPrimary mb-4 md:mb-6 leading-[36px] md:leading-tight"
        >
          {{ data_get($item, 'head', '') }}
        </h4>
        <p
          class="about-description text-justify md:text-center max-w-lg w-full"
        >
          {{ data_get($item, 'body', '') }}
        </p>
      </div>
      @endforeach
    </div>
    <div
      class="hidden md:flex w-full md:w-[565px] md:shrink-0 sticky top-[94px] h-[calc(100vh-94px)] items-start justify-end"
    >
      <div
        class="w-full h-full relative overflow-hidden shadow-2xl rounded-sm bg-gray-100"
      >
        @foreach ($journeyItems as $index => $item)
        <img
          src="{{ \App\Support\AssetPath::url(data_get($item, 'image'), 'assets/images/about-02.jpg') }}"
          class="timeline-image-layer absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-[cubic-bezier(0.25,0.1,0.25,1.0)] {{ $index === 0 ? 'translate-y-0' : 'translate-y-full' }}"
          style="z-index: {{ $index === 0 ? 1 : 2 }}"
          data-index="{{ $index }}"
          alt="{{ data_get($item, 'head', 'Journey image') }}"
        />
        @endforeach
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  (() => {
    const initAboutJourneyImages = () => {
      if (window.__aboutJourneyImagesInitialized) {
        return;
      }

      const timelineItems = document.querySelectorAll(".timeline-item");
      const timelineImages = document.querySelectorAll(".timeline-image-layer");

      if (timelineItems.length === 0 || timelineImages.length === 0 || typeof IntersectionObserver !== "function") {
        return;
      }

      window.__aboutJourneyImagesInitialized = true;

      const setActiveImage = (activeIndex) => {
        timelineImages.forEach((image) => {
          const imageIndex = Number.parseInt(image.dataset.index || "0", 10);

          image.classList.remove("translate-y-0", "translate-y-full");

          if (imageIndex === activeIndex) {
            image.classList.add("translate-y-0");
            image.style.zIndex = "1";
            return;
          }

          if (imageIndex < activeIndex) {
            image.classList.add("translate-y-0");
            image.style.zIndex = "0";
            return;
          }

          image.classList.add("translate-y-full");
          image.style.zIndex = "2";
        });
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) {
            return;
          }

          setActiveImage(Number.parseInt(entry.target.dataset.index || "0", 10));
        });
      }, {
        root: null,
        rootMargin: "-45% 0px -45% 0px",
        threshold: 0,
      });

      timelineItems.forEach((item) => observer.observe(item));
    };

    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", initAboutJourneyImages);
      return;
    }

    initAboutJourneyImages();
  })();
</script>
@endpush
