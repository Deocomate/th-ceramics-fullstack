<x-layouts.client title="Ngói Âm Dương" data-page="products" main-class="bg-background-secondary pb-14 md:pb-20" :hide-newsletter="true">

@push('styles')
<style>
    @import url("https://fonts.googleapis.com/css2?family=Italianno&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Italianno&display=swap");

    .size-options-scroll {
        scroll-behavior: smooth;
    }
</style>
@endpush

@php
    $sizeImage = \App\Support\AssetPath::url($product->size_image, 'assets/images/ngoi-am-duong-size.png');
@endphp

<!-- Top Banner for Detail -->
<section class="hidden md:flex relative w-full h-[180px] md:h-[210px] items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('assets/images/detail-banner.png') }}" alt="Ngói Âm Dương Banner" class="w-full h-full object-cover" />
    </div>
    <div class="relative z-10 text-center text-white px-4 pt-4">
        <h1 class="text-2xl md:text-3xl font-bold mb-2.5 uppercase">
            NGÓI ÂM DƯƠNG
        </h1>
        <p class="text-xs md:text-sm text-white/80">
            <a href="{{ route('client.home') }}" class="hover:text-white transition-colors">Trang chủ</a>
            <svg class="w-2.5 h-2.5 inline-block mx-2 fill-current opacity-80" viewBox="0 0 35 35" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.5797 31.4214C11.1695 31.0111 10.9391 30.4548 10.9391 29.8747C10.9391 29.2946 11.1695 28.7383 11.5797 28.3281L22.4078 17.5L11.5797 6.67184C11.1937 6.25726 10.9836 5.70915 10.9936 5.14283C11.0036 4.5765 11.2328 4.03612 11.6331 3.63539C12.0334 3.23465 12.5735 3.0048 13.1399 2.99421C13.7062 2.98361 14.2545 3.1931 14.6695 3.57858L27.046 15.9516C27.4561 16.3618 27.6865 16.9182 27.6865 17.4983C27.6865 18.0783 27.4561 18.6347 27.046 19.0449L14.6729 31.4214C14.2627 31.8315 13.7064 32.0619 13.1263 32.0619C12.5462 32.0619 11.9899 31.8315 11.5797 31.4214Z"
                    fill="currentColor" />
            </svg>
            <a href="{{ route('client.products.ngoi-am-duong.index') }}" class="hover:text-white transition-colors">Ngói âm dương</a>
        </p>
    </div>
</section>

<!-- Sub Breadcrumb -->
<div class="hidden md:block w-[85%] max-w-[1320px] mx-auto py-8">
    <x-products.breadcrumb current-label="NGÓI ÂM DƯƠNG" parent-label="Sản phẩm" parent-href="{{ route('client.products.ngoi-am-duong.index') }}" />
    <hr class="border-t border-black/10 mt-4 w-full" />
</div>

<!-- Product Detail Container -->
<x-products.product-detail-container
    title="{{ $product->name }}"
    price="{{ $product->price > 0 ? number_format($product->price, 0, ',', '.') . ' đ/m²' : 'Liên hệ' }}"
    rawPrice="{{ $product->price }}"
    sku="{{ $product->code ?? '' }}"
    :features="$product->des ?? null"
    :images="$product->images ?? []"
    productType="ngoi_am_duong_ct"
    productId="{{ $product->ngoi_am_duong_ct_id }}"
/>

<x-products.journey-video :hide-title="true" />

