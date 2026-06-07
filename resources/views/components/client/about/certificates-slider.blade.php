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
  $certificates = collect($about->gs_giai_thuong ?? [])->filter(fn ($item) => is_array($item))->values();
@endphp

@if($certificates->isNotEmpty())
<div class="w-[85%] lg:w-[85%] max-w-[1320px] mx-auto px-4 max-md:w-[92.5%] max-md:mr-0 max-md:px-0">
  <div class="mt-16 md:mt-24 mb-16 relative">
    <div class="swiper certificates-swiper w-full px-4 md:px-3 max-md:px-0">
      <div class="swiper-wrapper">
        @foreach($certificates as $cert)
        <div class="swiper-slide max-md:!w-[286px] max-md:shrink-0">
          <div class="flex flex-col items-center group cursor-pointer w-full">
            <div class="text-[36px] leading-[36px] font-archivo font-bold text-textPrimary text-center uppercase mb-[43px] max-md:text-[20px] max-md:mb-[5px] max-md:leading-[36px]">
              {{ data_get($cert, 'head', '2016') }}
            </div>
            <div class="w-full relative h-[2px] mb-[63px] max-md:mb-[33px] flex items-center justify-center">
              <div class="absolute left-0 top-0 h-full bg-secondary z-0 w-full max-md:w-[calc(100%+15px)]"></div>
              <div class="relative z-10 w-6 h-6 bg-neutral-1 rounded-full border-2 border-secondary box-border flex items-center justify-center max-md:w-4 max-md:h-4">
                <div class="w-4 h-4 bg-secondary rounded-full max-md:w-2 max-md:h-2"></div>
              </div>
            </div>
            <div class="w-full px-2 md:px-4 max-md:px-0">
              <a href="{{ \App\Support\AssetPath::url(data_get($cert, 'image')) }}" class="glightbox block w-full aspect-[3/4] overflow-hidden shadow-lg transition-transform duration-500 hover:-translate-y-2" data-gallery="certificates">
                <img src="{{ \App\Support\AssetPath::url(data_get($cert, 'image')) }}" alt="{{ data_get($cert, 'head', 'Chứng nhận') }}" class="w-full h-full object-cover" />
              </a>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <div class="certificates-prev absolute -left-[10%] lg:-left-[7%] top-[60%] -translate-y-1/2 z-20 w-10 h-10 border border-black/50 rounded-full flex items-center justify-center text-black/70 hover:border-black hover:text-black transition-all cursor-pointer max-md:hidden">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
      </svg>
    </div>
    <div class="certificates-next absolute -right-[10%] lg:-right-[7%] top-[60%] -translate-y-1/2 z-20 w-10 h-10 border border-black/50 rounded-full flex items-center justify-center text-black/70 hover:border-black hover:text-black transition-all cursor-pointer max-md:hidden">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
      </svg>
    </div>
  </div>
</div>
@endif
