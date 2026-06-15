@props([
    'pageConfig' => null,
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
    'items' => null,
])

@if ($pageConfig?->promo_enabled)
<!-- Catalog Section -->
<section
  id="catalog-section"
  class="py-16 lg:py-28 bg-white relative overflow-hidden"
>
  <!-- Decorative Elements -->
  <img
    src="{{ asset('assets/images/news-decor.svg') }}"
    alt="Decor Left"
    class="absolute md:left-0 left-[-5%] top-0 h-full w-auto max-h-[1000px] opacity-15 pointer-events-none"
  />
  <img
    src="{{ asset('assets/images/gtt-decorate-left.svg') }}"
    alt="Decor Right"
    class="absolute -right-[13%] top-0 h-[160%] w-auto max-h-[1000px] opacity-15 pointer-events-none rotate-180"
  />

  <div class="container-custom relative z-10 w-[85%] max-w-[1320px] mx-auto">
    <div class="w-[85%] mx-auto flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
      <!-- Left Content -->
      <div
        class="w-full lg:w-5/12"
        data-aos="fade-right"
      >
        <h2 class="text-[36px] leading-[45px] lg:text-[48px] font-arima font-light text-primary lg:leading-tight mb-8 md:mb-4">
          {!! nl2br(e($pageConfig->promo_title)) !!}
        </h2>
        <a
          href="{{ $pageConfig->promo_cta_url ?: '#' }}"
          class="inline-block px-4 md:px-8 py-2 md:py-3 border border-primary text-[15px] leading-[22.5px] tracking-[0.38px] font-semibold text-primary uppercase hover:bg-primary hover:text-white transition-all duration-300"
        >
          {{ $pageConfig->promo_cta_label }}
        </a>
      </div>

      <!-- Right Image -->
      <div
        class="w-full lg:w-7/12"
        data-aos="fade-left"
      >
        <div class="relative group">
          <img
            src="{{ \App\Support\AssetPath::url($pageConfig->promo_image, 'assets/images/news-detail-5.png') }}"
            alt="{{ \Illuminate\Support\Str::before($pageConfig->promo_title, chr(10)) }}"
            class="w-full h-auto aspect-[67/45] object-cover shadow-2xl transition-transform duration-500 group-hover:scale-[1.02]"
          />
        </div>
      </div>
    </div>
  </div>
</section>
@endif
