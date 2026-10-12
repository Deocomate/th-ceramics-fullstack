const OPTIONS_URL = "/gio-hang/san-pham-options";
const ADD_URL = "/gio-hang/them";
const MINI_URL = "/gio-hang/mini";

let modalState = null;
let modalInitialized = false;
let miniCartInitialized = false;

const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]')?.content || "";

export const formatVnd = (amount) => {
    const value = Number.parseInt(amount || 0, 10);

    if (!value) {
        return "Liên hệ";
    }

    return new Intl.NumberFormat("vi-VN").format(value) + " đ";
};

export const showCartToast = (message, type = "success") => {
    const container = document.getElementById("cart-toast-container");

    if (!container) {
        return;
    }

    const toast = document.createElement("div");
    toast.className = [
        "pointer-events-auto px-4 py-3 rounded-sm shadow-lg border text-sm font-medium animate-[fadeIn_0.2s_ease]",
        type === "error"
            ? "bg-red-50 border-red-200 text-red-800"
            : "bg-white border-secondary/30 text-primary",
    ].join(" ");
    toast.textContent = message;
    container.appendChild(toast);

    window.setTimeout(() => {
        toast.classList.add("opacity-0", "transition-opacity", "duration-300");
        window.setTimeout(() => toast.remove(), 300);
    }, 4000);
};

export const dispatchCartUpdated = (detail = {}) => {
    window.dispatchEvent(new CustomEvent("cart:updated", { detail }));
};

const updateAllBadges = (count) => {
    document.querySelectorAll("[data-mini-cart-badge]").forEach((badge) => {
        const value = Number.parseInt(count || 0, 10) || 0;

        if (value > 0) {
            badge.textContent = String(value);
            badge.classList.remove("hidden");
        } else {
            badge.textContent = "0";
            badge.classList.add("hidden");
        }
    });
};

const escapeHtml = (value) =>
    String(value ?? "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;");

const renderMiniCartItems = (root, data) => {
    const itemsEl = root.querySelector("[data-mini-cart-items]");
    const emptyEl = root.querySelector("[data-mini-cart-empty]");
    const footerEl = root.querySelector("[data-mini-cart-footer]");
    const subtotalEl = root.querySelector("[data-mini-cart-subtotal]");

    if (!itemsEl || !emptyEl || !footerEl) {
        return;
    }

    const items = data?.items || [];

    if (items.length === 0) {
        itemsEl.innerHTML = "";
        itemsEl.classList.add("hidden");
        emptyEl.classList.remove("hidden");
        footerEl.classList.add("hidden");
        return;
    }

    emptyEl.classList.add("hidden");
    footerEl.classList.remove("hidden");
    itemsEl.classList.remove("hidden");
    subtotalEl.textContent = data.subtotal_formatted || formatVnd(data.subtotal);

    itemsEl.innerHTML = items
        .map(
            (item) => `
        <div class="flex gap-3 px-4 py-3">
            <img src="${escapeHtml(item.image_url)}" alt="" class="w-14 h-14 rounded-sm object-cover bg-neutral-2 shrink-0" loading="lazy" />
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-primary truncate">${escapeHtml(item.name)}</p>
                <p class="text-xs text-primary/60 mt-0.5">
                    ${escapeHtml(item.variant_name || "")}${item.variant_name ? " · " : ""}SL ${item.quantity} × ${escapeHtml(item.price_formatted)}
                </p>
                <p class="text-sm font-bold text-secondary mt-1">${escapeHtml(item.line_total_formatted)}</p>
            </div>
        </div>
    `,
        )
        .join("");
};

const fetchMiniCart = async () => {
    const response = await fetch(MINI_URL, {
        headers: { Accept: "application/json" },
    });

    if (!response.ok) {
        throw new Error("Failed to load mini cart");
    }

    return response.json();
};

const closeMiniCartPanels = (exceptRoot = null) => {
    document.querySelectorAll("[data-mini-cart]").forEach((root) => {
        if (exceptRoot && root === exceptRoot) {
            return;
        }

        const panel = root.querySelector("[data-mini-cart-panel]");
        const toggle = root.querySelector("[data-mini-cart-toggle]");

        panel?.classList.add("hidden");
        toggle?.setAttribute("aria-expanded", "false");
    });
};

