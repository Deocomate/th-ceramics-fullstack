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
<div class="flex-1 lg:pl-12">
  <h1 class="text-[30px] lg:text-[36px] font-arima font-medium text-primary mb-6 lg:mt-[-6px]">Tải Catalog</h1>

  <p class="text-primary/80 text-base leading-[28px] mb-10 max-w-[1000px] font-archivo">
    Để quý khách dễ dàng lựa chọn giải pháp tối ưu, Gốm sứ Thanh Hải trân trọng giới thiệu hệ thống catalog chuyên biệt theo từng
    dòng sản phẩm từ ngói lợp, gạch trang trí đến gốm phong thủy. Bên cạnh đó, bộ catalog tổng thể sẽ giúp quý vị có cái nhìn toàn
    diện nhất về hệ sinh thái sản phẩm đa dạng mà chúng tôi cung cấp.
  </p>

  <div class="space-y-10 lg:space-y-12">
    @if($featuredCatalog)
    <div class="flex flex-col lg:flex-row gap-6 lg:gap-24 bg-transparent mb-16 lg:mb-12">
      <div class="w-full lg:w-[480px] aspect-[1/1.1] bg-[#D9D9D9] rounded-sm flex-shrink-0">
        @if($featuredCatalog->anh_dai_dien)
        <img src="{{ asset('storage/' . $featuredCatalog->anh_dai_dien) }}" alt="{{ $featuredCatalog->tieu_de ?? 'Catalog' }}" class="w-full h-full object-cover rounded-sm" />
        @endif
      </div>
      <div class="flex flex-col justify-end">
        <div class="flex items-center gap-3 mt-3 lg:mt-0 mb-2.5">
          <h3 class="text-sm lg:text-base font-semibold text-primary font-archivo">{{ $featuredCatalog->tieu_de }}</h3>
        </div>
        <div>
          @if($featuredCatalog->file)
            <a href="{{ route('client.dich-vu.tai-catalog.read', $featuredCatalog->catalog_id) }}"
              class="flex items-center font-extralight justify-center lg:justify-between gap-4 px-2 py-1.5 border border-primary text-primary text-xs lg:text-sm hover:bg-primary hover:text-white transition-all w-fit min-w-[97px] lg:min-w-[110px]"
            >
              <span class="font-archivo">Xem chi tiết</span>
              <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1]" />
            </a>
          @else
            <span
              class="flex items-center font-extralight justify-center lg:justify-between gap-4 px-2 py-1.5 border border-primary/30 text-primary/30 text-xs lg:text-sm cursor-not-allowed w-fit min-w-[97px] lg:min-w-[110px]"
            >
              <span class="font-archivo">Xem chi tiết</span>
              <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1]" />
            </span>
          @endif
        </div>
      </div>
    </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-16 lg:gap-12">
      @forelse($catalogs as $item)
      <div class="flex flex-col">
        <div class="aspect-[1/1.1] bg-[#D9D9D9] rounded-sm mb-4 lg:mb-6">
          @if($item->anh_dai_dien)
          <img src="{{ asset('storage/' . $item->anh_dai_dien) }}" alt="{{ $item->tieu_de ?? 'Catalog' }}" class="w-full h-full object-cover rounded-sm" />
          @endif
        </div>
        <div class="flex items-center gap-3 mb-2.5">
          <h3 class="text-sm lg:text-base font-semibold text-primary font-archivo">{{ $item->tieu_de }}</h3>
        </div>
        <div>
          @if($item->file)
            <a href="{{ route('client.dich-vu.tai-catalog.read', $item->catalog_id) }}"
              class="flex items-center font-extralight justify-center lg:justify-between gap-4 px-2 py-1.5 border border-primary text-primary text-xs lg:text-sm hover:bg-primary hover:text-white transition-all w-fit min-w-[97px] lg:min-w-[110px]"
            >
              <span class="font-archivo">Xem chi tiết</span>
              <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1]" />
            </a>
          @else
            <span
              class="flex items-center font-extralight justify-center lg:justify-between gap-4 px-2 py-1.5 border border-primary/30 text-primary/30 text-xs lg:text-sm cursor-not-allowed w-fit min-w-[97px] lg:min-w-[110px]"
            >
              <span class="font-archivo">Xem chi tiết</span>
              <img src="{{ asset('assets/images/triangle.svg') }}" alt="" class="hidden lg:block w-[6px] h-[10px] rotate-180 scale-x-[-1]" />
            </span>
          @endif
        </div>
      </div>
      @empty
      <div class="col-span-full text-center py-16">
        <p class="text-primary/60 text-lg font-archivo">Đang cập nhật catalog...</p>
      </div>
      @endforelse
    </div>
  </div>
</div>
