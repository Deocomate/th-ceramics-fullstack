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
  class="relative h-[30vh] lg:h-[70vh] xl:h-[95vh] min-h-[400px] max-h-[740px] flex items-start justify-center text-white overflow-hidden">
  <!-- Background Image -->
  <div class="absolute inset-0 z-0">
    <img src="{{ asset('assets/images/news-banner.png') }}" alt="News Banner" class="w-full h-full object-cover" />
    <div class="absolute inset-0 md:bg-black/10 bg-black/20"></div>
  </div>

  <!-- Content -->
  <div class="relative z-10 text-center container mx-auto px-4 flex flex-col items-center pt-12" data-aos="fade-up"
    data-aos-duration="1000">
    <p class="text-xs md:text-sm mb-4 opacity-100 font-medium tracking-wide text-white md:text-[#2E2F2A]">Trang chủ
      &nbsp; > &nbsp; Tin tức</p>
    <h1
      class="text-4xl md:text-5xl lg:text-6xl font-arima uppercase leading-tight md:text-[#2E2F2A] md:text-primary drop-shadow-sm">
      TIN TỨC</h1>
  </div>
</section>