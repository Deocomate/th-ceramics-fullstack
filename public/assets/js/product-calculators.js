const numberFormatter = new Intl.NumberFormat("vi-VN");

const formatNumber = (value) => numberFormatter.format(value);

const parseNumber = (raw = "") => {
    const normalized = String(raw).replace(/\s/g, "").replace(/,/g, ".").replace(/[^\d.]/g, "");

    return Number.parseFloat(normalized) || 0;
};

const syncPurchaseQuantity = (quantity) => {
    if (quantity <= 0) {
        return;
    }

    document.querySelectorAll('input[name="qty"], input[name="quantity"], .qty-input').forEach((input) => {
        input.value = quantity;
        input.dispatchEvent(new Event("input", { bubbles: true }));
        input.dispatchEvent(new Event("change", { bubbles: true }));
    });
};

const getVisibleNumberInputs = (block) =>
    Array.from(block.querySelectorAll('input[type="number"]')).filter((input) => {
        const wrapper = input.closest("[data-input-wrapper]") || input.closest(".flex-1") || input.parentElement;

        return wrapper?.style.display !== "none";
    });

const getShapeType = (select) => (select?.value || select?.options[select.selectedIndex]?.text || "").toUpperCase();

const isRectangle = (type) => type.includes("CHỮ NHẬT") || type.includes("CHá»® NHáº¬T");
const isTrapezoid = (type) => type.includes("THANG");
const isTriangle = (type) => type.includes("TAM GI") || type.includes("TAM GIÃ");

const calculateShapeArea = (block) => {
    const select = block.querySelector("select");
    const type = getShapeType(select);
    const inputs = getVisibleNumberInputs(block);

    if (isRectangle(type)) {
        return parseNumber(inputs[0]?.value) * parseNumber(inputs[1]?.value);
    }

    if (isTrapezoid(type)) {
        return ((parseNumber(inputs[0]?.value) + parseNumber(inputs[1]?.value)) * parseNumber(inputs[2]?.value)) / 2;
    }

    if (isTriangle(type)) {
        return (parseNumber(inputs[0]?.value) * parseNumber(inputs[1]?.value)) / 2;
    }

    return parseNumber(inputs[0]?.value) * parseNumber(inputs[1]?.value);
};

const calculateShapeLength = (block) => {
    const inputs = getVisibleNumberInputs(block);

    return parseNumber(inputs[0]?.value);
};

const setLabelHtml = (wrapper, html) => {
    const label = wrapper?.querySelector("label");

    if (label) {
        label.innerHTML = html;
    }
};

const applyShapeInputs = (block) => {
    const select = block.querySelector("[data-shape-select]") || block.querySelector("select");
    const type = getShapeType(select);
    const wrappers = Array.from(block.querySelectorAll("[data-input-wrapper]"));

    if (wrappers.length === 0) {
        return;
    }

    if (isRectangle(type)) {
        setLabelHtml(wrappers[0], 'CHIỀU DÀI <span class="block text-[7px] md:text-[12px] font-normal italic normal-case text-[rgba(199,110,0,0.70)] md:text-secondary mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">Chiều tính diềm mái</span>');
        setLabelHtml(wrappers[1], 'CHIỀU RỘNG <span class="block text-[7px] md:text-[12px] opacity-0 mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">_</span>');
        wrappers[1]?.style.removeProperty("display");
        if (wrappers[2]) wrappers[2].style.display = "none";
        return;
    }

    if (isTrapezoid(type)) {
        setLabelHtml(wrappers[0], 'ĐÁY LỚN <span class="block text-[7px] md:text-[12px] font-normal italic normal-case text-[rgba(199,110,0,0.70)] md:text-secondary mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">Chiều tính diềm mái</span>');
        setLabelHtml(wrappers[1], 'ĐÁY BÉ <span class="block text-[7px] md:text-[12px] opacity-0 mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">_</span>');
        setLabelHtml(wrappers[2], 'CHIỀU CAO <span class="block text-[7px] md:text-[12px] opacity-0 mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">_</span>');
        wrappers[1]?.style.removeProperty("display");
        wrappers[2]?.style.removeProperty("display");
        return;
    }

    if (isTriangle(type)) {
        setLabelHtml(wrappers[0], 'ĐÁY <span class="block text-[7px] md:text-[12px] font-normal italic normal-case text-[rgba(199,110,0,0.70)] md:text-secondary mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">Chiều tính diềm mái</span>');
        if (wrappers[1]) wrappers[1].style.display = "none";
        setLabelHtml(wrappers[2], 'CHIỀU CAO <span class="block text-[7px] md:text-[12px] opacity-0 mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">_</span>');
        wrappers[2]?.style.removeProperty("display");
    }
};

