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
  $defaultCoreValues = [
      [
          'image' => 'assets/images/about-02.jpg',
          'head' => 'Tính kế thừa',
          'body' => 'Gốm sứ xây dựng Thanh Hải là câu chuyện được tiếp nối qua nhiều thế hệ.',
      ],
      [
          'image' => 'assets/images/about-02.jpg',
          'head' => 'Nghệ thuật thủ công',
          'body' => '"Nghệ thuật thủ công" là giá trị cốt lõi khiến sản phẩm khác biệt.',
      ],
      [
          'image' => 'assets/images/about-02.jpg',
          'head' => 'Giá trị vượt thời gian',
          'body' => 'Mỗi viên gạch, viên ngói không chỉ để dựng nhà, mà để gìn giữ một tổ ấm.',
      ],
  ];
  $coreValues = collect($about->gs_gia_tri ?? [])->filter(fn ($item) => is_array($item))->values();
  if ($coreValues->isEmpty()) {
      $coreValues = collect($defaultCoreValues);
  }
@endphp

<!-- Section 3: Core Values / Giá trị cốt lõi -->
<div class="mt-[40px] md:mt-24">
  <h3
    class="text-[20px] md:text-4xl font-bold text-center text-textPrimary mb-8 md:mb-12 uppercase"
    data-aos="fade-up"
  >
    GIÁ TRỊ CỐT LÕI
  </h3>
  <div
    class="flex flex-row md:grid md:grid-cols-3 gap-6 md:gap-10 overflow-x-auto snap-x snap-mandatory pb-5 md:pb-8 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]"
  >
    @foreach ($coreValues as $index => $value)
    <div
      class="flex flex-col items-center min-w-[85%] md:min-w-0 snap-center"
      data-aos="fade-up"
      @if ($index > 0)
      data-aos-delay="{{ min($index * 150, 450) }}"
      @endif
    >
      <div class="w-full aspect-[1/1] overflow-hidden shadow-lg mb-6 md:mb-12">
        <img
          src="{{ \App\Support\AssetPath::url(data_get($value, 'image'), 'assets/images/about-02.jpg') }}"
          alt="{{ data_get($value, 'head', 'Giá trị cốt lõi') }}"
          class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700"
        />
      </div>
      <!-- Title -->
      <h4
        class="text-[20px] md:text-[36px] leading-[55px] font-archivo font-bold text-center text-textPrimary mb-2 md:mb-6"
      >
        {{ data_get($value, 'head', 'Giá trị cốt lõi') }}
      </h4>
      <!-- Description -->
      <p
        class="text-textPrimary text-justify md:text-center font-['Roboto'] text-[16px] font-medium leading-[28px] tracking-[0.32px]"
      >
        {{ data_get($value, 'body', '') }}
      </p>
    </div>
    @endforeach
  </div>
</div>
