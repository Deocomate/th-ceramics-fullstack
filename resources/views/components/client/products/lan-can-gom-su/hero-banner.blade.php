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
<!-- Banner Section -->
<section
  class="relative h-[320px] md:h-[70vh] md:min-h-[500px] md:max-h-[740px] lg:h-[663px] flex items-center justify-center text-neutral-1 overflow-hidden">
  <!-- Background Image -->
  <div class="absolute inset-0 z-0">
    <img src="{{ $config->thumbnail_main ? asset('storage/' . $config->thumbnail_main) : asset('assets/images/about-banner.jpg') }}" alt="About Banner" class="w-full h-full object-cover" />
    <div class="absolute inset-0"
      style="background: linear-gradient(180deg, rgba(0, 0, 0, 0) 16.89%, rgba(21, 21, 21, 0.5) 75.07%, rgba(0, 0, 0, 0.5) 99.72%), linear-gradient(0deg, rgba(46, 47, 42, 0.59) 0%, rgba(46, 47, 42, 0.59) 100%);">
    </div>
    <!-- Dark overlay -->
  </div>

  <!-- Content -->
  <div class="relative z-10 text-center container mx-auto px-4 flex flex-col items-center" data-aos="fade-up"
    data-aos-duration="1000">
    <h1
      class="text-[20px] leading-[28px] tracking-[0.5px] font-semibold mb-[1px] md:text-2xl md:mb-4 md:tracking-wide uppercase text-neutral-1">
      GỐM SỨ THANH HẢI
    </h1>
    <h2
      class="text-[32px] leading-[44px] font-archivo font-extrabold uppercase md:text-[48px] md:leading-[65px] max-w-4xl text-neutral-1">
      LAN CAN GỐM SỨ
    </h2>
  </div>
</section>