<!-- Flex Container for Mobile Reordering -->
<div class="flex flex-col w-full">
    <!-- Sizes Options Section -->
    <section class="order-2 md:order-1 w-[85%] max-w-[1320px] mx-auto pb-[40px] md:pb-16 pt-1" data-aos="fade-up">
        <h2
            class="text-[20px] leading-[32px] tracking-[0.6px] md:text-3xl md:leading-normal md:tracking-wide font-semibold text-center text-secondary mb-6 md:mb-12 uppercase break-words">
            Bảng kích thước
        </h2>
        <div class="size-options-scroll mobile-scroll-visible w-full pb-2 overflow-x-scroll md:overflow-x-hidden">
            <img src="{{ $sizeImage }}" alt="Bảng kích thước {{ $product->name }}"
                class="h-auto object-contain max-w-none w-[200%] md:w-full"
                onload="window.dispatchEvent(new Event('resize'))" />
        </div>
    </section>

    <!-- Sections Wrapped in Accordion for Mobile -->
    <div class="order-1 md:order-2 w-full flex flex-col pt-0 pb-2 md:pb-0">
        <!-- Product Color Palette Accordion Item -->
        <div class="md:border-none">
            <button class="md:hidden w-full flex justify-between items-center py-[20px] px-[30px]"
                onclick="toggleMobileAccordion(this)">
                <span class="text-[#2E2F2A] text-[14px] font-bold tracking-tight">Bảng màu</span>
                <div
                    class="chevron w-6 h-6 bg-[#E5E7EB] rounded-full flex justify-center items-center transition-transform duration-300">
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L5 5L9 1" stroke="#6B7280" stroke-width="1.33" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
            </button>
            <div class="accordion-content hidden md:block w-full pb-6 md:pb-0 pt-2 md:pt-0">
                <x-products.color-palette :colors="$colors" />
            </div>
        </div>
        <hr class="mx-[30px] border-t border-black/10 md:hidden" />

        <!-- Weight Calculator Accordion Item -->
        <div class="md:border-none">
            <button class="md:hidden w-full flex justify-between items-center py-[20px] px-[30px]"
                onclick="toggleMobileAccordion(this)">
                <span class="text-[#2E2F2A] text-[14px] font-bold tracking-tight">Ước tính khối lượng</span>
                <div
                    class="chevron w-6 h-6 bg-[#E5E7EB] rounded-full flex justify-center items-center transition-transform duration-300">
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L5 5L9 1" stroke="#6B7280" stroke-width="1.33" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
            </button>
            <div class="accordion-content hidden md:block w-full pb-6 md:pb-0">
                @include('clients.products.ngoi-am-duong.partials.weight-calculator', ['dinhMuc' => $dinhMuc])
            </div>
        </div>
        <hr class="mx-[30px] border-t border-black/10 md:hidden" />

        <!-- Applications Accordion Item -->
        <div class="md:border-none">
            <button class="md:hidden w-full flex justify-between items-center py-[20px] px-[30px]"
                onclick="toggleMobileAccordion(this)">
                <span class="text-[#2E2F2A] text-[14px] font-bold tracking-tight">Ứng dụng</span>
                <div
                    class="chevron w-6 h-6 bg-[#E5E7EB] rounded-full flex justify-center items-center transition-transform duration-300">
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L5 5L9 1" stroke="#6B7280" stroke-width="1.33" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
            </button>
            <div class="accordion-content hidden md:block w-full pb-6 md:pb-0">
                @include('clients.products.ngoi-am-duong.partials.applications')
            </div>
        </div>
        <hr class="mx-[30px] border-t border-black/10 md:hidden" />

        <!-- Installation Guide Accordion Item -->
        <div class="md:border-none">
            <button class="md:hidden w-full flex justify-between items-center py-[20px] px-[30px]"
                onclick="toggleMobileAccordion(this)">
                <span class="text-[#2E2F2A] text-[14px] font-bold tracking-tight">Hướng dẫn lắp đặt</span>
                <div
                    class="chevron w-6 h-6 bg-[#E5E7EB] rounded-full flex justify-center items-center transition-transform duration-300">
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L5 5L9 1" stroke="#6B7280" stroke-width="1.33" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
            </button>
            <div class="accordion-content hidden md:block w-full pb-6 md:pb-0">
                @include('clients.products.ngoi-am-duong.partials.installation-guide')
            </div>
        </div>
        <hr class="mx-[30px] border-t border-black/10 md:hidden mb-8 md:mb-0" />
    </div>
</div>

<x-products.works />
<x-products.recommendations
    :related-products="$relatedProducts"
    route-name="client.products.ngoi-am-duong.detail"
    pk-field="ngoi_am_duong_ct_id"
    product-type="ngoi_am_duong_ct"
/>
<x-products.faq2 />

@push('scripts')
<script>
    function toggleMobileAccordion(btn) {
        const content = btn.nextElementSibling;
        const chevron = btn.querySelector('.chevron');
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            chevron.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            chevron.classList.remove('rotate-180');
        }
    }
</script>
@endpush
</x-layouts.client>
