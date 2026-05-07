<!-- Giá trị vượt trội Section -->
<section
  class="w-[85%] max-w-[1320px] mx-auto md:pb-20 pt-[30px] md:pt-12 gach-hoa-value-section"
  data-product-section
>
  <!-- Section Title -->
  <div class="text-center mb-[30px] md:mb-20" data-aos="fade-up">
    <h2
      class="text-[20px] md:text-3xl font-semibold text-secondary uppercase gach-hoa-value-title"
    >
      GIÁ TRỊ VƯỢT TRỘI
    </h2>
  </div>

  @php
    $bgColors = ['#1D78AD', '#5A7E46', '#B28373', '#C08B5C', '#7B6B8A', '#4A7C82'];
  @endphp
  <div
    class="gach-hoa-value-slider flex md:grid md:grid-cols-3 gap-6 md:gap-8 overflow-x-auto md:overflow-visible snap-x snap-mandatory md:snap-none touch-auto scrollbar-hide"
    data-product-carousel
    data-aos="fade-up"
    data-aos-delay="200"
  >
    @foreach ($config->giaTri as $item)
    @php
      $bgColor = $bgColors[$loop->index % count($bgColors)];
      $isOdd = $loop->odd;
    @endphp
    <!-- Column {{ $loop->iteration }} -->
    <div
      class="flex flex-col box-border min-w-full md:min-w-0 snap-start"
      data-product-slide
    >
      @if($isOdd)
      <div class="w-full flex justify-center mb-10 lg:mb-36">
        <div class="relative w-full aspect-[10/9]">
          <div class="absolute top-0 left-0 w-full h-[70%]" style="background: {{ $item->background ?: $bgColor }}"></div>
          <div
            class="absolute top-[20%] left-[18%] right-[18%] bottom-[-15%] shadow-lg bg-black/5 overflow-hidden"
          >
            <a
              href="{{ !empty($item->image) ? asset('storage/' . $item->image) : asset('assets/images/gach-hoa-value.png') }}"
              class="glightbox group block w-full h-full cursor-zoom-in"
              data-gallery="gia-tri-vuot-troi"
            >
              <img
                src="{{ !empty($item->image) ? asset('storage/' . $item->image) : asset('assets/images/gach-hoa-value.png') }}"
                alt="{{ $item->title ?? '' }}"
                class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.2]"
              />
            </a>
          </div>
        </div>
      </div>
      <div class="text-center mt-6 px-2 flex-grow flex flex-col justify-start">
        <h3
          class="text-[22px] lg:text-[36px] font-bold text-textPrimary mb-3 leading-snug gach-hoa-value-heading"
        >
          {!! nl2br(e($item->title ?? '')) !!}
        </h3>
        <p
          class="text-[14px] lg:text-base font-medium text-textPrimary leading-relaxed max-w-[290px] mx-auto gach-hoa-value-copy"
        >
          {{ $item->desscription ?? '' }}
        </p>
      </div>
      @else
      <div
        class="text-center mb-12 md:mb-6 px-2 flex-grow flex flex-col justify-start"
      >
        <h3
          class="text-[22px] lg:text-[36px] font-bold text-textPrimary mb-3 leading-snug gach-hoa-value-heading"
        >
          {!! nl2br(e($item->title ?? '')) !!}
        </h3>
        <p
          class="text-[14px] lg:text-base font-medium text-textPrimary leading-relaxed max-w-[290px] mx-auto gach-hoa-value-copy"
        >
          {{ $item->desscription ?? '' }}
        </p>
      </div>
      <div class="w-full flex justify-center mt-12 md:mt-10">
        <div class="relative w-full aspect-[10/9]">
          <div class="absolute bottom-0 left-0 w-full h-[70%]" style="background: {{ $item->background ?: $bgColor }}"></div>
          <div
            class="absolute bottom-[20%] left-[18%] right-[18%] top-[-15%] shadow-lg bg-black/5 overflow-hidden"
          >
            <a
              href="{{ !empty($item->image) ? asset('storage/' . $item->image) : asset('assets/images/work-03.jpg') }}"
              class="glightbox group block w-full h-full cursor-zoom-in"
              data-gallery="gia-tri-vuot-troi"
            >
              <img
                src="{{ !empty($item->image) ? asset('storage/' . $item->image) : asset('assets/images/work-03.jpg') }}"
                alt="{{ $item->title ?? '' }}"
                class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.2]"
              />
            </a>
          </div>
        </div>
      </div>
      @endif
    </div>
    @endforeach
  </div>

  <div
    class="mt-6 flex items-center justify-center gap-[7px] md:hidden"
    aria-label="Giá trị vượt trội mobile pagination"
  >
    @foreach ($config->giaTri as $item)
    <button
      type="button"
      class="product-section-dot h-2 w-2 rounded-full {{ $loop->first ? 'bg-secondary' : 'bg-[#C76E00]/30' }}"
      data-product-dot="{{ $loop->index }}"
      aria-label="Slide {{ $loop->iteration }}"
      aria-current="{{ $loop->first ? 'true' : 'false' }}"
    ></button>
    @endforeach
  </div>
</section>