<x-client.layouts.main title="Showroom" data-page="showroom" mainClass="bg-[#2E2E29]">

    @push('styles')
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
        </style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @endpush

    <x-client.showroom.hero-banner />
    <x-client.showroom.main-content :showroom-images="$showroomImages" />
    <x-client.showroom.display-slider :showroom-images="$showroomImages" :showroom-content="$showroomContent" />
    <x-client.showroom.location-map />
    <x-client.shared.newsletter />

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <!-- Swiper Initialization -->
        <script>
            if (window.AOS) {
                AOS.init({
                    once: true,
                    duration: 800,
                    offset: 50
                });
            }

            if (window.Swiper) {
                new Swiper(".showroomSwiper", {
                    slidesPerView: "auto",
                    spaceBetween: 24,
                    loop: false,
                    navigation: {
                        nextEl: ".showroom-next",
                        prevEl: ".showroom-prev",
                    },
                });

                new Swiper(".showroomSwiper2", {
                    slidesPerView: "auto",
                    spaceBetween: 24,
                    loop: false,
                    initialSlide: 1,
                    rtl: true,
                });
            }
        </script>
    @endpush

</x-client.layouts.main>
