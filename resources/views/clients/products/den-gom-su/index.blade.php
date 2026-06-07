<x-client.layouts.main title="Đèn Gốm Sứ" data-page="products" main-class="bg-background-secondary overflow-hidden" :hide-newsletter="true">

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<style>
  @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Carattere&family=Charm:wght@400;700&family=Ephesis&display=swap");
</style>
@endpush

<x-client.shared.catalog-sticky-btn />

<x-client.products.den-gom-su.hero-banner :trang-chu="$trangChu ?? null" :ngoi-am-duongs="$ngoiAmDuongs ?? null" :ngoi-hais="$ngoiHais ?? null" :gach-hoas="$gachHoas ?? null" :about="$about ?? null" :factory="$factory ?? null" :showroom-images="$showroomImages ?? null" :showroom-content="$showroomContent ?? null" :news="$news ?? null" :article="$article ?? null" :articles="$articles ?? null" :related-articles="$relatedArticles ?? null" :history-articles="$historyArticles ?? null" :projects="$projects ?? null" :project="$project ?? null" :related-projects="$relatedProjects ?? null" :categories="$categories ?? null" :selected-category="$selectedCategory ?? null" :current-category="$currentCategory ?? null" :config="$config ?? null" :products="$products ?? null" :related-products="$relatedProducts ?? null" :product="$product ?? null" :colors="$colors ?? null" :dinh-muc="$dinhMuc ?? null" :gia-tri-vuot-troi="$giaTriVuotTroi ?? null" :parent-config="$parentConfig ?? null" :page-label="$pageLabel ?? null" :index-route-name="$indexRouteName ?? null" :category-type="$categoryType ?? null" :category-label="$categoryLabel ?? null" :den-gom-products="$denGomProducts ?? null" :den-su-products="$denSuProducts ?? null" :featured-products="$featuredProducts ?? null" :collection-products="$collectionProducts ?? null" :nghe-products="$ngheProducts ?? null" :linh-vat-products="$linhVatProducts ?? null" />

<div class="w-[85%] max-w-[1320px] mx-auto pt-6 pb-3 md:pb-6 md:pt-8 relative z-10">
  <x-client.shared.breadcrumb current-label="Đèn Gốm Sứ" />
</div>
<x-client.shared.product-filter />

<!-- Danh mục Đèn Gốm -->
<x-client.products.den-gom-su.category-den-gom :trang-chu="$trangChu ?? null" :ngoi-am-duongs="$ngoiAmDuongs ?? null" :ngoi-hais="$ngoiHais ?? null" :gach-hoas="$gachHoas ?? null" :about="$about ?? null" :factory="$factory ?? null" :showroom-images="$showroomImages ?? null" :showroom-content="$showroomContent ?? null" :news="$news ?? null" :article="$article ?? null" :articles="$articles ?? null" :related-articles="$relatedArticles ?? null" :history-articles="$historyArticles ?? null" :projects="$projects ?? null" :project="$project ?? null" :related-projects="$relatedProjects ?? null" :categories="$categories ?? null" :selected-category="$selectedCategory ?? null" :current-category="$currentCategory ?? null" :config="$config ?? null" :products="$products ?? null" :related-products="$relatedProducts ?? null" :product="$product ?? null" :colors="$colors ?? null" :dinh-muc="$dinhMuc ?? null" :gia-tri-vuot-troi="$giaTriVuotTroi ?? null" :parent-config="$parentConfig ?? null" :page-label="$pageLabel ?? null" :index-route-name="$indexRouteName ?? null" :category-type="$categoryType ?? null" :category-label="$categoryLabel ?? null" :den-gom-products="$denGomProducts ?? null" :den-su-products="$denSuProducts ?? null" :featured-products="$featuredProducts ?? null" :collection-products="$collectionProducts ?? null" :nghe-products="$ngheProducts ?? null" :linh-vat-products="$linhVatProducts ?? null" />
<x-client.products.den-gom-su.product-list :products="$denGomProducts" :section-id="'den-gom-products'" />

