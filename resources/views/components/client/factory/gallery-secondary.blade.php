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
    use App\Support\AssetPath;

    $gallery2 = is_string($factory->gallery_2) ? json_decode($factory->gallery_2, true) : $factory->gallery_2;
    $gallery2 = is_array($gallery2) ? $gallery2 : [];
@endphp
<section class="bg-[#F5EDE8] pb-8 md:pb-24 lg:pb-36 overflow-hidden">
  <!-- Header row -->
  <div class="w-[85%] max-w-[1320px] mx-auto mb-8 md:flex justify-end hidden">
    <!-- Navigation Buttons -->
    <div class="flex gap-4">
      <button
        class="slider-final-prev w-10 h-10 md:w-12 md:h-12 border border-black/20 rounded-full flex items-center justify-center hover:bg-black/5 transition-all focus:outline-none"
      >
        <svg
          width="20"
          height="20"
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
        class="slider-final-next w-10 h-10 md:w-12 md:h-12 border border-black/20 rounded-full flex items-center justify-center hover:bg-black/5 transition-all focus:outline-none"
      >
        <svg
          width="20"
          height="20"
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
  </div>

  <!-- Swiper Container -->
  <div
    class="ml-[7.5%] min-[1553px]:ml-[calc((100vw-1320px)/2)] lg:max-w-[1920px] overflow-hidden"
  >
    <div class="swiper slider-final-swiper overflow-visible">
      <div class="swiper-wrapper">
        @if(!empty($gallery2))
          @foreach($gallery2 as $image)
            <div class="swiper-slide w-full md:w-[70%] lg:w-[80%]">
              <div class="aspect-[2/1] overflow-hidden shadow-lg bg-neutral-1">
                <img
                  src="{{ AssetPath::url($image) }}"
                  alt="Gallery image"
                  class="w-full h-full object-cover transform transition-transform duration-700 hover:scale-105"
                />
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    if (typeof Swiper !== "undefined") {
      new Swiper(".slider-final-swiper", {
        slidesPerView: "auto",
        spaceBetween: 24,
        navigation: {
          nextEl: ".slider-final-next",
          prevEl: ".slider-final-prev",
        },
        breakpoints: {
          768: {
            spaceBetween: 32,
          },
        },
      });
    }
  });
</script>
@endpush
