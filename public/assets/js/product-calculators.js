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

const DIEM_NOTE = '<span class="block text-[7px] md:text-[12px] font-normal italic normal-case text-[rgba(199,110,0,0.70)] md:text-secondary mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">Chiều tính diềm mái</span>';
const LABEL_SPACER = '<span class="block text-[7px] md:text-[12px] opacity-0 mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">_</span>';

const getShapeSelect = (block) => block.querySelector("[data-shape-select]") || block.querySelector("select");

const getShapeType = (select) => {
    const value = (select?.value || "").toLowerCase();

    if (value === "rectangle" || value === "trapezoid" || value === "triangle") {
        return value;
    }

    const text = (select?.options[select.selectedIndex]?.text || "").toUpperCase();

    if (text.includes("THANG")) {
        return "trapezoid";
    }

    if (text.includes("TAM GI")) {
        return "triangle";
    }

    return "rectangle";
};

const getDimension = (block, role) => parseNumber(block.querySelector(`[data-dimension="${role}"]`)?.value);

const getInputWrapper = (block, role) => block.querySelector(`[data-input-wrapper][data-input-role="${role}"]`);

const setLabelHtml = (wrapper, html) => {
    const label = wrapper?.querySelector("label");

    if (label) {
        label.innerHTML = html;
    }
};

const showWrapper = (wrapper) => wrapper?.style.removeProperty("display");
const hideWrapper = (wrapper) => {
    if (wrapper) {
        wrapper.style.display = "none";
    }
};

const calculateShapeArea = (block) => {
    const shape = getShapeType(getShapeSelect(block));

    if (shape === "trapezoid") {
        return ((getDimension(block, "primary") + getDimension(block, "secondary")) * getDimension(block, "height")) / 2;
    }

    if (shape === "triangle") {
        return (getDimension(block, "primary") * getDimension(block, "height")) / 2;
    }

    return getDimension(block, "primary") * getDimension(block, "secondary");
};

const calculateDiemLength = (block) => {
    const shape = getShapeType(getShapeSelect(block));

    if (shape === "trapezoid") {
        return getDimension(block, "secondary");
    }

    return getDimension(block, "primary");
};

const applyShapeInputs = (block) => {
    const shape = getShapeType(getShapeSelect(block));
    const primary = getInputWrapper(block, "primary");
    const secondary = getInputWrapper(block, "secondary");
    const height = getInputWrapper(block, "height");

    if (!primary || !secondary || !height) {
        return;
    }

    if (shape === "trapezoid") {
        setLabelHtml(primary, `ĐÁY BÉ ${LABEL_SPACER}`);
        setLabelHtml(secondary, `ĐÁY LỚN ${DIEM_NOTE}`);
        setLabelHtml(height, `CHIỀU CAO ${LABEL_SPACER}`);
        showWrapper(primary);
        showWrapper(secondary);
        showWrapper(height);
        return;
    }

    if (shape === "triangle") {
        setLabelHtml(primary, `CHIỀU DÀI ${DIEM_NOTE}`);
        setLabelHtml(height, `CHIỀU CAO ${LABEL_SPACER}`);
        showWrapper(primary);
        hideWrapper(secondary);
        showWrapper(height);
        return;
    }

    setLabelHtml(primary, `CHIỀU DÀI ${DIEM_NOTE}`);
    setLabelHtml(secondary, `CHIỀU RỘNG ${LABEL_SPACER}`);
    showWrapper(primary);
    showWrapper(secondary);
    hideWrapper(height);
};

const resetShapeBlock = (block) => {
    const select = getShapeSelect(block);

    if (select) {
        select.value = "rectangle";
    }

    block.querySelectorAll("[data-dimension]").forEach((input) => {
        input.value = "";
    });

    applyShapeInputs(block);
};