const initQuantityCalculator = (section) => {
    if (section.dataset.quantityCalculatorInitialized === "true") {
        return;
    }

    section.dataset.quantityCalculatorInitialized = "true";

    const areaBlocks = () => Array.from(section.querySelectorAll("[data-area-block]"));
    const initialBlocks = areaBlocks();
    const masterTemplate = (initialBlocks.find((block) => block.querySelector("[data-remove-area]")) || initialBlocks[initialBlocks.length - 1])?.cloneNode(true);
    const addAreaBtn = section.querySelector("[data-add-area]");
    const calculateBtn = section.querySelector("[data-calculate-quantity]");
    const totalAreaOutput = section.querySelector("[data-total-area-output]");
    const rateOutputs = Array.from(section.querySelectorAll("[data-rate-output]"));
    const valueOutputs = Array.from(section.querySelectorAll("[data-value-output]"));
    const extraLossCheckbox = section.querySelector("#extra-loss-quantity");
    const lossRadios = Array.from(section.querySelectorAll('input[name="loss-rate"]'));

    const getLossFactor = () => {
        if (!extraLossCheckbox || !extraLossCheckbox.checked) return 1;

        return Number.parseFloat(lossRadios.find((radio) => radio.checked)?.value) || 1;
    };

    const getBlockArea = (block) => {
        const inputs = Array.from(block.querySelectorAll('input[type="number"]'));

        return parseNumber(inputs[0]?.value) * parseNumber(inputs[1]?.value);
    };

    const renumberAreas = () => {
        areaBlocks().forEach((block, index) => {
            const title = Array.from(block.querySelectorAll("span")).find((span) => (span.textContent || "").toUpperCase().includes("DI"));
            if (title) title.textContent = `DIỆN TÍCH ${index + 1}`;
        });
    };

    const updateResults = () => {
        const roundedArea = Math.ceil(areaBlocks().reduce((sum, block) => sum + getBlockArea(block), 0));
        const lossFactor = getLossFactor();
        let qtyToSync = 0;

        if (totalAreaOutput) totalAreaOutput.textContent = `${formatNumber(roundedArea)} m²`;

        rateOutputs.forEach((rateEl, index) => {
            const rate = parseNumber(rateEl.textContent || "0");
            const quantity = Math.ceil(roundedArea * rate * lossFactor);

            if (!valueOutputs[index]) return;

            valueOutputs[index].textContent = rate > 0 ? `${formatNumber(quantity)} viên` : "00 viên";
            if (index === 0) qtyToSync = quantity;
        });

        syncPurchaseQuantity(qtyToSync);
    };

    const wireBlockInputs = (block) => {
        block.querySelectorAll('input[type="number"]').forEach((input) => {
            input.addEventListener("input", scheduleUpdate);
        });
    };

    const attachRemoveAreaListener = (block) => {
        block.querySelector("[data-remove-area]")?.addEventListener("click", (event) => {
            event.preventDefault();
            block.remove();
            renumberAreas();
            updateResults();
        });
    };

    const addArea = () => {
        if (!masterTemplate || !addAreaBtn?.parentElement) return;

        const newBlock = masterTemplate.cloneNode(true);
        newBlock.querySelectorAll('input[type="number"]').forEach((input) => {
            input.value = "";
        });

        addAreaBtn.parentElement.before(newBlock);
        attachRemoveAreaListener(newBlock);
        wireBlockInputs(newBlock);
        renumberAreas();
    };

    let calcTimeout;
    const scheduleUpdate = () => {
        clearTimeout(calcTimeout);
        calcTimeout = setTimeout(updateResults, 300);
    };

    areaBlocks().forEach((block) => {
        attachRemoveAreaListener(block);
        wireBlockInputs(block);
    });

    addAreaBtn?.addEventListener("click", (event) => {
        event.preventDefault();
        addArea();
    });
    calculateBtn?.addEventListener("click", (event) => {
        event.preventDefault();
        updateResults();
    });
    extraLossCheckbox?.addEventListener("change", updateResults);
    lossRadios.forEach((radio) => radio.addEventListener("change", updateResults));
    renumberAreas();
};

