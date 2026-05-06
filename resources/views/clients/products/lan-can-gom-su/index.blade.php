<x-layouts.client title="Lan Can Gốm Sứ" data-page="products" main-class="page-lan-can-gom-su bg-background-secondary">

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<style>
  @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
  @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");

  @media (max-width: 767.98px) {
    .page-lan-can-gom-su .lan-can-category-title {
      color: #c76e00;
      font-size: 24px;
      font-family: "Archivo", sans-serif;
      font-weight: 600;
      text-transform: uppercase;
      line-height: 32px;
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su h2[class*="text-[20px] md:text-3xl font-semibold text-secondary uppercase"]:not(.lan-can-category-title) {
      color: #c76e00;
      font-size: 20px;
      font-family: "Archivo", sans-serif;
      font-weight: 600;
      text-transform: uppercase;
      line-height: 32px;
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su .lan-can-brush-title {
      color: #fff;
      font-size: 24px;
      font-family: "Archivo", sans-serif;
      font-weight: 700;
      text-transform: uppercase;
      line-height: 36px;
      letter-spacing: 1.2px;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su h3[class*="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase mb-1 tracking-wide"] {
      color: #212121;
      font-size: 14px;
      font-family: "Archivo", sans-serif;
      font-weight: 700;
      text-transform: uppercase;
      line-height: 21px;
      letter-spacing: 0.35px;
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su p[class*="text-gray-500 text-[12px] lg:text-[13px] mb-1"] {
      color: #6b7280;
      font-size: 12px;
      font-family: "Archivo", sans-serif;
      font-weight: 400;
      line-height: 18px;
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su p[class*="font-bold text-[#C47526] text-[13px] lg:text-[14px]"] {
      color: #c47526;
      font-size: 13px;
      font-family: "Archivo", sans-serif;
      font-weight: 700;
      line-height: 19.5px;
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su .product-card .product-overlay span {
      color: #fff;
      font-size: 14px;
      font-family: "Archivo", sans-serif;
      font-weight: 400;
      text-transform: uppercase;
      line-height: 21px;
      letter-spacing: 1px;
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su #slide-title {
      color: #000;
      font-size: 16px;
      font-family: "Archivo", sans-serif;
      font-weight: 700;
      text-transform: uppercase;
      line-height: 24px;
      letter-spacing: 0.4px;
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su #slide-meta,
    .page-lan-can-gom-su #slide-meta p,
    .page-lan-can-gom-su #slide-meta span {
      color: #000;
      font-size: 15px;
      font-family: "Archivo", sans-serif;
      line-height: 22.5px;
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su #slide-meta .font-bold {
      font-weight: 700;
    }

    .page-lan-can-gom-su #slide-meta span {
      font-weight: 400;
    }

    .page-lan-can-gom-su #slide-link {
      color: #000;
      font-size: 13px;
      font-family: "Archivo", sans-serif;
      font-weight: 700;
      line-height: 19.5px;
      letter-spacing: 0.65px;
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su [data-faq-component] h2 {
      color: #c76e00;
      font-size: 20px;
      font-family: "Archivo", sans-serif;
      font-weight: 600;
      text-transform: uppercase;
      line-height: 36px;
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su [data-faq-component] .faq-button>span:first-child {
      color: #2e2f2a;
      font-size: 14px;
      font-family: "Archivo", sans-serif;
      font-weight: 700;
      line-height: 21px;
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su [data-faq-component] .faq-content {
      color: #4b5563;
      font-size: 14px;
      font-family: "Archivo", sans-serif;
      font-weight: 400;
      line-height: 22.75px;
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su [data-faq-component] .faq-content .font-bold {
      color: #2e2f2a;
      font-weight: 700;
    }

    .page-lan-can-gom-su [data-faq-component] .faq-item .faq-content a {
      color: #2e2f2a;
      font-size: 14px;
      font-family: "Archivo", sans-serif;
      font-weight: 700;
      line-height: 22.75px;
      overflow-wrap: break-word;
    }

    .page-lan-can-gom-su [data-faq-component] .mt-8>a {
      color: #2e2f2a;
      font-size: 14px;
      font-family: "Archivo", sans-serif;
      font-weight: 700;
      line-height: 21px;
      overflow-wrap: break-word;
    }
  }
</style>
@endpush

<x-catalog-button />

@include('clients.products.lan-can-gom-su.partials.category-section-1')
@include('clients.products.lan-can-gom-su.partials.category-section-2')

<!-- Product List Section -->
<section class="relative mx-auto pb-10 lg:pb-16 overflow-visible">
  <div class="relative w-[85%] max-w-[1320px] mx-auto z-10">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 lg:gap-x-8 gap-y-4 lg:gap-y-6" data-aos="fade-up">
      <div class="flex flex-col col-span-2 lg:col-span-2 group cursor-pointer" data-aos="fade-up" data-aos-delay="100"
        onclick="window.location.href = '#'">
        <div
          class="product-card relative w-full aspect-[2/1] md:aspect-[19/10] mb-4 flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:-translate-y-1 shadow-md">
          <img src="{{ asset('assets/images/lan-can-giot-le.jpg') }}" alt="Lan Can Giọt Lệ" class="w-full h-full object-cover" />
          <div class="product-overlay">
            <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
            <span>Xem chi tiết</span>
          </div>
        </div>
        <h3
          class="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase mb-1 tracking-wide transition-colors group-hover:text-secondary">
          Lan Can Giọt Lệ</h3>
        <p class="text-gray-500 text-[12px] lg:text-[13px] mb-1">MSP: 1234RDS</p>
        <p class="font-bold text-[#C47526] text-[13px] lg:text-[14px]">Giá: 675.000 đ/m2</p>
      </div>
      <!-- Product 3 -->
      <div class="flex flex-col group cursor-pointer" data-aos="fade-up" data-aos-delay="200"
        onclick="window.location.href = '#'">
        <div
          class="product-card relative w-full aspect-[1.1/1] md:aspect-[9/10] mb-4 flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:-translate-y-1 shadow-md">
          <img src="{{ asset('assets/images/lan-can-07.jpg') }}" alt="Lan Can Bầu" class="w-full h-full object-cover" />
          <div class="product-overlay">
            <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
            <span>Xem chi tiết</span>
          </div>
        </div>
        <h3
          class="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase mb-1 tracking-wide transition-colors group-hover:text-secondary">
          Lan Can Bầu</h3>
        <p class="text-gray-500 text-[12px] lg:text-[13px] mb-1">MSP: 1234RDS</p>
        <p class="font-bold text-[#C47526] text-[13px] lg:text-[14px]">Giá: 675.000 đ/m2</p>
      </div>
      <!-- Product 4 -->
      <div class="flex flex-col group cursor-pointer" data-aos="fade-up" data-aos-delay="300"
        onclick="window.location.href = '#'">
        <div
          class="product-card relative w-full aspect-[1.1/1] md:aspect-[9/10] mb-4 flex items-center justify-center overflow-hidden transition-all duration-300 group-hover:-translate-y-1 shadow-md">
          <img src="{{ asset('assets/images/lan-can-08.jpg') }}" alt="Lan Can Chữ Thọ" class="w-full h-full object-cover" />
          <div class="product-overlay">
            <img src="{{ asset('assets/images/eye.svg') }}" alt="Search" />
            <span>Xem chi tiết</span>
          </div>
        </div>
        <h3
          class="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase mb-1 tracking-wide transition-colors group-hover:text-secondary">
          Lan Can Chữ Thọ</h3>
        <p class="text-gray-500 text-[12px] lg:text-[13px] mb-1">MSP: 1234RDS</p>
        <p class="font-bold text-[#C47526] text-[13px] lg:text-[14px]">Giá: 675.000 đ/m2</p>
      </div>
    </div>
  </div>
</section>

<x-products.outstanding-value />
<x-products.journey-video />
<x-products.works />

<!-- FAQ Section -->
<section class="w-full relative pb-16 bg-background-secondary overflow-visible" data-aos="fade-up">
  <x-products.faq-content />
</section>

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