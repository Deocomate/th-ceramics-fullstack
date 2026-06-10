<x-client.layouts.main title="Dự án" data-page="projects" main-class="bg-[#F5EDE8]">

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
@endpush

<x-client.projects.list-section :trang-chu="$trangChu ?? null" :ngoi-am-duongs="$ngoiAmDuongs ?? null" :ngoi-hais="$ngoiHais ?? null" :gach-hoas="$gachHoas ?? null" :about="$about ?? null" :factory="$factory ?? null" :showroom-images="$showroomImages ?? null" :showroom-content="$showroomContent ?? null" :news="$news ?? null" :article="$article ?? null" :articles="$articles ?? null" :related-articles="$relatedArticles ?? null" :history-articles="$historyArticles ?? null" :projects="$projects ?? null" :project="$project ?? null" :related-projects="$relatedProjects ?? null" :categories="$categories ?? null" :selected-category="$selectedCategory ?? null" :current-category="$currentCategory ?? null" :config="$config ?? null" :products="$products ?? null" :related-products="$relatedProducts ?? null" :product="$product ?? null" :colors="$colors ?? null" :dinh-muc="$dinhMuc ?? null" :gia-tri-vuot-troi="$giaTriVuotTroi ?? null" :parent-config="$parentConfig ?? null" :page-label="$pageLabel ?? null" :index-route-name="$indexRouteName ?? null" :category-type="$categoryType ?? null" :category-label="$categoryLabel ?? null" :den-gom-products="$denGomProducts ?? null" :den-su-products="$denSuProducts ?? null" :featured-products="$featuredProducts ?? null" :collection-products="$collectionProducts ?? null" :nghe-products="$ngheProducts ?? null" :linh-vat-products="$linhVatProducts ?? null" />
<x-client.projects.catalog-section :trang-chu="$trangChu ?? null" :ngoi-am-duongs="$ngoiAmDuongs ?? null" :ngoi-hais="$ngoiHais ?? null" :gach-hoas="$gachHoas ?? null" :about="$about ?? null" :factory="$factory ?? null" :showroom-images="$showroomImages ?? null" :showroom-content="$showroomContent ?? null" :news="$news ?? null" :article="$article ?? null" :articles="$articles ?? null" :related-articles="$relatedArticles ?? null" :history-articles="$historyArticles ?? null" :projects="$projects ?? null" :project="$project ?? null" :related-projects="$relatedProjects ?? null" :categories="$categories ?? null" :selected-category="$selectedCategory ?? null" :current-category="$currentCategory ?? null" :config="$config ?? null" :products="$products ?? null" :related-products="$relatedProducts ?? null" :product="$product ?? null" :colors="$colors ?? null" :dinh-muc="$dinhMuc ?? null" :gia-tri-vuot-troi="$giaTriVuotTroi ?? null" :parent-config="$parentConfig ?? null" :page-label="$pageLabel ?? null" :index-route-name="$indexRouteName ?? null" :category-type="$categoryType ?? null" :category-label="$categoryLabel ?? null" :den-gom-products="$denGomProducts ?? null" :den-su-products="$denSuProducts ?? null" :featured-products="$featuredProducts ?? null" :collection-products="$collectionProducts ?? null" :nghe-products="$ngheProducts ?? null" :linh-vat-products="$linhVatProducts ?? null" />

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
  if (typeof GLightbox !== "undefined") {
    // Use actual rendered img URLs so lightbox works with Vite asset hashing.
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
</script>
@endpush

</x-client.layouts.main>
