<x-client.layouts.main title="Về chúng tôi" data-page="about" main-class="about-content">
    @push('styles')
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css"
    />
    <style>
      #catalog-sticky-btn {
        top: 170px !important;
      }

      .about-description {
        color: #2e2f2a;
        font-family: "Roboto", sans-serif;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 28px;
        letter-spacing: 0.32px;
      }
    </style>
    @endpush

    <x-client.shared.catalog-sticky-btn />

    <x-client.about.hero-banner :about="$about ?? null" />
    <x-client.about.tab-navigation :about="$about ?? null" />
    <x-client.shared.newsletter />

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
</x-client.layouts.main>
