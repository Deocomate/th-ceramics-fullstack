@props([
    'trangChu' => null,
    'ngoiAmDuongs' => null,
    'ngoiHais' => null,
    'gachHoas' => null,
    'about' => null,
    'factory' => null,
    'showroomImages' => null,
    'showroomContent' => null,
    'news' => null,
    'article' => null,
    'articles' => null,
    'relatedArticles' => null,
    'historyArticles' => null,
    'projects' => null,
    'project' => null,
    'relatedProjects' => null,
    'categories' => null,
    'selectedCategory' => null,
    'currentCategory' => null,
    'config' => null,
    'products' => null,
    'relatedProducts' => null,
    'product' => null,
    'colors' => null,
    'dinhMuc' => null,
    'giaTriVuotTroi' => null,
    'parentConfig' => null,
    'pageLabel' => null,
    'indexRouteName' => null,
    'categoryType' => null,
    'categoryLabel' => null,
    'denGomProducts' => null,
    'denSuProducts' => null,
    'featuredProducts' => null,
    'collectionProducts' => null,
    'ngheProducts' => null,
    'linhVatProducts' => null,
    'bgImage' => null,
    'activeOrder' => false,
    'activeAccount' => false,
    'activeCatalog' => false,
    'activeGuide' => false,
    'activeProcess' => false,
    'activePrivacy' => false,
    'activeReturn' => false,
    'activeShipping' => false,
    'image' => null,
    'label1' => null,
    'rate1' => null,
    'label2' => null,
    'rate2' => null,
    'sectionId' => null,
    'sectionClass' => null,
    'sectionTitle' => null,
    'desktopLinkHref' => null,
    'detailRouteName' => null,
    'wrapperClass' => null,
    'titleClass' => null,
    'title' => null,
    'subtitle' => null,
    'description' => null,
    'items' => null,])
