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
<section class="w-full pb-16 animate-fade-in-up">
  <div
    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-10 sm:gap-x-6 sm:gap-y-14"
    data-aos="fade-up"
    data-aos-delay="200"
  >
    @foreach($products as $product)
      @php
        $productImage = (!empty($product->images) && is_array($product->images)) ? $product->images[0] : null;
        $imageUrl = $productImage ? asset('storage/' . $productImage) : asset('assets/images/ngoi-01.jpg');
      @endphp
      <x-client.shared.product-card
        href="{{ route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id) }}"
        image="{{ $imageUrl }}"
        title="{{ $product->name }}"
        code="MSP: {{ $product->code }}"
        price="{{ $product->price > 0 ? number_format($product->price) . 'đ' : 'Liên hệ' }}"
        :show-overlay="true"
      />
    @endforeach
  </div>
</section>
