const initWorksCarousel = (container) => {
    if (container.dataset.worksCarouselInitialized === "true") {
        return;
    }

    const section = container.closest("section") ?? container;
    const worksCarousel = container.querySelector(".works-carousel");
    const worksTrack = container.querySelector(".works-track");
    const scrollbarThumb = section.querySelector(".works-scrollbar-thumb");
    const scrollbarTrack = section.querySelector(".works-scrollbar-track");

    if (!worksCarousel || !worksTrack) {
        return;
    }

    container.dataset.worksCarouselInitialized = "true";

    const updateScrollbar = () => {
        if (!scrollbarThumb || !scrollbarTrack) {
            return;
        }

        const scrollWidth = worksCarousel.scrollWidth;
        const clientWidth = worksCarousel.clientWidth;
        const scrollLeft = worksCarousel.scrollLeft;
        const trackWidth = scrollbarTrack.clientWidth;

        if (scrollWidth <= clientWidth || trackWidth <= 0) {
            scrollbarThumb.style.width = "100%";
            scrollbarThumb.style.left = "0";
            return;
        }

        const thumbWidth = Math.max((clientWidth / scrollWidth) * trackWidth, 40);
        const thumbLeft =
            (scrollLeft / (scrollWidth - clientWidth)) * (trackWidth - thumbWidth);

        scrollbarThumb.style.width = `${thumbWidth}px`;
        scrollbarThumb.style.left = `${thumbLeft}px`;
    };

    let carouselPointerId = null;
    let carouselStartX = 0;
    let carouselStartScrollLeft = 0;
    let carouselDidDrag = false;
    let thumbPointerId = null;
    let isInteracting = false;
    let autoScrollFrameId = null;
    let isVisible = !("IntersectionObserver" in window);

    const AUTO_SCROLL_SPEED = 0.75;
    const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    const tickAutoScroll = () => {
        const scrollWidth = worksCarousel.scrollWidth;
        const clientWidth = worksCarousel.clientWidth;
        const maxScroll = scrollWidth - clientWidth;

        if (
            !prefersReducedMotion &&
            isVisible &&
            !isInteracting &&
            carouselPointerId === null &&
            thumbPointerId === null &&
            maxScroll > 0
        ) {
            if (worksCarousel.scrollLeft >= maxScroll - 1) {
                worksCarousel.scrollLeft = 0;
            } else {
                worksCarousel.scrollLeft += AUTO_SCROLL_SPEED;
            }

            updateScrollbar();
        }

        autoScrollFrameId = window.requestAnimationFrame(tickAutoScroll);
    };

    const endCarouselDrag = () => {
        if (carouselPointerId === null) {
            return;
        }

        worksCarousel.classList.remove("is-dragging");
        carouselPointerId = null;

        window.setTimeout(() => {
            carouselDidDrag = false;
            isInteracting = false;
        }, 300);
    };

    worksCarousel.addEventListener("pointerdown", (event) => {
        if (event.button !== undefined && event.button !== 0) {
            return;
        }

        isInteracting = true;
        carouselPointerId = event.pointerId;
        carouselStartX = event.clientX;
        carouselStartScrollLeft = worksCarousel.scrollLeft;
        carouselDidDrag = false;
        worksCarousel.classList.add("is-dragging");
        worksCarousel.setPointerCapture?.(event.pointerId);
    });

    worksCarousel.addEventListener("pointermove", (event) => {
        if (carouselPointerId !== event.pointerId) {
            return;
        }

        const deltaX = event.clientX - carouselStartX;
        if (Math.abs(deltaX) > 4) {
            carouselDidDrag = true;
        }

        worksCarousel.scrollLeft = carouselStartScrollLeft - deltaX;
    });

    worksCarousel.addEventListener("pointerup", endCarouselDrag);
    worksCarousel.addEventListener("pointercancel", endCarouselDrag);
    worksCarousel.addEventListener("pointerleave", endCarouselDrag);

    worksCarousel.addEventListener(
        "click",
        (event) => {
            if (!carouselDidDrag) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
        },
        true,
    );

    if (scrollbarThumb && scrollbarTrack) {
        let thumbStartX = 0;
        let thumbStartScrollLeft = 0;

        const endThumbDrag = () => {
            if (thumbPointerId === null) {
                return;
            }

            scrollbarThumb.classList.remove("is-dragging");
            thumbPointerId = null;
            document.body.style.userSelect = "";

            window.setTimeout(() => {
                isInteracting = false;
            }, 300);
        };

        scrollbarThumb.addEventListener("pointerdown", (event) => {
            if (event.button !== undefined && event.button !== 0) {
                return;
            }

            isInteracting = true;
            event.preventDefault();
            event.stopPropagation();

            thumbPointerId = event.pointerId;
            thumbStartX = event.clientX;
            thumbStartScrollLeft = worksCarousel.scrollLeft;
            scrollbarThumb.classList.add("is-dragging");
            scrollbarThumb.setPointerCapture?.(event.pointerId);
            document.body.style.userSelect = "none";
        });

        scrollbarThumb.addEventListener("pointermove", (event) => {
            if (thumbPointerId !== event.pointerId) {
                return;
            }

            const scrollableWidth = worksCarousel.scrollWidth - worksCarousel.clientWidth;
            const thumbWidth = scrollbarThumb.offsetWidth;
            const draggableWidth = scrollbarTrack.clientWidth - thumbWidth;

            if (scrollableWidth <= 0 || draggableWidth <= 0) {
                return;
            }

            const deltaX = event.clientX - thumbStartX;
            worksCarousel.scrollLeft =
                thumbStartScrollLeft + (deltaX / draggableWidth) * scrollableWidth;
        });

        scrollbarThumb.addEventListener("pointerup", endThumbDrag);
        scrollbarThumb.addEventListener("pointercancel", endThumbDrag);
        scrollbarThumb.addEventListener("lostpointercapture", endThumbDrag);

        scrollbarTrack.addEventListener("pointerdown", (event) => {
            if (event.target !== scrollbarTrack) {
                return;
            }
            if (event.button !== undefined && event.button !== 0) {
                return;
            }

            isInteracting = true;

            const scrollableWidth = worksCarousel.scrollWidth - worksCarousel.clientWidth;
            const thumbWidth = scrollbarThumb.offsetWidth;
            const draggableWidth = scrollbarTrack.clientWidth - thumbWidth;

            if (scrollableWidth <= 0 || draggableWidth <= 0) {
                isInteracting = false;
                return;
            }

            const clickRatio = Math.max(
                0,
                Math.min(
                    1,
                    (event.clientX - scrollbarTrack.getBoundingClientRect().left - thumbWidth / 2) /
                        draggableWidth,
                ),
            );

            worksCarousel.scrollLeft = clickRatio * scrollableWidth;
            updateScrollbar();

            window.setTimeout(() => {
                isInteracting = false;
            }, 400);
        });
    }

    worksCarousel.addEventListener("scroll", updateScrollbar);
    window.addEventListener("resize", updateScrollbar);
    updateScrollbar();

    if ("IntersectionObserver" in window) {
        const observer = new IntersectionObserver(
            ([entry]) => {
                isVisible = entry.isIntersecting;
            },
            { threshold: 0.15 },
        );

        observer.observe(section);
    }

    if (!prefersReducedMotion) {
        autoScrollFrameId = window.requestAnimationFrame(tickAutoScroll);
    }

    window.addEventListener("beforeunload", () => {
        if (autoScrollFrameId !== null) {
            window.cancelAnimationFrame(autoScrollFrameId);
        }
    });
};

const initWorksCarousels = () => {
    document.querySelectorAll("[data-works-carousel]").forEach(initWorksCarousel);
};

export { initWorksCarousels };
