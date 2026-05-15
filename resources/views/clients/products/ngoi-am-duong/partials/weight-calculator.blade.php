@php
    $dinhMuc = $dinhMuc ?? [];
    $dinhMucItems = collect($dinhMuc)->filter()->values();
    $roofTypes = $dinhMucItems->pluck('roof_type')->filter()->unique()->values();
@endphp

<!-- Ngoi Am Duong Calculator -->
<section class="w-full pb-12 pt-8 lg:pt-16 lg:pb-16 bg-background-secondary" data-aos="fade-up" data-weight-calculator>
    <div class="w-[85%] max-w-[1320px] mx-auto">
        <h2 class="text-[20px] font-semibold text-center text-secondary mb-8 uppercase block lg:hidden">
            CÁCH TÍNH KHỐI LƯỢNG
        </h2>
        <div class="flex flex-col-reverse lg:grid lg:grid-cols-5 gap-8 lg:gap-24 items-start">
            <div class="w-full space-y-4 col-span-1 lg:col-span-3">
                <h2
                    class="text-[20px] md:text-3xl font-semibold text-center text-secondary mb-5 uppercase hidden lg:block">
                    CÁCH TÍNH KHỐI LƯỢNG
                </h2>
                <hr class="border-t border-black/10 w-full mb-6 lg:mb-0 block lg:hidden" />
                <hr class="border-t border-black/10 mt-4 w-full hidden lg:block" />

                <!-- Phân loại ngói và kiểu mái -->
                <div class="grid grid-cols-2 gap-5 lg:gap-6">
                    <div class="relative">
                        <select id="roof-style"
                            class="w-full h-[26px] lg:h-[44px] px-2 lg:px-4 pr-6 lg:pr-10 border border-black/10 rounded-sm bg-transparent text-[10px] lg:text-[13px] uppercase appearance-none outline-none focus:border-secondary transition-colors">
                            <option value="" disabled selected>KIỂU MÁI</option>
                            @forelse ($roofTypes as $roofType)
                                <option value="{{ $roofType }}">{{ $roofType }}</option>
                            @empty
                                <option value="Mái gỗ">Mái gỗ</option>
                                <option value="Mái bê tông">Mái bê tông</option>
                            @endforelse
                        </select>
                        <div class="absolute right-2 lg:right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-3 h-3 lg:w-4 lg:h-4 text-secondary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative">
                        <select id="tile-type"
                            class="w-full h-[26px] lg:h-[44px] px-2 lg:px-4 pr-6 lg:pr-10 border border-black/10 rounded-sm bg-transparent text-[10px] lg:text-[13px] uppercase appearance-none outline-none focus:border-secondary transition-colors">
                            <option value="" disabled selected>LOẠI NGÓI</option>
                            @forelse ($dinhMucItems as $dm)
                                <option value="{{ $dm->tile_type }}"
                                    data-roof="{{ $dm->roof_type }}"
                                    data-am="{{ $dm->ngoi_am }}"
                                    data-duong="{{ $dm->ngoi_duong }}"
                                    data-diem="{{ $dm->diem }}">
                                    {{ $dm->tile_type }}
                                </option>
                            @empty
                                <option value="15 cặp/m²" data-am="0" data-duong="0" data-diem="0">15 cặp/m²</option>
                                <option value="27 cặp/m²" data-am="0" data-duong="0" data-diem="0">27 cặp/m²</option>
                                <option value="43 cặp/m²" data-am="0" data-duong="0" data-diem="0">43 cặp/m²</option>
                                <option value="80 cặp/m²" data-am="0" data-duong="0" data-diem="0">80 cặp/m²</option>
                            @endforelse
                        </select>
                        <div class="absolute right-2 lg:right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-3 h-3 lg:w-4 lg:h-4 text-secondary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Template tính diện tích -->
                <div class="flex flex-col lg:flex-row gap-2 lg:gap-4 items-start lg:items-end mb-6 lg:mb-0">
                    <div class="hidden lg:flex w-full lg:w-[150px] shrink-0 pb-1 flex-col">
                        <span class="font-semibold text-primary uppercase text-base">DIỆN TÍCH 1</span>
                        <button class="text-[14px] text-secondary underline text-left font-bold w-fit ml-4 mt-1"
                            style="display: none;">Loại bỏ</button>
                    </div>
                    <div class="flex-1 w-full flex flex-row lg:grid lg:grid-cols-12 gap-2 lg:gap-7 items-end">
                        <div
                            class="w-[111px] md:w-[111px] lg:w-auto shrink-0 col-span-12 lg:col-span-3 flex flex-col justify-end">
                            <div class="flex items-center gap-2 lg:hidden mb-2 pl-2">
                                <span class="font-semibold text-primary uppercase text-[11px] leading-tight">DIỆN TÍCH
                                    1</span>
                                <button class="text-[8px] text-secondary/70 underline italic font-bold"
                                    style="display: none;">Loại bỏ</button>
                            </div>
                            <div class="relative">
                                <select
                                    class="w-full h-[30px] lg:h-[45px] px-2 lg:px-3 pr-5 lg:pr-6 border border-black/10 rounded-sm bg-transparent text-[9px] lg:text-[11px] uppercase appearance-none outline-none focus:border-secondary transition-colors">
                                    <option selected>HÌNH CHỮ NHẬT</option>
                                    <option>HÌNH THANG</option>
                                    <option>HÌNH TAM GIÁC</option>
                                </select>
                                <div
                                    class="absolute right-1 lg:right-3 top-1/2 -translate-y-1/2 pointer-events-none text-secondary">
                                    <svg class="w-2.5 h-2.5 lg:w-3 lg:h-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Các Input: Dài/Đáy Lớn, Rộng/Đáy Bé, Chiều Cao -->
                        <div class="flex-1 lg:col-span-3 flex flex-col gap-1 lg:gap-2">
                            <label
                                class="text-[9px] lg:text-[16px] uppercase font-semibold text-primary text-center tracking-tight leading-none min-h-[16px] lg:min-h-auto">
                                CHIỀU DÀI
                                <span
                                    class="block text-[7px] lg:text-[12px] font-normal italic normal-case text-secondary/70 mt-[2px] lg:mt-1 tracking-normal">Chiều
                                    tính diềm mái</span>
                            </label>
                            <div class="relative">
                                <input type="number" step="0.01" placeholder="0"
                                    class="w-full h-[30px] lg:h-[45px] p-2 lg:p-3 border border-black/10 rounded-sm bg-transparent text-[12px] lg:text-base text-left pr-4 lg:pr-6 focus:border-secondary outline-none transition-colors" />
                                <span
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-primary/40">m</span>
                            </div>
                        </div>
                        <div class="flex-1 lg:col-span-3 flex flex-col gap-1 lg:gap-2">
                            <label
                                class="text-[9px] lg:text-[16px] uppercase font-semibold text-primary text-center tracking-tight leading-none min-h-[16px] lg:min-h-auto">
                                CHIỀU RỘNG
                                <span
                                    class="block text-[7px] lg:text-[12px] opacity-0 mt-[2px] lg:mt-1 tracking-normal">_</span>
                            </label>
                            <div class="relative">
                                <input type="number" step="0.01" placeholder="0"
                                    class="w-full h-[30px] lg:h-[45px] p-2 lg:p-3 border border-black/10 rounded-sm bg-transparent text-[12px] lg:text-base text-left pr-4 lg:pr-6 focus:border-secondary outline-none transition-colors" />
                                <span
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-primary/40">m</span>
                            </div>
                        </div>
                        <div class="flex-1 lg:col-span-3 flex flex-col gap-1 lg:gap-2" style="display: none">
                            <label
                                class="text-[9px] lg:text-[16px] uppercase font-semibold text-primary text-center tracking-tight leading-none min-h-[16px] lg:min-h-auto">
                                CHIỀU CAO
                                <span
                                    class="block text-[7px] lg:text-[12px] opacity-0 mt-[2px] lg:mt-1 tracking-normal">_</span>
                            </label>
                            <div class="relative">
                                <input type="number" step="0.01" placeholder="0"
                                    class="w-full h-[30px] lg:h-[45px] p-2 lg:p-3 border border-black/10 rounded-sm bg-transparent text-[12px] lg:text-base text-left pr-4 lg:pr-6 focus:border-secondary outline-none transition-colors" />
                                <span
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-primary/40">m</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button class="text-secondary font-semibold text-sm underline">+ Thêm diện tích</button>
                </div>

                <!-- Tính hao hụt -->
                <div class="space-y-6 pt-4">
                    <input type="checkbox" id="extra-loss" class="peer sr-only" />
                    <label for="extra-loss"
                        class="w-fit flex items-center gap-2 cursor-pointer select-none peer-checked:[&_.loss-checkbox-box]:border-secondary peer-checked:[&_.loss-checkbox-box]:bg-secondary peer-checked:[&_.loss-checkbox-icon]:opacity-100 peer-focus-visible:[&_.loss-checkbox-box]:ring-2 peer-focus-visible:[&_.loss-checkbox-box]:ring-secondary/40 peer-focus-visible:[&_.loss-checkbox-box]:ring-offset-1">
                        <span
                            class="loss-checkbox-box relative flex h-5 w-5 items-center justify-center border border-black/20 rounded-none bg-transparent transition-all">
                            <svg class="loss-checkbox-icon h-3 w-3 text-white opacity-0 transition-opacity"
                                viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M4 10.5L8 14.5L16 6.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                        <span class="text-[15px] font-medium text-primary/80">Cộng thêm hao hụt</span>
                        <span class="group relative inline-block ml-1">
                            <span
                                class="w-5 h-5 rounded-full text-primary border-primary border flex items-center justify-center text-[12px] font-bold">?</span>
                            <span
                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-64 p-3 bg-primary text-white text-[12px] rounded-sm opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity shadow-lg z-10 leading-relaxed">
                                Tính toán dựa trên độ dốc mái thực tế và hao hụt vận chuyển/thi công.
                            </span>
                        </span>
                    </label>

                    <div class="pl-7 space-y-4 hidden peer-checked:block">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center justify-center">
                                <input type="radio" name="loss-rate" value="1.15"
                                    class="peer appearance-none w-5 h-5 border border-black/30 rounded-full checked:border-primary transition-all cursor-pointer"
                                    checked />
                                <div
                                    class="absolute w-2.5 h-2.5 rounded-full bg-primary scale-0 peer-checked:scale-100 transition-transform">
                                </div>
                            </div>
                            <span class="text-[14px] text-primary/70 font-medium">Thêm 15%</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center justify-center">
                                <input type="radio" name="loss-rate" value="1.20"
                                    class="peer appearance-none w-5 h-5 border border-black/30 rounded-full checked:border-primary transition-all cursor-pointer" />
                                <div
                                    class="absolute w-2.5 h-2.5 rounded-full bg-primary scale-0 peer-checked:scale-100 transition-transform">
                                </div>
                            </div>
                            <span class="text-[14px] text-primary/70 font-medium">Thêm 20%</span>
                        </label>
                    </div>
                </div>

                <button id="calculate-btn"
                    class="w-full mt-4 py-4 bg-secondary text-white text-sm uppercase tracking-widest rounded-sm shadow-md hover:bg-secondary/90 hover:scale-[1.01] transition-all duration-300">
                    TÍNH TOÁN KHỐI LƯỢNG
                </button>

                <div id="sync-notice" class="hidden text-center text-green-600 font-semibold text-sm mt-2">
                    ✓ Đã tự động cập nhật số lượng lên mục đặt hàng
                </div>

                <div class="pt-8">
                    <div class="grid grid-cols-12 gap-4 pb-4 items-center">
                        <div class="col-span-6 md:col-span-5">
                            <h4 class="font-semibold text-primary uppercase text-base tracking-wider">KẾT QUẢ</h4>
                        </div>
                        <div class="col-span-3 md:col-span-4 text-center">
                            <span class="text-base font-semibold text-primary">Định mức</span>
                        </div>
                        <div class="col-span-3 md:col-span-3 text-right">
                            <span class="text-base font-semibold text-primary">Số lượng</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <!-- Result Row: Ngói Âm -->
                        <div class="grid grid-cols-12 gap-4 items-center" id="res-am">
                            <div class="col-span-3 md:col-span-3"><span
                                    class="text-primary/80 font-medium text-base">Ngói âm</span></div>
                            <div class="col-span-3 md:col-span-2 text-right"><span
                                    class="text-primary/80 font-medium text-base">0 m²</span></div>
                            <div class="col-span-3 md:col-span-4 text-center"><span
                                    class="text-primary/80 font-medium text-base">-- viên/m²</span></div>
                            <div class="col-span-3 md:col-span-3 text-right"><span
                                    class="text-primary font-extrabold text-[16px]">0 viên</span></div>
                        </div>
                        <!-- Result Row: Ngói Dương -->
                        <div class="grid grid-cols-12 gap-4 items-center" id="res-duong">
                            <div class="col-span-3 md:col-span-3"><span
                                    class="text-primary/80 font-medium text-base">Ngói dương</span></div>
                            <div class="col-span-3 md:col-span-2 text-right"><span
                                    class="text-primary/80 font-medium text-base">0 m²</span></div>
                            <div class="col-span-3 md:col-span-4 text-center"><span
                                    class="text-primary/80 font-medium text-base">-- viên/m²</span></div>
                            <div class="col-span-3 md:col-span-3 text-right"><span
                                    class="text-primary font-extrabold text-[16px]">0 viên</span></div>
                        </div>
                        <!-- Result Row: Diềm -->
                        <div class="grid grid-cols-12 gap-4 items-center" id="res-diem">
                            <div class="col-span-3 md:col-span-3"><span
                                    class="text-primary/80 font-medium text-base">Diềm</span></div>
                            <div class="col-span-3 md:col-span-2 text-right"><span
                                    class="text-primary/80 font-medium text-base">0 md</span></div>
                            <div class="col-span-3 md:col-span-4 text-center"><span
                                    class="text-primary/80 font-medium text-base">-- cặp/md</span></div>
                            <div class="col-span-3 md:col-span-3 text-right"><span
                                    class="text-primary font-extrabold text-[16px]">0 cặp</span></div>
                        </div>
                    </div>
                    <hr class="border-black/10 mt-8 mb-8" />
                </div>
            </div>

            <!-- Cột hình minh họa -->
            <div class="flex flex-col gap-12 lg:gap-20 col-span-1 lg:col-span-2">
                <img src="{{ asset('assets/images/weight-01.svg') }}" alt="Cách đo mái chữ nhật"
                    class="w-full h-auto object-contain" />
                <img src="{{ asset('assets/images/weight-02.svg') }}" alt="Cách đo mái hình thang"
                    class="w-full h-auto object-contain" />
                <img src="{{ asset('assets/images/weight-03.svg') }}" alt="Cách đo diện tích mái"
                    class="w-full h-auto object-contain" />
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const calculatorSection = document.querySelector("[data-weight-calculator]");
            if (!calculatorSection) return;

            const dinhMucData = @json($dinhMuc);

            const roofStyleSelect = calculatorSection.querySelector("#roof-style");
            const tileTypeSelect = calculatorSection.querySelector("#tile-type");
            const tileTypeOptions = Array.from(tileTypeSelect?.querySelectorAll("option[data-roof]") || []);
            const areasContainer = calculatorSection.querySelector(".space-y-4.col-span-1.lg\\:col-span-3");

            // Master Block Template (Clone logic)
            const allInitialBlocks = Array.from(areasContainer.children).filter(
                (el) => el.classList.contains("flex") && el.querySelector("span")?.textContent.includes(
                    "DIỆN TÍCH"),
            );
            const masterTemplate = allInitialBlocks[0]?.cloneNode(true);
            // Bật nút xoá cho template clone
            masterTemplate.querySelectorAll("button.underline").forEach(btn => btn.style.display = 'block');

            const addAreaBtn = Array.from(areasContainer.querySelectorAll("button")).find((btn) => btn.textContent
                .includes("+ Thêm diện tích"));
            const calculateBtn = calculatorSection.querySelector("#calculate-btn");

            const extraLossCheckbox = calculatorSection.querySelector("#extra-loss");
            const lossRadios = Array.from(calculatorSection.querySelectorAll('input[name="loss-rate"]'));
            const vnFormatter = new Intl.NumberFormat("vi-VN");
            const format = (num) => vnFormatter.format(num);

            const getAreaBlocks = () =>
                Array.from(areasContainer.children).filter(
                    (el) => el.classList.contains("flex") && Array.from(el.querySelectorAll("span")).some((span) =>
                        /DIỆN\s*TÍCH/i.test(span.textContent || ""))
                );

            // Render lại UI Input cho từng loại Hình dạng
            const handleTypeChange = (block) => {
                const select = block.querySelector("select");
                const inputGrid = block.querySelector(".lg\\:grid-cols-12") || block.querySelector(
                    ".flex-1.w-full");
                if (!select || !inputGrid) return;

                const type = select.value || select.options[select.selectedIndex].text;
                const inputWrappers = Array.from(inputGrid.children);

                if (type.includes("CHỮ NHẬT")) {
                    if (inputWrappers[1]?.querySelector("label")) inputWrappers[1].querySelector("label")
                        .innerHTML =
                        'CHIỀU DÀI\n<span class="block text-[7px] lg:text-[12px] font-normal italic normal-case text-secondary/70 mt-[2px] lg:mt-1 tracking-normal">Chiều tính diềm mái</span>';
                    inputWrappers[1]?.classList.replace("col-span-4", "col-span-6");

                    if (inputWrappers[2]?.querySelector("label")) inputWrappers[2].querySelector("label")
                        .innerHTML =
                        'CHIỀU RỘNG\n<span class="block text-[7px] lg:text-[12px] opacity-0 mt-[2px] lg:mt-1 tracking-normal">_</span>';
                    inputWrappers[2]?.classList.replace("col-span-4", "col-span-6");

                    if (inputWrappers[3]) inputWrappers[3].style.display = "none";
                } else if (type.includes("THANG")) {
                    if (inputWrappers[1]?.querySelector("label")) inputWrappers[1].querySelector("label")
                        .innerHTML =
                        'ĐÁY LỚN\n<span class="block text-[7px] lg:text-[12px] font-normal italic normal-case text-secondary/70 mt-[2px] lg:mt-1 tracking-normal">Chiều tính diềm mái</span>';
                    inputWrappers[1]?.classList.replace("col-span-6", "col-span-4");

                    if (inputWrappers[2]?.querySelector("label")) inputWrappers[2].querySelector("label")
                        .innerHTML =
                        'ĐÁY BÉ\n<span class="block text-[7px] lg:text-[12px] opacity-0 mt-[2px] lg:mt-1 tracking-normal">_</span>';
                    inputWrappers[2]?.classList.replace("col-span-6", "col-span-4");

                    if (inputWrappers[3]) {
                        inputWrappers[3].style.display = "";
                        inputWrappers[3]?.classList.replace("col-span-6", "col-span-4");
                        if (inputWrappers[3].querySelector("label")) inputWrappers[3].querySelector("label")
                            .innerHTML =
                            'CHIỀU CAO\n<span class="block text-[7px] lg:text-[12px] opacity-0 mt-[2px] lg:mt-1 tracking-normal">_</span>';
                    }
                } else if (type.includes("TAM GIÁC")) {
                    if (inputWrappers[1]?.querySelector("label")) inputWrappers[1].querySelector("label")
                        .innerHTML =
                        'CHIỀU DÀI ĐÁY\n<span class="block text-[7px] lg:text-[12px] font-normal italic normal-case text-secondary/70 mt-[2px] lg:mt-1 tracking-normal">Chiều tính diềm mái</span>';
                    inputWrappers[1]?.classList.replace("col-span-4", "col-span-6");

                    if (inputWrappers[2]?.querySelector("label")) inputWrappers[2].querySelector("label")
                        .innerHTML =
                        'CHIỀU CAO\n<span class="block text-[7px] lg:text-[12px] opacity-0 mt-[2px] lg:mt-1 tracking-normal">_</span>';
                    inputWrappers[2]?.classList.replace("col-span-4", "col-span-6");

                    if (inputWrappers[3]) inputWrappers[3].style.display = "none";
                }

                [inputWrappers[1], inputWrappers[2], inputWrappers[3]].forEach((wrapper) => {
                    if (wrapper?.querySelector("label")) wrapper.querySelector("label").classList.add(
                        "flex", "flex-col", "items-center", "justify-end", "lg:h-[44px]");
                });
            };

            const renumberAreas = () => {
                getAreaBlocks().forEach((block, index) => {
                    Array.from(block.querySelectorAll("span"))
                        .filter(span => /DIỆN\s*TÍCH/i.test(span.textContent))
                        .forEach(span => {
                            span.textContent = `DIỆN TÍCH ${index + 1}`;
                        });
                });
            };

            const setupListeners = (block) => {
                const select = block.querySelector("select");
                if (select) {
                    select.addEventListener("change", () => handleTypeChange(block));
                    handleTypeChange(block);
                }
                block.querySelectorAll("button.underline").forEach((removeBtn) => {
                    if (removeBtn.textContent.includes("Loại bỏ")) {
                        removeBtn.addEventListener("click", (event) => {
                            event.preventDefault();
                            block.remove();
                            renumberAreas();
                        });
                    }
                });
            };

            // Khởi tạo các block mặc định
            getAreaBlocks().forEach(setupListeners);

            addAreaBtn?.addEventListener("click", (event) => {
                event.preventDefault();
                if (!masterTemplate) return;
                const newBlock = masterTemplate.cloneNode(true);
                newBlock.querySelectorAll("input").forEach((input) => input.value = "");
                addAreaBtn.parentElement.before(newBlock);
                renumberAreas();
                setupListeners(newBlock);
            });

            // ==== XỬ LÝ KHI BẤM "TÍNH TOÁN KHỐI LƯỢNG" ====
            const updateResults = () => {
                let totalS = 0;
                let totalL = 0;

                getAreaBlocks().forEach((block) => {
                    const select = block.querySelector("select");
                    if (!select) return;
                    const type = select.value || select.options[select.selectedIndex].text;

                    // Chỉ lấy các input đang hiển thị
                    const inputs = Array.from(block.querySelectorAll("input[type='number']")).filter(
                        (input) => input.closest(".relative")?.parentElement.style.display !==
                        "none"
                    );

                    let area = 0;
                    let length = 0;

                    if (type.includes("CHỮ NHẬT")) {
                        const dai = Number.parseFloat(inputs[0]?.value || 0);
                        const rong = Number.parseFloat(inputs[1]?.value || 0);
                        area = dai * rong;
                        length = dai;
                    } else if (type.includes("THANG")) {
                        const dayLon = Number.parseFloat(inputs[0]?.value || 0);
                        const dayBe = Number.parseFloat(inputs[1]?.value || 0);
                        const cao = Number.parseFloat(inputs[2]?.value || 0);
                        area = ((dayLon + dayBe) * cao) / 2;
                        length = dayLon;
                    } else if (type.includes("TAM GIÁC")) {
                        const day = Number.parseFloat(inputs[0]?.value || 0);
                        const cao = Number.parseFloat(inputs[1]?.value || 0);
                        area = (day * cao) / 2;
                        length = day; // Đường diềm đáy
                    }

                    totalS += area;
                    totalL += length;
                });

                const ceilS = Math.ceil(totalS);

                // Áp dụng hệ số hao hụt
                let factor = 1.0;
                if (extraLossCheckbox?.checked) {
                    const checkedRadio = lossRadios.find((radio) => radio.checked);
                    if (checkedRadio) factor = Number.parseFloat(checkedRadio.value) || 1.0;
                }

                const roof = roofStyleSelect.value || '';
                const selectedTileOption = tileTypeSelect.options[tileTypeSelect.selectedIndex];
                const tile = selectedTileOption?.value || '';

                let amCoeff = 0,
                    duongCoeff = 0,
                    diemCoeff = 0;

                if (selectedTileOption?.dataset?.am) {
                    amCoeff = Number.parseFloat(selectedTileOption.dataset.am) || 0;
                    duongCoeff = Number.parseFloat(selectedTileOption.dataset.duong) || 0;
                    diemCoeff = Number.parseFloat(selectedTileOption.dataset.diem) || 0;
                } else {
                    const row = dinhMucData.find(r => r.roof_type === roof && r.tile_type === tile);
                    if (row) {
                    amCoeff = row.ngoi_am;
                    duongCoeff = row.ngoi_duong;
                    diemCoeff = row.diem;
                    }
                }

                // Tính toán & làm tròn lên
                const ngoiAm = Math.ceil(ceilS * factor * amCoeff);
                const ngoiDuong = Math.ceil(ceilS * factor * duongCoeff);
                const diem = Math.ceil(totalL * factor * diemCoeff);

                // Hiển thị kết quả lên View
                const resAm = document.getElementById("res-am");
                const resDuong = document.getElementById("res-duong");
                const resDiem = document.getElementById("res-diem");

                if (resAm) {
                    resAm.querySelectorAll("span")[1].textContent = `${format(ceilS)} m²`;
                    resAm.querySelectorAll("span")[2].textContent = amCoeff > 0 ? `${amCoeff} viên/m²` :
                        '-- viên/m²';
                    resAm.querySelectorAll("span")[3].textContent = `${format(ngoiAm)} viên`;
                }
                if (resDuong) {
                    resDuong.querySelectorAll("span")[1].textContent = `${format(ceilS)} m²`;
                    resDuong.querySelectorAll("span")[2].textContent = duongCoeff > 0 ?
                        `${duongCoeff} viên/m²` : '-- viên/m²';
                    resDuong.querySelectorAll("span")[3].textContent = `${format(ngoiDuong)} viên`;
                }
                if (resDiem) {
                    resDiem.querySelectorAll("span")[1].textContent = `${format(totalL)} md`;
                    resDiem.querySelectorAll("span")[2].textContent = diemCoeff > 0 ? `${diemCoeff} cặp/md` :
                        '-- cặp/md';
                    resDiem.querySelectorAll("span")[3].textContent = `${format(diem)} cặp`;
                }

                // ĐỒNG BỘ LÊN Ô INPUT SỐ LƯỢNG MUA 
                // Với sản phẩm "Ngói Âm Dương", số lượng bán ra thường đại diện theo bộ/cặp, vì vậy sẽ dùng `ngoiAm` để đồng bộ.
                if (ngoiAm > 0) {
                    const qtyInputs = document.querySelectorAll(
                        'input[name="qty"], input[name="quantity"], .qty-input');
                    qtyInputs.forEach(input => {
                        input.value = ngoiAm;
                        input.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                        input.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    });

                    // Hiển thị thông báo nhỏ
                    const notice = document.getElementById('sync-notice');
                    if (notice) {
                        clearTimeout(noticeTimer);
                        notice.classList.remove('hidden');
                        noticeTimer = setTimeout(() => notice.classList.add('hidden'), 3000);
                    }
                }
            };

            calculateBtn?.addEventListener("click", (event) => {
                event.preventDefault();
                updateResults();
            });

            extraLossCheckbox?.addEventListener("change", updateResults);
            lossRadios.forEach((radio) => radio.addEventListener("change", updateResults));

            let calcTimeout;
            let noticeTimer;
            const scheduleUpdate = () => {
                clearTimeout(calcTimeout);
                calcTimeout = setTimeout(updateResults, 300);
            };

            const wireBlockInputs = (block) => {
                block.querySelectorAll('input[type="number"]').forEach((input) => {
                    input.addEventListener("input", scheduleUpdate);
                });
                const shapeSelect = block.querySelector("select");
                if (shapeSelect) {
                    shapeSelect.addEventListener("change", scheduleUpdate);
                }
            };

            getAreaBlocks().forEach(wireBlockInputs);

            addAreaBtn?.addEventListener("click", () => {
                setTimeout(() => {
                    const blocks = getAreaBlocks();
                    const last = blocks[blocks.length - 1];
                    if (last) wireBlockInputs(last);
                }, 0);
            });

            const syncTileOptions = () => {
                const roof = roofStyleSelect?.value || "";
                tileTypeOptions.forEach((option) => {
                    const isVisible = !roof || option.dataset.roof === roof;
                    option.hidden = !isVisible;
                    option.disabled = !isVisible;
                });

                const selectedOption = tileTypeSelect?.options[tileTypeSelect.selectedIndex];
                if (selectedOption?.disabled) {
                    tileTypeSelect.value = "";
                }
            };

            const checkButtonState = () => {
                if (!calculateBtn) return;
                const isReady = roofStyleSelect.value !== "" && tileTypeSelect.value !== "";
                calculateBtn.disabled = !isReady;
                calculateBtn.classList.toggle("opacity-50", !isReady);
                calculateBtn.classList.toggle("cursor-not-allowed", !isReady);
            };

            roofStyleSelect?.addEventListener("change", () => { syncTileOptions(); checkButtonState(); scheduleUpdate(); });
            tileTypeSelect?.addEventListener("change", () => { checkButtonState(); scheduleUpdate(); });

            // Khởi tạo state của button (Disabled lúc mới vào web do Select đang rỗng)
            syncTileOptions();
            checkButtonState();
        });
    </script>
@endpush
