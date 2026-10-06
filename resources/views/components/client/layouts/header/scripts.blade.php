@props(['categories' => []])

@push('scripts')
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

            // Header search
            const searchEndpoint = @json(route('client.search.quick'));
            const searchCategories = @json($categories ?? []);
            const expandableSearchRoots = document.querySelectorAll("[data-expandable-search]");
            const mobileMenuSearchRoots = document.querySelectorAll("[data-mobile-menu-search]");

            const normalizeSearch = (value) => String(value || "")
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "")
                .replace(/đ/g, "d")
                .replace(/Đ/g, "D")
                .toLowerCase();

            const escapeHtml = (value) => String(value ?? "")
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");

            const swapClasses = (element, classes, enabled) => {
                classes.split(" ").filter(Boolean).forEach((className) => {
                    element.classList.toggle(className, enabled);
                });
            };

            const filterCategories = (keyword) => {
                const normalizedKeyword = normalizeSearch(keyword);

                if (!normalizedKeyword) return [];

                return searchCategories
                    .filter((category) => {
                        const haystack = normalizeSearch([
                            category.name,
                            ...(category.keywords || []),
                        ].join(" "));

                        return haystack.includes(normalizedKeyword);
                    })
                    .slice(0, 5);
            };

            const renderSearchDropdown = (dropdown, categories, products, state = "ready") => {
                const hasCategories = categories.length > 0;
                const hasProducts = products.length > 0;

                if (!hasCategories && !hasProducts && state === "ready") {
                    dropdown.innerHTML = `
                        <div class="px-4 py-5 text-sm text-primary/70">Không tìm thấy kết quả phù hợp.</div>
                    `;
                    dropdown.classList.remove("hidden");
                    return;
                }

                const categoryHtml = hasCategories ? `
                    <div class="border-b border-neutral-1">
                        <div class="px-4 pt-3 pb-2 text-[11px] font-bold uppercase tracking-[0.08em] text-primary/50">Danh mục</div>
                        <div class="pb-2">
                            ${categories.map((category) => `
                                <a href="${escapeHtml(category.url)}" class="block px-4 py-2 text-sm font-semibold hover:bg-neutral-2 hover:text-secondary transition-colors">
                                    ${escapeHtml(category.name)}
                                </a>
                            `).join("")}
                        </div>
                    </div>
                ` : "";

                const productHtml = hasProducts ? `
                    <div>
                        <div class="px-4 pt-3 pb-2 text-[11px] font-bold uppercase tracking-[0.08em] text-primary/50">Sản phẩm</div>
                        <div class="pb-2">
                            ${products.map((product) => `
                                <a href="${escapeHtml(product.url)}" class="flex items-center gap-3 px-4 py-2 hover:bg-neutral-2 transition-colors">
                                    <img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.name)}" class="h-12 w-12 shrink-0 rounded-sm object-cover bg-neutral-1" loading="lazy" />
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-semibold text-primary">${escapeHtml(product.name)}</span>
                                        <span class="mt-1 flex items-center gap-2 text-xs text-primary/60">
                                            <span class="truncate">${escapeHtml(product.code || product.category || "")}</span>
                                            <span class="font-semibold text-secondary">${escapeHtml(product.price_formatted || "")}</span>
                                        </span>
                                    </span>
                                </a>
                            `).join("")}
                        </div>
                    </div>
                ` : "";

                const stateHtml = state === "loading" ? `
                    <div class="px-4 py-3 text-sm text-primary/60">Đang tìm sản phẩm...</div>
                ` : state === "error" ? `
                    <div class="px-4 py-3 text-sm text-red-600">Không thể tải gợi ý sản phẩm.</div>
                ` : "";

                dropdown.innerHTML = `${categoryHtml}${productHtml}${stateHtml}`;
                dropdown.classList.remove("hidden");
            };

            const openSearch = (root) => {
                const input = root.querySelector("[data-search-input]");
                if (!input) return;

                const target = root.querySelector("[data-search-pill]") || input;
                const openClasses = target.dataset.openClasses || "w-48 opacity-100 pointer-events-auto";
                root.dataset.searchOpen = "true";
                target.classList.remove("w-0", "max-w-0", "opacity-0", "pointer-events-none");
                swapClasses(target, openClasses, true);
                root.querySelector("[data-search-toggle]")?.classList.add("hidden");
                document.querySelector("[data-nav-trailing]")?.classList.add("!hidden");

                // Remove overflow-hidden after transition to allow dropdown to show
                setTimeout(() => {
                    if (root.dataset.searchOpen === "true") {
                        target.classList.remove("overflow-hidden");
                    }
                }, 300);

                input.focus();
            };

            const closeSearch = (root) => {
                const input = root.querySelector("[data-search-input]");
                const dropdown = root.querySelector("[data-search-dropdown]");
                if (!input) return;

                const target = root.querySelector("[data-search-pill]") || input;
                const openClasses = target.dataset.openClasses || "w-48 opacity-100 pointer-events-auto";
                root.dataset.searchOpen = "false";

                // Add overflow-hidden immediately when closing to hide content during transition
                target.classList.add("overflow-hidden");

                swapClasses(target, openClasses, false);
                target.classList.add("w-0", "max-w-0", "opacity-0", "pointer-events-none");
                input.value = "";
                dropdown?.classList.add("hidden");
                root.querySelector("[data-search-toggle]")?.classList.remove("hidden");
                document.querySelector("[data-nav-trailing]")?.classList.remove("!hidden");
            };

            const bindSearchInput = (root, dropdown, input) => {
                let searchTimer = null;
                let searchToken = 0;

                input.addEventListener("input", () => {
                    const keyword = input.value.trim();
                    const token = ++searchToken;
                    window.clearTimeout(searchTimer);

                    if (!keyword) {
                        dropdown.classList.add("hidden");
                        return;
                    }

                    const categories = filterCategories(keyword);

                    if (keyword.length < 2) {
                        if (categories.length > 0) {
                            renderSearchDropdown(dropdown, categories, [], "ready");
                        } else {
                            dropdown.classList.add("hidden");
                        }
                        return;
                    }

                    renderSearchDropdown(dropdown, categories, [], "loading");

                    searchTimer = window.setTimeout(async () => {
                        try {
                            const response = await fetch(`${searchEndpoint}?q=${encodeURIComponent(keyword)}`, {
                                headers: { "Accept": "application/json" },
                            });

                            if (!response.ok) throw new Error("Search request failed");

                            const payload = await response.json();
                            if (token !== searchToken) return;

                            renderSearchDropdown(dropdown, categories, payload.products || [], "ready");
                        } catch (error) {
                            if (token !== searchToken) return;
                            renderSearchDropdown(dropdown, categories, [], "error");
                        }
                    }, 300);
                });
            };

            const resetMenuSearch = (root) => {
                const input = root.querySelector("[data-search-input]");
                const dropdown = root.querySelector("[data-search-dropdown]");
                if (input) input.value = "";
                dropdown?.classList.add("hidden");
            };

            expandableSearchRoots.forEach((root) => {
                const input = root.querySelector("[data-search-input]");
                const toggle = root.querySelector("[data-search-toggle]");
                const dropdown = root.querySelector("[data-search-dropdown]");
                const submit = root.querySelector("[data-search-submit]");

                if (!input || !toggle || !dropdown) return;

                toggle.addEventListener("click", (event) => {
                    event.preventDefault();
                    openSearch(root);
                });

                submit?.addEventListener("click", (event) => {
                    event.preventDefault();
                    input.focus();
                });

                input.addEventListener("keydown", (event) => {
                    if (event.key === "Escape") {
                        closeSearch(root);
                    }
                });

                bindSearchInput(root, dropdown, input);
            });

            mobileMenuSearchRoots.forEach((root) => {
                const input = root.querySelector("[data-search-input]");
                const submit = root.querySelector("[data-search-submit]");
                const dropdown = root.querySelector("[data-search-dropdown]");

                if (!input || !dropdown) return;

                submit?.addEventListener("click", (event) => {
                    event.preventDefault();
                    input.focus();
                });

                input.addEventListener("keydown", (event) => {
                    if (event.key === "Escape") {
                        event.target.value = "";
                        dropdown.classList.add("hidden");
                    }
                });

                bindSearchInput(root, dropdown, input);
            });

            document.addEventListener("click", (event) => {
                expandableSearchRoots.forEach((root) => {
                    if (root.dataset.searchOpen === "true" && !root.contains(event.target)) {
                        closeSearch(root);
                    }
                });

                mobileMenuSearchRoots.forEach((root) => {
                    const dropdown = root.querySelector("[data-search-dropdown]");
                    if (dropdown && !dropdown.classList.contains("hidden") && !root.contains(event.target)) {
                        dropdown.classList.add("hidden");
                    }
                });
            });

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

            const setActive = (link) => {
                if (link.classList.contains("nav-link")) {
                    // Desktop: Remove default light text and add underline
                    link.classList.remove("text-[#FFFAF3]");
                    link.classList.add("underline", "underline-offset-8", "decoration-2");
                } else if (
                    link.classList.contains("mobile-nav-link") ||
                    link.classList.contains("mobile-nav-toggle")
                ) {
                    // Mobile: Remove default light text
                    link.classList.remove("text-[#FFFAF3]", "text-white", "text-white/90");
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
                const isOpening = mobileMenu.classList.contains("hidden");
                mobileMenu.classList.toggle("hidden");

                if (isOpening) {
                    document.body.style.overflow = "hidden";
                    const menuSearchInput = document.querySelector("[data-mobile-menu-search] [data-search-input]");
                    menuSearchInput?.focus();
                } else {
                    document.body.style.overflow = "";
                    mobileMenuSearchRoots.forEach(resetMenuSearch);
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

            // 3. Mobile Products Submenu Toggle
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
        });

        // Auto-detect home route and apply fixed transparent header
        (function() {
            const header = document.getElementById("site-header");
            if (!header) return;
            const isHome = window.location.pathname === "/" || window.location.pathname === "/index.html";
            if (isHome) {
                header.classList.remove("sticky");
                header.classList.add("fixed", "top-0", "left-0", "right-0", "bg-opacity-60");
            }
        })();
    </script>
@endpush
