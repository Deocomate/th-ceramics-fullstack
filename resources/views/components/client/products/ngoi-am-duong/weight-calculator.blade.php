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
@php
    $dinhMuc = $dinhMuc ?? [];
    $dinhMucItems = collect($dinhMuc)->filter()->values();
    $roofTypes = $dinhMucItems->pluck('roof_type')->filter()->unique()->values();
@endphp

<!-- Ngoi Am Duong Calculator -->
<section class="w-full pb-12 pt-8 lg:pt-16 lg:pb-16 bg-background-secondary" data-aos="fade-up" data-weight-calculator>
    <script type="application/json" data-dinh-muc-json>@json($dinhMuc)</script>
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
                                <option value="{{ $dm->roof_type }}::{{ $dm->tile_type }}"
                                    data-roof="{{ $dm->roof_type }}"
                                    data-tile="{{ $dm->tile_type }}"
                                    data-am="{{ $dm->ngoi_am }}"
                                    data-duong="{{ $dm->ngoi_duong }}"
                                    data-diem="{{ $dm->diem }}">
                                    {{ $dm->tile_type }}
                                </option>
                            @empty
                                <option value="Mái bê tông::15 cặp/m²" data-roof="Mái bê tông" data-tile="15 cặp/m²" data-am="15" data-duong="15" data-diem="3">15 cặp/m²</option>
                                <option value="Mái bê tông::27 cặp/m²" data-roof="Mái bê tông" data-tile="27 cặp/m²" data-am="27" data-duong="27" data-diem="5">27 cặp/m²</option>
                                <option value="Mái bê tông::43 cặp/m²" data-roof="Mái bê tông" data-tile="43 cặp/m²" data-am="43" data-duong="43" data-diem="6">43 cặp/m²</option>
                                <option value="Mái bê tông::80 cặp/m²" data-roof="Mái bê tông" data-tile="80 cặp/m²" data-am="80" data-duong="80" data-diem="8">80 cặp/m²</option>
                                <option value="Mái gỗ::15 cặp/m²" data-roof="Mái gỗ" data-tile="15 cặp/m²" data-am="25" data-duong="15" data-diem="3">15 cặp/m²</option>
                                <option value="Mái gỗ::27 cặp/m²" data-roof="Mái gỗ" data-tile="27 cặp/m²" data-am="40" data-duong="27" data-diem="5">27 cặp/m²</option>
                                <option value="Mái gỗ::43 cặp/m²" data-roof="Mái gỗ" data-tile="43 cặp/m²" data-am="60" data-duong="43" data-diem="6">43 cặp/m²</option>
                                <option value="Mái gỗ::80 cặp/m²" data-roof="Mái gỗ" data-tile="80 cặp/m²" data-am="120" data-duong="80" data-diem="8">80 cặp/m²</option>
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

