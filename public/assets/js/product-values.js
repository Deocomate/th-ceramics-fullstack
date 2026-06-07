const updateValueSection = (section, item) => {
    const valueImages = Array.from(section.querySelectorAll("[data-value-image]"));
    const valueTitle = section.querySelector("[data-value-title]");
    const valueDescription = section.querySelector("[data-value-description]");

    if (!valueImages.length || !valueTitle || !valueDescription || item.classList.contains("active")) {
        return;
    }

    valueImages.forEach((image) => image.classList.remove("active"));
    item.classList.add("active");

    valueTitle.style.opacity = "0";
    valueDescription.style.opacity = "0";
    valueTitle.style.transform = "translateY(10px)";
    valueDescription.style.transform = "translateY(10px)";

    window.setTimeout(() => {
        valueTitle.textContent = item.dataset.title || "";
        valueDescription.textContent = item.dataset.description || "";
        valueTitle.style.opacity = "1";
        valueDescription.style.opacity = "1";
        valueTitle.style.transform = "translateY(0)";
        valueDescription.style.transform = "translateY(0)";
    }, 300);
};

const initProductValueSection = (section) => {
    if (section.dataset.productValuesInitialized === "true") {
        return;
    }

    section.dataset.productValuesInitialized = "true";

    section.querySelectorAll("[data-value-image]").forEach((item) => {
        item.addEventListener("mouseenter", () => {
            if (window.innerWidth >= 1024) {
                updateValueSection(section, item);
            }
        });

        item.addEventListener("click", () => {
            updateValueSection(section, item);
        });
    });
};

const initProductValues = () => {
    document.querySelectorAll("[data-product-values]").forEach(initProductValueSection);
};

export { initProductValues };
