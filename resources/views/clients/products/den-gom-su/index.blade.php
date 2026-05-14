<x-layouts.client title="Đèn Gốm Sứ" data-page="products" main-class="bg-background-secondary overflow-hidden" :hide-newsletter="true">

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<style>
  @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Carattere&family=Charm:wght@400;700&family=Ephesis&display=swap");
</style>
@endpush

<x-catalog-button />

@include('clients.products.den-gom-su.partials.banner')

<div class="w-[85%] max-w-[1320px] mx-auto pt-6 pb-3 md:pb-6 md:pt-8 relative z-10">
  <x-products.breadcrumb current-label="Đèn Gốm Sứ" />
</div>
<x-products.product-filter />

<!-- Danh mục Đèn Gốm -->
@include('clients.products.den-gom-su.partials.category-den-gom')
@include('clients.products.den-gom-su.partials.product-list', [
  'products' => $denGomProducts,
  'sectionId' => 'den-gom-products',
])

<!-- Danh mục Đèn Sứ -->
@include('clients.products.den-gom-su.partials.category-den-su')
@include('clients.products.den-gom-su.partials.product-list', [
  'products' => $denSuProducts,
  'sectionId' => 'den-su-products',
])

<!-- Các phần khác -->
@include('clients.products.den-gom-su.partials.advantages-section')
<x-products.fabrication-process />
<x-products.journey-video :hide-title="true" />
<x-products.recommendations
  :related-products="$relatedProducts"
  route-name="client.products.den-gom-su.detail"
  pk-field="den_vuon_gom_su_ct_id"
  product-type="den_vuon_gom_su_ct"
/>

<!-- FAQ Section -->
<section class="w-full relative pb-[70px] md:pb-32 bg-background-secondary overflow-visible" data-aos="fade-up">
  <x-products.faq-content />
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    if (typeof GLightbox !== "undefined") {
      document.querySelectorAll(".glightbox").forEach((anchor) => {
        const image = anchor.querySelector("img");
        if (image) {
          anchor.setAttribute("href", image.currentSrc || image.src);
        }
      });
      GLightbox({
        touchNavigation: true,
        loop: true,
        autoplayVideos: true,
      });
    }
  });
</script>
@endpush

</x-layouts.client>
