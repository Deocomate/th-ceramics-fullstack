@props([
    'href' => '#',
    'image' => null,
    'alt' => '',
    'title' => '',
    'code' => '',
    'price' => '',
    'aspect' => 'aspect-square shadow-lg',
    'blend' => true,
    'showOverlay' => false,
    'titleClass' => '',
])

<div
  {{ $attributes->merge(['class' => 'flex flex-col group cursor-pointer']) }}
>
  <a
    href="{{ $href }}"
    class="flex flex-col flex-grow"
  >
    <div class="product-card relative bg-white rounded-sm overflow-hidden mb-3 md:mb-5 transition-all duration-300 group-hover:-translate-y-1 {{ $aspect }}">
      <img
        src="{{ $image ?? asset('assets/images/ngoi-01.jpg') }}"
        alt="{{ $alt ?: $title }}"
        class="w-full h-full object-cover {{ $blend ? 'mix-blend-multiply' : '' }}"
        onerror="this.onerror=null; this.src='{{ asset('assets/images/ngoi-01.jpg') }}'"
      />
      @if($showOverlay)
      <div class="product-overlay">
        <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
        <span>Xem chi tiết</span>
      </div>
      @endif
    </div>
    <h3 class="{{ $titleClass ?: 'text-[#101010] font-medium text-[16px] leading-[25px] uppercase mb-1 transition-colors group-hover:text-secondary' }}">
      <span class="block lowercase first-letter:uppercase md:uppercase">
        {{ $title }}
      </span>
    </h3>
    @if($code)
    <p class="text-[#3C4043] font-light text-[14px] leading-[25px] mb-1">
      {{ $code }}
    </p>
    @endif
    @if($price)
    <p class="text-secondary font-bold text-[15px] leading-[15px]">
      {{ $price }}
    </p>
    @endif
  </a>

  @if($slot->isNotEmpty())
    <div class="mt-2">
      {{ $slot }}
    </div>
  @endif
</div>