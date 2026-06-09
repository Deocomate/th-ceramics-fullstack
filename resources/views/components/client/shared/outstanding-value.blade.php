<section class="w-full relative pb-8 md:pb-16" data-aos="fade-up" data-product-values>
  <div class="text-center mb-8 md:mb-12">
    <h2 class="text-[20px] md:text-[32px] md:leading-[45px] font-archivo font-semibold text-secondary uppercase">
      Giá trị vượt trội
    </h2>
  </div>

  <div
    class="relative w-full overflow-hidden flex flex-col min-h-[511px] md:min-h-[900px]"
  >
    <img
      src="{{ asset('assets/images/gia-tri-vuot-troi.png') }}"
      alt="Giá trị vượt trội nền"
      class="absolute inset-0 w-full h-full object-cover z-0"
    />
    <!-- Linear Gradient Overlay matching Figma design -->
    <div
      class="absolute inset-0 z-[1]"
      style="background: linear-gradient(92deg, rgba(255, 255, 255, 0) 0%, rgba(116.02, 44.05, 44.05, 0.54) 55%, rgba(116.02, 44.05, 44.05, 0.60) 84%), rgba(186.27, 112.52, 112.52, 0.34);"
    ></div>

    <div
      class="relative z-10 w-full flex-grow flex flex-col items-end justify-start md:justify-center text-white pt-[44px] md:pt-32 pr-6 md:pr-[10%] lg:pr-[240px]"
    >
      <!-- Title & Description Container: Align items to end (right) & text right, and keep it right-aligned relative to parent -->
      <div class="w-full max-w-[999px] flex flex-col items-end text-right gap-2 md:gap-10 pr-6 md:pr-0">
        <h3
          data-value-title
          class="text-[20px] leading-[32px] md:text-[32px] md:leading-[45px] font-semibold mb-[5px] md:mb-0 tracking-[0.6px] md:tracking-wide drop-shadow-md transition-all duration-500 uppercase font-archivo"
        >
          {{ $values->first()->title }}
        </h3>

        <p
          data-value-description
          class="font-charm text-[16px] leading-[28px] md:text-[32px] md:leading-[50px] md:tracking-[0.64px] transition-all duration-500 font-normal"
        >
          {{ $values->first()->desscription }}
        </p>
      </div>
    </div>

    <!-- Bottom Gallery Overlay aligned with left screen border -->
    <div
      class="absolute bottom-0 left-0 z-10 w-full md:relative md:bottom-auto md:left-auto mb-0 md:mb-16 pl-4 md:pl-0"
    >
      <div
        class="flex flex-row justify-start gap-2 md:gap-5 items-end translate-y-0 md:translate-y-[20px] pb-4 md:pb-0"
      >
        @foreach($values as $index => $item)
        <div
          class="value-image-item {{ $index === 0 ? 'active' : '' }} flex-shrink-0 shadow-lg"
          data-value-image
          data-title="{{ $item->title }}"
          data-description="{{ $item->desscription }}"
        >
          <img
            src="{{ $item->image_url }}"
            alt="{{ $item->title }}"
            class="w-full h-full object-cover"
          />
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>

@push('styles')
<style>
  .value-image-item {
    transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    cursor: pointer;
    position: relative;
    overflow: hidden;
    width: 80px;
    height: 130px;
  }

  .value-image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
  }

  .value-image-item:hover,
  .value-image-item.active {
    z-index: 20;
    width: 110px !important;
  }

  @media (min-width: 768px) {
    .value-image-item {
      width: 190px;
      height: 330px;
    }
    .value-image-item:hover,
    .value-image-item.active {
      width: 290px !important;
      height: 330px !important;
    }
  }
</style>
@endpush
