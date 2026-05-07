<x-layouts.client title="Phụ Kiện Ngói" data-page="products" main-class="bg-background-secondary overflow-hidden" :hide-newsletter="true">

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<style>
  @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");
</style>
@endpush

<x-catalog-button />

<!-- Banner Section -->
<section class="relative w-full overflow-hidden">
  <div class="relative z-0">
    <img
      src="{{ $config->thumbnail_main ? asset('storage/' . $config->thumbnail_main) : asset('assets/images/den-gom-banner.png') }}"
      alt="Phụ Kiện Ngói Banner"
      class="w-full h-auto -mt-[60px] md:-mt-[100px] object-cover"
    />
  </div>
  <div
    class="absolute bottom-12 right-12 md:bottom-32 md:right-24 lg:bottom-44 lg:right-[10%] z-10 text-white text-right"
    data-aos="fade-left"
  >
    <h1
      class="font-carattere text-[48px] md:text-[80px] lg:text-[128px] leading-[0.8] drop-shadow-2xl select-none"
    >
      Phụ kiện ngói
    </h1>
  </div>
</section>

{{-- Ngói Bờ Nóc --}}
@include('clients.products.phu-kien-ngoi.partials.category-section-1')

{{-- Bờ Nóc Chữ Vạn --}}
@include('clients.products.phu-kien-ngoi.partials.category-section-2')

<x-products.outstanding-value />
<x-products.journey-video />
<x-products.works />

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    if (typeof GLightbox !== "undefined") {
      document.querySelectorAll(".glightbox").forEach((anchor) => {
        const image = anchor.querySelector("img");
        if (image) {
          anchor.setAttribute("href", image.currentSrc || image.src);
        }
      });
      GLightbox({
        touchNavigation: true,
        loop: true,
        autoplayVideos: true,
      });
    }
  });
</script>
@endpush

</x-layouts.client>