const showNotice = (notice, timerRef) => {
    if (!notice) {
        return timerRef;
    }

    clearTimeout(timerRef);
    notice.classList.remove("hidden");

    return setTimeout(() => notice.classList.add("hidden"), 3000);
};

const initHaiVanMieuCalculator = (section) => {
    if (section.dataset.haiVmCalculatorInitialized === "true") {
        return;
    }

    section.dataset.haiVmCalculatorInitialized = "true";

    const roofStyleSelect = section.querySelector("[data-roof-style]");
    const addAreaBtn = section.querySelector("[data-add-area]");
    const calculateBtn = section.querySelector("[data-calculate-btn]");
    const totalAreaOutput = section.querySelector("[data-total-area-output]");
    const rateOutputs = Array.from(section.querySelectorAll("[data-rate-output]"));
    const valueOutputs = Array.from(section.querySelectorAll("[data-value-output]"));
    const extraLossCheckbox = section.querySelector("#extra-loss-hai-vm");
    const lossRadios = Array.from(section.querySelectorAll('input[name="loss-rate-hai-vm"]'));
    const getAreaBlocks = () => Array.from(section.querySelectorAll("[data-area-block]"));
    const initialBlocks = getAreaBlocks();
    const masterTemplate = (initialBlocks.find((block) => block.querySelector("[data-remove-area]")) || initialBlocks[initialBlocks.length - 1])?.cloneNode(true);
    let noticeTimer;

    const getLossFactor = () => {
        if (!extraLossCheckbox || !extraLossCheckbox.checked) return 1;

        return Number.parseFloat(lossRadios.find((radio) => radio.checked)?.value) || 1;
    };

    const getRoofFactor = () => {
        if (!roofStyleSelect || roofStyleSelect.value === "") return 1;

        return Number.parseFloat(roofStyleSelect.options[roofStyleSelect.selectedIndex].dataset.factor || "1") || 1;
    };

    const renumberAreas = () => {
        getAreaBlocks().forEach((block, index) => {
            block.querySelectorAll("[data-area-title]").forEach((title) => {
                title.textContent = `DIỆN TÍCH ${index + 1}`;
            });
        });
    };

    const updateResults = () => {
        const totalArea = getAreaBlocks().reduce((sum, block) => sum + calculateShapeArea(block), 0);
        const roundedArea = Math.ceil(totalArea);
        const combinedFactor = getLossFactor() * getRoofFactor();
        let qtyToSync = 0;

        if (totalAreaOutput) totalAreaOutput.textContent = `${formatNumber(roundedArea)} m²`;

        rateOutputs.forEach((rateEl, index) => {
            const rateMatch = (rateEl.textContent || "").match(/([\d.,]+)/);
            const rate = rateMatch ? parseNumber(rateMatch[1]) : 0;
            const quantity = Math.ceil(roundedArea * rate * combinedFactor);

            if (!valueOutputs[index]) return;

            valueOutputs[index].textContent = rate > 0 && totalArea > 0 ? `${formatNumber(quantity)} viên` : "0 viên";
            if (index === 0) qtyToSync = quantity;
        });

        syncPurchaseQuantity(qtyToSync);
        noticeTimer = showNotice(section.querySelector("#sync-notice-hai-vm"), noticeTimer);
    };

    const wireBlockInputs = (block) => {
        block.querySelectorAll('input[type="number"]').forEach((input) => input.addEventListener("input", scheduleUpdate));
        block.querySelector("[data-shape-select]")?.addEventListener("change", () => {
            applyShapeInputs(block);
            scheduleUpdate();
        });
    };

    const setupBlock = (block) => {
        applyShapeInputs(block);
        wireBlockInputs(block);
        block.querySelectorAll("[data-remove-area]").forEach((button) => {
            button.addEventListener("click", (event) => {
                event.preventDefault();
                block.remove();
                renumberAreas();
                updateResults();
            });
        });
    };

    const addArea = () => {
        if (!masterTemplate || !addAreaBtn?.parentElement) return;

        const newBlock = masterTemplate.cloneNode(true);
        newBlock.querySelectorAll('input[type="number"]').forEach((input) => {
            input.value = "";
        });
        addAreaBtn.parentElement.before(newBlock);
        setupBlock(newBlock);
        renumberAreas();
    };

    let calcTimeout;
    const scheduleUpdate = () => {
        clearTimeout(calcTimeout);
        calcTimeout = setTimeout(updateResults, 300);
    };

    const checkButtonState = () => {
        if (!calculateBtn) return;

        const disabled = roofStyleSelect && roofStyleSelect.value === "";
        calculateBtn.disabled = disabled;
        calculateBtn.classList.toggle("opacity-50", disabled);
        calculateBtn.classList.toggle("cursor-not-allowed", disabled);
    };

    getAreaBlocks().forEach(setupBlock);
    addAreaBtn?.addEventListener("click", (event) => {
        event.preventDefault();
        addArea();
    });
    calculateBtn?.addEventListener("click", (event) => {
        event.preventDefault();
        updateResults();
    });
    extraLossCheckbox?.addEventListener("change", updateResults);
    lossRadios.forEach((radio) => radio.addEventListener("change", updateResults));
    roofStyleSelect?.addEventListener("change", () => {
        checkButtonState();
        scheduleUpdate();
    });

    checkButtonState();
    renumberAreas();
};

