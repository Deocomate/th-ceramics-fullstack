<x-client.layouts.main title="Gạch Trang Trí" data-page="products" main-class="bg-background-secondary" :hide-newsletter="true">

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Lavishly+Yours&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");

            :root {
                --slider-main-w: 850px;
                --slider-main-h: 781px;
                --slider-side-w: 112px;
                --slider-side-h: 781px;
                /* Side slides height equals main slide height */
                --slider-offset-1: 513px;
                --slider-offset-2: 657px;
                --slider-radius: 80px;
                --slider-motion-duration: 1.15s;
                --slider-motion-ease: cubic-bezier(0.33, 1, 0.68, 1);
                --slider-content-duration: 0.95s;
            }

            @media (min-width: 768px) and (max-width: 1279px) {
                :root {
                    --slider-main-w: 520px;
                    --slider-main-h: 480px;
                    --slider-side-w: 80px;
                    --slider-side-h: 480px;
                    /* Side slides height equals main slide height */
                    --slider-offset-1: 320px;
                    --slider-offset-2: 420px;
                    --slider-radius: 40px;
                }
            }

            @media (max-width: 767px) {
                :root {
                    --slider-main-w: min(82vw, 320px);
                    --slider-main-h: 380px;
                    --slider-side-w: min(82vw, 320px);
                    --slider-side-h: 380px;
                    /* Side slides height equals main slide height */
                    --slider-offset-1: calc(min(82vw, 320px) + 12px);
                    --slider-offset-2: calc(2 * min(82vw, 320px) + 24px);
                    --slider-radius: 30px;
                }
            }

            #custom-project-slider {
                touch-action: pan-y;
            }

            .custom-project-slide {
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                border-radius: var(--slider-radius);
                overflow: hidden;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
                will-change: width, height, transform, opacity;
                opacity: 0;
                pointer-events: none;
                transition: none;
            }

            .custom-project-slide.slide-center,
            .custom-project-slide.slide-left-1,
            .custom-project-slide.slide-left-2,
            .custom-project-slide.slide-right-1,
            .custom-project-slide.slide-right-2 {
                transition: width var(--slider-motion-duration) var(--slider-motion-ease),
                    height var(--slider-motion-duration) var(--slider-motion-ease),
                    transform var(--slider-motion-duration) var(--slider-motion-ease),
                    opacity var(--slider-motion-duration) var(--slider-motion-ease),
                    border-radius var(--slider-motion-duration) var(--slider-motion-ease),
                    filter 0.55s ease;
                pointer-events: auto;
                opacity: 1;
            }

            .custom-project-slide.slide-left-1,
            .custom-project-slide.slide-left-2,
            .custom-project-slide.slide-right-1,
            .custom-project-slide.slide-right-2 {
                cursor: pointer;
            }

            .custom-project-slide.slide-left-1:hover,
            .custom-project-slide.slide-left-2:hover,
            .custom-project-slide.slide-right-1:hover,
            .custom-project-slide.slide-right-2:hover {
                filter: brightness(1.05);
            }

            .custom-project-slide.slide-center {
                width: var(--slider-main-w);
                height: var(--slider-main-h);
                transform: translate(-50%, -50%);
                z-index: 10;
            }

            .custom-project-slide.slide-left-1 {
                width: var(--slider-side-w);
                height: var(--slider-side-h);
                transform: translate(calc(-50% - var(--slider-offset-1)), -50%);
                z-index: 5;
            }

            .custom-project-slide.slide-left-2 {
                width: var(--slider-side-w);
                height: var(--slider-side-h);
                transform: translate(calc(-50% - var(--slider-offset-2)), -50%);
                z-index: 4;
            }

            .custom-project-slide.slide-right-1 {
                width: var(--slider-side-w);
                height: var(--slider-side-h);
                transform: translate(calc(-50% + var(--slider-offset-1)), -50%);
                z-index: 5;
            }

            .custom-project-slide.slide-right-2 {
                width: var(--slider-side-w);
                height: var(--slider-side-h);
                transform: translate(calc(-50% + var(--slider-offset-2)), -50%);
                z-index: 4;
            }

            .custom-project-slide.slide-outer-left {
                width: var(--slider-side-w);
                height: var(--slider-side-h);
                transform: translate(calc(-50% - var(--slider-offset-2) - 150px), -50%);
                z-index: 1;
                opacity: 0;
            }

            .custom-project-slide.slide-outer-right {
                width: var(--slider-side-w);
                height: var(--slider-side-h);
                transform: translate(calc(-50% + var(--slider-offset-2) + 150px), -50%);
                z-index: 1;
                opacity: 0;
            }

            .custom-project-slide.slide-outer-left,
            .custom-project-slide.slide-outer-right {
                transition: transform var(--slider-motion-duration) var(--slider-motion-ease),
                    opacity calc(var(--slider-motion-duration) * 0.85) var(--slider-motion-ease);
            }

            .custom-project-slide .slide-content {
                opacity: 0;
                transform: translateY(20px);
                pointer-events: none;
                transition: opacity var(--slider-content-duration) var(--slider-motion-ease),
                    transform var(--slider-content-duration) var(--slider-motion-ease);
                transition-delay: 0s;
                z-index: 10;
            }

            .custom-project-slide.slide-center .slide-content {
                opacity: 1;
                transform: translateY(0);
                pointer-events: auto;
                transition-delay: 0.2s;
            }

            .project-dot {
                background-color: rgba(199, 110, 0, 0.3);
                transition: width 0.5s var(--slider-motion-ease), background-color 0.5s var(--slider-motion-ease);
            }

            .project-dot.active {
                background-color: #c76e00;
                width: 16px;
            }
        </style>
    @endpush

    <x-client.content.shared.catalog-sticky-btn />

    <x-client.catalog.products.gach-trang-tri.hero-banner :config="$config ?? null" />

    <x-client.catalog.products.gach-trang-tri.applications-section :config="$config ?? null" />

    <!-- BREADCRUMB & PRODUCT FILTER -->
    <x-client.shared.product-breadcrumb-filter current-label="Gạch Trang Trí" />
    <x-client.catalog.shared.product-grid category="gach-trang-tri" :products="$products"
        routeName="client.products.gach-trang-tri.detail" />
    <x-client.content.shared.custom-design-process :images="$config && is_array($config->images) ? $config->images : []" />

    <x-client.catalog.products.gach-trang-tri.crafting-process />

    <x-client.content.shared.outstanding-value />

    <x-client.catalog.shared.journey-video :hide-title="true" />

    <x-client.catalog.products.gach-trang-tri.artistic-value />

    <x-client.catalog.products.gach-trang-tri.project-showcase :projects="$projects ?? collect()" />

    <!-- FAQ Section -->
    <section class="w-full relative pt-[40px] pb-20 md:pb-[120px] bg-background-secondary overflow-hidden"
        data-aos="fade-up">
        <img src="{{ asset('assets/images/gtt-decorate-left.svg') }}"
            class="absolute top-[38%] -translate-y-1/3 md:left-[-5rem] -left-1/2 w-[42%] object-contain opacity-100 pointer-events-none z-0"
            alt="" />
        <img src="{{ asset('assets/images/gtt-decorate-right.svg') }}"
            class="absolute top-[38%] -translate-y-1/3 md:right-[-5rem] -right-1/2 w-[42%] object-contain opacity-100 pointer-events-none z-0"
            alt="" />
        <x-client.content.shared.faq-accordion />
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // --- Init GLightbox ---
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

                // --- Init Custom Project Showcase Slider ---
                var customSliderContainer = document.getElementById("custom-project-slider");
                if (customSliderContainer) {
                    var wrapper = customSliderContainer.querySelector(".slider-inner");
                    var originalSlides = Array.from(customSliderContainer.querySelectorAll(".custom-project-slide"));
                    if (originalSlides.length === 0) {
                        return;
                    }
                    var slides = [...originalSlides];

                    // We need at least 7 slides for a smooth circular loop transition
                    var minSlidesRequired = 7;
                    if (originalSlides.length > 0 && originalSlides.length < minSlidesRequired) {
                        var cloneIndex = 0;
                        while (slides.length < minSlidesRequired) {
                            var originalSlide = originalSlides[cloneIndex % originalSlides.length];
                            var clone = originalSlide.cloneNode(true);
                            wrapper.appendChild(clone);
                            slides.push(clone);
                            cloneIndex++;
                        }
                    }

                    var currentIndex = 0;
                    var N = slides.length;

                    // Populate pagination dots dynamically
                    var dotsContainer = document.getElementById("custom-project-dots");
                    if (dotsContainer && originalSlides.length > 0) {
                        dotsContainer.innerHTML = "";
                        originalSlides.forEach(function(_, index) {
                            var dot = document.createElement("button");
                            dot.className =
                                "project-dot w-2 h-2 md:w-3 md:h-3 rounded-full transition-all duration-500 cursor-pointer" +
                                (index === 0 ? " active" : "");
                            dot.setAttribute("data-index", index);
                            dotsContainer.appendChild(dot);
                        });
                    }
                    var dots = dotsContainer ? dotsContainer.querySelectorAll(".project-dot") : [];
                    var hoverTimer = null;

                    function promoteSideSlide(slide) {
                        if (!slide || slide.classList.contains("slide-center")) {
                            return false;
                        }

                        if (slide.classList.contains("slide-left-1")) {
                            currentIndex = (currentIndex - 1 + N) % N;
                        } else if (slide.classList.contains("slide-right-1")) {
                            currentIndex = (currentIndex + 1) % N;
                        } else if (slide.classList.contains("slide-left-2")) {
                            currentIndex = (currentIndex - 2 + N) % N;
                        } else if (slide.classList.contains("slide-right-2")) {
                            currentIndex = (currentIndex + 2) % N;
                        } else {
                            return false;
                        }

                        updateSlider();
                        resetAutoPlay();
                        return true;
                    }

                    function scheduleSideSlidePromotion(slide) {
                        clearTimeout(hoverTimer);
                        hoverTimer = setTimeout(function() {
                            promoteSideSlide(slide);
                        }, 220);
                    }

                    function cancelSideSlidePromotion() {
                        clearTimeout(hoverTimer);
                    }

                    function updateSlider() {
                        slides.forEach(function(slide, i) {
                            // Remove previous classes
                            slide.classList.remove(
                                "slide-center", "slide-left-1", "slide-left-2",
                                "slide-right-1", "slide-right-2", "slide-outer-left", "slide-outer-right"
                            );

                            // Calculate circular relative index
                            var diff = i - currentIndex;
                            if (diff < -Math.floor(N / 2)) {
                                diff += N;
                            } else if (diff > Math.floor((N - 1) / 2)) {
                                diff -= N;
                            }

                            // Assign class based on relative position
                            if (diff === 0) {
                                slide.classList.add("slide-center");
                            } else if (diff === -1) {
                                slide.classList.add("slide-left-1");
                            } else if (diff === -2) {
                                slide.classList.add("slide-left-2");
                            } else if (diff === 1) {
                                slide.classList.add("slide-right-1");
                            } else if (diff === 2) {
                                slide.classList.add("slide-right-2");
                            } else if (diff < -2) {
                                slide.classList.add("slide-outer-left");
                            } else if (diff > 2) {
                                slide.classList.add("slide-outer-right");
                            }
                        });

                        // Update active dot based on original data index
                        if (dots.length > 0) {
                            var currentSlide = slides[currentIndex];
                            var originalIndex = parseInt(currentSlide.getAttribute("data-index")) || 0;
                            dots.forEach(function(dot, idx) {
                                dot.classList.toggle("active", idx === originalIndex);
                            });
                        }
                    }

                    // Click and hover navigation for side slides
                    slides.forEach(function(slide) {
                        slide.addEventListener("click", function() {
                            promoteSideSlide(slide);
                        });

                        slide.addEventListener("mouseenter", function() {
                            scheduleSideSlidePromotion(slide);
                        });

                        slide.addEventListener("mouseleave", cancelSideSlidePromotion);
                    });

                    // Pagination dots click handler
                    dots.forEach(function(dot) {
                        dot.addEventListener("click", function() {
                            var targetIdx = parseInt(dot.getAttribute("data-index"));
                            // Find the first slide in slides array matching targetIdx
                            for (var i = 0; i < N; i++) {
                                var slideIdx = parseInt(slides[i].getAttribute("data-index"));
                                if (slideIdx === targetIdx) {
                                    // Make sure it goes to the closest matching slide
                                    var diff = i - currentIndex;
                                    if (diff < -Math.floor(N / 2)) diff += N;
                                    else if (diff > Math.floor((N - 1) / 2)) diff -= N;

                                    currentIndex = (currentIndex + diff + N) % N;
                                    updateSlider();
                                    resetAutoPlay();
                                    break;
                                }
                            }
                        });
                    });

                    // Auto play setup
                    var autoPlayInterval;

                    function startAutoPlay() {
                        autoPlayInterval = setInterval(function() {
                            currentIndex = (currentIndex + 1) % N;
                            updateSlider();
                        }, 5000);
                    }

                    function stopAutoPlay() {
                        clearInterval(autoPlayInterval);
                    }

                    // Auto play control functions
                    function resetAutoPlay() {
                        stopAutoPlay();
                        startAutoPlay();
                    }

                    // Pause on hover
                    customSliderContainer.addEventListener("mouseenter", stopAutoPlay);
                    customSliderContainer.addEventListener("mouseleave", startAutoPlay);

                    // Swipe and Drag gesture support (mouse and touch)
                    var startX = 0;
                    var diffX = 0;
                    var isDragging = false;

                    function handleStart(clientX) {
                        startX = clientX;
                        isDragging = true;
                        diffX = 0;
                    }

                    function handleMove(clientX) {
                        if (!isDragging) return;
                        diffX = clientX - startX;
                    }

                    function handleEnd() {
                        if (!isDragging) return;
                        isDragging = false;

                        var swipeThreshold = 50;
                        if (diffX < -swipeThreshold) {
                            currentIndex = (currentIndex + 1) % N;
                            updateSlider();
                            resetAutoPlay();
                        } else if (diffX > swipeThreshold) {
                            currentIndex = (currentIndex - 1 + N) % N;
                            updateSlider();
                            resetAutoPlay();
                        }
                        diffX = 0;
                    }

                    // Touch events
                    customSliderContainer.addEventListener("touchstart", function(e) {
                        handleStart(e.touches[0].clientX);
                    }, {
                        passive: true
                    });

                    customSliderContainer.addEventListener("touchmove", function(e) {
                        handleMove(e.touches[0].clientX);
                    }, {
                        passive: true
                    });

                    customSliderContainer.addEventListener("touchend", function(e) {
                        handleEnd();
                    }, {
                        passive: true
                    });

                    customSliderContainer.addEventListener("touchcancel", function(e) {
                        handleEnd();
                    }, {
                        passive: true
                    });

                    // Mouse events for desktop dragging
                    customSliderContainer.addEventListener("mousedown", function(e) {
                        // Only drag on left click
                        if (e.button !== 0) return;
                        handleStart(e.clientX);
                        // Prevent text/image selection/dragging
                        e.preventDefault();
                    });

                    window.addEventListener("mousemove", function(e) {
                        if (isDragging) {
                            handleMove(e.clientX);
                        }
                    });

                    window.addEventListener("mouseup", function(e) {
                        if (isDragging) {
                            handleEnd();
                        }
                    });

                    // Initialize Custom Slider
                    updateSlider();
                    startAutoPlay();
                }

                // --- Init Art Value Swiper ---
                var artValueSwiperEl = document.querySelector(".art-value-swiper");
                if (artValueSwiperEl && typeof Swiper !== "undefined") {
                    var artValueSwiperInstance = null;

                    function initArtValueSwiper() {
                        var isMobile = window.innerWidth < 768;
                        if (isMobile && !artValueSwiperInstance) {
                            artValueSwiperInstance = new Swiper(artValueSwiperEl, {
                                slidesPerView: 1.15,
                                spaceBetween: 20,
                                grabCursor: true,
                                pagination: {
                                    el: ".art-value-pagination",
                                    clickable: true,
                                },
                            });
                        } else if (!isMobile && artValueSwiperInstance) {
                            artValueSwiperInstance.destroy(true, true);
                            artValueSwiperInstance = null;
                        }
                    }

                    initArtValueSwiper();
                    window.addEventListener("resize", initArtValueSwiper);
                }
            });
        </script>
    @endpush

</x-client.layouts.main>
