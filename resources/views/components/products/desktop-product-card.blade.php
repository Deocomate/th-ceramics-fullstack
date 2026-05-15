@props([
    'href',
    'image',
    'title',
    'code',
    'price',
    'aspectClass' => 'aspect-square',
    'class' => '',
])

<div class="flex flex-col group cursor-pointer {{ $class }}" onclick="window.location.href='{{ $href }}'">
  <div class="product-card relative w-full {{ $aspectClass }} shadow mb-4 flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:-translate-y-1">
    <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover" loading="lazy" />
    <div class="product-overlay">
      <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
      <span>Xem chi tiết</span>
    </div>
  </div>
  <h3 class="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase mb-1 tracking-wide transition-colors group-hover:text-secondary">
    {{ $title }}
  </h3>
  <p class="text-gray-500 text-[12px] lg:text-[13px] mb-1">{{ $code }}</p>
  <p class="font-bold text-[#C47526] text-[13px] lg:text-[14px]">{{ $price }}</p>
</div>
