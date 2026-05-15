@php
    use App\Support\AssetPath;

    $gallery1 = is_string($factory->gallery_1) ? json_decode($factory->gallery_1, true) : $factory->gallery_1;
    $gallery1 = is_array($gallery1) ? $gallery1 : [];
@endphp
<section class="bg-background-secondary py-8 overflow-hidden">
  <!-- Header row -->
  <div class="w-[85%] max-w-[1320px] mx-auto mb-8 justify-end hidden md:flex">
    <!-- Navigation Buttons -->
    <div class="flex gap-4">
      <button
        class="factory-prev w-10 h-10 md:w-12 md:h-12 border border-black/20 rounded-full flex items-center justify-center hover:bg-black/5 transition-all focus:outline-none"
      >
        <svg
          width="20"
          height="20"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="1.5"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M19 12H5M12 19l-7-7 7-7" />
        </svg>
      </button>
      <button
        class="factory-next w-10 h-10 md:w-12 md:h-12 border border-black/20 rounded-full flex items-center justify-center hover:bg-black/5 transition-all focus:outline-none"
      >
        <svg
          width="20"
          height="20"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="1.5"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path d="M5 12h14M12 5l7 7-7 7" />
        </svg>
      </button>
    </div>
  </div>

  <!-- Swiper Container -->
  <div
    class="md:ml-[7.5%] min-[1553px]:ml-[calc((100vw-1320px)/2)] lg:max-w-[1920px] overflow-hidden"
  >
    <div class="swiper factory-swiper overflow-visible">
      <div class="swiper-wrapper">
        @if(!empty($gallery1))
          @foreach($gallery1 as $image)
            <div class="swiper-slide w-full md:w-[70%] lg:w-[80%]">
              <div class="aspect-[2/1] overflow-hidden shadow-lg">
                <img
                  src="{{ AssetPath::url($image) }}"
                  alt="Gallery image"
                  class="w-full h-full object-cover transform transition-transform duration-700 hover:scale-105"
                />
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    new Swiper(".factory-swiper", {
      slidesPerView: "auto",
      spaceBetween: 24,
      navigation: {
        nextEl: ".factory-next",
        prevEl: ".factory-prev",
      },
      breakpoints: {
        768: {
          spaceBetween: 32,
        },
      },
    });
  });
</script>
@endpush
