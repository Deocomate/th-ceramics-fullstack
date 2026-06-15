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
  $founderImage = \App\Support\AssetPath::url($about->gs_nguoi_sang_lap_anh ?? null, 'assets/images/about-01.png');
  $founderContent = $about->gs_nguoi_sang_lap_noi_dung ?? 'Trải qua nhiều thăng trầm của nghề, hai người sáng lập vẫn bền bỉ theo đuổi sản phẩm gốm sứ xây dựng.';
@endphp

<!-- Section 5: Founders / Người sáng lập -->
<div class="md:mt-24 mb-[30px] md:mb-16">
  <h3
    class="relative z-20 text-[20px] md:text-4xl font-bold text-center text-textPrimary mb-8 md:mb-14 uppercase tracking-normal"
    data-aos="fade-up"
  >
    NGƯỜI SÁNG LẬP
  </h3>
  <div
    class="flex flex-col md:flex-row items-center gap-8 md:gap-16"
    data-aos="fade-up"
    data-aos-delay="200"
  >
    <!-- Image -->
    <div class="w-full md:w-1/2">
      <div
        class="aspect-[1/1] relative overflow-hidden shadow-lg rounded-sm mx-auto md:mx-0 max-w-[604px]"
      >
        <img
          src="{{ $founderImage }}"
          alt="Người sáng lập"
          class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700"
        />
      </div>
    </div>
    <!-- Text Content -->
    <div class="w-full md:w-1/2 xl:pr-16">
      <p class="about-description text-justify md:text-left">
        {{ $founderContent }}
      </p>
    </div>
  </div>
</div>
