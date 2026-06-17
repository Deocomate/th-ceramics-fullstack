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
<!-- Projects Grid -->
@if($projects->isEmpty())
<div class="text-center py-16" data-aos="fade-up">
  <p class="text-lg text-primary/60 font-archivo">Chưa có dự án nào trong danh mục này.</p>
</div>
@else
<div
  class="grid grid-cols-1 md:grid-cols-3 gap-[25px] mb-8 md:mb-20 w-full"
  data-aos="fade-up"
>
  @foreach($projects as $project)
  <a
    href="{{ route('client.projects.detail', $project->slug) }}"
    class="group flex flex-col overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-500 bg-white rounded-[16px] md:h-[400px]"
  >
    <div class="aspect-[4/3] md:aspect-auto md:h-[320px] overflow-hidden shrink-0">
      <img
        src="{{ asset('storage/' . ($project->images[0] ?? 'assets/images/placeholder.jpg')) }}"
        alt="{{ $project->ten_du_an }}"
        class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
        onerror="this.onerror=null;this.src='{{ asset('assets/images/factory-01.jpg') }}'"
      />
    </div>
    <div class="p-3 md:p-0 md:pt-[14px] md:pb-[18px] text-center bg-white flex-1 flex flex-col items-center justify-start">
      <h3
        class="uppercase md:normal-case text-lg md:text-[18px] font-extrabold md:font-bold text-primary md:text-[#171717] font-archivo mb-2 md:mb-0 leading-normal md:leading-[22px] md:break-words w-full px-4 truncate group-hover:text-secondary transition-colors"
      >
        {{ $project->ten_du_an }}
      </h3>
      <p class="text-[15px] md:text-[16px] text-primary md:text-[#171717] font-archivo font-medium md:font-light leading-normal md:leading-[20px] md:mt-[8px] md:break-words w-full px-4 truncate">
        <span class="font-semibold md:font-light">Địa điểm:</span> {{ $project->dia_diem }}
      </p>
    </div>
  </a>
  @endforeach
</div>
@endif
