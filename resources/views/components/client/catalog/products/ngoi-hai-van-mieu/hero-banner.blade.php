@props([
    'config' => null,
])
<!-- Top Banner -->
<section class="relative w-full pt-0 md:pt-20 pb-0 md:pb-8 lg:pb-10">
  <!-- Mobile Top Banner -->
  <div class="md:hidden relative h-[403px] overflow-hidden">
    <div class="absolute inset-x-0 top-0 h-[245px] z-0">
      <img src="{{ $config && $config->thumbnail_main ? asset('storage/' . $config->thumbnail_main) : asset('assets/images/ngoi-hai-van-mieu-banner.jpg') }}" alt="Ngói Hài Văn Miếu Banner"
        class="w-full h-full object-cover object-center" />
      <div class="absolute inset-0" style="
                background: linear-gradient(
                  188deg,
                  #000 -7.92%,
                  rgba(0, 0, 0, 0) 94.09%
                );
              "></div>
    </div>

    <div class="absolute inset-x-0 top-[245px] h-[158px] bg-background-secondary z-0"></div>

    <div class="relative z-10 h-full w-full">
      <h1
        class="ngoi-hai-mobile-title pt-[75px] text-center text-[30px] font-archivo font-bold uppercase text-white leading-[42px]"
        data-aos="fade-up" data-aos-duration="1000">
        Ngói Hài Văn Miếu
      </h1>

      <div class="w-full max-w-[340px] absolute left-1/2 top-[165px] z-20 grid grid-cols-3 gap-[51px] -translate-x-1/2">
        <div class="flex flex-col items-center">
          <div class="relative w-[82px]">
            <div
              class="absolute -top-[5px] -left-[5px] right-[5px] bottom-[5px] border border-white pointer-events-none">
            </div>
            <img src="{{ $config && $config->thumbnail1 ? asset('storage/' . $config->thumbnail1) : asset('assets/images/ngoi-hai-01.png') }}" alt="{{ $config->title1 ?? 'Ngói văn miếu tròn' }}"
              class="relative z-10 block w-full h-[136px] object-cover" />
          </div>
          <h3
            class="ngoi-hai-mobile-name mt-[20px] text-center text-[16px] font-charm font-normal text-textPrimary leading-[24px]">
            {!! nl2br(e($config->title1 ?? 'Ngói văn miếu tròn')) !!}
          </h3>
        </div>

        <div class="mt-[35px] flex flex-col items-center">
          <div class="relative w-[82px]">
            <div
              class="absolute -top-[5px] -left-[5px] right-[5px] bottom-[5px] border border-white pointer-events-none">
            </div>
            <img src="{{ $config && $config->thumbnail2 ? asset('storage/' . $config->thumbnail2) : asset('assets/images/ngoi-hai-02.png') }}" alt="{{ $config->title2 ?? 'Ngói văn miếu mũi' }}"
              class="relative z-10 block w-full h-[136px] object-cover" />
          </div>
          <h3
            class="ngoi-hai-mobile-name mt-[20px] text-center text-[16px] font-charm font-normal text-textPrimary leading-[24px]">
            {!! nl2br(e($config->title2 ?? 'Ngói văn miếu mũi')) !!}
          </h3>
        </div>

        <div class="flex flex-col items-center">
          <div class="relative w-[82px]">
            <div
              class="absolute -top-[5px] -left-[5px] right-[5px] bottom-[5px] border border-white pointer-events-none">
            </div>
            <img src="{{ $config && $config->thumbnail3 ? asset('storage/' . $config->thumbnail3) : asset('assets/images/ngoi-hai-03.png') }}" alt="{{ $config->title3 ?? 'Ngói hài cổ' }}"
              class="relative z-10 block w-full h-[136px] object-cover" />
          </div>
          <h3
            class="ngoi-hai-mobile-name ngoi-hai-mobile-name--tall mt-[20px] text-center text-[16px] font-charm font-normal text-textPrimary leading-[30px]">
            {!! nl2br(e($config->title3 ?? 'Ngói hài cổ')) !!}
          </h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Desktop Top Banner -->
  <div class="hidden md:block">
    <!-- Background Image with Dark Overlay -->
    <div class="absolute inset-x-0 top-0 h-[65%] lg:h-[75%] z-0">
      <img src="{{ $config && $config->thumbnail_main ? asset('storage/' . $config->thumbnail_main) : asset('assets/images/ngoi-hai-van-mieu-banner.jpg') }}" alt="Ngói Hài Văn Miếu Banner"
        class="w-full h-full object-cover object-center" />
      <div class="absolute inset-0" style="
                background: linear-gradient(
                  188deg,
                  #000 -7.92%,
                  rgba(0, 0, 0, 0) 94.09%
                );
              "></div>
      <div class="absolute bottom-0 left-0 right-0 h-32 bg-background-secondary"></div>
    </div>

    <!-- Content Container -->
    <div class="relative z-10 w-[85%] max-w-[1320px] mx-auto flex flex-col items-center">
      <!-- Title -->
      <h1
        class="ngoi-hai-mobile-title text-[30px] md:text-[40px] font-archivo font-bold uppercase text-white leading-[42px] md:leading-[55px] mb-8 md:mb-16 text-center"
        data-aos="fade-up" data-aos-duration="1000">
        Ngói Hài Văn Miếu
      </h1>

      <!-- 3 Images Layout -->
      <div class="flex flex-row justify-center items-start gap-6 md:gap-8 lg:gap-10 w-full max-w-[1200px]">
        <!-- Item 1 -->
        <div class="flex flex-col items-center w-full md:w-1/3 md:mt-8 pl-4 pt-4 sm:pl-5 sm:pt-5 pr-2"
          data-aos="fade-up" data-aos-delay="100">
          <div class="relative w-full mb-6">
            <div
              class="absolute -top-2 -left-2 sm:-top-3 sm:-left-3 w-full h-full border border-white/80 z-20 pointer-events-none">
            </div>
            <img src="{{ $config && $config->thumbnail1 ? asset('storage/' . $config->thumbnail1) : asset('assets/images/ngoi-hai-01.png') }}" alt="{{ $config->title1 ?? 'Ngói văn miếu tròn' }}"
              class="relative z-10 w-full h-auto aspect-[3/5] object-cover shadow-2xl" />
          </div>
          <h3
            class="ngoi-hai-mobile-name font-charm text-[20px] lg:text-[32px] text-textPrimary text-center drop-shadow-sm -translate-x-2">
            {{ $config->title1 ?? 'Ngói văn miếu tròn' }}
          </h3>
        </div>

        <!-- Item 2 (Center, shifted down) -->
        <div class="flex flex-col items-center w-full md:w-1/3 mt-8 md:mt-24 pl-4 pt-4 sm:pl-5 sm:pt-5 pr-2"
          data-aos="fade-up" data-aos-delay="250">
          <div class="relative w-full mb-6">
            <div
              class="absolute -top-2 -left-2 sm:-top-3 sm:-left-3 w-full h-full border border-white/80 z-20 pointer-events-none">
            </div>
            <img src="{{ $config && $config->thumbnail2 ? asset('storage/' . $config->thumbnail2) : asset('assets/images/ngoi-hai-02.png') }}" alt="{{ $config->title2 ?? 'Ngói văn miếu mũi' }}"
              class="relative z-10 w-full h-auto aspect-[3/5] object-cover shadow-2xl" />
          </div>
          <h3
            class="ngoi-hai-mobile-name font-charm text-[20px] lg:text-[32px] text-textPrimary text-center drop-shadow-sm -translate-x-2">
            {{ $config->title2 ?? 'Ngói văn miếu mũi' }}
          </h3>
        </div>

        <!-- Item 3 -->
        <div class="flex flex-col items-center w-full md:w-1/3 md:mt-8 pl-4 pt-4 sm:pl-5 sm:pt-5 pr-2"
          data-aos="fade-up" data-aos-delay="400">
          <div class="relative w-full mb-6">
            <div
              class="absolute -top-2 -left-2 sm:-top-3 sm:-left-3 w-full h-full border border-white/80 z-20 pointer-events-none">
            </div>
            <img src="{{ $config && $config->thumbnail3 ? asset('storage/' . $config->thumbnail3) : asset('assets/images/ngoi-hai-03.png') }}" alt="{{ $config->title3 ?? 'Ngói hài cổ' }}"
              class="relative z-10 w-full h-auto aspect-[3/5] object-cover shadow-2xl" />
          </div>
          <h3
            class="ngoi-hai-mobile-name ngoi-hai-mobile-name--tall font-charm text-[20px] lg:text-[32px] text-textPrimary text-center drop-shadow-sm -translate-x-2">
            {{ $config->title3 ?? 'Ngói hài cổ' }}
          </h3>
        </div>
      </div>
    </div>
  </div>
</section>
