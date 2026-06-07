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
  $bannerImage = \App\Support\AssetPath::url($about->banner ?? null, 'assets/images/about-banner.jpg');
  $bannerHeader = $about->header_banner ?? 'GỐM SỨ THANH HẢI';
  $bannerBody = $about->body_banner ?? "40 NĂM - LỬA NGHỀ\nCHƯA BAO GIỜ TẮT";
  $bannerLines = preg_split('/\r\n|\r|\n/', trim((string) $bannerBody)) ?: [];
@endphp

<section
  class="relative h-[572px] md:h-[70vh] md:min-h-[500px] md:max-h-[740px] lg:h-[663px] flex items-start md:items-center justify-center overflow-hidden"
  style="color-scheme: light"
>
  <div class="absolute inset-0 z-0">
    <img
      src="{{ $bannerImage }}"
      alt="About Banner"
      class="w-full h-full object-cover"
    />
    <div
      class="absolute inset-0 bg-[linear-gradient(180deg,rgba(255,255,255,0)_0%,rgba(77.47,77.47,77.47,0.48)_24%,rgba(0,0,0,0.60)_49%)] md:bg-[linear-gradient(180deg,rgba(0,0,0,0)_16.89%,rgba(21,21,21,0.5)_75.07%,rgba(0,0,0,0.5)_99.72%),linear-gradient(0deg,rgba(46,47,42,0.59)_0%,rgba(46,47,42,0.59)_100%)]"
    ></div>
  </div>

  <div
    class="relative z-10 text-center container mx-auto px-4 flex flex-col items-center pt-[170px] md:pt-0"
    data-aos="fade-up"
    data-aos-duration="1000"
  >
    <p
      class="text-[14px] md:text-base mb-6 font-light leading-[24.5px] tracking-[0.35px] md:tracking-wide !text-[#F5EDE7] md:!text-[#EFE4DE]"
    >
      Trang chủ &nbsp; > &nbsp; Về chúng tôi
    </p>
    <h1
      class="text-[20px] md:text-2xl font-semibold mb-4 uppercase leading-[28px] tracking-[0.5px] md:tracking-wide !text-[#F5EDE7] md:!text-[#EFE4DE]"
    >
      {{ $bannerHeader }}
    </h1>
    <h2
      class="text-[32px] md:text-[48px] font-archivo font-extrabold uppercase leading-[44px] md:leading-[65px] max-w-4xl flex flex-col gap-2 md:gap-4 !text-[#F5EDE7] md:!text-[#EFE4DE]"
    >
      @forelse ($bannerLines as $line)
        <span>{{ $line }}</span>
      @empty
        <span>40 NĂM - LỬA NGHỀ</span>
        <span>CHƯA BAO GIỜ TẮT</span>
      @endforelse
    </h2>
  </div>
</section>
