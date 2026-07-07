<div id="consultation-modal"
    class="hidden fixed inset-0 z-[110]"
    aria-hidden="true"
    data-consultation-url="{{ route('client.consultation.store') }}"
    @auth
        data-user-name="{{ auth()->user()->name }}"
        data-user-email="{{ auth()->user()->email }}"
        data-user-phone="{{ auth()->user()->phone }}"
    @endauth>
    <div class="absolute inset-0 bg-black/50" data-consultation-overlay></div>
    <div class="absolute inset-0 flex items-end sm:items-center justify-center p-0 sm:p-4" role="dialog" aria-modal="true"
        aria-labelledby="consultation-modal-title">
        <div class="relative w-full sm:max-w-lg max-h-[92vh] overflow-y-auto bg-white rounded-t-sm sm:rounded-sm shadow-2xl p-6">
            <button type="button"
                class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center rounded-full bg-neutral-2 text-primary hover:text-secondary transition-colors"
                data-consultation-close aria-label="Đóng">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>

            <div data-consultation-form-view>
                <h2 id="consultation-modal-title" class="text-lg font-bold text-primary pr-8 mb-1">Nhận báo giá / Tư vấn</h2>
                <p data-consultation-product-summary class="text-sm text-primary/70 mb-5"></p>

                <form data-consultation-form class="space-y-4">
                    <input type="hidden" name="product_id" data-consultation-product-id>
                    <input type="hidden" name="product_type" data-consultation-product-type>
                    <input type="hidden" name="product_name" data-consultation-product-name>
                    <input type="hidden" name="variant_name" data-consultation-variant-name>

                    <div>
                        <label for="consultation-customer-name" class="block text-xs font-bold uppercase tracking-wide text-primary/50 mb-1">Họ và tên *</label>
                        <input id="consultation-customer-name" name="customer_name" type="text" required
                            class="w-full border border-neutral-1 rounded-sm px-3 py-2 text-sm text-primary focus:outline-none focus:border-secondary"
                            value="{{ auth()->user()?->name }}">
                        <p class="hidden text-xs text-red-600 mt-1" data-consultation-error="customer_name"></p>
                    </div>

                    <div>
                        <label for="consultation-phone" class="block text-xs font-bold uppercase tracking-wide text-primary/50 mb-1">Số điện thoại *</label>
                        <input id="consultation-phone" name="phone" type="tel" required
                            class="w-full border border-neutral-1 rounded-sm px-3 py-2 text-sm text-primary focus:outline-none focus:border-secondary"
                            value="{{ auth()->user()?->phone }}">
                        <p class="hidden text-xs text-red-600 mt-1" data-consultation-error="phone"></p>
                    </div>

                    <div>
                        <label for="consultation-email" class="block text-xs font-bold uppercase tracking-wide text-primary/50 mb-1">Email</label>
                        <input id="consultation-email" name="email" type="email"
                            class="w-full border border-neutral-1 rounded-sm px-3 py-2 text-sm text-primary focus:outline-none focus:border-secondary"
                            value="{{ auth()->user()?->email }}">
                        <p class="hidden text-xs text-red-600 mt-1" data-consultation-error="email"></p>
                    </div>

                    <div>
                        <label for="consultation-note" class="block text-xs font-bold uppercase tracking-wide text-primary/50 mb-1">Ghi chú</label>
                        <textarea id="consultation-note" name="note" rows="3"
                            class="w-full border border-neutral-1 rounded-sm px-3 py-2 text-sm text-primary focus:outline-none focus:border-secondary resize-y"></textarea>
                        <p class="hidden text-xs text-red-600 mt-1" data-consultation-error="note"></p>
                    </div>

                    <p class="hidden text-sm text-red-600" data-consultation-general-error></p>

                    <button type="submit"
                        class="w-full px-6 py-3 bg-secondary text-white font-bold text-sm uppercase rounded-sm hover:bg-secondary/90 transition-colors disabled:opacity-50">
                        Gửi yêu cầu
                    </button>
                </form>
            </div>

            <div data-consultation-success-view class="hidden text-center py-8">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-2xl">✓</div>
                <h3 class="text-lg font-bold text-primary mb-2">Đã gửi yêu cầu!</h3>
                <p data-consultation-success-message class="text-sm text-primary/70"></p>
                <button type="button" data-consultation-close
                    class="mt-6 px-6 py-2 bg-secondary text-white text-sm font-semibold rounded-sm">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>