const initQuantityCalculator = (section) => {
    if (section.dataset.quantityCalculatorInitialized === "true") {
        return;
    }

    section.dataset.quantityCalculatorInitialized = "true";

    const defaultRate = Number.parseFloat(section.dataset.defaultRate) || 25;
    const fieldsContainer = section.querySelector("#calculator-fields-container");
    const areaBlocks = () => Array.from(fieldsContainer?.querySelectorAll("[data-area-block]") || []);
    const masterTemplate = areaBlocks()[0]?.cloneNode(true);
    const addAreaBtn = section.querySelector("#btn-add-area");
    const calculateBtn = section.querySelector("#btn-calculate-quantity");
    const outputTotalArea = section.querySelector("#output-total-area");
    const outputTotalBricks = section.querySelector("#output-total-bricks");
    const extraLossCheckbox = section.querySelector("#extra-loss-quantity");
    const lossRadios = Array.from(section.querySelectorAll('input[name="loss-rate"]'));

    const getLossFactor = () => {
        if (!extraLossCheckbox?.checked) {
            return 1;
        }

        return Number.parseFloat(lossRadios.find((radio) => radio.checked)?.value) || 1;
    };

    const getBlockArea = (block) => {
        const length = parseNumber(block.querySelector("[data-input-length]")?.value);
        const width = parseNumber(block.querySelector("[data-input-width]")?.value);

        return length * width;
    };

    const renumberAreas = () => {
        areaBlocks().forEach((block, index) => {
            const label = block.querySelector(".area-title-label");
            if (label) {
                label.textContent = `DIỆN TÍCH ${index + 1}`;
            }

            const removeBtn = block.querySelector("[data-remove-area]");
            if (removeBtn) {
                removeBtn.classList.toggle("hidden", index === 0);
            }
        });
    };

    const updateResults = () => {
        let totalArea = 0;

        areaBlocks().forEach((block) => {
            totalArea += getBlockArea(block);
        });

        if (totalArea <= 0) {
            if (outputTotalArea) outputTotalArea.textContent = "0 m²";
            if (outputTotalBricks) outputTotalBricks.textContent = "0 viên";
            return;
        }

        const calculatedAreaWithLoss = totalArea * getLossFactor();
        const roundedArea = Math.ceil(calculatedAreaWithLoss);
        const totalBricksNeeded = Math.ceil(roundedArea * defaultRate);

        if (outputTotalArea) outputTotalArea.textContent = `${formatNumber(roundedArea)} m²`;
        if (outputTotalBricks) outputTotalBricks.textContent = `${formatNumber(totalBricksNeeded)} viên`;

        syncPurchaseQuantity(totalBricksNeeded);
    };

    const attachRemoveAreaListener = (block) => {
        block.querySelector("[data-remove-area]")?.addEventListener("click", (event) => {
            event.preventDefault();
            block.remove();
            renumberAreas();
        });
    };

    const addArea = () => {
        if (!masterTemplate || !fieldsContainer) return;

        const newBlock = masterTemplate.cloneNode(true);
        newBlock.querySelectorAll("input").forEach((input) => {
            input.value = "";
        });

        fieldsContainer.appendChild(newBlock);
        attachRemoveAreaListener(newBlock);
        renumberAreas();
    };

    areaBlocks().forEach((block) => {
        attachRemoveAreaListener(block);
    });

    addAreaBtn?.addEventListener("click", (event) => {
        event.preventDefault();
        addArea();
    });
    calculateBtn?.addEventListener("click", (event) => {
        event.preventDefault();
        updateResults();
    });
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
    const masterTemplate = getAreaBlocks()[0]?.cloneNode(true);
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

            block.querySelectorAll("[data-remove-area]").forEach((button) => {
                button.classList.toggle("hidden", index === 0);
            });
        });
    };

    const updateResults = () => {
        const totalArea = getAreaBlocks().reduce((sum, block) => sum + calculateShapeArea(block), 0);
        const roundedArea = Math.ceil(totalArea * getLossFactor());
        const roofFactor = getRoofFactor();
        let qtyToSync = 0;

        if (totalAreaOutput) totalAreaOutput.textContent = `${formatNumber(roundedArea)} m²`;

        rateOutputs.forEach((rateEl, index) => {
            const rateMatch = (rateEl.textContent || "").match(/([\d.,]+)/);
            const rate = rateMatch ? parseNumber(rateMatch[1]) : 0;
            const quantity = Math.ceil(roundedArea * rate * roofFactor);

            if (!valueOutputs[index]) return;

            valueOutputs[index].textContent = rate > 0 && totalArea > 0 ? `${formatNumber(quantity)} viên` : "0 viên";
            if (index === 0) qtyToSync = quantity;
        });

        syncPurchaseQuantity(qtyToSync);
        noticeTimer = showNotice(section.querySelector("#sync-notice-hai-vm"), noticeTimer);
    };

    const wireBlockInputs = (block) => {
        block.querySelectorAll('input[type="number"]').forEach((input) => input.addEventListener("input", scheduleUpdate));
        getShapeSelect(block)?.addEventListener("change", () => {
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
        resetShapeBlock(newBlock);
        addAreaBtn.parentElement.before(newBlock);
        setupBlock(newBlock);
        renumberAreas();
    };

    let calcTimeout;
    const scheduleUpdate = () => {
        clearTimeout(calcTimeout);
        calcTimeout = setTimeout(updateResults, 300);
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
        scheduleUpdate();
    });

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
    const areasContainer = section.querySelector("[data-weight-calculator-areas]");
    const addAreaBtn = section.querySelector("[data-add-area]");
    const calculateBtn = section.querySelector("#calculate-btn");
    const extraLossCheckbox = section.querySelector("#extra-loss");
    const lossRadios = Array.from(section.querySelectorAll('input[name="loss-rate"]'));
    const getAreaBlocks = () => Array.from(areasContainer?.querySelectorAll("[data-area-block]") || []);
    const masterTemplate = getAreaBlocks()[0]?.cloneNode(true);
    let noticeTimer;

    const getLossFactor = () => {
        if (!extraLossCheckbox || !extraLossCheckbox.checked) return 1;

        return Number.parseFloat(lossRadios.find((radio) => radio.checked)?.value) || 1;
    };

    const renumberAreas = () => {
        getAreaBlocks().forEach((block, index) => {
            block.querySelectorAll("[data-area-title]").forEach((title) => {
                title.textContent = `DIỆN TÍCH ${index + 1}`;
            });

            block.querySelectorAll("[data-remove-area]").forEach((button) => {
                button.classList.toggle("hidden", index === 0);
            });
        });
    };

    const updateResults = () => {
        let totalArea = 0;
        let totalLength = 0;

        getAreaBlocks().forEach((block) => {
            totalArea += calculateShapeArea(block);
            totalLength += calculateDiemLength(block);
        });

        const lossFactor = getLossFactor();
        const roundedArea = Math.ceil(totalArea * lossFactor);
        const roundedLength = Math.ceil(totalLength * lossFactor);
        const selectedTileOption = getSelectedTileOption();
        const roof = roofStyleSelect?.value || selectedTileOption?.dataset.roof || "";
        const tile = selectedTileOption?.dataset.tile || selectedTileOption?.textContent?.trim() || "";
        let amCoeff = parseNumber(selectedTileOption?.dataset.am || "0");
        let duongCoeff = parseNumber(selectedTileOption?.dataset.duong || "0");
        let diemCoeff = parseNumber(selectedTileOption?.dataset.diem || "0");

        if (!amCoeff && dinhMucData.length) {
            const row = dinhMucData.find((item) => item.roof_type === roof && item.tile_type === tile);
            amCoeff = parseNumber(row?.ngoi_am || "0");
            duongCoeff = parseNumber(row?.ngoi_duong || "0");
            diemCoeff = parseNumber(row?.diem || "0");
        }

        const ngoiAm = Math.ceil(roundedArea * amCoeff);
        const ngoiDuong = Math.ceil(roundedArea * duongCoeff);
        const diem = Math.ceil(roundedLength * diemCoeff);
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
            if (spans[1]) spans[1].textContent = `${formatNumber(roundedLength)} md`;
            if (spans[2]) spans[2].textContent = diemCoeff > 0 ? `${diemCoeff} cặp/md` : "-- cặp/md";
            if (spans[3]) spans[3].textContent = `${formatNumber(diem)} cặp`;
        }

        syncPurchaseQuantity(ngoiAm);
        noticeTimer = showNotice(section.querySelector("#sync-notice"), noticeTimer);
    };

    const wireBlockInputs = (block) => {
        block.querySelectorAll('input[type="number"]').forEach((input) => input.addEventListener("input", scheduleUpdate));
        getShapeSelect(block)?.addEventListener("change", () => {
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
        if (!masterTemplate || !areasContainer) return;

        const newBlock = masterTemplate.cloneNode(true);
        resetShapeBlock(newBlock);
        areasContainer.appendChild(newBlock);
        setupBlock(newBlock);
        renumberAreas();
    };

    let calcTimeout;
    const scheduleUpdate = () => {
        clearTimeout(calcTimeout);
        calcTimeout = setTimeout(updateResults, 300);
    };

    const getSelectedTileOption = () => {
        const selected = tileTypeSelect?.options[tileTypeSelect.selectedIndex];

        if (!selected || selected.disabled || !selected.dataset.roof) {
            return null;
        }

        return selected;
    };

    const syncTileOptions = () => {
        const roof = roofStyleSelect?.value || "";
        tileTypeOptions.forEach((option) => {
            const visible = !roof || option.dataset.roof === roof;
            option.hidden = !visible;
            option.disabled = !visible;
        });

        const selected = tileTypeSelect?.options[tileTypeSelect.selectedIndex];
        if (!selected || selected.disabled || selected.value === "" || (roof && selected.dataset.roof !== roof)) {
            tileTypeSelect.selectedIndex = 0;
        }
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
        scheduleUpdate();
    });
    tileTypeSelect?.addEventListener("change", scheduleUpdate);

    syncTileOptions();
    renumberAreas();
};

const initProductCalculators = () => {
    document.querySelectorAll("[data-quantity-calculator]").forEach(initQuantityCalculator);
    document.querySelectorAll("[data-weight-calculator]").forEach(initWeightCalculator);
    document.querySelectorAll("[data-hai-vm-calculator]").forEach(initHaiVanMieuCalculator);
};

export {
    applyShapeInputs,
    calculateDiemLength,
    calculateShapeArea,
    getShapeType,
    initProductCalculators,
    resetShapeBlock,
};
