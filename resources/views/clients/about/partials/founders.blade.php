@php
  $founderImage = \App\Support\AssetPath::url($about->gs_nguoi_sang_lap_anh ?? null, 'assets/images/about-01.png');
  $founderContent = $about->gs_nguoi_sang_lap_noi_dung ?? 'Trải qua nhiều thăng trầm của nghề, hai người sáng lập vẫn bền bỉ theo đuổi sản phẩm gốm sứ xây dựng.';
@endphp

<!-- Section 5: Founders / Người sáng lập -->
<div class="md:mt-24 mb-[30px] md:mb-16">
  <h3
    class="relative z-20 text-[20px] md:text-4xl font-bold text-center text-textPrimary mb-8 md:mb-14 uppercase tracking-normal"
    data-aos="fade-up"
  >
    NGƯỜI SÁNG LẬP
  </h3>
  <div
    class="flex flex-col md:flex-row items-center gap-8 md:gap-16"
    data-aos="fade-up"
    data-aos-delay="200"
  >
    <!-- Image -->
    <div class="w-full md:w-1/2">
      <div
        class="aspect-[1/1] relative overflow-hidden shadow-lg rounded-sm mx-auto md:mx-0 max-w-[604px]"
      >
        <img
          src="{{ $founderImage }}"
          alt="Người sáng lập"
          class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700"
        />
      </div>
    </div>
    <!-- Text Content -->
    <div class="w-full md:w-1/2 xl:pr-16">
      <p
        class="text-textPrimary leading-[28px] text-justify md:text-left font-medium tracking-wide"
      >
        {{ $founderContent }}
      </p>
    </div>
  </div>
</div>