export const initMiniCart = () => {
    if (miniCartInitialized) {
        return;
    }

    miniCartInitialized = true;

    document.querySelectorAll("[data-mini-cart]").forEach((root) => {
        const toggle = root.querySelector("[data-mini-cart-toggle]");
        const panel = root.querySelector("[data-mini-cart-panel]");

        if (!toggle || !panel) {
            return;
        }

        toggle.addEventListener("click", async (event) => {
            event.preventDefault();
            event.stopPropagation();

            const isOpen = !panel.classList.contains("hidden");

            if (isOpen) {
                panel.classList.add("hidden");
                toggle.setAttribute("aria-expanded", "false");
                return;
            }

            closeMiniCartPanels(root);
            panel.classList.remove("hidden");
            toggle.setAttribute("aria-expanded", "true");

            try {
                const data = await fetchMiniCart();
                renderMiniCartItems(root, data);
            } catch {
                showCartToast("Không thể tải giỏ hàng.", "error");
            }
        });
    });

    document.addEventListener("click", (event) => {
        if (!event.target.closest("[data-mini-cart]")) {
            closeMiniCartPanels();
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeMiniCartPanels();
        }
    });

    window.addEventListener("cart:updated", async (event) => {
        const count = event.detail?.count;

        if (count !== undefined) {
            updateAllBadges(count);
        }

        document.querySelectorAll("[data-mini-cart]").forEach(async (root) => {
            const panel = root.querySelector("[data-mini-cart-panel]");

            if (panel && !panel.classList.contains("hidden")) {
                try {
                    const data = await fetchMiniCart();
                    renderMiniCartItems(root, data);
                } catch {
                    // ignore refresh errors
                }
            }
        });
    });
};

const getModalElements = () => ({
    modal: document.getElementById("cart-modal"),
    overlay: document.querySelector("[data-cart-modal-overlay]"),
    closeBtn: document.querySelector("[data-cart-modal-close]"),
    loading: document.getElementById("cart-modal-loading"),
    title: document.getElementById("cart-modal-title"),
    sku: document.getElementById("cart-modal-sku"),
    image: document.getElementById("cart-modal-image"),
    variantsWrap: document.getElementById("cart-modal-variants-wrap"),
    variantLabel: document.getElementById("cart-modal-variant-label"),
    variants: document.getElementById("cart-modal-variants"),
    qtyDisplay: document.getElementById("cart-modal-qty"),
    total: document.getElementById("cart-modal-total"),
    submit: document.getElementById("cart-modal-submit"),
    contactOnly: document.getElementById("cart-modal-contact-only"),
    qtyDecrease: document.querySelector("[data-cart-modal-qty-decrease]"),
    qtyIncrease: document.querySelector("[data-cart-modal-qty-increase]"),
});

const setModalLoading = (loading) => {
    const { loading: loadingEl, submit } = getModalElements();

    if (loadingEl) {
        loadingEl.classList.toggle("hidden", !loading);
    }

    if (submit && modalState && !modalState.contactOnly) {
        submit.disabled = loading;
    }
};

const getSelectedVariant = () => {
    if (!modalState?.options?.variants?.length) {
        return null;
    }

    const selected = document.querySelector("#cart-modal-variants [data-variant-id].selected");

    return selected ? Number.parseInt(selected.dataset.variantId, 10) : null;
};

const getUnitPrice = () => {
    if (!modalState?.options) {
        return 0;
    }

    const selectedId = getSelectedVariant();

    if (selectedId) {
        const variant = modalState.options.variants.find((item) => item.id === selectedId);

        return variant?.price || 0;
    }

    return modalState.options.unit_price || 0;
};

const updateModalTotal = () => {
    const { total, sku, image, submit, contactOnly: contactEl } = getModalElements();

    if (!modalState?.options || !total) {
        return;
    }

    const qty = modalState.qty;
    const unitPrice = getUnitPrice();
    const selectedId = getSelectedVariant();
    const variant = modalState.options.variants.find((item) => item.id === selectedId);

    if (variant?.image_url && image) {
        image.src = variant.image_url;
    }

    if (sku) {
        if (variant?.sku) {
            sku.textContent = `Mã: ${variant.sku}`;
            sku.classList.remove("hidden");
        } else {
            sku.classList.add("hidden");
        }
    }

    if (modalState.options.contact_only || unitPrice <= 0) {
        total.textContent = "Liên hệ";
        submit?.classList.add("hidden");
        contactEl?.classList.remove("hidden");
        return;
    }

    submit?.classList.remove("hidden");
    contactEl?.classList.add("hidden");
    total.textContent = formatVnd(unitPrice * qty);

    if (submit) {
        const needsVariant = modalState.options.requires_variant && modalState.options.variants.length > 0;
        submit.disabled = needsVariant && !selectedId;
    }
};

