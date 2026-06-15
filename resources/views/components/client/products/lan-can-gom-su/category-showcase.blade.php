@props([
    'products' => collect(),
    'sectionImage' => null,
    'sectionTitle' => 'LAN CAN BẦU',
])
<!-- Danh Mục Sản Phẩm Section 2 -->
<section class="w-full pb-[30px] md:pb-16">
    <div class="bg-[#EBCFC2] opacity-80 p-6 lg:p-0" style="margin-right: max(0px, calc((100% - 1320px) / 2))"
        data-aos="fade-up" data-aos-delay="100">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-16 max-w-[1320px] ml-auto lg:py-10 lg:pr-10">
            <div class="w-full lg:w-[45%] flex flex-col justify-stretch">
                <div
                    class="w-full flex-grow relative shadow-xl overflow-hidden bg-black/5 min-h-[400px] lg:min-h-[500px] border border-black/10">
                    <img src="{{ $sectionImage ? asset('storage/' . $sectionImage) : asset('assets/images/lan-can-bau.png') }}" alt="{{ $sectionTitle }}"
                        class="absolute inset-0 w-full h-full object-cover" />
                    <div
                        class="absolute bottom-6 left-1/2 -translate-x-1/2 w-[85%] max-w-[560px] z-10 hover:scale-105 transition-transform duration-300">
                        <div class="relative w-full flex items-center justify-center">
                            <img src="{{ asset('assets/images/brush.svg') }}" alt=""
                                class="w-full drop-shadow-xl opacity-90" />
                            <span
                                class="lan-can-brush-title absolute text-white font-bold text-[24px] md:text-[32px] uppercase tracking-wider"
                                style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4)">
                                {{ $sectionTitle }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-[55%] flex flex-col justify-between">
                <div
                    class="grid grid-cols-2 gap-x-4 md:gap-x-8 lg:gap-x-16 gap-y-6 md:gap-y-10 lg:gap-y-12 mb-6 md:mb-10">
                    @forelse ($products->take(4) as $product)
                        <x-client.shared.product-card
                            href="{{ route('client.products.lan-can-gom-su.detail', $product->lan_can_gom_su_ct_id) }}"
                            image="{{ !empty($product->images) ? asset('storage/' . $product->images[0]) : asset('assets/images/lan-can-02.jpg') }}"
                            title="{{ $product->name }}"
                            title-class="font-bold text-[#212121] text-[14px] lg:text-[15px] uppercase -mb-[5px] tracking-wide transition-colors group-hover:text-secondary"
                            code="MSP: {{ $product->display_code }}"
                            price="{{ $product->display_price }}"
                            :show-overlay="true"
                            product-type="lan_can_gom_su_ct"
                            :product-id="$product->lan_can_gom_su_ct_id"
                            :product="$product" />
                    @empty
                        <p class="col-span-2 text-center text-gray-600 py-8">Chưa có sản phẩm.</p>
                    @endforelse
                </div>

                <div class="flex items-center justify-center gap-5 mt-0 md:mt-10 lg:mt-auto">
                    <button type="button"
                        class="w-[40px] h-[40px] rounded-full border border-secondary flex items-center justify-center text-secondary hover:bg-secondary hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18">
                            </path>
                        </svg>
                    </button>
                    <button type="button"
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
