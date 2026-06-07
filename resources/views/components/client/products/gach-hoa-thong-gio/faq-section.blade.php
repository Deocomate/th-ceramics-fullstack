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
<!-- FAQ Section -->
<section
  class="w-full relative pb-[65px] md:pb-32 bg-background-secondary overflow-visible gach-hoa-faq-section"
  data-aos="fade-up"
>
  <!-- Background Decoration -->
  <div
    class="absolute z-[2] left-[-40%] md:left-[-30%] lg:left-[-17%] top-[50%] w-[65%] md:w-[55%] lg:w-[50%] pointer-events-none"
    data-aos="fade-up-right"
  >
    <img
      src="{{ asset('assets/images/gach-hoa-decorate.png') }}"
      alt=""
      class="w-full origin-center -translate-y-1/2 rotate-[-45deg] md:opacity-60 opacity-50 drop-shadow-sm"
    />
  </div>
  <div
    class="absolute z-[2] right-[-40%] md:right-[-30%] lg:right-[-17%] top-[50%] w-[65%] md:w-[55%] lg:w-[50%] pointer-events-none"
    data-aos="fade-up-left"
  >
    <img
      src="{{ asset('assets/images/gach-hoa-decorate.png') }}"
      alt=""
      class="w-full origin-center -translate-y-1/2 rotate-[45deg] scale-x-[-1] md:opacity-60 opacity-50 drop-shadow-sm"
    />
  </div>
  <x-client.shared.faq-accordion />
</section>