<x-client.layouts.main title="{{ $article->tieu_de ?? 'Chi tiết tin tức' }}" data-page="news-detail"
    main-class="bg-background-secondary">

    {{-- NỘI DUNG TRANG CHI TIẾT TIN TỨC --}}
    <x-client.news.detail-hero :trang-chu="$trangChu ?? null" :ngoi-am-duongs="$ngoiAmDuongs ?? null" :ngoi-hais="$ngoiHais ?? null" :gach-hoas="$gachHoas ?? null" :about="$about ?? null"
        :factory="$factory ?? null" :showroom-images="$showroomImages ?? null" :showroom-content="$showroomContent ?? null" :news="$news ?? null" :article="$article ?? null" :articles="$articles ?? null"
        :related-articles="$relatedArticles ?? null" :history-articles="$historyArticles ?? null" :projects="$projects ?? null" :project="$project ?? null" :related-projects="$relatedProjects ?? null"
        :categories="$categories ?? null" :selected-category="$selectedCategory ?? null" :current-category="$currentCategory ?? null" :config="$config ?? null" :products="$products ?? null"
        :related-products="$relatedProducts ?? null" :product="$product ?? null" :colors="$colors ?? null" :dinh-muc="$dinhMuc ?? null" :gia-tri-vuot-troi="$giaTriVuotTroi ?? null"
        :parent-config="$parentConfig ?? null" :page-label="$pageLabel ?? null" :index-route-name="$indexRouteName ?? null" :category-type="$categoryType ?? null" :category-label="$categoryLabel ?? null"
        :den-gom-products="$denGomProducts ?? null" :den-su-products="$denSuProducts ?? null" :featured-products="$featuredProducts ?? null" :collection-products="$collectionProducts ?? null" :nghe-products="$ngheProducts ?? null"
        :linh-vat-products="$linhVatProducts ?? null" />
    <x-client.news.detail-content :trang-chu="$trangChu ?? null" :ngoi-am-duongs="$ngoiAmDuongs ?? null" :ngoi-hais="$ngoiHais ?? null" :gach-hoas="$gachHoas ?? null"
        :about="$about ?? null" :factory="$factory ?? null" :showroom-images="$showroomImages ?? null" :showroom-content="$showroomContent ?? null" :news="$news ?? null"
        :article="$article ?? null" :articles="$articles ?? null" :related-articles="$relatedArticles ?? null" :history-articles="$historyArticles ?? null" :projects="$projects ?? null"
        :project="$project ?? null" :related-projects="$relatedProjects ?? null" :categories="$categories ?? null" :selected-category="$selectedCategory ?? null" :current-category="$currentCategory ?? null"
        :config="$config ?? null" :products="$products ?? null" :related-products="$relatedProducts ?? null" :product="$product ?? null" :colors="$colors ?? null"
        :dinh-muc="$dinhMuc ?? null" :gia-tri-vuot-troi="$giaTriVuotTroi ?? null" :parent-config="$parentConfig ?? null" :page-label="$pageLabel ?? null" :index-route-name="$indexRouteName ?? null"
        :category-type="$categoryType ?? null" :category-label="$categoryLabel ?? null" :den-gom-products="$denGomProducts ?? null" :den-su-products="$denSuProducts ?? null" :featured-products="$featuredProducts ?? null"
        :collection-products="$collectionProducts ?? null" :nghe-products="$ngheProducts ?? null" :linh-vat-products="$linhVatProducts ?? null" />
    <x-client.news.catalog-section :trang-chu="$trangChu ?? null" :ngoi-am-duongs="$ngoiAmDuongs ?? null" :ngoi-hais="$ngoiHais ?? null" :gach-hoas="$gachHoas ?? null"
        :about="$about ?? null" :factory="$factory ?? null" :showroom-images="$showroomImages ?? null" :showroom-content="$showroomContent ?? null" :news="$news ?? null"
        :article="$article ?? null" :articles="$articles ?? null" :related-articles="$relatedArticles ?? null" :history-articles="$historyArticles ?? null" :projects="$projects ?? null"
        :project="$project ?? null" :related-projects="$relatedProjects ?? null" :categories="$categories ?? null" :selected-category="$selectedCategory ?? null" :current-category="$currentCategory ?? null"
        :config="$config ?? null" :products="$products ?? null" :related-products="$relatedProducts ?? null" :product="$product ?? null" :colors="$colors ?? null"
        :dinh-muc="$dinhMuc ?? null" :gia-tri-vuot-troi="$giaTriVuotTroi ?? null" :parent-config="$parentConfig ?? null" :page-label="$pageLabel ?? null" :index-route-name="$indexRouteName ?? null"
        :category-type="$categoryType ?? null" :category-label="$categoryLabel ?? null" :den-gom-products="$denGomProducts ?? null" :den-su-products="$denSuProducts ?? null" :featured-products="$featuredProducts ?? null"
        :collection-products="$collectionProducts ?? null" :nghe-products="$ngheProducts ?? null" :linh-vat-products="$linhVatProducts ?? null" />
    <x-client.news.author-section />
    <x-client.news.detail-related :trang-chu="$trangChu ?? null" :ngoi-am-duongs="$ngoiAmDuongs ?? null" :ngoi-hais="$ngoiHais ?? null" :gach-hoas="$gachHoas ?? null"
        :about="$about ?? null" :factory="$factory ?? null" :showroom-images="$showroomImages ?? null" :showroom-content="$showroomContent ?? null" :news="$news ?? null"
        :article="$article ?? null" :articles="$articles ?? null" :related-articles="$relatedArticles ?? null" :related-news="$relatedNews ?? null" :history-articles="$historyArticles ?? null"
        :projects="$projects ?? null" :project="$project ?? null" :related-projects="$relatedProjects ?? null" :categories="$categories ?? null" :selected-category="$selectedCategory ?? null"
        :current-category="$currentCategory ?? null" :config="$config ?? null" :products="$products ?? null" :related-products="$relatedProducts ?? null" :product="$product ?? null"
        :colors="$colors ?? null" :dinh-muc="$dinhMuc ?? null" :gia-tri-vuot-troi="$giaTriVuotTroi ?? null" :parent-config="$parentConfig ?? null" :page-label="$pageLabel ?? null"
        :index-route-name="$indexRouteName ?? null" :category-type="$categoryType ?? null" :category-label="$categoryLabel ?? null" :den-gom-products="$denGomProducts ?? null" :den-su-products="$denSuProducts ?? null"
        :featured-products="$featuredProducts ?? null" :collection-products="$collectionProducts ?? null" :nghe-products="$ngheProducts ?? null" :linh-vat-products="$linhVatProducts ?? null" />
    <x-client.shared.newsletter />

</x-client.layouts.main>
