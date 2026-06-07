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
<!-- Section danh mục Đèn Sứ -->
<section class="pb-[50px] md:pb-24 max-w-[1920px] mx-auto">
  <div
    class="w-[85%] mx-auto md:w-auto md:mx-0 md:mr-[calc(max(7.5%,(100%-1320px)/2))] flex flex-col md:flex-row items-center gap-8 md:gap-16 lg:gap-24"
  >
    <!-- Hình ảnh Lớn (Landscape) có nội dung text -->
    <div class="w-full md:w-[70%] lg:w-[68%] relative group" data-aos="fade-up">
      <div class="relative overflow-hidden rounded-2xl shadow-xl">
        <img
          src="{{ $config->image3 ? asset('storage/' . $config->image3) : asset('assets/images/den-gom-02.png') }}"
          alt="ĐÈN SỨ"
          class="w-full h-auto aspect-[6/5] object-cover"
          onerror="this.src = '{{ asset('assets/images/about-01.png') }}'"
        />

        <!-- Khung text trang trí dưới cùng của hình -->
        <div class="absolute bottom-[-10px] left-0 w-full z-10">
          <div class="relative rounded-b-2xl overflow-hidden">
            <img
              src="{{ asset('assets/images/den-gom-decorate-2.svg') }}"
              class="w-full h-auto block scale-[1.05] origin-bottom"
              alt="nền trang trí"
              onerror="this.style.display = 'none'"
            />

            <div
              class="absolute inset-0 flex items-center justify-center pt-[8%] pb-[8%]"
            >
              <h2
                class="text-[#C76E00] font-semibold text-[20px] md:text-3xl lg:text-[40px] uppercase"
              >
                {{ $config->title3 ?? 'ĐÈN SỨ' }}
              </h2>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Hình ảnh Dọc (Portrait) -->
    <div
      class="w-full md:w-[30%] lg:w-[32%]"
      data-aos="fade-up"
      data-aos-delay="200"
    >
      <div class="aspect-[4/6] overflow-hidden rounded-2xl shadow-xl">
        <img
          src="{{ $config->image4 ? asset('storage/' . $config->image4) : asset('assets/images/den-gom-01.png') }}"
          alt="ĐÈN SỨ"
          class="w-full h-full object-cover"
          onerror="this.src = '{{ asset('assets/images/ngoi-01.jpg') }}'"
        />
      </div>
    </div>
  </div>
</section>