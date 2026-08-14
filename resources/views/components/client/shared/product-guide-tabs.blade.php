<section class="w-full pb-8 md:pb-16 bg-background-secondary" data-product-guide-tabs data-aos="fade-up">
    <div class="w-[85%] max-w-[1320px] mx-auto">
        <div class="hidden md:flex justify-center mb-8 md:mb-12">
            <div class="flex border-b border-black/10 w-full max-w-[720px]" role="tablist" aria-label="Hướng dẫn và ứng dụng">
                <button
                    type="button"
                    role="tab"
                    data-guide-tab="0"
                    aria-selected="true"
                    class="flex-1 pb-2 md:pb-4 text-[14px] md:text-2xl font-semibold uppercase text-secondary border-b-2 md:border-b-4 border-secondary transition-all text-center font-archivo"
                >
                    Hướng dẫn lắp đặt
                </button>
                <button
                    type="button"
                    role="tab"
                    data-guide-tab="1"
                    aria-selected="false"
                    class="flex-1 pb-2 md:pb-4 text-[14px] md:text-2xl font-semibold uppercase text-secondary/50 border-b-2 md:border-b-4 border-transparent hover:border-secondary/40 transition-all text-center font-archivo"
                >
                    Ứng dụng đa dạng
                </button>
            </div>
        </div>
    </div>

    <div class="swiper product-guide-tabs-swiper overflow-hidden" data-guide-tabs-swiper>
        <div class="swiper-wrapper">
            <div class="swiper-slide" role="tabpanel">
                {{ $install }}
            </div>
            <div class="swiper-slide" role="tabpanel">
                {{ $applications }}
            </div>
        </div>
        <div class="swiper-pagination md:hidden mt-4" data-guide-tabs-pagination></div>
    </div>
</section>
