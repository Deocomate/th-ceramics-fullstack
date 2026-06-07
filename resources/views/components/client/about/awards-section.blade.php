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
@php
  $awards = collect($giaiThuongThanhTuu ?? [])->values();
@endphp

<!-- Section 6: Awards / Giải thưởng & Thành tựu -->
<div class="mt-8 md:mt-24 mb-16 w-full">
  <h3
    class="text-[20px] leading-[24px] md:text-4xl font-bold text-center text-[#C76E00] mb-5 md:mb-10 uppercase tracking-normal"
    data-aos="fade-up"
  >
    GIẢI THƯỞNG & THÀNH TỰU
  </h3>
  @if ($awards->isNotEmpty())
  <x-client.home.awards-deck :awards="$awards" />
  @else
  <x-client.home.awards-deck :trang-chu="$trangChu ?? null" :ngoi-am-duongs="$ngoiAmDuongs ?? null" :ngoi-hais="$ngoiHais ?? null" :gach-hoas="$gachHoas ?? null" :about="$about ?? null" :factory="$factory ?? null" :showroom-images="$showroomImages ?? null" :showroom-content="$showroomContent ?? null" :news="$news ?? null" :article="$article ?? null" :articles="$articles ?? null" :related-articles="$relatedArticles ?? null" :history-articles="$historyArticles ?? null" :projects="$projects ?? null" :project="$project ?? null" :related-projects="$relatedProjects ?? null" :categories="$categories ?? null" :selected-category="$selectedCategory ?? null" :current-category="$currentCategory ?? null" :config="$config ?? null" :products="$products ?? null" :related-products="$relatedProducts ?? null" :product="$product ?? null" :colors="$colors ?? null" :dinh-muc="$dinhMuc ?? null" :gia-tri-vuot-troi="$giaTriVuotTroi ?? null" :parent-config="$parentConfig ?? null" :page-label="$pageLabel ?? null" :index-route-name="$indexRouteName ?? null" :category-type="$categoryType ?? null" :category-label="$categoryLabel ?? null" :den-gom-products="$denGomProducts ?? null" :den-su-products="$denSuProducts ?? null" :featured-products="$featuredProducts ?? null" :collection-products="$collectionProducts ?? null" :nghe-products="$ngheProducts ?? null" :linh-vat-products="$linhVatProducts ?? null" />
  @endif
</div>
