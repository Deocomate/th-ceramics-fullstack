const isDesktopGuideTabs = () => window.matchMedia("(min-width: 768px)").matches;

const syncGuideTabButtons = (root, activeIndex) => {
    root.querySelectorAll("[data-guide-tab]").forEach((tab) => {
        const index = Number.parseInt(tab.dataset.guideTab || "0", 10);
        const isActive = index === activeIndex;

        tab.classList.toggle("border-secondary", isActive);
        tab.classList.toggle("text-secondary", isActive);
        tab.classList.toggle("border-transparent", !isActive);
        tab.classList.toggle("text-secondary/50", !isActive);
        tab.setAttribute("aria-selected", isActive ? "true" : "false");
    });
};

const initProductGuideTabs = () => {
    document.querySelectorAll("[data-product-guide-tabs]").forEach((root) => {
        if (root.dataset.guideTabsInitialized === "true") {
            return;
        }

        const swiperElement = root.querySelector("[data-guide-tabs-swiper]");
        const paginationElement = root.querySelector("[data-guide-tabs-pagination]");

        if (!swiperElement || typeof window.Swiper !== "function") {
            return;
        }

        root.dataset.guideTabsInitialized = "true";

        const swiper = new window.Swiper(swiperElement, {
            slidesPerView: 1,
            spaceBetween: 0,
            autoHeight: true,
            observer: true,
            observeParents: true,
            nested: true,
            allowTouchMove: !isDesktopGuideTabs(),
            pagination: paginationElement
                ? {
                      el: paginationElement,
                      clickable: true,
                  }
                : undefined,
            on: {
                init() {
                    syncGuideTabButtons(root, this.activeIndex);
                },
                slideChange() {
                    syncGuideTabButtons(root, this.activeIndex);
                },
            },
        });

        root.querySelectorAll("[data-guide-tab]").forEach((tab) => {
            tab.addEventListener("click", () => {
                const index = Number.parseInt(tab.dataset.guideTab || "0", 10);
                swiper.slideTo(Number.isNaN(index) ? 0 : index);
            });
        });

        window.addEventListener("resize", () => {
            swiper.allowTouchMove = !isDesktopGuideTabs();
        });
    });
};

export { initProductGuideTabs };
