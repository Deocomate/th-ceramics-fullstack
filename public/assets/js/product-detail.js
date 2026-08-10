import { addToCart, dispatchCartUpdated, showCartToast } from "./cart-ui.js";
import {
    extractYoutubeId,
    mountInlineVideoShell,
    resetInlineVideoShell,
} from "./youtube-embed.js";

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

const resetVideoShell = (shell) => resetInlineVideoShell(shell);

const resetAllVideoShells = (root) => {
    root.querySelectorAll("[data-product-video-shell]").forEach((shell) => resetVideoShell(shell));
};

const mountVideoIframe = (shell, { autoplay = true } = {}) => {
    if (!shell) {
        return Promise.resolve(null);
    }

    if (!shell.dataset.youtubeId && shell.dataset.embedSrc) {
        const id = extractYoutubeId(shell.dataset.embedSrc);
        if (id) {
            shell.dataset.youtubeId = id;
        }
    }

    return mountInlineVideoShell(shell, { autoplay });
};

const mountActiveVideoSlide = (mainSwiperElement, swiper) => {
    if (!mainSwiperElement || !swiper) {
        return;
    }

    const activeSlide = swiper.slides?.[swiper.activeIndex];
    if (!activeSlide || activeSlide.dataset.galleryType !== "video") {
        return;
    }

    const shell = activeSlide.querySelector("[data-product-video-shell]");
    mountVideoIframe(shell, { autoplay: true });
};

const focusThumbSlide = (thumbSwiper, index) => {
    if (!thumbSwiper || thumbSwiper.destroyed) {
        return;
    }

    const target = Math.max(0, Math.min(index, thumbSwiper.slides.length - 1));
    // Keep active thumb near the center of the visible rail when possible.
    const offset = Math.floor((thumbSwiper.params.slidesPerView || 1) / 2);
    thumbSwiper.slideTo(Math.max(0, target - offset), 280);
};

const initProductGallery = (container) => {
    if (container.dataset.productGalleryInitialized === "true") {
        return;
    }

    const mainSwiperElement = container.querySelector("[data-product-main-swiper]");
    const thumbSwiperElement = container.querySelector("[data-product-thumb-swiper]");
    const paginationElement = container.querySelector("[data-product-main-pagination]");
    const prevButton = container.querySelector("[data-product-main-prev]");
    const nextButton = container.querySelector("[data-product-main-next]");

    if (!mainSwiperElement || typeof window.Swiper !== "function") {
        return;
    }

    container.dataset.productGalleryInitialized = "true";

    const thumbSwiper = thumbSwiperElement
        ? new window.Swiper(thumbSwiperElement, {
              spaceBetween: 16,
              slidesPerView: 5,
              watchSlidesProgress: true,
              slideToClickedSlide: true,
              centerInsufficientSlides: true,
              watchOverflow: true,
              breakpoints: {
                  1024: {
                      slidesPerView: 7,
                      spaceBetween: 16,
                  },
              },
          })
        : null;

    const options = {
        slidesPerView: 1,
        spaceBetween: 0,
        observer: true,
        observeParents: true,
        on: {
            slideChange(swiper) {
                // Swiper transform + YouTube iframe = màn đen; unmount khi rời slide.
                resetAllVideoShells(mainSwiperElement);
                focusThumbSlide(thumbSwiper, swiper.activeIndex);
                mountActiveVideoSlide(mainSwiperElement, swiper);
            },
        },
    };

    if (paginationElement) {
        options.pagination = {
            el: paginationElement,
            clickable: true,
        };
    }

    if (prevButton || nextButton) {
        options.navigation = {
            prevEl: prevButton,
            nextEl: nextButton,
        };
    }

    if (thumbSwiper) {
        options.thumbs = {
            swiper: thumbSwiper,
        };
    }

    const mainSwiper = new window.Swiper(mainSwiperElement, options);
    focusThumbSlide(thumbSwiper, mainSwiper.activeIndex);
    mountActiveVideoSlide(mainSwiperElement, mainSwiper);
};

