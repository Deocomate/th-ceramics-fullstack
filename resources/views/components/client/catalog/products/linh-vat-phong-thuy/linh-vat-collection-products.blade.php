@props([
    'products' => collect(),
])
<section class="w-full pb-16 animate-fade-in-up">
  <div
    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-10 sm:gap-x-6 sm:gap-y-14"
    data-aos="fade-up"
    data-aos-delay="200"
  >
    @foreach($products as $product)
      @php
        $productImage = \App\Domains\Catalog\Infrastructure\ProductGallery::firstImagePath($product->images ?? []);
        $imageUrl = $productImage ? asset('storage/' . $productImage) : asset('assets/images/ngoi-01.jpg');
      @endphp
      <x-client.catalog.shared.product-card
        href="{{ route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id) }}"
        image="{{ $imageUrl }}"
        title="{{ $product->name }}"
        code="MSP: {{ $product->code ?: 'Đang cập nhật' }}"
        price="{{ $product->price > 0 ? number_format($product->price, 0, ',', '.') . 'đ' : 'Liên hệ' }}"
        :show-overlay="true"
        detail-route-name="client.products.linh-vat-phong-thuy.detail"
        :product="$product"
      />
    @endforeach
  </div>
</section>
