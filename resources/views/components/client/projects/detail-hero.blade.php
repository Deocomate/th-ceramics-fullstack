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
<section
  class="w-full flex flex-col-reverse lg:flex-row min-h-[500px] md:h-[60vh] lg:h-[70vh] bg-[#F5EDE8] overflow-hidden"
>
  <!-- Left Half: Image -->
  <div
    class="w-full lg:w-1/2 h-[400px] lg:h-full relative overflow-hidden"
    data-aos="fade-right"
  >
    <img
      src="{{ asset('assets/images/factory-03.png') }}"
      alt="Project Hero"
      class="w-full h-full object-cover transform transition-transform duration-700 hover:scale-105"
      onerror="this.src = '{{ asset('assets/images/factory-01.jpg') }}'"
    />
  </div>

  <!-- Right Half: Text Content -->
  <div
    class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-24 bg-white"
    data-aos="fade-left"
  >
    <div class="max-w-md flex flex-col items-start gap-6">
      <div>
        <p
          class="text-[12px] font-bold text-primary opacity-60 uppercase tracking-widest mb-3"
        >
          Dự Án Nổi Bật
        </p>
        <h1
          class="text-3xl md:text-5xl xl:text-4xl font-arima text-primary leading-tight tracking-wide"
        >
          Tòa nhà văn phòng Thanh Hải Plaza
        </h1>
      </div>
      <p
        class="text-base text-primary opacity-80 leading-relaxed text-left font-light"
      >
        Một công trình kiến trúc độc đáo kết hợp gốm sứ truyền thống với thiết
        kế hiện đại, tạo nên không gian làm việc sang trọng và bền vững.
      </p>
      <div class="flex gap-8 pt-4 text-sm">
        <div>
          <p
            class="font-bold text-primary uppercase mb-1 text-[11px] opacity-60"
          >
            Địa điểm
          </p>
          <p class="text-primary font-arima text-lg">Hà Nội</p>
        </div>
        <div>
          <p
            class="font-bold text-primary uppercase mb-1 text-[11px] opacity-60"
          >
            Năm
          </p>
          <p class="text-primary font-arima text-lg">2023</p>
        </div>
      </div>
    </div>
  </div>
</section>
