@php
    $contactHotline = data_get($globalContact ?? null, 'hotline', '0966 55 8808');
@endphp

<div id="cart-modal" class="hidden fixed inset-0 z-[110]" aria-hidden="true">
    <div class="absolute inset-0 bg-black/50" data-cart-modal-overlay></div>
    <div class="absolute inset-0 flex items-end sm:items-center justify-center p-0 sm:p-4" role="dialog" aria-modal="true"
        aria-labelledby="cart-modal-title">
        <div
            class="relative w-full sm:max-w-[640px] max-h-[92vh] overflow-y-auto bg-white rounded-t-sm sm:rounded-sm shadow-2xl flex flex-col sm:flex-row">
            <button type="button"
                class="absolute top-3 right-3 z-10 w-8 h-8 flex items-center justify-center rounded-full bg-neutral-2 text-primary hover:text-secondary transition-colors"
                data-cart-modal-close aria-label="Đóng">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>

            <div class="sm:w-[240px] shrink-0 bg-neutral-2 p-4 flex items-center justify-center">
                <img id="cart-modal-image" src="" alt="" class="w-full max-h-[200px] object-contain" />
            </div>

            <div class="flex-1 p-5 sm:p-6 flex flex-col gap-4">
                <div>
                    <h2 id="cart-modal-title" class="text-lg font-bold text-primary pr-8"></h2>
                    <p id="cart-modal-sku" class="mt-1 text-sm text-primary/60 hidden"></p>
                </div>

                <div id="cart-modal-variants-wrap" class="hidden">
                    <p id="cart-modal-variant-label" class="text-xs font-bold uppercase tracking-wide text-primary/50 mb-2">
                        Phân loại</p>
                    <div id="cart-modal-variants" class="flex flex-wrap gap-2"></div>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wide text-primary/50 mb-2">Số lượng</p>
                    <div class="inline-flex items-center border border-neutral-1 rounded-sm">
                        <button type="button" data-cart-modal-qty-decrease
                            class="w-10 h-10 flex items-center justify-center text-primary hover:text-secondary transition-colors"
                            aria-label="Giảm số lượng">−</button>
                        <span id="cart-modal-qty" class="w-12 text-center font-semibold text-primary">1</span>
                        <button type="button" data-cart-modal-qty-increase
                            class="w-10 h-10 flex items-center justify-center text-primary hover:text-secondary transition-colors"
                            aria-label="Tăng số lượng">+</button>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-neutral-1 pt-4 mt-auto">
                    <div>
                        <p class="text-xs text-primary/50">Tạm tính</p>
                        <p id="cart-modal-total" class="text-xl font-bold text-secondary">0 đ</p>
                    </div>
                    <button type="button" id="cart-modal-submit"
                        class="px-6 py-3 bg-secondary text-white font-bold text-sm uppercase rounded-sm hover:bg-secondary/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Thêm vào giỏ
                    </button>
                </div>

                <div id="cart-modal-contact-only" class="hidden rounded-sm bg-neutral-2 p-4 text-sm text-primary">
                    <p class="font-semibold">Liên hệ đặt hàng</p>
                    <p class="mt-1 text-primary/70">Sản phẩm này cần tư vấn trước khi đặt.</p>
                    <a href="tel:{{ preg_replace('/\s+/', '', $contactHotline) }}"
                        class="mt-2 inline-block font-bold text-secondary">{{ $contactHotline }}</a>
                </div>

                <div id="cart-modal-loading" class="hidden absolute inset-0 bg-white/80 flex items-center justify-center">
                    <span class="text-sm text-primary/60">Đang tải...</span>
                </div>
            </div>
        </div>
    </div>
</div>
