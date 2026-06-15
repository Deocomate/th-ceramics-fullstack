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
  <div class="hidden lg:block py-[40px] relative">
    <div class="max-w-[1600px] mx-auto px-[60px] xl:px-[140px] relative min-h-[740px]">
      <div class="flex flex-col lg:flex-row gap-[40px] xl:gap-[93.78px] justify-between lg:items-stretch items-start">
        <div class="w-full lg:w-[574px] shrink-0" data-aos="fade-right">
          <h2 class="text-[#BC5A13] text-[36px] font-bold mb-[10px] uppercase leading-[62.50px]" style="font-family: 'Archivo', sans-serif;">Lời tri ân</h2>
          @if($trangChu && !empty($trangChu->loi_tri_an))
            @foreach($trangChu->loi_tri_an as $paragraph)
            <p class="text-white text-[16px] font-normal leading-[28px] tracking-[0.32px] @if(!$loop->last) mb-[30px] @endif" style="font-family: 'Roboto', sans-serif;">
              {{ $paragraph }}
            </p>
            @endforeach
          @endif
          <div class="flex flex-col items-start mt-[30px]">
            <img src="{{ asset('assets/images/sign.svg') }}" alt="Signature" class="w-[272.34px] h-[163.60px] object-contain">
            <p class="text-[#EFE4DE] font-bold text-[20px] leading-[25px] ml-[40px] mt-[4px]" style="font-family: 'Roboto', sans-serif;">Giám đốc Vũ Mạnh Hải</p>
          </div>
        </div>
        <div class="relative w-full lg:w-[643.44px] lg:self-stretch shrink-0 flex lg:items-center" data-aos="fade-left" data-aos-delay="200">
          <div class="relative w-full h-[643.44px]">
            <img src="{{ asset('assets/images/background-decorate.svg') }}" alt="" class="absolute right-[-235px] top-[400px] w-[550px] h-[550px] opacity-50 pointer-events-none z-0 max-w-none">
            <img
              src="{{ $trangChu?->loi_tri_an_anh
                  ? (Str::startsWith($trangChu->loi_tri_an_anh, 'assets/') ? asset($trangChu->loi_tri_an_anh) : asset('storage/' . $trangChu->loi_tri_an_anh))
                  : asset('assets/images/ceo.jpg') }}"
              alt="Director" class="relative z-10 w-full h-full object-cover rounded-[7px] shadow-[0px_7px_4px_rgba(0,0,0,0.25)]"
            >
          </div>
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
          <img src="{{ asset('assets/images/sign.svg') }}" alt="" class="h-[55px] w-[91px] object-contain">
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
