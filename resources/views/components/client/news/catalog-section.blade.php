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
<section id="catalog-section" class="py-16 lg:py-20 mb-16 lg:mb-20 bg-white relative overflow-hidden">
  <!-- Decorative Elements -->
  <img
    src="{{ asset('assets/images/news-decor.svg') }}"
    alt="Decor Left"
    class="absolute left-0 top-0 h-full w-auto max-h-[1000px] opacity-100 pointer-events-none hidden md:block"
    data-aos="fade-right"
  />
  <img
    src="{{ asset('assets/images/gtt-decorate-left.svg') }}"
    alt="Decor Right"
    class="absolute -right-[10%] top-0 h-[150%] w-auto max-h-[1000px] opacity-20 pointer-events-none rotate-180 hidden md:block"
  />
  <img
    src="{{ asset('assets/images/gtt-decorate-right.svg') }}"
    alt="Decor Right"
    class="absolute -right-[44%] top-[8%] scale-[1.7] w-auto max-h-[1000px] opacity-25 pointer-events-none rotate-180 md:hidden"
  />

  <div class="container-custom relative z-10 w-[85%] max-w-[1200px] mx-auto">
    <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
      <!-- Left Content -->
      <div
        class="w-full lg:w-5/12"
        data-aos="fade-right"
      >
        <h2 class="text-4xl lg:text-[48px] font-arima font-light text-primary leading-tight mb-4">
          {{ $article->tieu_de ?? 'Tin tức Thanh Hải' }}
        </h2>
        <a
          href="#"
          class="inline-block px-10 py-3.5 border border-primary text-[13px] font-bold text-primary uppercase tracking-[0.2em] hover:bg-primary hover:text-white transition-all duration-300"
        >
          TẢI CATALOG
        </a>
      </div>

      <!-- Right Image -->
      <div
        class="w-full lg:w-7/12"
        data-aos="fade-left"
      >
        <div class="relative group">
          <img
            src="{{ \App\Support\AssetPath::url($article->anh_dai_dien ?? null, 'assets/images/news-detail-5.png') }}"
            alt="{{ $article->tieu_de ?? 'Tin tức' }}"
            class="w-full h-auto object-cover shadow-2xl transition-transform duration-500 group-hover:scale-[1.02]"
          />
        </div>
      </div>
    </div>
  </div>
</section>
