<script>
    document.addEventListener("DOMContentLoaded", () => {
        // 0. Sticky header scroll effect (home page only)
        const siteHeader = document.getElementById("site-header");
        if (siteHeader && siteHeader.classList.contains("fixed")) {
            const onScroll = () => {
                if (window.scrollY > 20) {
                    siteHeader.classList.remove("bg-opacity-60");
                    siteHeader.classList.add("bg-opacity-100", "shadow-md");
                } else {
                    siteHeader.classList.add("bg-opacity-60");
                    siteHeader.classList.remove("bg-opacity-100", "shadow-md");
                }
            };
            window.addEventListener("scroll", onScroll, {
                passive: true
            });
            onScroll();
        }

        // 1. Highlight Active Link
        const currentPath = window.location.pathname;

        // Normalize path (ensure trailing slash for comparison if needed, or matched exactly)
        // Simple matching:
        const desktopLinks = document.querySelectorAll(".nav-link");
        const desktopDropdownLinks = document.querySelectorAll(
            ".desktop-dropdown-link",
        );
        const mobileLinks = document.querySelectorAll(".mobile-nav-link");
        const mobileProductsToggle = document.getElementById(
            "mobile-products-toggle",
        );
        const mobileSpctToggle = document.getElementById("mobile-spct-toggle");

        const setActive = (link) => {
            if (link.classList.contains("nav-link")) {
                // Desktop: Remove default light text and add underline
                link.classList.remove("text-[#FFFAF3]");
                link.classList.add(
                    "underline",
                    "underline-offset-8",
                    "decoration-2",
                );
            } else if (
                link.classList.contains("mobile-nav-link") ||
                link.classList.contains("mobile-nav-toggle")
            ) {
                // Mobile: Remove default white text
                link.classList.remove("text-white", "text-white/90");
            } else if (link.classList.contains("desktop-dropdown-link")) {
                // Desktop Dropdown: Remove hover text color if any, but specifically remove black text
                link.classList.remove("text-[#212121]");
            }

            // Add Active styling - Orange text
            link.classList.add("text-secondary");
        };

        [
            ...desktopLinks,
            ...desktopDropdownLinks,
            ...mobileLinks,
            mobileProductsToggle,
            mobileSpctToggle,
        ]
        .filter(Boolean)
            .forEach((link) => {
                const linkPath = link.getAttribute("data-path");
                if (
                    currentPath === linkPath ||
                    (currentPath === "/index.html" && linkPath === "/") ||
                    (linkPath !== "/" && currentPath.startsWith(linkPath))
                ) {
                    setActive(link);
                }
            });

        // 2. Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById("mobile-menu-button");
        const closeMenuBtn = document.getElementById("close-menu-button");
        const mobileMenu = document.getElementById("mobile-menu");

        const toggleMenu = () => {
            mobileMenu.classList.toggle("hidden");
            // Prevent body scroll when menu is open
            if (!mobileMenu.classList.contains("hidden")) {
                document.body.style.overflow = "hidden";
            } else {
                document.body.style.overflow = "";
            }
        };

        mobileMenuBtn?.addEventListener("click", toggleMenu);
        closeMenuBtn?.addEventListener("click", toggleMenu);

        // Close menu when clicking a link
        mobileLinks.forEach((link) => {
            link.addEventListener("click", () => {
                if (!mobileMenu.classList.contains("hidden")) toggleMenu();
            });
        });

        // 3. Mobile Products/SPCT Submenu Toggle
        const bindMobileSubmenuToggle = (toggleButton, submenuId, iconId) => {
            if (!toggleButton) return;

            toggleButton.addEventListener("click", (e) => {
                e.preventDefault();
                const submenu = document.getElementById(submenuId);
                const icon = document.getElementById(iconId);

                if (!submenu) return;

                if (submenu.classList.contains("hidden")) {
                    submenu.classList.remove("hidden");
                    submenu.classList.add("flex");
                    if (icon) icon.classList.add("rotate-180");
                } else {
                    submenu.classList.add("hidden");
                    submenu.classList.remove("flex");
                    if (icon) icon.classList.remove("rotate-180");
                }
            });
        };

        bindMobileSubmenuToggle(
            mobileProductsToggle,
            "mobile-products-submenu",
            "mobile-products-icon",
        );
        bindMobileSubmenuToggle(
            mobileSpctToggle,
            "mobile-spct-submenu",
            "mobile-spct-icon",
        );
    });

    // Auto-detect home route and apply fixed transparent header
    (function() {
        const header = document.getElementById("site-header");
        if (!header) return;
        const isHome =
            window.location.pathname === "/" ||
            window.location.pathname === "/index.html";
        if (isHome) {
            header.classList.remove("sticky");
            header.classList.add(
                "fixed",
                "top-0",
                "left-0",
                "right-0",
                "bg-opacity-60",
            );
        }
    })();
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const bannerSection = document.querySelector(".banner-section");
        if (!bannerSection) return;

        const bannerSlides = Array.from(
            bannerSection.querySelectorAll(".banner-slide"),
        );
        const bannerDots = Array.from(
            bannerSection.querySelectorAll(".banner-dot"),
        );
        const bannerNext = bannerSection.querySelector(".banner-next");
        const bannerPrev = bannerSection.querySelector(".banner-prev");

        if (!bannerSlides.length) return;

        let bannerCurrentSlide = 0;
        const totalBannerSlides = bannerSlides.length;

        const showBannerSlide = (index) => {
            bannerSlides.forEach((slide, slideIndex) => {
                const isActive = slideIndex === index;
                slide.classList.toggle("active", isActive);
                slide.classList.toggle("opacity-100", isActive);
                slide.classList.toggle("opacity-0", !isActive);
            });

            bannerDots.forEach((dot, dotIndex) => {
                const isActive = dotIndex === index;
                dot.classList.toggle("active", isActive);
                dot.classList.toggle("bg-secondary", isActive);
                dot.classList.toggle("bg-white/40", !isActive);
            });
        };

        const nextBannerSlide = () => {
            bannerCurrentSlide = (bannerCurrentSlide + 1) % totalBannerSlides;
            showBannerSlide(bannerCurrentSlide);
        };

        const prevBannerSlide = () => {
            bannerCurrentSlide =
                (bannerCurrentSlide - 1 + totalBannerSlides) % totalBannerSlides;
            showBannerSlide(bannerCurrentSlide);
        };

        bannerNext?.addEventListener("click", nextBannerSlide);
        bannerPrev?.addEventListener("click", prevBannerSlide);

        bannerDots.forEach((dot) => {
            dot.addEventListener("click", () => {
                const targetIndex = Number.parseInt(dot.dataset.slide || "0", 10);
                if (Number.isNaN(targetIndex)) return;
                bannerCurrentSlide = targetIndex;
                showBannerSlide(bannerCurrentSlide);
            });
        });

        showBannerSlide(bannerCurrentSlide);
        window.setInterval(nextBannerSlide, 5000);
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const worksSection = document
            .querySelector(".works-carousel-container")
            ?.closest("section");
        if (!worksSection) return;

        const worksCarousel = worksSection.querySelector(".works-carousel");
        const worksTrack = worksSection.querySelector(".works-track");
        const worksPrev = worksSection.querySelector(".works-prev");
        const worksNext = worksSection.querySelector(".works-next");
        const scrollbarThumb = worksSection.querySelector(
            ".works-scrollbar-thumb",
        );
        const scrollbarTrack = worksSection.querySelector(
            ".works-scrollbar-track",
        );

        if (!worksCarousel || !worksTrack) return;

        const getScrollStep = () => {
            const firstItem = worksTrack.querySelector(".works-item");
            if (!firstItem) return 0;

            const itemWidth = firstItem.getBoundingClientRect().width;
            const trackStyles = window.getComputedStyle(worksTrack);
            const gap =
                parseFloat(trackStyles.columnGap || trackStyles.gap || "0") || 0;
            return itemWidth + gap;
        };

        worksPrev?.addEventListener("click", () => {
            const scrollStep = getScrollStep();
            worksCarousel.scrollBy({
                left: -scrollStep,
                behavior: "smooth",
            });
        });

        worksNext?.addEventListener("click", () => {
            const scrollStep = getScrollStep();
            worksCarousel.scrollBy({
                left: scrollStep,
                behavior: "smooth",
            });
        });

        const updateScrollbar = () => {
            if (!scrollbarThumb || !scrollbarTrack) return;

            const scrollWidth = worksCarousel.scrollWidth;
            const clientWidth = worksCarousel.clientWidth;
            const scrollLeft = worksCarousel.scrollLeft;
            const trackWidth = scrollbarTrack.clientWidth;

            if (scrollWidth <= clientWidth || trackWidth <= 0) {
                scrollbarThumb.style.width = "100%";
                scrollbarThumb.style.left = "0";
                return;
            }

            const thumbWidth = Math.max(
                (clientWidth / scrollWidth) * trackWidth,
                40,
            );
            const thumbLeft =
                (scrollLeft / (scrollWidth - clientWidth)) *
                (trackWidth - thumbWidth);

            scrollbarThumb.style.width = `${thumbWidth}px`;
            scrollbarThumb.style.left = `${thumbLeft}px`;
        };

        worksCarousel.addEventListener("scroll", updateScrollbar);
        window.addEventListener("resize", updateScrollbar);
        updateScrollbar();
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const partnerTrack = document.getElementById("partners-track");
        if (!partnerTrack) return;

        const section = partnerTrack.closest("section");
        if (!section) return;

        const partnerDots = Array.from(
            section.querySelectorAll(".partner-dot"),
        );
        const partnerPrev = section.querySelector(".partner-prev");
        const partnerNext = section.querySelector(".partner-next");
        const totalPartnerSlides = Math.max(partnerDots.length, 2);

        let partnerCurrentSlide = 0;

        const showPartnerSlide = (index) => {
            partnerTrack.style.transform = `translateX(-${index * 50}%)`;

            partnerDots.forEach((dot, dotIndex) => {
                const isActive = dotIndex === index;
                dot.classList.toggle("active", isActive);
                dot.classList.toggle("bg-secondary", isActive);
                dot.classList.toggle("bg-white/40", !isActive);
            });
        };

        const nextPartnerSlide = () => {
            partnerCurrentSlide = (partnerCurrentSlide + 1) % totalPartnerSlides;
            showPartnerSlide(partnerCurrentSlide);
        };

        const prevPartnerSlide = () => {
            partnerCurrentSlide =
                (partnerCurrentSlide - 1 + totalPartnerSlides) % totalPartnerSlides;
            showPartnerSlide(partnerCurrentSlide);
        };

        partnerNext?.addEventListener("click", nextPartnerSlide);
        partnerPrev?.addEventListener("click", prevPartnerSlide);

        partnerDots.forEach((dot, index) => {
            dot.addEventListener("click", () => {
                partnerCurrentSlide = index;
                showPartnerSlide(partnerCurrentSlide);
            });
        });

        showPartnerSlide(partnerCurrentSlide);
    });
