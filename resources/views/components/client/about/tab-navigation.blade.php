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
<!-- Tabs Section -->
<section class="py-[20px] md:py-16 bg-neutral-2">
  <!-- Tab Headers -->
  <div class="w-[85%] lg:w-[85%] max-w-[1320px] mx-auto md:px-4">
    <div class="flex w-full mx-auto mb-12">
      <button
        class="tab-btn active group flex-1 py-4 text-[14px] md:text-3xl font-bold text-secondary border-b-4 border-secondary transition-all duration-300 uppercase focus:outline-none"
        data-tab="tab-introduction"
      >
        VỀ GỐM SỨ THANH HẢI
      </button>
      <button
        class="tab-btn group flex-1 py-4 text-[14px] md:text-3xl font-bold text-secondary border-b-4 border-gray-300 hover:border-secondary transition-all duration-300 uppercase focus:outline-none"
        data-tab="tab-craft"
      >
        NGHỆ THUẬT THỦ CÔNG
      </button>
    </div>
  </div>
  <x-client.about.tab-introduction :about="$about ?? null" />
  <x-client.about.tab-craft :about="$about ?? null" />
</section>
