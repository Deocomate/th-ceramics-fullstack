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
<!-- Filters -->
<div class="mb-10 md:mb-16 flex flex-col items-center md:block" data-aos="fade-up">
  <!-- Mobile Dropdown -->
  <div class="md:hidden relative min-w-[184px] h-[32px]">
    <select class="w-full h-full pl-3 pr-8 border border-[#ACACAC] bg-transparent text-[#565656] text-[14px] font-archivo font-semibold uppercase appearance-none outline-none cursor-pointer"
            onchange="if(this.value) window.location.href=this.value">
      <option value="{{ route('client.projects.index') }}">TẤT CẢ</option>
      @foreach($categories as $cat)
        @php $catSlug = \Illuminate\Support\Str::slug($cat->ten_danh_muc); @endphp
        <option value="{{ route('client.projects.index', ['category' => $catSlug]) }}"
                {{ request('category') == $catSlug ? 'selected' : '' }}>
          {{ $cat->ten_danh_muc }}
        </option>
      @endforeach
    </select>
    <div class="pointer-events-none absolute inset-y-0 right-1 flex items-center px-2 text-[#C76E00]">
      <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.33"/>
      </svg>
    </div>
  </div>

  <!-- Desktop Links -->
  <div class="hidden md:flex flex-wrap justify-between items-center gap-6 md:gap-12">
    <a href="{{ route('client.projects.index') }}"
       class="text-base md:text-xl font-archivo font-semibold uppercase hover:text-secondary transition-colors
              {{ !request('category') ? 'text-secondary relative after:content-[\'\'] after:absolute after:-bottom-0 after:left-0 after:w-full after:h-[2px] after:bg-secondary' : 'text-primary' }}">
      TẤT CẢ
    </a>
    @foreach($categories as $cat)
      @php $catSlug = \Illuminate\Support\Str::slug($cat->ten_danh_muc); @endphp
      <a href="{{ route('client.projects.index', ['category' => $catSlug]) }}"
         class="text-base md:text-xl font-archivo font-semibold uppercase hover:text-secondary transition-colors
                {{ request('category') == $catSlug ? 'text-secondary relative after:content-[\'\'] after:absolute after:-bottom-0 after:left-0 after:w-full after:h-[2px] after:bg-secondary' : 'text-primary' }}">
        {{ $cat->ten_danh_muc }}
      </a>
    @endforeach
  </div>
</div>
