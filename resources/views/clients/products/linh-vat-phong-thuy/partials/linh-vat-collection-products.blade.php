<section class="w-full pb-16 animate-fade-in-up">
  <div
    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-10 sm:gap-x-6 sm:gap-y-14"
    data-aos="fade-up"
    data-aos-delay="200"
  >
    @foreach($products as $product)
      @php
        $productImage = (!empty($product->images) && is_array($product->images)) ? $product->images[0] : null;
      @endphp
      <a href="{{ route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id) }}" class="flex flex-col group cursor-pointer">
        <div class="product-card relative bg-white rounded-sm shadow-lg overflow-hidden mb-4 aspect-square transition-all duration-300 group-hover:-translate-y-1">
          <img src="{{ $productImage ? asset('storage/' . $productImage) : asset('assets/images/ngoi-01.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover mix-blend-multiply" />
          <div class="product-overlay">
            <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
            <span>Xem chi tiết</span>
          </div>
        </div>
        <h3 class="text-primary font-semibold text-sm uppercase mb-2 transition-colors group-hover:text-secondary">
          <span class="block lowercase first-letter:uppercase md:uppercase">{{ $product->name }}</span>
        </h3>
        <p class="text-gray-500 text-[13px] mb-2">MSP: {{ $product->code }}</p>
        <p class="text-secondary font-bold text-[14px]">Giá: {{ $product->price > 0 ? number_format($product->price) . 'đ' : 'Liên hệ' }}</p>
      </a>
    @endforeach
  </div>
</section>
