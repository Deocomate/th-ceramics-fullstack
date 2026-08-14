const STORAGE_KEY = "thc-hide-weight-bar";

const setWeightBarPadding = (visible) => {
    document.documentElement.classList.toggle("has-weight-calculator-bar", visible);
};

const initWeightCalculatorStickyBar = () => {
    document.querySelectorAll("[data-weight-calculator-bar]").forEach((bar) => {
        if (bar.dataset.weightBarInitialized === "true") {
            return;
        }

        bar.dataset.weightBarInitialized = "true";

        if (window.sessionStorage.getItem(STORAGE_KEY) === "1") {
            bar.classList.add("hidden");
            setWeightBarPadding(false);
            return;
        }

        bar.classList.remove("hidden");
        setWeightBarPadding(true);

        const link = bar.querySelector("[data-weight-calculator-bar-link]");
        const dismiss = bar.querySelector("[data-weight-calculator-bar-dismiss]");
        const targetSelector = bar.dataset.target || "#cach-tinh-khoi-luong";

        link?.addEventListener("click", (event) => {
            const target = document.querySelector(targetSelector);
            if (!target) {
                return;
            }

            event.preventDefault();
            target.scrollIntoView({ behavior: "smooth", block: "start" });
        });

        dismiss?.addEventListener("click", (event) => {
            event.preventDefault();
            event.stopPropagation();
            bar.classList.add("hidden");
            setWeightBarPadding(false);
            window.sessionStorage.setItem(STORAGE_KEY, "1");
        });
    });
};

export { initWeightCalculatorStickyBar };
