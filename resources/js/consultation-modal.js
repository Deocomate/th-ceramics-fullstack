const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]')?.content || "";

const getSelectedVariantName = () => {
    const container = document.querySelector("[data-product-detail-container]");
    if (!container) {
        return "";
    }

    const selected = container.querySelector("[data-product-variant].selected");
    if (!selected) {
        return "";
    }

    return selected.dataset.name || selected.textContent?.trim() || "";
};

const clearFieldErrors = (modal) => {
    modal.querySelectorAll("[data-consultation-error]").forEach((el) => {
        el.textContent = "";
        el.classList.add("hidden");
    });

    const general = modal.querySelector("[data-consultation-general-error]");
    if (general) {
        general.textContent = "";
        general.classList.add("hidden");
    }
};

const showFieldErrors = (modal, errors) => {
    Object.entries(errors).forEach(([field, messages]) => {
        const el = modal.querySelector(`[data-consultation-error="${field}"]`);
        if (!el) {
            return;
        }

        el.textContent = Array.isArray(messages) ? messages[0] : String(messages);
        el.classList.remove("hidden");
    });
};

const openConsultationModal = (trigger) => {
    const modal = document.getElementById("consultation-modal");
    if (!modal) {
        return;
    }

    const form = modal.querySelector("[data-consultation-form]");
    const formView = modal.querySelector("[data-consultation-form-view]");
    const successView = modal.querySelector("[data-consultation-success-view]");
    const summary = modal.querySelector("[data-consultation-product-summary]");

    if (!form || !formView || !successView) {
        return;
    }

    clearFieldErrors(modal);
    formView.classList.remove("hidden");
    successView.classList.add("hidden");

    const productType = trigger.dataset.productType || "";
    const productId = trigger.dataset.productId || "";
    const productName = trigger.dataset.productName || "";
    let variantName = trigger.dataset.variantName || "";

    if (!variantName && trigger.hasAttribute("data-open-consultation")) {
        variantName = getSelectedVariantName();
    }

    modal.querySelector("[data-consultation-product-id]").value = productId;
    modal.querySelector("[data-consultation-product-type]").value = productType;
    modal.querySelector("[data-consultation-product-name]").value = productName;
    modal.querySelector("[data-consultation-variant-name]").value = variantName;

    if (summary) {
        const parts = [productName, variantName].filter(Boolean);
        summary.textContent = parts.length ? parts.join(" · ") : "Sản phẩm Thanh Hải";
    }

    const nameInput = form.querySelector('[name="customer_name"]');
    const phoneInput = form.querySelector('[name="phone"]');
    const emailInput = form.querySelector('[name="email"]');

    if (nameInput && !nameInput.value && modal.dataset.userName) {
        nameInput.value = modal.dataset.userName;
    }
    if (phoneInput && !phoneInput.value && modal.dataset.userPhone) {
        phoneInput.value = modal.dataset.userPhone;
    }
    if (emailInput && !emailInput.value && modal.dataset.userEmail) {
        emailInput.value = modal.dataset.userEmail;
    }

    modal.classList.remove("hidden");
    modal.setAttribute("aria-hidden", "false");
    document.body.classList.add("overflow-hidden");
};

const closeConsultationModal = () => {
    const modal = document.getElementById("consultation-modal");
    if (!modal) {
        return;
    }

    modal.classList.add("hidden");
    modal.setAttribute("aria-hidden", "true");
    document.body.classList.remove("overflow-hidden");
};

const submitConsultationForm = async (modal, form) => {
    const submitButton = form.querySelector('[type="submit"]');
    const url = modal.dataset.consultationUrl;
    const formView = modal.querySelector("[data-consultation-form-view]");
    const successView = modal.querySelector("[data-consultation-success-view]");
    const successMessage = modal.querySelector("[data-consultation-success-message]");

    if (!url || !submitButton) {
        return;
    }

    clearFieldErrors(modal);
    submitButton.disabled = true;

    try {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": getCsrfToken(),
                Accept: "application/json",
            },
            body: new FormData(form),
        });

        const data = await response.json().catch(() => ({}));

        if (response.ok) {
            form.reset();
            if (successMessage) {
                successMessage.textContent = data.message || "Yêu cầu của bạn đã được ghi nhận.";
            }
            formView?.classList.add("hidden");
            successView?.classList.remove("hidden");

            window.setTimeout(() => {
                closeConsultationModal();
                formView?.classList.remove("hidden");
                successView?.classList.add("hidden");
            }, 3500);

            return;
        }

        if (response.status === 422 && data.errors) {
            showFieldErrors(modal, data.errors);
            return;
        }

        const general = modal.querySelector("[data-consultation-general-error]");
        if (general) {
            general.textContent = data.message || "Không thể gửi yêu cầu. Vui lòng thử lại.";
            general.classList.remove("hidden");
        }
    } catch {
        const general = modal.querySelector("[data-consultation-general-error]");
        if (general) {
            general.textContent = "Không thể kết nối máy chủ. Vui lòng thử lại.";
            general.classList.remove("hidden");
        }
    } finally {
        submitButton.disabled = false;
    }
};

export const initConsultationModal = () => {
    const modal = document.getElementById("consultation-modal");
    if (!modal || modal.dataset.consultationInitialized === "true") {
        return;
    }

    modal.dataset.consultationInitialized = "true";

    document.addEventListener("click", (event) => {
        const trigger = event.target.closest(".js-open-consultation, [data-open-consultation]");
        if (!trigger) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        openConsultationModal(trigger);
    });

    modal.querySelectorAll("[data-consultation-close], [data-consultation-overlay]").forEach((el) => {
        el.addEventListener("click", closeConsultationModal);
    });

    const form = modal.querySelector("[data-consultation-form]");
    form?.addEventListener("submit", (event) => {
        event.preventDefault();
        submitConsultationForm(modal, form);
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && !modal.classList.contains("hidden")) {
            closeConsultationModal();
        }
    });
};
