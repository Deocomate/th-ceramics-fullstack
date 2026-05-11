<x-layouts.client title="Về chúng tôi" data-page="about" main-class="about-content">
    @push('styles')
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css"
    />
    <style>
      #catalog-sticky-btn {
        top: 170px !important;
      }

      .about-content p {
        line-height: 1.75;
      }

      .tab-craft-copy p {
        color: #2e2f2a;
        font-family: "Roboto", sans-serif;
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        line-height: 28px;
        letter-spacing: 0.48px;
      }
    </style>
    @endpush

    <x-catalog-button />

    @include('clients.about.partials.banner', ['about' => $about ?? null])
    @include('clients.about.partials.tabs', ['about' => $about ?? null])
    <x-newsletter />

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <script>
      // Keep lightbox links in sync with actual rendered image URLs (works with Vite asset hashing).
      document.querySelectorAll(".glightbox").forEach((anchor) => {
        const image = anchor.querySelector("img");
        if (image) {
          anchor.setAttribute("href", image.currentSrc || image.src);
        }
      });

      const lightbox = GLightbox({
        touchNavigation: true,
        loop: true,
        autoplayVideos: true,
      });
    </script>
    @endpush
</x-layouts.client>