const initWeightCalculator = (section) => {
    if (section.dataset.weightCalculatorInitialized === "true") {
        return;
    }

    section.dataset.weightCalculatorInitialized = "true";

    const dinhMucData = JSON.parse(section.querySelector("[data-dinh-muc-json]")?.textContent || "[]");
    const roofStyleSelect = section.querySelector("#roof-style");
    const tileTypeSelect = section.querySelector("#tile-type");
    const tileTypeOptions = Array.from(tileTypeSelect?.querySelectorAll("option[data-roof]") || []);
    const areasContainer = section.querySelector(".space-y-4.col-span-1.lg\\:col-span-3") || section.querySelector(".space-y-4");
    const addAreaBtn = Array.from(section.querySelectorAll("button")).find((button) => (button.textContent || "").includes("+"));
    const calculateBtn = section.querySelector("#calculate-btn");
    const extraLossCheckbox = section.querySelector("#extra-loss");
    const lossRadios = Array.from(section.querySelectorAll('input[name="loss-rate"]'));
    const getAreaBlocks = () => Array.from(areasContainer?.children || []).filter((element) => element.classList.contains("flex") && element.querySelector("select") && element.querySelector('input[type="number"]'));
    const masterTemplate = getAreaBlocks()[0]?.cloneNode(true);
    let noticeTimer;

    const getLossFactor = () => {
        if (!extraLossCheckbox || !extraLossCheckbox.checked) return 1;

        return Number.parseFloat(lossRadios.find((radio) => radio.checked)?.value) || 1;
    };

    const renumberAreas = () => {
        getAreaBlocks().forEach((block, index) => {
            Array.from(block.querySelectorAll("span"))
                .filter((span) => (span.textContent || "").toUpperCase().includes("DI"))
                .forEach((span) => {
                    span.textContent = `DIỆN TÍCH ${index + 1}`;
                });
        });
    };

    const updateResults = () => {
        let totalArea = 0;
        let totalLength = 0;

        getAreaBlocks().forEach((block) => {
            totalArea += calculateShapeArea(block);
            totalLength += calculateShapeLength(block);
        });

        const roundedArea = Math.ceil(totalArea);
        const factor = getLossFactor();
        const selectedTileOption = tileTypeSelect?.options[tileTypeSelect.selectedIndex];
        const roof = roofStyleSelect?.value || "";
        const tile = selectedTileOption?.value || "";
        let amCoeff = parseNumber(selectedTileOption?.dataset.am || "0");
        let duongCoeff = parseNumber(selectedTileOption?.dataset.duong || "0");
        let diemCoeff = parseNumber(selectedTileOption?.dataset.diem || "0");

        if (!amCoeff && dinhMucData.length) {
            const row = dinhMucData.find((item) => item.roof_type === roof && item.tile_type === tile);
            amCoeff = parseNumber(row?.ngoi_am || "0");
            duongCoeff = parseNumber(row?.ngoi_duong || "0");
            diemCoeff = parseNumber(row?.diem || "0");
        }

        const ngoiAm = Math.ceil(roundedArea * factor * amCoeff);
        const ngoiDuong = Math.ceil(roundedArea * factor * duongCoeff);
        const diem = Math.ceil(totalLength * factor * diemCoeff);
        const resAm = section.querySelector("#res-am");
        const resDuong = section.querySelector("#res-duong");
        const resDiem = section.querySelector("#res-diem");

        if (resAm) {
            const spans = resAm.querySelectorAll("span");
            if (spans[1]) spans[1].textContent = `${formatNumber(roundedArea)} m²`;
            if (spans[2]) spans[2].textContent = amCoeff > 0 ? `${amCoeff} viên/m²` : "-- viên/m²";
            if (spans[3]) spans[3].textContent = `${formatNumber(ngoiAm)} viên`;
        }

        if (resDuong) {
            const spans = resDuong.querySelectorAll("span");
            if (spans[1]) spans[1].textContent = `${formatNumber(roundedArea)} m²`;
            if (spans[2]) spans[2].textContent = duongCoeff > 0 ? `${duongCoeff} viên/m²` : "-- viên/m²";
            if (spans[3]) spans[3].textContent = `${formatNumber(ngoiDuong)} viên`;
        }

        if (resDiem) {
            const spans = resDiem.querySelectorAll("span");
            if (spans[1]) spans[1].textContent = `${formatNumber(totalLength)} md`;
            if (spans[2]) spans[2].textContent = diemCoeff > 0 ? `${diemCoeff} cặp/md` : "-- cặp/md";
            if (spans[3]) spans[3].textContent = `${formatNumber(diem)} cặp`;
        }

        syncPurchaseQuantity(ngoiAm);
        noticeTimer = showNotice(section.querySelector("#sync-notice"), noticeTimer);
    };

    const wireBlockInputs = (block) => {
        block.querySelectorAll('input[type="number"]').forEach((input) => input.addEventListener("input", scheduleUpdate));
        block.querySelector("select")?.addEventListener("change", () => {
            applyShapeInputs(block);
            scheduleUpdate();
        });
    };

    const setupBlock = (block) => {
        applyShapeInputs(block);
        wireBlockInputs(block);
        block.querySelectorAll("button.underline").forEach((button) => {
            if (!(button.textContent || "").includes("Lo")) return;

            button.style.display = "block";
            button.addEventListener("click", (event) => {
                event.preventDefault();
                block.remove();
                renumberAreas();
                updateResults();
            });
        });
    };

    const addArea = () => {
        if (!masterTemplate || !addAreaBtn?.parentElement) return;

        const newBlock = masterTemplate.cloneNode(true);
        newBlock.querySelectorAll("input").forEach((input) => {
            input.value = "";
        });
        addAreaBtn.parentElement.before(newBlock);
        setupBlock(newBlock);
        renumberAreas();
    };

    let calcTimeout;
    const scheduleUpdate = () => {
        clearTimeout(calcTimeout);
        calcTimeout = setTimeout(updateResults, 300);
    };

    const syncTileOptions = () => {
        const roof = roofStyleSelect?.value || "";
        tileTypeOptions.forEach((option) => {
            const visible = !roof || option.dataset.roof === roof;
            option.hidden = !visible;
            option.disabled = !visible;
        });

        if (tileTypeSelect?.options[tileTypeSelect.selectedIndex]?.disabled) {
            tileTypeSelect.value = "";
        }
    };

    const checkButtonState = () => {
        if (!calculateBtn) return;

        const disabled = !roofStyleSelect?.value || !tileTypeSelect?.value;
        calculateBtn.disabled = disabled;
        calculateBtn.classList.toggle("opacity-50", disabled);
        calculateBtn.classList.toggle("cursor-not-allowed", disabled);
    };

    getAreaBlocks().forEach(setupBlock);
    addAreaBtn?.addEventListener("click", (event) => {
        event.preventDefault();
        addArea();
    });
    calculateBtn?.addEventListener("click", (event) => {
        event.preventDefault();
        updateResults();
    });
    extraLossCheckbox?.addEventListener("change", updateResults);
    lossRadios.forEach((radio) => radio.addEventListener("change", updateResults));
    roofStyleSelect?.addEventListener("change", () => {
        syncTileOptions();
        checkButtonState();
        scheduleUpdate();
    });
    tileTypeSelect?.addEventListener("change", () => {
        checkButtonState();
        scheduleUpdate();
    });

    syncTileOptions();
    checkButtonState();
    renumberAreas();
};

const initProductCalculators = () => {
    document.querySelectorAll("[data-quantity-calculator]").forEach(initQuantityCalculator);
    document.querySelectorAll("[data-weight-calculator]").forEach(initWeightCalculator);
    document.querySelectorAll("[data-hai-vm-calculator]").forEach(initHaiVanMieuCalculator);
};

export { initProductCalculators };
