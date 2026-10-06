<x-client.layouts.main title="Ngói Hài Văn Miếu" data-page="products" main-class="bg-background-secondary page-ngoi-hai-van-mieu" :hide-newsletter="true">

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<style>
  /* Đã gộp 3 font vào 1 request để tăng tốc độ tải trang */
  @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&family=Playfair+Display:wght@400;500;600;700&display=swap");

  @media (max-width: 767.98px) {
    .page-ngoi-hai-van-mieu .ngoi-hai-mobile-title {
      color: #fff;
      font-family: Archivo, sans-serif;
      font-size: 30px;
      font-weight: 700;
      line-height: 42px;
      text-transform: uppercase;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-mobile-name {
      color: #2e2f2a;
      font-family: Charm, cursive;
      font-size: 16px;
      font-weight: 400;
      line-height: 24px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-mobile-name--tall {
      line-height: 30px;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-breadcrumb-scope p,
    .page-ngoi-hai-van-mieu .ngoi-hai-breadcrumb-scope a,
    .page-ngoi-hai-van-mieu .ngoi-hai-breadcrumb-scope span {
      font-family: Archivo, sans-serif;
      font-size: 12px;
      line-height: 16px;
      text-transform: uppercase;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-breadcrumb-scope p>a,
    .page-ngoi-hai-van-mieu .ngoi-hai-breadcrumb-scope p>span:not(:last-child) {
      color: rgba(46, 47, 42, 0.6);
      font-weight: 700;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-breadcrumb-scope p>span:last-child {
      color: #2e2f2a;
      font-weight: 600;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-product-grid-scope button {
      color: #2e2f2a;
      font-family: Archivo, sans-serif;
      font-size: 13px;
      font-weight: 700;
      line-height: 19.5px;
      letter-spacing: 0.65px;
      text-transform: uppercase;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-product-grid-scope h3 {
      color: #000;
      font-family: Archivo, sans-serif;
      font-size: 14px;
      font-weight: 600;
      line-height: 20px;
      text-transform: lowercase;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-product-grid-scope h3::first-letter {
      text-transform: uppercase;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-product-grid-scope p.text-gray-500 {
      color: #6b7280;
      font-family: Archivo, sans-serif;
      font-size: 12px;
      font-weight: 400;
      line-height: 20px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-product-grid-scope p.text-secondary {
      color: #c76e00;
      font-family: Archivo, sans-serif;
      font-size: 14px;
      font-weight: 700;
      line-height: 20px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-product-grid-scope .mt-\[50px\] a,
    .page-ngoi-hai-van-mieu .ngoi-hai-product-grid-scope .mt-\[50px\] span {
      font-family: Archivo, sans-serif;
      font-size: 17px;
      font-weight: 700;
      line-height: 25.5px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-product-grid-scope .mt-\[50px\] a.text-black {
      color: #000;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-product-grid-scope .mt-\[50px\] a.text-black\/40,
    .page-ngoi-hai-van-mieu .ngoi-hai-product-grid-scope .mt-\[50px\] span.text-black\/40 {
      color: rgba(0, 0, 0, 0.4);
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-product-grid-scope .mt-\[50px\] span.tracking-widest {
      letter-spacing: 1.7px;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-difference-title {
      color: #c76e00;
      font-family: Archivo, sans-serif;
      font-size: 20px;
      font-weight: 600;
      line-height: 32px;
      text-transform: uppercase;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-difference-heading {
      color: #333;
      font-family: Archivo, sans-serif;
      font-size: 20px;
      font-weight: 700;
      line-height: 36px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-difference-copy {
      color: #444;
      font-family: Archivo, sans-serif;
      font-size: 13px;
      font-weight: 500;
      line-height: 21.13px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-journey-scope h2,
    .page-ngoi-hai-van-mieu .ngoi-hai-works-scope h2 {
      color: #c76e00;
      font-family: Archivo, sans-serif;
      font-size: 20px;
      font-weight: 600;
      line-height: 32px;
      text-transform: uppercase;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-works-scope #slide-title {
      color: #000;
      font-family: Archivo, sans-serif;
      font-size: 16px;
      font-weight: 700;
      line-height: 24px;
      letter-spacing: 0.4px;
      text-transform: uppercase;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-works-scope #slide-meta {
      color: #000;
      font-family: Archivo, sans-serif;
      font-size: 15px;
      line-height: 22.5px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-works-scope #slide-meta .font-bold {
      font-weight: 700;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-works-scope #slide-meta span:not(.font-bold) {
      font-weight: 400;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-works-scope #slide-link {
      color: #000;
      font-family: Archivo, sans-serif;
      font-size: 13px;
      font-weight: 700;
      line-height: 19.5px;
      letter-spacing: 0.65px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-faq-scope h2 {
      color: #c76e00;
      font-family: Archivo, sans-serif;
      font-size: 20px;
      font-weight: 600;
      line-height: 36px;
      text-transform: uppercase;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-faq-scope .faq-button span:first-child {
      color: #2e2f2a;
      font-family: Archivo, sans-serif;
      font-size: 14px;
      font-weight: 700;
      line-height: 21px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-faq-scope .faq-content {
      color: #4b5563;
      font-family: Archivo, sans-serif;
      font-size: 14px;
      font-weight: 400;
      line-height: 22.75px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-faq-scope .faq-content .font-bold {
      color: #2e2f2a;
      font-weight: 700;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-faq-scope .faq-content a {
      color: #2e2f2a;
      font-family: Archivo, sans-serif;
      font-size: 14px;
      font-weight: 700;
      line-height: 22.75px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-faq-scope .mt-8.text-right a {
      color: #2e2f2a;
      font-family: Archivo, sans-serif;
      font-size: 14px;
      font-weight: 700;
      line-height: 21px;
      word-wrap: break-word;
    }

    /* Các css của .ngoi-hai-footer-scope đã được giữ nguyên */
    .page-ngoi-hai-van-mieu .ngoi-hai-footer-scope h2 {
      color: #efe4de;
      font-family: Archivo, sans-serif;
      font-size: 28px;
      font-weight: 600;
      line-height: 35px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-footer-scope input::placeholder {
      color: #9ca3af;
      font-family: Archivo, sans-serif;
      font-size: 14px;
      font-weight: 300;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-footer-scope .text-\[15px\].leading-\[26px\] p {
      color: #fff;
      font-family: Archivo, sans-serif;
      font-size: 15px;
      line-height: 26px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-footer-scope .text-\[15px\].leading-\[26px\] p:first-child {
      font-weight: 700;
      text-transform: uppercase;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-footer-scope .text-\[15px\].leading-\[26px\] p:not(:first-child) {
      font-weight: 400;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-footer-scope h3 {
      color: #fff;
      font-family: Archivo, sans-serif;
      font-weight: 600;
      line-height: 26px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-footer-scope h3.text-\[14px\] {
      font-size: 14px;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-footer-scope h3.text-\[16px\] {
      font-size: 16px;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-footer-scope .leading-\[30px\] a {
      color: #fff;
      font-family: Archivo, sans-serif;
      font-size: 12px;
      font-weight: 400;
      line-height: 30px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-footer-scope .border-t .text-\[\#909090\].text-\[12px\] {
      color: #909090;
      font-family: Archivo, sans-serif;
      font-size: 12px;
      font-weight: 400;
      line-height: 26px;
      word-wrap: break-word;
    }

    .page-ngoi-hai-van-mieu .ngoi-hai-footer-scope .border-t .text-\[\#909090\].text-\[12px\] span {
      color: #909090;
      font-size: 14px;
      font-weight: 400;
      line-height: 26px;
      word-wrap: break-word;
    }
  }
</style>
@endpush

<x-client.content.shared.catalog-sticky-btn />

<x-client.catalog.products.ngoi-hai-van-mieu.hero-banner :config="$config ?? null" />

<!-- BREADCRUMB & PRODUCT FILTER -->
<x-client.shared.product-breadcrumb-filter current-label="Ngói Hài Văn Miếu" />

<div class="ngoi-hai-product-grid-scope">
  <x-client.catalog.shared.product-grid category="ngoi-hai-van-mieu" :products="$products" routeName="client.products.ngoi-hai-van-mieu.detail" />
</div>

<x-client.catalog.products.ngoi-hai-van-mieu.difference-section />

<div class="ngoi-hai-journey-scope">
  <x-client.content.shared.outstanding-value />
  <x-client.catalog.shared.journey-video :video="$config->video ?? null" />
</div>

<div class="ngoi-hai-works-scope">
  <x-client.content.shared.works />
</div>

<!-- FAQ Section -->
<section class="w-full relative pb-[65px] md:pb-32 bg-background-secondary overflow-hidden" data-aos="fade-up">
  <!-- Background Decoration -->
  <img src="{{ asset('assets/images/background-decorate-03.svg') }}"
       class="absolute top-[20%] -translate-y-1/2 left-0 -translate-x-[35%] xl:-translate-x-[20%] w-auto max-h-[85%] object-contain opacity-100 pointer-events-none z-0"
       alt="" />
  <img src="{{ asset('assets/images/background-decorate-02.svg') }}"
       class="absolute top-[80%] -translate-y-1/2 right-0 translate-x-[35%] xl:translate-x-[20%] w-auto max-h-[85%] object-contain opacity-50 pointer-events-none z-0"
       alt="" />

  <div class="ngoi-hai-faq-scope">
    <x-client.content.shared.faq-accordion />
  </div>
</section>

<div class="ngoi-hai-footer-scope"></div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
<script>
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
</script>
@endpush
</x-client.layouts.main>
