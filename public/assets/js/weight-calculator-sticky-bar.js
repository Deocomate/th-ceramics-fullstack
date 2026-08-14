const STORAGE_KEY = "thc-hide-weight-bar";

const readDismissed = () => {
    try {
        return window.sessionStorage.getItem(STORAGE_KEY) === "1";
    } catch (error) {
        return false;
    }
};

const writeDismissed = () => {
    try {
        window.sessionStorage.setItem(STORAGE_KEY, "1");
    } catch (error) {
        // Private / blocked storage on iOS Safari should not hide the bar.
    }
};

const hideBar = (bar) => {
    bar.hidden = true;
    bar.setAttribute("aria-hidden", "true");
    document.documentElement.classList.remove("has-weight-calculator-bar");
};

export const initWeightCalculatorStickyBar = () => {
    const bar = document.querySelector("[data-weight-calculator-bar]");
    if (!bar || bar.dataset.weightBarInitialized === "true") {
        return;
    }

    bar.dataset.weightBarInitialized = "true";

    if (bar.parentElement !== document.body) {
        document.body.appendChild(bar);
    }

    if (readDismissed()) {
        hideBar(bar);
        return;
    }

    document.documentElement.classList.add("has-weight-calculator-bar");

    const scrollLink = bar.querySelector("[data-weight-bar-scroll]");
    const dismissButton = bar.querySelector("[data-weight-bar-dismiss]");
    const targetSelector = bar.dataset.target || "#cach-tinh-khoi-luong";

    scrollLink?.addEventListener("click", (event) => {
        const target = document.querySelector(targetSelector);
        if (!target) {
            return;
        }

        event.preventDefault();
        target.scrollIntoView({ behavior: "smooth", block: "start" });
    });

    dismissButton?.addEventListener("click", () => {
        writeDismissed();
        hideBar(bar);
    });
};
