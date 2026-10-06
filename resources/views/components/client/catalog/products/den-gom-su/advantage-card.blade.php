@props([
    'bgImage' => null,
    'title' => 'Bền bỉ & Thân thiện',
    'description' => null,
])
<!-- Card Ưu điểm vượt trội (Dùng chung) -->
<div
  class="relative group h-full pt-[30px] md:pt-12"
  data-aos="fade-up"
  data-aos-delay="100"
>
  <div
    class="relative aspect-square overflow-hidden rounded-xl md:rounded-[4px] shadow-none md:shadow-[0px_4px_4px_rgba(0,0,0,0.25)] flex flex-col justify-center items-center text-center p-6 lg:p-8"
  >
    <!-- Hình nền của card -->
    <img
      src="{{ isset($bgImage) ? asset('storage/' . $bgImage) : asset('assets/images/den-gom-bg.png') }}"
      class="absolute inset-0 w-full h-full object-cover z-0 opacity-100 scale-[1.02]"
      alt="background"
    />

    <!-- Lớp phủ (Overlay) làm tối nền khi hover -->
    <div
      class="absolute inset-0 bg-black/50 z-10 transition-opacity group-hover:opacity-60"
    ></div>

    <!-- Nội dung text miêu tả ở giữa -->
    <p
      class="relative z-20 text-white text-sm lg:text-lg leading-relaxed font-normal md:font-medium md:text-[18px] md:leading-[30px] font-archivo max-w-[244px] mx-auto drop-shadow-md mb-16"
    >
      {!! $description ?? 'Chịu tốt mọi thời tiết, không han gỉ hay nứt vỡ như sắt và thủy tinh. Chất liệu đất nung tự nhiên cực kỳ an toàn và thân thiện môi trường' !!}
    </p>

    <!-- Trang trí footer ở dưới cùng -->
    <div class="absolute bottom-[-1px] left-0 w-full z-30">
      <!-- Mobile only decorative banner -->
      <div
        class="md:hidden relative overflow-hidden rounded-b-xl border-b border-black/5"
      >
        <img
          src="{{ asset('assets/images/den-gom-decorate-2.svg') }}"
          class="w-full h-auto block scale-x-110"
          alt="decorate"
        />
        <div
          class="absolute inset-0 flex items-center justify-center pt-2"
        >
          <!-- Tiêu đề của card -->
          <span
            class="text-[#C76E00] font-bold text-sm lg:text-base tracking-wider"
            >{{ $title ?? 'Bền bỉ & Thân thiện' }}</span
          >
        </div>
      </div>

      <!-- Desktop only clean banner -->
      <div
        class="hidden md:block relative w-full h-[62px] bg-white rounded-b-[4px] overflow-hidden"
        style="background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(199, 110, 0, 0.20) 100%), white;"
      >
        <div
          class="absolute inset-0 flex items-center justify-center"
        >
          <!-- Tiêu đề của card -->
          <span
            class="text-[#C76E00] font-semibold text-[18px] font-archivo leading-[30px] tracking-wide"
            >{{ $title ?? 'Bền bỉ & Thân thiện' }}</span
          >
        </div>
      </div>
    </div>
  </div>
</div>