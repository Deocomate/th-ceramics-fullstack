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
    'items' => null,
])
<!-- Danh Mục Sản Phẩm Section 2 -->
<section class="w-full pb-[30px] md:pb-16">
    <div class="bg-[#EBCFC2] opacity-80 p-6 lg:p-0" style="margin-right: max(0px, calc((100% - 1320px) / 2))"
        data-aos="fade-up" data-aos-delay="100">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-16 max-w-[1320px] ml-auto lg:py-10 lg:pr-10">
            <div class="w-full lg:w-[45%] flex flex-col justify-stretch">
                <div
                    class="w-full flex-grow relative shadow-xl overflow-hidden bg-black/5 min-h-[400px] lg:min-h-[500px] border border-black/10">
                    <img src="{{ asset('assets/images/lan-can-bau.png') }}" alt=""
                        class="absolute inset-0 w-full h-full object-cover" />
                    <div
                        class="absolute bottom-6 left-1/2 -translate-x-1/2 w-[85%] max-w-[560px] z-10 hover:scale-105 transition-transform duration-300">
                        <div class="relative w-full flex items-center justify-center">
                            <img src="{{ asset('assets/images/brush.svg') }}" alt=""
                                class="w-full drop-shadow-xl opacity-90" />
                            <span
                                class="lan-can-brush-title absolute text-white font-bold text-[24px] md:text-[32px] uppercase tracking-wider"
                                style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4)">
                                LAN CAN BẦU
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-[55%] flex flex-col justify-between">
                <div
                    class="grid grid-cols-2 gap-x-4 md:gap-x-8 lg:gap-x-16 gap-y-6 md:gap-y-10 lg:gap-y-12 mb-6 md:mb-10">
                    <x-client.shared.product-card href="#" image="{{ asset('assets/images/lan-can-02.jpg') }}"
                        title="Riềm Âm Dương Sen Bầu"
                        title-class="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase -mb-[5px] tracking-wide transition-colors group-hover:text-secondary"
                        code="MSP: 1234RDS" price="Giá: 675.000 đ/m2" :show-overlay="true" />

                    <x-client.shared.product-card href="#" image="{{ asset('assets/images/lan-can-04.jpg') }}"
                        title="Riềm Âm Dương Sen Bầu"
                        title-class="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase -mb-[5px] tracking-wide transition-colors group-hover:text-secondary"
                        code="MSP: 1234RDS" price="Giá: 675.000 đ/m2" :show-overlay="true" />

                    <x-client.shared.product-card href="#" image="{{ asset('assets/images/lan-can-05.jpg') }}"
                        title="Riềm Âm Dương Sen Bầu"
                        title-class="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase -mb-[5px] tracking-wide transition-colors group-hover:text-secondary"
                        code="MSP: 1234RDS" price="Giá: 675.000 đ/m2" :show-overlay="true" />

                    <x-client.shared.product-card href="#" image="{{ asset('assets/images/lan-can-06.jpg') }}"
                        title="Riềm Âm Dương Sen Bầu"
                        title-class="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase -mb-[5px] tracking-wide transition-colors group-hover:text-secondary"
                        code="MSP: 1234RDS" price="Giá: 675.000 đ/m2" :show-overlay="true" />
                </div>

                <div class="flex items-center justify-center gap-5 mt-0 md:mt-10 lg:mt-auto">
                    <button
                        class="w-[40px] h-[40px] rounded-full border border-secondary flex items-center justify-center text-secondary hover:bg-secondary hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18">
                            </path>
                        </svg>
                    </button>
                    <button
                        class="w-[40px] h-[40px] rounded-full bg-secondary flex items-center justify-center text-white hover:opacity-90 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
