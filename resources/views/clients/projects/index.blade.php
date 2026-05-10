<x-layouts.client title="Dự án" data-page="projects">

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
@endpush

@include('clients.projects.partials.list')
@include('clients.projects.partials.catalog-section')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
  if (typeof GLightbox !== "undefined") {
    // Use actual rendered img URLs so lightbox works with Vite asset hashing.
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

</x-layouts.client>