const bindProductVideoPlayDelegation = () => {
    if (document.body.dataset.productVideoPlayBound === "true") {
        return;
    }

    document.body.dataset.productVideoPlayBound = "true";

    document.addEventListener("click", (event) => {
        const playButton = event.target.closest("[data-product-video-play]");

        if (!playButton) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        const shell = playButton.closest("[data-product-video-shell]");
        const mainSwiperElement = playButton.closest("[data-product-main-swiper]");

        if (mainSwiperElement) {
            resetAllVideoShells(mainSwiperElement);
        }

        mountVideoIframe(shell, { autoplay: true });
    });
};

const initStandaloneProductGalleries = () => {
    const bindLegacyResets = () => {
        document.querySelectorAll("[data-product-main-swiper]").forEach((mainSwiperElement) => {
            if (mainSwiperElement.closest("[data-product-detail-container]")) {
                return;
            }

            if (mainSwiperElement.dataset.videoResetBound === "true") {
                return;
            }

            const swiper = mainSwiperElement.swiper;
            if (!swiper || typeof swiper.on !== "function") {
                return;
            }

            mainSwiperElement.dataset.videoResetBound = "true";
            swiper.on("slideChange", () => {
                resetAllVideoShells(mainSwiperElement);
                mountActiveVideoSlide(mainSwiperElement, swiper);
            });
            mountActiveVideoSlide(mainSwiperElement, swiper);
        });
    };

    bindLegacyResets();
    window.setTimeout(bindLegacyResets, 400);
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
        if (slide.dataset.galleryType === "video") {
            return false;
        }

        const image = slide.querySelector("img");

        return sameUrl(image?.currentSrc || image?.src, imageUrl);
    });

    if (targetIndex >= 0) {
        swiper.slideTo(targetIndex);
    }
};

const syncQuantityInputWidth = (input) => {
    if (!input) {
        return;
    }

    const digits = Math.max(1, String(input.value ?? "").length);
    input.style.width = `${Math.max(3, digits) + 2.5}ch`;
};

const setQuantity = (container, value) => {
    const quantity = Math.max(1, Number.parseInt(value || 1, 10) || 1);
    const input = container.querySelector("[data-detail-quantity-input]");

    if (input) {
        input.value = quantity;
        syncQuantityInputWidth(input);
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
            const variantElements = Array.from(container.querySelectorAll("[data-product-variant]"));
            const selectedVariant = container.querySelector("[data-product-variant].selected");

            if (!type || !id) {
                showCartToast("Thông tin sản phẩm không đầy đủ.", "error");
                return;
            }

            if (variantElements.length > 0 && !selectedVariant) {
                showCartToast("Vui lòng chọn màu sắc/phân loại trước khi thêm vào giỏ hàng!", "error");
                return;
            }

            addToCartButton.disabled = true;

            addToCart({
                productType: type,
                productId: id,
                variantId: selectedVariant?.dataset.variantId
                    ? Number.parseInt(selectedVariant.dataset.variantId, 10)
                    : null,
                qty,
            })
                .then((data) => {
                    showCartToast(data.message || "Đã thêm vào giỏ hàng!");
                    dispatchCartUpdated({ count: data.cart_count, total: data.cart_total });
                })
                .catch((error) => {
                    showCartToast(error.message || "Có lỗi xảy ra.", "error");
                })
                .finally(() => {
                    addToCartButton.disabled = false;
                });
        }
    });

    const quantityInput = container.querySelector("[data-detail-quantity-input]");
    syncQuantityInputWidth(quantityInput);

    quantityInput?.addEventListener("keydown", (event) => {
        if (["e", "E", "+", "-", "."].includes(event.key)) {
            event.preventDefault();
        }
    });

    quantityInput?.addEventListener("input", (event) => {
        syncQuantityInputWidth(event.target);
    });

    quantityInput?.addEventListener("change", (event) => {
        setQuantity(container, event.target.value);
    });

    quantityInput?.addEventListener("blur", (event) => {
        setQuantity(container, event.target.value);
    });
};

const initProductDetail = () => {
    bindProductVideoPlayDelegation();
    document.querySelectorAll("[data-product-detail-container]").forEach(initProductDetailContainer);
    initStandaloneProductGalleries();
};

export { initProductDetail };
