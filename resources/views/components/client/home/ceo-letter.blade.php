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
<section class="bg-primary text-white relative overflow-hidden">
  <img src="{{ asset('assets/images/background-decorate.svg') }}" alt="" class="hidden lg:block absolute -bottom-40 -right-20 w-[560px] h-[541px] opacity-60 pointer-events-none">

  <div class="hidden lg:block py-16 lg:py-20">
    <div class="w-[85%] max-w-[1320px] mx-auto">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="flex flex-col justify-center lg:max-w-xl" data-aos="fade-right">
          <h2 class="text-secondary text-2xl lg:text-4xl font-bold mb-8 uppercase">Lời tri ân</h2>
          @if($trangChu && !empty($trangChu->loi_tri_an))
            @foreach($trangChu->loi_tri_an as $paragraph)
            <p class="text-neutral-1 text-sm/5 lg:text-base/7 font-normal tracking-wider">
              {{ $paragraph }}
            </p>
            @if(!$loop->last)<br/><br/>@endif
            @endforeach
          @endif
          <div class="flex flex-col items-start">
            <div class="flex flex-col items-center">
              <img src="{{ asset('assets/images/sign.png') }}" alt="Signature">
              <p class="text-white font-bold text-lg mt-2">Giám đốc Vũ Mạnh Hải</p>
            </div>
          </div>
        </div>
        <div class="relative" data-aos="fade-left" data-aos-delay="200">
          <img
            src="{{ $trangChu?->loi_tri_an_anh
                ? (Str::startsWith($trangChu->loi_tri_an_anh, 'assets/') ? asset($trangChu->loi_tri_an_anh) : asset('storage/' . $trangChu->loi_tri_an_anh))
                : asset('assets/images/ceo.jpg') }}"
            alt="Director" class="w-full rounded-lg shadow-lg"
          >
        </div>
      </div>
    </div>
  </div>

  <div class="lg:hidden px-8 py-12">
    <div class="mx-auto flex w-full max-w-[368px] flex-col">
      <h2 class="text-center text-[20px] font-bold uppercase leading-[32px] text-secondary">Lời tri ân</h2>

      <div class="mt-6 space-y-6 text-justify text-[14px] font-light leading-[20px] tracking-[0.28px] text-white" style="font-family: 'Roboto', sans-serif;">
        @if($trangChu && !empty($trangChu->loi_tri_an))
          @foreach($trangChu->loi_tri_an as $paragraph)
          <p>{{ $paragraph }}</p>
          @endforeach
        @endif
      </div>

      <div class="mt-8 flex w-full justify-end pr-[38px]">
        <div class="flex w-fit flex-col items-start">
          <img src="{{ asset('assets/images/sign.png') }}" alt="" class="h-[55px] w-[91px] object-contain">
          <p class="pt-2 text-[12px] font-bold leading-[28px] text-white">Giám đốc Vũ Mạnh Hải</p>
        </div>
      </div>

      <img
        src="{{ $trangChu?->loi_tri_an_anh
            ? (Str::startsWith($trangChu->loi_tri_an_anh, 'assets/') ? asset($trangChu->loi_tri_an_anh) : asset('storage/' . $trangChu->loi_tri_an_anh))
            : asset('assets/images/ceo.jpg') }}"
        alt="Giám đốc Vũ Mạnh Hải" class="mt-10 aspect-square w-full object-cover"
      >
    </div>
  </div>
</section>
