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
  class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10 mb-8 md:mb-20 w-[85%] mx-auto"
  data-aos="fade-up"
>
  @foreach($projects as $project)
  <a
    href="{{ route('client.projects.detail', $project->slug) }}"
    class="group block overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-500 bg-white"
  >
    <div class="aspect-[4/3] overflow-hidden">
      <img
        src="{{ asset('storage/' . ($project->images[0] ?? 'assets/images/placeholder.jpg')) }}"
        alt="{{ $project->ten_du_an }}"
        class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
        onerror="this.onerror=null;this.src='{{ asset('assets/images/factory-01.jpg') }}'"
      />
    </div>
    <div class="p-3 text-center bg-white">
      <h3
        class="text-lg md:text-xl font-archivo font-extrabold text-primary mb-2 group-hover:text-secondary transition-colors"
      >
        {{ \Illuminate\Support\Str::upper($project->ten_du_an) }}
      </h3>
      <p class="text-[15px] text-primary font-archivo font-medium">
        <span class="text-primary font-semibold">Địa điểm:</span> {{ $project->dia_diem }}
      </p>
      <p class="text-[15px] text-primary font-archivo font-medium">
        <span class="text-primary font-semibold">Sản phẩm:</span> {{ $project->san_pham }}
      </p>
    </div>
  </a>
  @endforeach
</div>
@endif
