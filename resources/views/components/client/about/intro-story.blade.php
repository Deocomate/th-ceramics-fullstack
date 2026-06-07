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
  $headItems = collect($about->gs_head ?? [])->values();

  $firstSection = $headItems->get(0, []);
  $secondSection = $headItems->get(1, []);

  $firstImage = \App\Support\AssetPath::url(data_get($firstSection, 'image'), 'assets/images/about-01.png');
  $firstTitle = data_get($firstSection, 'head', 'Những công việc giản dị và ngọn lửa nghề luôn ấm');
  $firstBody = data_get($firstSection, 'body', 'Từ những bàn tay khéo léo của người thợ Việt tới ngôi nhà của bạn.');

  $secondImage = \App\Support\AssetPath::url(data_get($secondSection, 'image'), 'assets/images/about-02.jpg');
  $secondTitle = data_get($secondSection, 'head', 'Kiên định một con đường, bền vững qua thời gian');
  $secondBody = data_get($secondSection, 'body', 'Thanh Hải vẫn kiên định với lựa chọn ban đầu: gốm sứ xây dựng.');
@endphp

<!-- Section 1: Những công việc giản dị -->
<div
  class="flex flex-col md:flex-row items-center gap-8 md:gap-16 mb-[30px] md:mb-24"
  data-aos="fade-up"
>
  <div class="w-full max-w-[604px] md:w-1/2">
    <div class="aspect-[1/1] relative overflow-hidden shadow-lg">
      <img
        src="{{ $firstImage }}"
        alt="Nghệ nhân làm gốm"
        class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700"
      />
    </div>
  </div>
  <div class="w-full md:w-1/2 lg:ml-20">
    <h3
      class="text-[20px] md:text-[36px] font-archivo font-bold text-textPrimary mb-5 leading-[30px] md:leading-[55px] text-center md:text-left"
    >
      {!! nl2br(e($firstTitle)) !!}
    </h3>
    <p
      class="text-textPrimary font-['Roboto'] text-[16px] font-medium leading-[28px] tracking-[0.32px] text-justify md:text-left mb-4 lg:max-w-md"
    >
      {{ $firstBody }}
    </p>
  </div>
</div>
<!-- Section 2: Kiên định một con đường -->
<div
  class="flex flex-col md:flex-row-reverse items-center gap-8 md:gap-16"
  data-aos="fade-up"
>
  <div class="w-full max-w-[604px] md:w-1/2">
    <div class="aspect-[1/1] relative overflow-hidden shadow-lg">
      <img
        src="{{ $secondImage }}"
        alt="Công trình gốm sứ"
        class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700"
      />
    </div>
  </div>
  <div class="w-full md:w-1/2 lg:ml-20">
    <h3
      class="text-[20px] md:text-4xl font-bold text-textPrimary mb-6 flex flex-col gap-2 md:gap-4 leading-[30px] md:leading-normal text-center md:text-left"
    >
      <span>{!! nl2br(e($secondTitle)) !!}</span>
    </h3>
    <p
      class="text-textPrimary leading-[28px] text-justify md:text-left lg:max-w-md font-medium tracking-wide mb-4 md:mb-0"
    >
      {{ $secondBody }}
    </p>
  </div>
</div>
