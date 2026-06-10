const formatPrice = (raw) => {
    const value = Number.parseFloat(raw || "0");

    if (!value) {
        return "Liên hệ";
    }

    return new Intl.NumberFormat("vi-VN").format(value) + " đ/m²";
};

const sameUrl = (left, right) => {
    if (!left || !right) {
        return false;
    }

    try {
        return new URL(left, window.location.href).href === new URL(right, window.location.href).href;
    } catch (error) {
        return left === right;
    }
};

const initProductGallery = (container) => {
    if (container.dataset.productGalleryInitialized === "true") {
        return;
    }

    const mainSwiperElement = container.querySelector("[data-product-main-swiper]");
    const thumbSwiperElement = container.querySelector("[data-product-thumb-swiper]");
    const paginationElement = container.querySelector("[data-product-main-pagination]");

    if (!mainSwiperElement || typeof window.Swiper !== "function") {
        return;
    }

    container.dataset.productGalleryInitialized = "true";

    const thumbSwiper = thumbSwiperElement
        ? new window.Swiper(thumbSwiperElement, {
              spaceBetween: 20,
              slidesPerView: 7,
              freeMode: true,
              watchSlidesProgress: true,
          })
        : null;

    const options = {
        slidesPerView: 1,
        spaceBetween: 0,
    };

    if (paginationElement) {
        options.pagination = {
            el: paginationElement,
            clickable: true,
        };
    }

    if (thumbSwiper) {
        options.thumbs = {
            swiper: thumbSwiper,
        };
    }

    new window.Swiper(mainSwiperElement, options);
};

const switchGalleryToImage = (container, imageUrl) => {
    if (!imageUrl) {
        return;
    }

    const mainSwiperElement = container.querySelector("[data-product-main-swiper]");
    const swiper = mainSwiperElement?.swiper;

    if (!swiper) {
        return;
    }

    const slides = Array.from(mainSwiperElement.querySelectorAll(".swiper-slide"));
    const targetIndex = slides.findIndex((slide) => {
        const image = slide.querySelector("img");

        return sameUrl(image?.currentSrc || image?.src, imageUrl);
    });

    if (targetIndex >= 0) {
        swiper.slideTo(targetIndex);
    }
};

const setQuantity = (container, value) => {
    const quantity = Math.max(1, Number.parseInt(value || 1, 10) || 1);
    const display = container.querySelector("[data-detail-quantity-display]");
    const input = container.querySelector("[data-detail-quantity-input]");

    if (display) {
        display.textContent = quantity;
    }

    if (input) {
        input.value = quantity;
    }

    return quantity;
};

const initProductDetailContainer = (container) => {
    if (container.dataset.productDetailInitialized === "true") {
        initProductGallery(container);
        return;
    }

    container.dataset.productDetailInitialized = "true";
    initProductGallery(container);
    setQuantity(container, container.querySelector("[data-detail-quantity-input]")?.value || 1);

    container.addEventListener("click", (event) => {
        const variant = event.target.closest("[data-product-variant]");

        if (variant && container.contains(variant)) {
            const group = variant.parentElement;
            group?.querySelectorAll("[data-product-variant]").forEach((item) => {
                item.classList.remove("selected");
                item.setAttribute("aria-pressed", "false");
            });
            variant.classList.add("selected");
            variant.setAttribute("aria-pressed", "true");

            const skuOutput = container.querySelector("[data-detail-sku]");
            const priceOutput = container.querySelector("[data-detail-price]");

            if (skuOutput && variant.dataset.sku) {
                skuOutput.textContent = variant.dataset.sku;
            }

            if (priceOutput) {
                priceOutput.textContent = variant.dataset.priceFormatted || formatPrice(variant.dataset.price);
            }

            switchGalleryToImage(container, variant.dataset.image);
            return;
        }

        if (event.target.closest("[data-detail-quantity-decrease]")) {
            event.preventDefault();
            const input = container.querySelector("[data-detail-quantity-input]");
            setQuantity(container, (Number.parseInt(input?.value || "1", 10) || 1) - 1);
            return;
        }

        if (event.target.closest("[data-detail-quantity-increase]")) {
            event.preventDefault();
            const input = container.querySelector("[data-detail-quantity-input]");
            setQuantity(container, (Number.parseInt(input?.value || "1", 10) || 1) + 1);
            return;
        }

        const addToCartButton = event.target.closest("[data-detail-add-to-cart]");

        if (addToCartButton && container.contains(addToCartButton)) {
            event.preventDefault();

            if (addToCartButton.disabled || addToCartButton.classList.contains("cursor-not-allowed")) {
                return;
            }

            const type = container.dataset.productType || "";
            const id = Number.parseInt(container.dataset.productId || "", 10);
            const qty = Number.parseInt(container.querySelector("[data-detail-quantity-input]")?.value || "1", 10) || 1;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || "";
            const addToCartUrl = container.dataset.addToCartUrl || "/gio-hang/them";
            const variantElements = Array.from(container.querySelectorAll("[data-product-variant]"));
            const selectedVariant = container.querySelector("[data-product-variant].selected");

            if (!type || !id) {
                alert("Thông tin sản phẩm không đầy đủ.");
                return;
            }

            if (variantElements.length > 0 && !selectedVariant) {
                alert("Vui lòng chọn màu sắc/phân loại trước khi thêm vào giỏ hàng!");
                return;
            }

            addToCartButton.disabled = true;

            fetch(addToCartUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    product_type: type,
                    product_id: id,
                    variant_id: selectedVariant?.dataset.variantId ? Number.parseInt(selectedVariant.dataset.variantId, 10) : null,
                    qty,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === "success") {
                        alert("Đã thêm vào giỏ hàng!");
                        return;
                    }

                    alert(data.message || "Có lỗi xảy ra.");
                })
                .catch(() => alert("Lỗi kết nối. Vui lòng thử lại."))
                .finally(() => {
                    addToCartButton.disabled = false;
                });
        }
    });

    container.querySelector("[data-detail-quantity-input]")?.addEventListener("input", (event) => {
        setQuantity(container, event.target.value);
    });

    container.querySelector("[data-detail-quantity-input]")?.addEventListener("change", (event) => {
        setQuantity(container, event.target.value);
    });
};

const initProductDetail = () => {
    document.querySelectorAll("[data-product-detail-container]").forEach(initProductDetailContainer);
};

export { initProductDetail };
