<x-client.layouts.main title="Đèn Gốm Sứ" data-page="products" main-class="bg-background-secondary overflow-hidden" :hide-newsletter="true">

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<style>
  @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Carattere&family=Charm:wght@400;700&family=Ephesis&display=swap");
</style>
@endpush

<x-client.content.shared.catalog-sticky-btn />

<x-client.catalog.products.den-gom-su.hero-banner :config="$config ?? null" />

<!-- BREADCRUMB & PRODUCT FILTER -->
<x-client.shared.product-breadcrumb-filter current-label="Đèn Gốm Sứ" />

<!-- Danh mục Đèn Gốm -->
<x-client.catalog.products.den-gom-su.category-den-gom :config="$config ?? null" />
<x-client.catalog.products.den-gom-su.product-list :products="$denGomProducts" :section-id="'den-gom-products'" />

<!-- Danh mục Đèn Sứ -->
<x-client.catalog.products.den-gom-su.category-den-su :config="$config ?? null" />
<x-client.catalog.products.den-gom-su.product-list :products="$denSuProducts" :section-id="'den-su-products'" />

<!-- Các phần khác -->
<x-client.catalog.products.den-gom-su.advantages-section :config="$config ?? null" />
<x-client.content.shared.outstanding-value />
<x-client.content.shared.fabrication-process />
<x-client.catalog.shared.journey-video :hide-title="true" />
<x-client.catalog.shared.recommendations
  :related-products="$relatedProducts"
  route-name="client.products.den-gom-su.detail"
  pk-field="den_vuon_gom_su_ct_id"
  product-type="den_vuon_gom_su_ct"
/>

<!-- FAQ Section -->
<section class="w-full relative pb-[70px] md:pb-32 bg-background-secondary overflow-visible" data-aos="fade-up">
  <x-client.content.shared.faq-accordion />
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

</x-client.layouts.main>