const renderModalOptions = (options) => {
    const {
        title,
        image,
        variantsWrap,
        variantLabel,
        variants: variantsEl,
        qtyDisplay,
        contactOnly: contactEl,
        submit,
    } = getModalElements();

    modalState = { options, qty: 1 };

    if (title) {
        title.textContent = options.name;
    }

    if (image) {
        image.src = options.image_url;
        image.alt = options.name;
    }

    if (qtyDisplay) {
        qtyDisplay.textContent = "1";
    }

    if (options.contact_only) {
        submit?.classList.add("hidden");
        contactEl?.classList.remove("hidden");
        variantsWrap?.classList.add("hidden");
        updateModalTotal();
        return;
    }

    contactEl?.classList.add("hidden");
    submit?.classList.remove("hidden");

    if (options.variants?.length) {
        variantsWrap?.classList.remove("hidden");

        if (variantLabel) {
            variantLabel.textContent = options.variant_label || "Phân loại";
        }

        variantsEl.innerHTML = options.variants
            .map(
                (variant) => `
            <button type="button" data-variant-id="${variant.id}"
                class="px-3 py-1.5 text-sm border rounded-sm transition-colors border-neutral-1 hover:border-secondary ${variant.id === options.default_variant_id ? "selected border-secondary bg-secondary/5" : ""}">
                ${escapeHtml(variant.name)}
            </button>
        `,
            )
            .join("");
    } else {
        variantsWrap?.classList.add("hidden");
        variantsEl.innerHTML = "";
    }

    updateModalTotal();
};

const openModal = () => {
    const { modal } = getModalElements();

    if (!modal) {
        return;
    }

    modal.classList.remove("hidden");
    modal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
};

export const closeCartModal = () => {
    const { modal } = getModalElements();

    if (!modal) {
        return;
    }

    modal.classList.add("hidden");
    modal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
    modalState = null;
};

const initCartModal = () => {
    if (modalInitialized) {
        return;
    }

    modalInitialized = true;

    const {
        overlay,
        closeBtn,
        variants,
        qtyDecrease,
        qtyIncrease,
        submit,
    } = getModalElements();

    overlay?.addEventListener("click", closeCartModal);
    closeBtn?.addEventListener("click", closeCartModal);

    document.addEventListener("keydown", (event) => {
        const { modal } = getModalElements();

        if (event.key === "Escape" && modal && !modal.classList.contains("hidden")) {
            closeCartModal();
        }
    });

    variants?.addEventListener("click", (event) => {
        const button = event.target.closest("[data-variant-id]");

        if (!button) {
            return;
        }

        variants.querySelectorAll("[data-variant-id]").forEach((item) => {
            item.classList.remove("selected", "border-secondary", "bg-secondary/5");
        });
        button.classList.add("selected", "border-secondary", "bg-secondary/5");
        updateModalTotal();
    });

    qtyDecrease?.addEventListener("click", () => {
        if (!modalState) {
            return;
        }

        modalState.qty = Math.max(1, modalState.qty - 1);
        getModalElements().qtyDisplay.textContent = String(modalState.qty);
        updateModalTotal();
    });

    qtyIncrease?.addEventListener("click", () => {
        if (!modalState) {
            return;
        }

        modalState.qty = Math.min(99999, modalState.qty + 1);
        getModalElements().qtyDisplay.textContent = String(modalState.qty);
        updateModalTotal();
    });

    submit?.addEventListener("click", async () => {
        if (!modalState?.options) {
            return;
        }

        const variantId = getSelectedVariant();
        const needsVariant = modalState.options.requires_variant && modalState.options.variants.length > 0;

        if (needsVariant && !variantId) {
            showCartToast("Vui lòng chọn phân loại sản phẩm.", "error");
            return;
        }

        submit.disabled = true;

        try {
            const data = await addToCart({
                productType: modalState.options.product_type,
                productId: modalState.options.product_id,
                variantId,
                qty: modalState.qty,
            });

            closeCartModal();
            showCartToast(data.message || "Đã thêm vào giỏ hàng!");
            dispatchCartUpdated({ count: data.cart_count, total: data.cart_total });
        } catch (error) {
            showCartToast(error.message || "Có lỗi xảy ra.", "error");
        } finally {
            submit.disabled = false;
            updateModalTotal();
        }
    });
};

export const addToCart = async ({ productType, productId, variantId, qty }) => {
    const response = await fetch(ADD_URL, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": getCsrfToken(),
            Accept: "application/json",
        },
        body: JSON.stringify({
            product_type: productType,
            product_id: productId,
            variant_id: variantId,
            qty,
        }),
    });

    const data = await response.json();

    if (!response.ok || data.status !== "success") {
        throw new Error(data.message || "Có lỗi xảy ra.");
    }

    return data;
};

export const openCartModal = async ({ productType, productId, productName }) => {
    initCartModal();
    openModal();
    setModalLoading(true);

    const { title } = getModalElements();

    if (title && productName) {
        title.textContent = productName;
    }

    try {
        const response = await fetch(
            `${OPTIONS_URL}?product_type=${encodeURIComponent(productType)}&product_id=${encodeURIComponent(productId)}`,
            { headers: { Accept: "application/json" } },
        );

        const payload = await response.json();

        if (!response.ok || payload.status !== "success") {
            throw new Error(payload.message || "Không thể tải thông tin sản phẩm.");
        }

        renderModalOptions(payload.data);
    } catch (error) {
        closeCartModal();
        showCartToast(error.message || "Không thể mở giỏ hàng.", "error");
    } finally {
        setModalLoading(false);
    }
};

export const initCartUi = () => {
    initCartModal();
    initMiniCart();
};