</script>

<script>
    (function() {
        function initAwardsComponent(root) {
            if (!root || root.dataset.awardsInitDone === "true") {
                return;
            }

            if (typeof Swiper === "undefined") {
                return;
            }

            var swiperEl = root.querySelector(".awards-swiper");
            var prevEl = root.querySelector(".awards-prev");
            var nextEl = root.querySelector(".awards-next");

            if (!swiperEl || !prevEl || !nextEl) {
                return;
            }

            var isAboutPage = window.location.pathname.indexOf("/about") === 0;
            var isMobile = window.matchMedia("(max-width: 767px)").matches;
            var transitionSpeed = isMobile ? 780 : 620;
            var swiper = new Swiper(swiperEl, {
                effect: "slide",
                speed: transitionSpeed,
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                spaceBetween: 15,
                initialSlide: isMobile ? 0 : 1,
                loop: true,
                loopedSlides: 5,
                roundLengths: true,
                navigation: {
                    nextEl: nextEl,
                    prevEl: prevEl,
                },
                autoplay: {
                    delay: isAboutPage ? 3000 : 1000000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 14,
                    },
                    1024: {
                        slidesPerView: "auto",
                        spaceBetween: 12,
                    },
                    1280: {
                        slidesPerView: "auto",
                        spaceBetween: 16,
                    },
                },
            });

            root.dataset.awardsInitDone = "true";

            if (!window.__awardsSwiperInstances) {
                window.__awardsSwiperInstances = [];
            }
            window.__awardsSwiperInstances.push(swiper);

            if (isMobile) {
                var mobileLightbox = root.querySelector("#awards-mobile-lightbox");
                var mobileLightboxImage = root.querySelector(
                    "#awards-mobile-lightbox-image",
                );
                var mobileLightboxClose = root.querySelector(
                    "#awards-mobile-lightbox-close",
                );
                var awardSlides = Array.from(
                    root.querySelectorAll(".swiper-slide"),
                );

                if (
                    mobileLightbox &&
                    mobileLightboxImage &&
                    mobileLightboxClose &&
                    awardSlides.length
                ) {
                    var closeMobileLightbox = function() {
                        mobileLightbox.classList.remove("is-open");
                        document.body.classList.remove("overflow-hidden");

                        window.setTimeout(function() {
                            mobileLightbox.classList.add("pointer-events-none");
                            mobileLightboxImage.src = "";
                            mobileLightboxImage.alt = "";
                        }, 260);
                    };

                    var openMobileLightbox = function(source, alt) {
                        mobileLightboxImage.src = source;
                        mobileLightboxImage.alt = alt || "Award image preview";
                        mobileLightbox.classList.remove("pointer-events-none");
                        requestAnimationFrame(function() {
                            mobileLightbox.classList.add("is-open");
                            document.body.classList.add("overflow-hidden");
                        });
                    };

                    var triggerLightboxFromSlide = function(slide, event) {
                        if (
                            !slide ||
                            !window.matchMedia("(max-width: 767px)").matches
                        ) {
                            return;
                        }

                        var slideImage = slide.querySelector("img");
                        if (!slideImage) {
                            return;
                        }

                        if (event && typeof event.preventDefault === "function") {
                            event.preventDefault();
                        }

                        if (event && typeof event.stopPropagation === "function") {
                            event.stopPropagation();
                        }

                        openMobileLightbox(
                            slideImage.currentSrc || slideImage.src,
                            slideImage.alt,
                        );
                    };

                    var triggerLightboxFromTarget = function(target, event) {
                        if (!target || typeof target.closest !== "function") {
                            return;
                        }

                        var slide = target.closest(".swiper-slide");
                        triggerLightboxFromSlide(slide, event);
                    };

                    awardSlides.forEach(function(slide) {
                        slide.classList.add("cursor-zoom-in");
                        slide.setAttribute("role", "button");
                        slide.setAttribute("tabindex", "0");

                        slide.addEventListener("keydown", function(event) {
                            if (event.key === "Enter" || event.key === " ") {
                                triggerLightboxFromSlide(slide, event);
                            }
                        });
                    });

                    swiperEl.addEventListener("click", function(event) {
                        triggerLightboxFromTarget(event.target, event);
                    });

                    if (typeof swiper.on === "function") {
                        swiper.on("tap", function(swiperInstance, event) {
                            if (event && event.target) {
                                triggerLightboxFromTarget(event.target, event);
                                return;
                            }

                            triggerLightboxFromSlide(swiperInstance.clickedSlide, event);
                        });
                    }

                    mobileLightboxClose.addEventListener(
                        "click",
                        closeMobileLightbox,
                    );
                    mobileLightbox.addEventListener("click", function(event) {
                        if (event.target === mobileLightbox) {
                            closeMobileLightbox();
                        }
                    });

                    document.addEventListener("keydown", function(event) {
                        if (
                            event.key === "Escape" &&
                            mobileLightbox.classList.contains("is-open")
                        ) {
                            closeMobileLightbox();
                        }
                    });
                }
            }

            setTimeout(function() {
                swiper.update();
            }, 30);
        }

        function initAllAwardsComponents() {
            document
                .querySelectorAll(".awards-component")
                .forEach(initAwardsComponent);
        }

        if (!window.__awardsComponentRefreshBound) {
            window.addEventListener("awards:refresh", function() {
                if (!window.__awardsSwiperInstances) {
                    return;
                }

                window.__awardsSwiperInstances.forEach(function(swiper) {
                    if (swiper && typeof swiper.update === "function") {
                        swiper.update();
                    }
                });
            });

            window.__awardsComponentRefreshBound = true;
        }

        if (document.readyState === "loading") {
            document.addEventListener(
                "DOMContentLoaded",
                initAllAwardsComponents,
            );
        } else {
            initAllAwardsComponents();
        }
    })();
