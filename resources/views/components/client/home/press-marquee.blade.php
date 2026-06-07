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
<section class="bg-neutral-2 py-8 lg:py-20 overflow-hidden">
  <div class="w-[85%] max-w-[1320px] mx-auto mb-8 lg:mb-12">
    <h2 class="text-center lg:text-left text-secondary text-[20px] leading-[24px] lg:text-4xl font-bold uppercase" data-aos="fade-up">Báo chí nói về chúng tôi</h2>
  </div>

  @php $pressLogos = $trangChu?->ve_chung_toi_logo ?? []; @endphp

  @if(!empty($pressLogos))
  <div class="lg:hidden relative overflow-hidden -mx-[8%] mb-2" data-aos="fade-up" data-aos-delay="200">
    <div class="flex w-max animate-marquee">
      @foreach([$pressLogos, $pressLogos] as $setIndex => $logos)
      <div class="flex items-center gap-8 {{ $setIndex === 0 ? 'pl-6 pr-6' : 'pr-6' }}"
           @if($setIndex > 0) aria-hidden="true" @endif>
        @foreach($logos as $logo)
        <div class="flex items-center justify-center h-8 sm:h-10 min-w-[94px]">
          <img src="{{ Str::startsWith($logo, 'assets/') ? asset($logo) : asset('storage/' . $logo) }}"
               alt="Press" class="h-full w-auto max-w-none object-contain">
        </div>
        @endforeach
      </div>
      @endforeach
    </div>
  </div>

  <div class="hidden lg:flex w-[85%] max-w-[1320px] mx-auto flex-row justify-between items-center gap-2 md:gap-5 lg:gap-10" data-aos="fade-up" data-aos-delay="200">
    @foreach($pressLogos as $logo)
    <div class="flex items-center justify-center h-5 sm:h-6 md:h-10 lg:h-12">
      <img src="{{ Str::startsWith($logo, 'assets/') ? asset($logo) : asset('storage/' . $logo) }}"
           alt="Press" class="h-full w-auto object-contain">
    </div>
    @endforeach
  </div>
  @endif
</section>
