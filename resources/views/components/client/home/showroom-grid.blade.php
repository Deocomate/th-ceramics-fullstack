@props([
    'trangChu' => null,
])
<section class="bg-primary pb-[25px] md:pb-16 lg:pb-[106px] pt-8 lg:pt-12">
  <div class="w-[85%] max-w-[1320px] mx-auto">

    <div class="flex justify-center lg:justify-between items-center mb-8 lg:mb-12" data-aos="fade-up">
      <h2 class="text-secondary text-[20px] leading-[32px] lg:text-[36px] lg:font-bold lg:leading-[62.50px] uppercase text-center lg:text-left">Showroom & xưởng sản xuất</h2>
      <a href="{{ route('client.showroom') }}" class="hidden lg:flex text-secondary lg:text-[16px] lg:font-bold lg:leading-[32px] uppercase hover:opacity-80 transition-opacity items-center gap-2">
        Xem thêm
        <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M10.707 2.29292C10.5184 2.11076 10.2658 2.00997 10.0036 2.01224C9.7414 2.01452 9.49059 2.11969 9.30518 2.3051C9.11977 2.49051 9.0146 2.74132 9.01233 3.00352C9.01005 3.26571 9.11084 3.51832 9.293 3.70692L12.586 6.99992H1C0.734784 6.99992 0.48043 7.10528 0.292893 7.29281C0.105357 7.48035 0 7.7347 0 7.99992C0 8.26514 0.105357 8.51949 0.292893 8.70703C0.48043 8.89456 0.734784 8.99992 1 8.99992H12.586L9.293 12.2929C9.19749 12.3852 9.12131 12.4955 9.0689 12.6175C9.01649 12.7395 8.9889 12.8707 8.98775 13.0035C8.9866 13.1363 9.0119 13.268 9.06218 13.3909C9.11246 13.5138 9.18671 13.6254 9.28061 13.7193C9.3745 13.8132 9.48615 13.8875 9.60905 13.9377C9.73194 13.988 9.86362 14.0133 9.9964 14.0122C10.1292 14.011 10.2604 13.9834 10.3824 13.931C10.5044 13.8786 10.6148 13.8024 10.707 13.7069L15.707 8.70692C15.8945 8.51939 15.9998 8.26508 15.9998 7.99992C15.9998 7.73475 15.8945 7.48045 15.707 7.29292L10.707 2.29292Z" fill="currentColor"/>
        </svg>
      </a>
    </div>

    @php $showroomImages = $trangChu?->showroom_images ?? []; @endphp

    @if(!empty($showroomImages))
    <div class="showroom-container grid grid-cols-3 gap-[5px] lg:gap-6 mb-4 lg:mb-0" data-aos="fade-up" data-aos-delay="200">
      @foreach($showroomImages as $image)
      <img src="{{ Str::startsWith($image, 'assets/') ? asset($image) : asset('storage/' . $image) }}"
           alt="Showroom"
           class="w-full h-[119px] md:h-64 lg:h-[424px] object-cover rounded-none shadow-md md:shadow-lg {{ $loop->index === 1 ? 'mt-[10px] md:mt-0' : '' }}">
      @endforeach
    </div>
    @endif

    <div class="hidden">
      @if(!empty($showroomImages))
        @foreach($showroomImages as $index => $image)
        <button class="showroom-dot {{ $index === 0 ? 'active' : '' }} w-3 h-3 rounded-full {{ $index === 0 ? 'bg-secondary' : 'bg-white/40 hover:bg-white/60' }} transition-all" data-index="{{ $index }}" aria-label="Showroom image {{ $index + 1 }}"></button>
        @endforeach
      @endif
    </div>

    <div class="mt-8 md:mt-12 flex flex-col items-center" data-aos="fade-up" data-aos-delay="400">
      @if($trangChu?->showroom_noidung)
      <p class="text-white lg:text-[#EFE4DE] text-[12px] md:text-[20px] lg:text-[26px] font-['Roboto'] italic font-normal leading-[20px] md:leading-[32px] lg:leading-[43px] text-center max-w-[1142px] mx-auto tracking-[0.52px] break-words">
        {{ $trangChu->showroom_noidung }}
      </p>
      @endif
      <img src="{{ asset('assets/images/quotation-mark.svg') }}" alt="Quotation mark" class="w-10 h-10 md:w-16 md:h-16 mt-6 lg:mt-10">
    </div>
  </div>
</section>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const section = document.querySelector(".showroom-container")?.closest("section");
    if (!section) return;

    const showroomContainer = section.querySelector(".showroom-container");
    const showroomDots = Array.from(section.querySelectorAll(".showroom-dot"));

    if (!showroomContainer || !showroomDots.length) return;

    const updateActiveDot = () => {
      const firstImage = showroomContainer.querySelector("img");
      if (!firstImage) return;

      const itemWidth = firstImage.offsetWidth + 16;
      const index = Math.round(showroomContainer.scrollLeft / itemWidth);

      showroomDots.forEach((dot, dotIndex) => {
        const isActive = dotIndex === index;
        dot.classList.toggle("active", isActive);
        dot.classList.toggle("bg-secondary", isActive);
        dot.classList.toggle("bg-white/40", !isActive);
      });
    };

    showroomContainer.addEventListener("scroll", updateActiveDot);

    showroomDots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        const firstImage = showroomContainer.querySelector("img");
        if (!firstImage) return;

        const itemWidth = firstImage.offsetWidth + 16;
        showroomContainer.scrollTo({
          left: itemWidth * index,
          behavior: "smooth",
        });
      });
    });

    updateActiveDot();
  });
</script>
@endpush
