@props(['products' => [], 'category' => null, 'routeName' => null, 'pkField' => null])

<section
  class="w-[85%] max-w-[1320px] mx-auto pb-[43px] md:pb-16 animate-fade-in-up"
>
  <div
    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-[16px] gap-y-[30px] sm:gap-x-6 sm:gap-y-14"
    data-aos="fade-up"
    data-aos-delay="200"
  >
    @forelse ($products as $product)
    <a href="{{ $routeName ? route($routeName, $product->getKey()) : route('client.products.gach-hoa-thong-gio.detail', $product->gach_hoa_thong_gio_ct_id) }}" class="flex flex-col group cursor-pointer">
      <div
        class="product-card relative bg-white rounded-sm shadow-lg overflow-hidden mb-4 aspect-square transition-all duration-300 group-hover:-translate-y-1"
      >
        <img
          src="{{ !empty($product->images) && is_array($product->images) && isset($product->images[0]) ? Storage::url($product->images[0]) : asset('assets/images/ngoi-01.jpg') }}"
          alt="{{ $product->name ?? '' }}"
          class="w-full h-full object-cover mix-blend-multiply"
        />
        <div class="product-overlay">
          <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
          <span>Xem chi tiết</span>
        </div>
      </div>
      <h3
        class="text-primary font-semibold text-sm uppercase mb-2 transition-colors group-hover:text-secondary"
      >
        <span class="block lowercase first-letter:uppercase md:uppercase"
          >{{ $product->name ?? '' }}</span
        >
      </h3>
      <p class="text-gray-500 text-[13px] mb-2">MSP: {{ $product->code ?? '' }}</p>
      <p class="text-secondary font-bold text-[14px]">Giá: {{ $product->price > 0 ? number_format($product->price) . 'đ' : 'Liên hệ' }}</p>
    </a>
    @empty
    <div class="col-span-full text-center py-12 text-gray-500">Chưa có sản phẩm nào.</div>
    @endforelse
  </div>

  <div
    class="flex items-center justify-between gap-6 mt-[40px] md:mt-16 text-textPrimary font-bold text-[17px]"
  >
    <button
      class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer"
    >
      <svg
        class="w-5 h-5"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M15 19l-7-7 7-7"
        ></path>
      </svg>
    </button>

    <div class="flex items-center gap-5">
      <a href="#" class="text-black border-b-[3px] border-black pb-[2px] px-1"
        >1</a
      >
      <a href="#" class="text-black/40 hover:text-black transition-colors px-1"
        >2</a
      >
      <a href="#" class="text-black/40 hover:text-black transition-colors px-1"
        >3</a
      >
      <span class="text-black/40 tracking-widest px-1">...</span>
      <a href="#" class="text-black/40 hover:text-black transition-colors px-1"
        >9</a
      >
    </div>

    <button
      class="w-10 h-10 border-2 border-black/20 rounded-full flex items-center justify-center text-black/40 hover:border-black hover:text-black transition-all cursor-pointer"
    >
      <svg
        class="w-5 h-5"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M9 5l7 7-7 7"
        ></path>
      </svg>
    </button>
  </div>
</section>