<!-- Danh mục Đèn Sứ -->
<x-client.products.den-gom-su.category-den-su :trang-chu="$trangChu ?? null" :ngoi-am-duongs="$ngoiAmDuongs ?? null" :ngoi-hais="$ngoiHais ?? null" :gach-hoas="$gachHoas ?? null" :about="$about ?? null" :factory="$factory ?? null" :showroom-images="$showroomImages ?? null" :showroom-content="$showroomContent ?? null" :news="$news ?? null" :article="$article ?? null" :articles="$articles ?? null" :related-articles="$relatedArticles ?? null" :history-articles="$historyArticles ?? null" :projects="$projects ?? null" :project="$project ?? null" :related-projects="$relatedProjects ?? null" :categories="$categories ?? null" :selected-category="$selectedCategory ?? null" :current-category="$currentCategory ?? null" :config="$config ?? null" :products="$products ?? null" :related-products="$relatedProducts ?? null" :product="$product ?? null" :colors="$colors ?? null" :dinh-muc="$dinhMuc ?? null" :gia-tri-vuot-troi="$giaTriVuotTroi ?? null" :parent-config="$parentConfig ?? null" :page-label="$pageLabel ?? null" :index-route-name="$indexRouteName ?? null" :category-type="$categoryType ?? null" :category-label="$categoryLabel ?? null" :den-gom-products="$denGomProducts ?? null" :den-su-products="$denSuProducts ?? null" :featured-products="$featuredProducts ?? null" :collection-products="$collectionProducts ?? null" :nghe-products="$ngheProducts ?? null" :linh-vat-products="$linhVatProducts ?? null" />
<x-client.products.den-gom-su.product-list :products="$denSuProducts" :section-id="'den-su-products'" />

<!-- Các phần khác -->
<x-client.products.den-gom-su.advantages-section :trang-chu="$trangChu ?? null" :ngoi-am-duongs="$ngoiAmDuongs ?? null" :ngoi-hais="$ngoiHais ?? null" :gach-hoas="$gachHoas ?? null" :about="$about ?? null" :factory="$factory ?? null" :showroom-images="$showroomImages ?? null" :showroom-content="$showroomContent ?? null" :news="$news ?? null" :article="$article ?? null" :articles="$articles ?? null" :related-articles="$relatedArticles ?? null" :history-articles="$historyArticles ?? null" :projects="$projects ?? null" :project="$project ?? null" :related-projects="$relatedProjects ?? null" :categories="$categories ?? null" :selected-category="$selectedCategory ?? null" :current-category="$currentCategory ?? null" :config="$config ?? null" :products="$products ?? null" :related-products="$relatedProducts ?? null" :product="$product ?? null" :colors="$colors ?? null" :dinh-muc="$dinhMuc ?? null" :gia-tri-vuot-troi="$giaTriVuotTroi ?? null" :parent-config="$parentConfig ?? null" :page-label="$pageLabel ?? null" :index-route-name="$indexRouteName ?? null" :category-type="$categoryType ?? null" :category-label="$categoryLabel ?? null" :den-gom-products="$denGomProducts ?? null" :den-su-products="$denSuProducts ?? null" :featured-products="$featuredProducts ?? null" :collection-products="$collectionProducts ?? null" :nghe-products="$ngheProducts ?? null" :linh-vat-products="$linhVatProducts ?? null" />
<x-client.shared.fabrication-process />
<x-client.shared.journey-video :hide-title="true" />
<x-client.shared.recommendations
  :related-products="$relatedProducts"
  route-name="client.products.den-gom-su.detail"
  pk-field="den_vuon_gom_su_ct_id"
  product-type="den_vuon_gom_su_ct"
/>

<!-- FAQ Section -->
<section class="w-full relative pb-[70px] md:pb-32 bg-background-secondary overflow-visible" data-aos="fade-up">
  <x-client.shared.faq-accordion />
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
