// Swaps a paginated list in place when one of its page links is clicked, so the
// page does not reload. Each list is wrapped in a [data-pagination-region]
// element named after the list it holds. Links keep their real href: without
// JavaScript, or when a request fails, they navigate normally.

const REGION_SELECTOR = "[data-pagination-region]";
const NAV_SELECTOR = 'nav[aria-label="Pagination"]';
const LINK_SELECTOR = `${REGION_SELECTOR} ${NAV_SELECTOR} a[href]`;
const LOADING_CLASS = "is-pagination-loading";

const pageCache = new Map();
let latestRequest = 0;
let currentUrl = null;

const withoutHash = (url) => url.split("#")[0];

const regionsIn = (root) => Array.from(root.querySelectorAll(REGION_SELECTOR));

const fetchPage = (url) => {
    if (!pageCache.has(url)) {
        const request = window
            .fetch(url, { credentials: "same-origin", headers: { Accept: "text/html" } })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`Pagination request failed with ${response.status}`);
                }

                return response.text();
            })
            .then((html) => new DOMParser().parseFromString(html, "text/html"));

        request.catch(() => pageCache.delete(url));
        pageCache.set(url, request);
    }

    return pageCache.get(url);
};

// Content inserted after load is never picked up by the scroll animation
// library, so mark it as already revealed instead of leaving it transparent.
const revealAnimatedContent = (region) => {
    const animated = Array.from(region.querySelectorAll("[data-aos]"));

    if (region.hasAttribute("data-aos")) {
        animated.push(region);
    }

    animated.forEach((element) => element.classList.add("aos-init", "aos-animate"));
};

// Regions are matched to the fetched page by their order. The clicked list is
// replaced entirely; other lists keep their content and only take the new page
// links, which carry the updated query string. Without an active key (history
// navigation) every region is replaced.
const applyPage = (page, activeKey) => {
    const nextRegions = regionsIn(page);
    const swaps = regionsIn(document).map((region, index) => ({ region, next: nextRegions[index] }));
    const matches = ({ region, next }) =>
        next && next.dataset.paginationRegion === region.dataset.paginationRegion;

    if (swaps.length === 0 || swaps.length !== nextRegions.length || !swaps.every(matches)) {
        return false;
    }

    swaps.forEach(({ region, next }) => {
        if (activeKey === null || region.dataset.paginationRegion === activeKey) {
            const replacement = document.importNode(next, true);
            revealAnimatedContent(replacement);
            region.replaceWith(replacement);

            return;
        }

        const nav = region.querySelector(NAV_SELECTOR);
        const nextNav = next.querySelector(NAV_SELECTOR);

        if (nav && nextNav) {
            nav.replaceWith(document.importNode(nextNav, true));
        }
    });

    return true;
};

const scrollToRegion = (region) => {
    const header = document.getElementById("site-header");
    const offset = Math.max(0, header ? header.getBoundingClientRect().bottom : 0) + 24;
    const top = region.getBoundingClientRect().top;

    if (top >= offset) {
        return;
    }

    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    window.scrollTo({
        top: window.scrollY + top - offset,
        behavior: reduceMotion ? "auto" : "smooth",
    });
};

// `clickedRegion` is null when the visitor moves through the browser history.
const navigate = async (url, clickedRegion, onUpdate) => {
    const request = ++latestRequest;
    const fromHistory = clickedRegion === null;
    const activeKey = fromHistory ? null : clickedRegion.dataset.paginationRegion;
    const clickedIndex = regionsIn(document).indexOf(clickedRegion);
    const busyRegions = regionsIn(document).filter(
        (region) => fromHistory || region.dataset.paginationRegion === activeKey
    );

    busyRegions.forEach((region) => {
        region.classList.add(LOADING_CLASS);
        region.setAttribute("aria-busy", "true");
    });

    try {
        const page = await fetchPage(url);

        if (request !== latestRequest) {
            return;
        }

        if (!applyPage(page, activeKey)) {
            throw new Error("Pagination response is missing a region");
        }
    } catch (error) {
        if (request === latestRequest) {
            if (fromHistory) {
                window.location.reload();
            } else {
                window.location.assign(url);
            }
        }

        return;
    } finally {
        busyRegions.forEach((region) => {
            region.classList.remove(LOADING_CLASS);
            region.removeAttribute("aria-busy");
        });
    }

    if (!fromHistory) {
        window.history.pushState({}, "", url);
    }

    currentUrl = withoutHash(window.location.href);
    onUpdate();

    if (fromHistory) {
        return;
    }

    const region = regionsIn(document)[clickedIndex];

    if (region) {
        // The clicked link is gone, so move focus to the refreshed list.
        region.setAttribute("tabindex", "-1");
        region.focus({ preventScroll: true });
        scrollToRegion(region);
    }
};

const paginationLink = (event) => {
    const link = event.target instanceof Element ? event.target.closest(LINK_SELECTOR) : null;

    return link && link.origin === window.location.origin ? link : null;
};

const initPaginationNavigation = (onUpdate = () => {}) => {
    if (window.__paginationNavigationBound) {
        return;
    }

    window.__paginationNavigationBound = true;
    currentUrl = withoutHash(window.location.href);

    document.addEventListener("click", (event) => {
        const link = paginationLink(event);

        if (
            !link ||
            event.defaultPrevented ||
            event.button !== 0 ||
            event.metaKey ||
            event.ctrlKey ||
            event.shiftKey ||
            event.altKey ||
            (link.target && link.target !== "_self")
        ) {
            return;
        }

        event.preventDefault();
        navigate(link.href, link.closest(REGION_SELECTOR), onUpdate);
    });

    // Fetch the next page as soon as the visitor aims at a link, so the swap
    // after the click is immediate.
    const prefetch = (event) => {
        const link = paginationLink(event);

        if (link) {
            fetchPage(link.href).catch(() => {});
        }
    };

    document.addEventListener("pointerover", prefetch, { passive: true });
    document.addEventListener("touchstart", prefetch, { passive: true });
    document.addEventListener("focusin", prefetch);

    window.addEventListener("popstate", () => {
        if (
            withoutHash(window.location.href) === currentUrl ||
            !document.querySelector(REGION_SELECTOR)
        ) {
            return;
        }

        navigate(window.location.href, null, onUpdate);
    });
};

export { initPaginationNavigation };
