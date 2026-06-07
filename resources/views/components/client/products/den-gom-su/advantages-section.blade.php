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
<!-- Ưu điểm vượt trội Section -->
<section class="md:pb-16 bg-background-secondary overflow-hidden md:pt-4">
  <div class="w-[85%] max-w-[1320px] mx-auto">
    <h2
      class="text-secondary text-[20px] md:text-3xl font-semibold uppercase text-center md:mb-32 mt-1"
      data-aos="fade-up"
    >
      Ưu điểm vượt trội
    </h2>

    @php
      $galleryImages = $config->anh->pluck('image')->toArray();
      $cardImages = array_pad(array_slice($galleryImages, 0, 4), 4, null);
    @endphp

    <!-- Mobile Swiper (Chỉ hiển thị trên mobile) -->
    <div class="md:hidden">
      <div class="swiper advantage-swiper">
        <div class="swiper-wrapper">
          @foreach($cardImages as $galleryImage)
          <div class="swiper-slide h-auto">
            <x-client.products.den-gom-su.advantage-card :bg-image="$galleryImage" />
          </div>
          @endforeach
        </div>
      </div>
      <!-- Dots -->
      <div
        class="advantage-pagination flex justify-center items-center gap-[3px] w-full mt-6"
      ></div>
    </div>

    <!-- Desktop Grid (ẩn trên mobile, hiển thị trên md) -->
    <div
      class="hidden md:grid md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-6 items-start"
    >
      @foreach($cardImages as $galleryImage)
      <x-client.products.den-gom-su.advantage-card :bg-image="$galleryImage" />
      @endforeach
    </div>
  </div>
</section>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    new Swiper(".advantage-swiper", {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: false,
      pagination: {
        el: ".advantage-pagination",
        clickable: true,
        renderBullet: function (index, className) {
          return (
            '<span class="' +
            className +
            ' rounded-full bg-secondary/30 transition-all cursor-pointer inline-block"></span>'
          );
        },
      },
    });
  });
</script>
@endpush
