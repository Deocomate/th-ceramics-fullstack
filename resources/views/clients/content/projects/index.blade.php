<x-client.layouts.main title="Dự án" data-page="projects" main-class="bg-[#F5EDE8]">

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    @endpush

    <x-client.content.projects.list-section :categories="$categories" :projects="$projects" />
    <x-client.content.projects.catalog-section :page-config="$pageConfig ?? null" />
    <x-client.content.shared.newsletter />

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

</x-client.layouts.main>
