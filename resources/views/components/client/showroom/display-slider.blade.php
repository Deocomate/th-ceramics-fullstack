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
  $slides = collect($showroomImages ?? [])->values();
  if ($slides->isEmpty()) {
      $slides = collect(['assets/images/showroom-01.jpg', 'assets/images/showroom-02.jpg']);
  }
@endphp

<!-- Room Display Content (Symmetrical) -->
<section
  class="w-full max-w-[1920px] px-6 lg:px-[8%] 2xl:pr-[12.5%] mx-auto pb-16 lg:pb-32 lg:pt-8 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-10 xl:gap-20 items-center overflow-hidden"
>
  <!-- Left: Slider Image -->
  <div
    class="lg:col-span-7 relative h-auto pr-0 xl:pr-10 order-2 lg:order-1 z-20 mx-0 overflow-hidden md:overflow-visible"
    data-aos="fade-right"
  >
    <div
      class="swiper showroomSwiper2 h-[400px] md:h-[500px] lg:h-[600px] relative w-full overflow-visible md:[clip-path:inset(-100px_-20px_-100px_-100vw)] lg:[clip-path:inset(-50px_-40px_-50px_-100vw)] xl:[clip-path:inset(-50px_-60px_-50px_-100vw)]"
    >
      <div class="swiper-wrapper flex flex-row">
        @foreach ($slides->reverse()->values() as $index => $slide)
        <div class="swiper-slide w-[85%] sm:w-[75%] h-full {{ $index === 0 ? 'ml-auto' : '' }}">
          <img
            src="{{ \App\Support\AssetPath::url($slide, 'assets/images/showroom-01.jpg') }}"
            alt="Phòng trưng bày {{ $index + 1 }}"
            class="w-full h-full object-cover shadow-2xl"
          />
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Right: Text Info -->
  <div
    class="lg:col-span-5 flex flex-col gap-6 relative z-10 order-1 lg:order-2 justify-self-end"
    data-aos="fade-left"
  >
    <h2 class="font-arima text-3xl md:text-4xl lg:text-[48px] font-medium leading-[1.2]">Phòng trưng bày</h2>

    <div class="h-[1px] w-[100%] lg:mr-[20%] lg:w-[160%] lg:-ml-[80%] bg-white/20 mb-8 lg:mb-16 relative z-[-1]"></div>

    <div class="w-[85%] md:w-full mx-auto md:mx-0 flex flex-col gap-5 text-xs mb-8">
      <div class="grid grid-cols-[100px_1fr] md:grid-cols-[100px_1fr] gap-4 items-start">
        <span class="font-medium tracking-[0.1em] uppercase text-xs mt-[3px]">ĐỊA CHỈ</span>
        <span class="font-semibold text-sm leading-snug w-full max-w-[320px]">{{ $showroomContent ?? '10 ngõ 252 - Thôn 3, Giang Cao, Bát Tràng, Hà Nội' }}</span>
      </div>
      <div class="grid grid-cols-[100px_1fr] md:grid-cols-[100px_1fr] gap-4 items-center mt-2">
        <span class="font-medium tracking-[0.1em] uppercase text-xs">email</span>
        <span class="font-semibold text-sm">gshaithanh@gmail.com</span>
      </div>
    </div>

    <div class="flex flex-col gap-5 w-full mx-auto md:mx-0 max-w-[335px] md:max-w-[360px]">
      <a
        href="tel:0966558808"
        class="bg-gradient-to-r from-[#C76E00] via-[#DF8828] to-[#E79735] hover:from-secondary hover:to-[#DF8828] text-white flex flex-col items-center justify-center py-4 px-8 min-w-[180px] w-full transition-colors shadow-lg"
      >
        <img
          src="{{ asset('assets/images/phone.svg') }}"
          alt=""
          class="mb-1.5 shrink-0 brightness-0 invert"
        />
        <span class="font-extrabold text-[13px]">Điện thoại</span>
      </a>
      <a
        href="https://maps.google.com/?q=10+ngo+252+Thon+3,+Giang+Cao,+Bat+Trang,+Gia+Lam,+Ha+Noi"
        target="_blank"
        class="bg-primary hover:bg-[#3d3f38] text-white flex flex-col items-center justify-center py-4 px-8 min-w-[180px] w-full transition-colors border-2 border-[#DF8828]"
      >
        <img
          src="{{ asset('assets/images/direction.svg') }}"
          alt=""
          class="mb-1.5 shrink-0"
        />
        <span class="font-extrabold text-[13px]">Đường đi</span>
      </a>
    </div>
  </div>
</section>