<!-- Ngoi Hai Van Mieu Calculator -->
<section class="w-full pt-0 pb-0 md:pt-16 md:pb-16 bg-background-secondary" data-aos="fade-up" data-hai-vm-calculator>
    <div class="w-[85%] max-w-[1320px] mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8 md:gap-32 items-start">
            <div class="hidden md:flex flex-col items-center md:col-span-2">
                <h2 class="text-3xl font-semibold text-secondary mb-12 uppercase text-center">KÍCH THƯỚC</h2>
                <div class="w-full max-w-[500px] flex justify-center">
                    <img src="{{ isset($image) ? $image : asset('assets/images/gtt-size.png') }}"
                        alt="Kích thước sản phẩm" class="w-full h-auto object-contain" />
                </div>
            </div>

            <div class="flex flex-col md:col-span-3">
                <div class="md:hidden mb-8 md:mb-6">
                    <h2
                        class="text-[20px] font-semibold text-[#C76E00] uppercase text-center mb-0 md:mb-5 leading-[32px]">
                        KÍCH THƯỚC</h2>
                    <div class="w-full flex justify-center">
                        <img src="{{ isset($image) ? $image : asset('assets/images/gtt-size.png') }}"
                            alt="Kích thước sản phẩm" class="w-full h-auto object-contain" />
                    </div>
                </div>

                <h2
                    class="text-[20px] md:text-3xl font-semibold text-[#C76E00] md:text-secondary mb-8 md:mb-7 uppercase text-center leading-[32px] md:leading-normal">
                    CÁCH TÍNH KHỐI LƯỢNG
                </h2>

                <div class="md:hidden flex flex-col gap-8 mb-6">
                    <img src="{{ asset('assets/images/weight-01.svg') }}" alt="Cách đo mái chữ nhật"
                        class="w-full h-auto object-contain" />
                    <img src="{{ asset('assets/images/weight-02.svg') }}" alt="Cách đo mái hình thang"
                        class="w-full h-auto object-contain" />
                    <img src="{{ asset('assets/images/weight-03.svg') }}" alt="Cách đo diện tích mái"
                        class="w-full h-auto object-contain" />
                </div>

                <hr class="border-t border-black/10 w-full mb-4 block md:hidden" />

                <div class="space-y-4">
                    <div class="flex justify-center">
                        <div class="relative w-full md:max-w-[285px]">
                            <select data-roof-style
                                class="w-full h-[26px] md:h-[44px] px-2 md:px-4 pr-6 md:pr-10 border border-black/10 rounded-sm bg-transparent text-[10px] md:text-[13px] uppercase appearance-none outline-none focus:border-secondary transition-colors">
                                <option value="" disabled selected>KIỂU MÁI</option>
                                <option value="2-slope" data-factor="1.0">Mái dốc 2 mặt (Mái chữ A)</option>
                                <option value="4-slope" data-factor="1.0">Mái dốc 4 mặt (Mái bánh ít)</option>
                                <option value="chong-diem" data-factor="1.0">Mái chồng diêm</option>
                                <option value="dao" data-factor="1.1">Mái đao</option>
                            </select>
                            <div class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                <svg class="w-3 h-3 md:w-4 md:h-4 text-secondary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-2 lg:gap-4 items-start lg:items-end" data-area-block>
                        <div class="hidden md:flex w-full md:w-[150px] shrink-0 pb-1">
                            <span class="font-semibold text-primary uppercase text-base">DIỆN TÍCH 1</span>
                        </div>
                        <div class="flex-1 w-full flex flex-row md:grid md:grid-cols-12 gap-2 md:gap-7 items-end">
                            <div class="w-[111px] md:w-auto shrink-0 md:col-span-3 flex flex-col justify-end">
                                <span
                                    class="font-semibold text-primary uppercase text-[11px] leading-[24px] md:hidden mb-1 pl-0.5"
                                    data-area-title>DIỆN TÍCH 1</span>
                                <div class="relative">
                                    <select data-shape-select
                                        class="w-full h-[30px] md:h-[45px] px-2 md:px-3 pr-5 md:pr-6 border border-[#DDD6D0] md:border-black/10 rounded-sm bg-transparent text-[9px] md:text-[11px] uppercase appearance-none outline-none focus:border-secondary transition-colors">
                                        <option selected>HÌNH CHỮ NHẬT</option>
                                        <option>HÌNH THANG</option>
                                        <option>HÌNH TAM GIÁC</option>
                                    </select>
                                    <div
                                        class="absolute right-1 md:right-3 top-1/2 -translate-y-1/2 pointer-events-none text-secondary">
                                        <svg class="w-2.5 h-2.5 md:w-3 md:h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1 md:col-span-3 flex flex-col gap-1 md:gap-2" data-input-wrapper>
                                <label
                                    class="text-[9px] md:text-[16px] uppercase font-semibold text-[#2E2F2A] md:text-primary text-center tracking-tight leading-[10px] md:leading-none min-h-0 md:min-h-[44px] flex flex-col items-center justify-start">CHIỀU
                                    DÀI
                                    <span
                                        class="block text-[7px] md:text-[12px] font-normal italic normal-case text-[rgba(199,110,0,0.70)] md:text-secondary mt-0 md:mt-1 tracking-normal leading-[10px] md:leading-normal">Chiều
                                        tính diềm mái</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" placeholder="0"
                                        class="w-full h-[30px] md:h-[45px] p-2 md:p-3 border border-[#DDD6D0] md:border-black/10 rounded-sm bg-transparent text-[12px] md:text-base text-left pr-4 md:pr-6 focus:border-secondary outline-none transition-colors" />
                                    <span
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-primary/40">m</span>
                                </div>
                            </div>
                            <div class="flex-1 md:col-span-3 flex flex-col gap-1 md:gap-2" data-input-wrapper>
                                <label
                                    class="text-[9px] md:text-[16px] uppercase font-semibold text-[#2E2F2A] md:text-primary text-center tracking-tight leading-[10px] md:leading-none min-h-0 md:min-h-[44px] flex flex-col items-center justify-start">CHIỀU
                                    RỘNG
                                    <span
                                        class="block text-[7px] md:text-[12px] opacity-0 mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">_</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" placeholder="0"
                                        class="w-full h-[30px] md:h-[45px] p-2 md:p-3 border border-[#DDD6D0] md:border-black/10 rounded-sm bg-transparent text-[12px] md:text-base text-left pr-4 md:pr-6 focus:border-secondary outline-none transition-colors" />
                                    <span
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-primary/40">m</span>
                                </div>
                            </div>
                            <div class="flex-1 md:col-span-3 flex flex-col gap-1 md:gap-2" data-input-wrapper
                                style="display: none">
                                <label
                                    class="text-[9px] md:text-[16px] uppercase font-semibold text-[#2E2F2A] md:text-primary text-center tracking-tight leading-[10px] md:leading-none min-h-0 md:min-h-[44px] flex flex-col items-center justify-start">CHIỀU
                                    CAO
                                    <span
                                        class="block text-[7px] md:text-[12px] opacity-0 mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">_</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" placeholder="0"
                                        class="w-full h-[30px] md:h-[45px] p-2 md:p-3 border border-[#DDD6D0] md:border-black/10 rounded-sm bg-transparent text-[12px] md:text-base text-left pr-4 md:pr-6 focus:border-secondary outline-none transition-colors" />
                                    <span
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-primary/40">m</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row gap-2 lg:gap-4 items-start lg:items-end" data-area-block>
                        <div class="hidden md:flex w-full md:w-[150px] shrink-0 pb-1 flex-col">
                            <span class="font-semibold text-primary uppercase text-base">DIỆN TÍCH 2</span>
                            <button data-remove-area
                                class="text-[14px] text-secondary underline text-left font-medium ml-4">Loại
                                bỏ</button>
                        </div>
                        <div class="flex-1 w-full flex flex-row md:grid md:grid-cols-12 gap-2 md:gap-7 items-end">
                            <div class="w-[111px] md:w-auto shrink-0 md:col-span-3 flex flex-col justify-end">
                                <div class="flex items-center gap-2 md:hidden mb-1 pl-0.5">
                                    <span class="font-semibold text-primary uppercase text-[11px] leading-[24px]"
                                        data-area-title>DIỆN TÍCH 2</span>
                                    <button data-remove-area
                                        class="text-[8px] text-[rgba(199,110,0,0.70)] underline italic font-normal md:hidden">Loại
                                        bỏ</button>
                                </div>
                                <div class="relative">
                                    <select data-shape-select
                                        class="w-full h-[30px] md:h-[45px] px-2 md:px-3 pr-5 md:pr-6 border border-[#DDD6D0] md:border-black/10 rounded-sm bg-transparent text-[9px] md:text-[11px] uppercase appearance-none outline-none focus:border-secondary transition-colors">
                                        <option>HÌNH CHỮ NHẬT</option>
                                        <option selected>HÌNH THANG</option>
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
                            <div class="flex-1 lg:col-span-3 flex flex-col gap-1 lg:gap-2" data-input-wrapper>
                                <label
                                    class="text-[9px] lg:text-[16px] uppercase font-semibold text-[#2E2F2A] lg:text-primary text-center tracking-tight leading-[10px] lg:leading-none min-h-0 lg:min-h-[44px] flex flex-col items-center justify-start">ĐÁY
                                    LỚN
                                    <span
                                        class="block text-[7px] lg:text-[12px] font-normal italic normal-case text-[rgba(199,110,0,0.70)] lg:text-secondary mt-0 lg:mt-1 tracking-normal leading-[10px] lg:leading-normal">Chiều
                                        tính diềm mái</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" placeholder="0"
                                        class="w-full h-[30px] md:h-[45px] p-2 md:p-3 border border-[#DDD6D0] md:border-black/10 rounded-sm bg-transparent text-[12px] md:text-base text-left pr-4 md:pr-6 focus:border-secondary outline-none transition-colors" />
                                    <span
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-primary/40">m</span>
                                </div>
                            </div>
                            <div class="flex-1 lg:col-span-3 flex flex-col gap-1 lg:gap-2" data-input-wrapper>
                                <label
                                    class="text-[9px] lg:text-[16px] uppercase font-semibold text-[#2E2F2A] lg:text-primary text-center tracking-tight leading-[10px] lg:leading-none min-h-0 lg:min-h-[44px] flex flex-col items-center justify-start">ĐÁY
                                    BÉ
                                    <span
                                        class="block text-[7px] lg:text-[12px] opacity-0 mt-0 lg:mt-1 tracking-normal leading-[18px] lg:leading-normal">_</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" placeholder="0"
                                        class="w-full h-[30px] md:h-[45px] p-2 md:p-3 border border-[#DDD6D0] md:border-black/10 rounded-sm bg-transparent text-[12px] md:text-base text-left pr-4 md:pr-6 focus:border-secondary outline-none transition-colors" />
                                    <span
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-primary/40">m</span>
                                </div>
                            </div>
                            <div class="flex-1 md:col-span-3 flex flex-col gap-1 md:gap-2" data-input-wrapper>
                                <label
                                    class="text-[9px] md:text-[16px] uppercase font-semibold text-[#2E2F2A] md:text-primary text-center tracking-tight leading-[10px] md:leading-none min-h-0 md:min-h-[44px] flex flex-col items-center justify-start">CHIỀU
                                    CAO
                                    <span
                                        class="block text-[7px] md:text-[12px] opacity-0 mt-0 md:mt-1 tracking-normal leading-[18px] md:leading-normal">_</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" placeholder="0"
                                        class="w-full h-[30px] md:h-[45px] p-2 md:p-3 border border-[#DDD6D0] md:border-black/10 rounded-sm bg-transparent text-[12px] md:text-base text-left pr-4 md:pr-6 focus:border-secondary outline-none transition-colors" />
                                    <span
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-primary/40">m</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button data-add-area
                            class="text-[#C76E00] md:text-secondary font-semibold text-[10px] md:text-sm underline">+
                            Thêm diện tích</button>
                    </div>

                    <button data-calculate-btn
                        class="w-full py-4 bg-[#C16A00] hover:bg-secondary text-[#EFE4DE] text-[14px] md:text-sm font-semibold md:font-bold uppercase tracking-[0.42px] md:tracking-widest rounded-sm shadow-md transition-all duration-300">TÍNH
                        TOÁN KHỐI LƯỢNG</button>

                    <!-- Thông báo đồng bộ (ẩn mặc định) -->
                    <div id="sync-notice-hai-vm"
                        class="hidden text-center text-green-600 font-semibold text-sm mt-2 transition-opacity duration-300">
                        ✓ Đã tự động cập nhật số lượng lên mục đặt hàng
                    </div>

                    <div class="pt-6 md:pt-8 md:border-t border-black/10">
                        <div class="flex items-center justify-between mb-2">
                            <h4
                                class="font-semibold text-[#2E2F2A] md:text-primary uppercase text-[16px] md:text-xl tracking-[0.80px] md:tracking-normal leading-[24px]">
                                KẾT QUẢ</h4>
                            <span data-total-area-output
                                class="text-[16px] md:text-2xl font-semibold md:font-bold text-[#2E2F2A] md:text-primary leading-[24px]">0
                                m²</span>
                        </div>
                        <div
                            class="grid grid-cols-[2fr_1fr_1fr] md:grid-cols-[7fr_1.5fr_1.5fr] gap-x-4 md:gap-x-6 gap-y-4 items-center w-fit md:w-full">
                            <p
                                class="text-[12px] italic text-[#2E2F2A] md:text-primary font-light leading-[18px] md:leading-normal self-start">
                                Diện tích được làm tròn lên <br class="md:hidden"> đến m² gần nhất.
                            </p>
                            <span
                                class="text-[16px] md:text-base font-semibold text-[#2E2F2A] md:text-primary leading-[24px] text-center">Định
                                mức</span>
                            <span
                                class="invisible text-[16px] md:text-base font-extrabold leading-[24px] whitespace-nowrap">00
                                viên</span>

                            <!-- Rate 1 -->
                            <span
                                class="text-[16px] md:text-base text-[rgba(46,47,42,0.80)] md:text-primary font-medium md:font-normal leading-[24px]">{{ isset($label1) ? $label1 : 'Ngói trên mái gỗ' }}</span>
                            <span data-rate-output
                                class="text-[16px] md:text-base text-[rgba(46,47,42,0.80)] md:text-primary font-medium md:font-normal leading-[24px] text-center whitespace-nowrap">{{ isset($rate1) ? $rate1 : '125 viên/m²' }}</span>
                            <span data-value-output
                                class="text-[16px] md:text-base text-[#2E2F2A] md:text-primary font-extrabold leading-[24px] text-right whitespace-nowrap">0
                                viên</span>

                            <!-- Rate 2 -->
                            @isset($label2)
                                <span
                                    class="text-[16px] md:text-base text-[rgba(46,47,42,0.80)] md:text-primary font-medium md:font-normal leading-[24px]">{{ $label2 }}</span>
                                <span data-rate-output
                                    class="text-[16px] md:text-base text-[rgba(46,47,42,0.80)] md:text-primary font-medium md:font-normal leading-[24px] text-center whitespace-nowrap">{{ isset($rate2) ? $rate2 : '75 viên/m²' }}</span>
                                <span data-value-output
                                    class="text-[16px] md:text-base text-[#2E2F2A] md:text-primary font-extrabold leading-[24px] text-right whitespace-nowrap">0
                                    viên</span>
                            @endisset
                        </div>
                    </div>

                    <div class="space-y-4 pt-1 md:pt-4 border-t border-black/10">
                        <input type="checkbox" id="extra-loss-hai-vm" class="peer sr-only" />
                        <label for="extra-loss-hai-vm"
                            class="w-fit text-[15px] font-medium text-primary/80 cursor-pointer flex items-center gap-3 select-none peer-checked:[&_.loss-checkbox-box]:border-secondary peer-checked:[&_.loss-checkbox-box]:bg-secondary peer-checked:[&_.loss-checkbox-icon]:opacity-100 peer-focus-visible:[&_.loss-checkbox-box]:ring-2 peer-focus-visible:[&_.loss-checkbox-box]:ring-secondary/40 peer-focus-visible:[&_.loss-checkbox-box]:ring-offset-1">
                            <span
                                class="loss-checkbox-box relative flex h-5 w-5 items-center justify-center border border-black/20 rounded-none bg-transparent transition-all">
                                <svg class="loss-checkbox-icon h-3 w-3 text-white opacity-0 transition-opacity"
                                    viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M4 10.5L8 14.5L16 6.5" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </svg>
                            </span>
                            <span class="flex items-center gap-2">
                                Cộng thêm hao hụt
                                <span
                                    class="w-5 h-5 rounded-full border border-primary/40 text-primary/60 flex items-center justify-center text-[10px] font-bold">?</span>
                            </span>
                        </label>

                        <div class="pl-8 space-y-3 hidden peer-checked:block">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center justify-center">
                                    <input type="radio" name="loss-rate-hai-vm" value="1.05"
                                        class="peer appearance-none w-5 h-5 border border-black/30 rounded-full checked:border-primary transition-all cursor-pointer"
                                        checked />
                                    <div
                                        class="absolute w-2.5 h-2.5 rounded-full bg-primary scale-0 peer-checked:scale-100 transition-transform">
                                    </div>
                                </div>
                                <span class="text-[14px] text-primary/70 font-medium tracking-tight">Thêm 5%</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <div class="relative flex items-center justify-center">
                                    <input type="radio" name="loss-rate-hai-vm" value="1.10"
                                        class="peer appearance-none w-5 h-5 border border-black/30 rounded-full checked:border-primary transition-all cursor-pointer" />
                                    <div
                                        class="absolute w-2.5 h-2.5 rounded-full bg-primary scale-0 peer-checked:scale-100 transition-transform">
                                    </div>
                                </div>
                                <span class="text-[14px] text-primary/70 font-medium tracking-tight">Thêm 10% (Đối với
                                    mái có cấu trúc phức tạp)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