</script>

<script>
    (function() {
        const counters = document.querySelectorAll(".stat-number");
        if (!counters.length) return;

        // Store active interval per element
        const timers = new WeakMap();

        const reset = (el) => {
            if (timers.has(el)) {
                clearInterval(timers.get(el));
                timers.delete(el);
            }
            const suffix = el.getAttribute("data-suffix") || "";
            el.textContent = "0" + suffix;
        };

        const animate = (el) => {
            // Clear any existing animation first
            if (timers.has(el)) {
                clearInterval(timers.get(el));
                timers.delete(el);
            }

            const target = parseInt(el.getAttribute("data-target"), 10);
            const suffix = el.getAttribute("data-suffix") || "";
            const duration = 3000;
            const frameDuration = 1000 / 60;
            const totalFrames = Math.round(duration / frameDuration);
            let frame = 0;

            const easeOut = (t) => 1 - Math.pow(1 - t, 4);

            const id = setInterval(() => {
                frame++;
                const progress = easeOut(frame / totalFrames);
                const current = Math.round(progress * target);
                el.textContent = current + suffix;
                if (frame >= totalFrames) {
                    el.textContent = target + suffix;
                    clearInterval(id);
                    timers.delete(el);
                }
            }, frameDuration);

            timers.set(el, id);
        };

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        animate(entry.target);
                    } else {
                        // Reset when scrolled away so it replays next time
                        reset(entry.target);
                    }
                });
            }, {
                threshold: 0.4
            },
        );

        counters.forEach((el) => observer.observe(el));
    })();
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const section = document
            .querySelector(".showroom-container")
            ?.closest("section");
        if (!section) return;

        const showroomContainer = section.querySelector(".showroom-container");
        const showroomDots = Array.from(
            section.querySelectorAll(".showroom-dot"),
        );

        if (!showroomContainer || !showroomDots.length) return;

        const updateActiveDot = () => {
            const firstImage = showroomContainer.querySelector("img");
            if (!firstImage) return;

            const itemWidth = firstImage.offsetWidth + 16;
            const index = Math.round(showroomContainer.scrollLeft / itemWidth);

            showroomDots.forEach((dot, dotIndex) => {
                const isActive = dotIndex === index;
                dot.classList.toggle("active", isActive);
                dot.classList.toggle("bg-secondary", isActive);
                dot.classList.toggle("bg-white/40", !isActive);
            });
        };

        showroomContainer.addEventListener("scroll", updateActiveDot);

        showroomDots.forEach((dot, index) => {
            dot.addEventListener("click", () => {
                const firstImage = showroomContainer.querySelector("img");
                if (!firstImage) return;

                const itemWidth = firstImage.offsetWidth + 16;
                showroomContainer.scrollTo({
                    left: itemWidth * index,
                    behavior: "smooth",
                });
            });
        });

        updateActiveDot();
    });
</script>
