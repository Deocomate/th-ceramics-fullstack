@props(['count' => 0, 'iconClass' => 'w-5 h-5'])

<div class="relative" data-mini-cart>
    <button type="button" data-mini-cart-toggle
        class="hover:text-secondary transition-colors relative flex items-center justify-center"
        aria-label="Giỏ hàng" aria-expanded="false">
        <img src="{{ asset('assets/images/cart-2.svg') }}" alt="" class="{{ $iconClass }}" />
        <span data-mini-cart-badge
            class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full bg-secondary text-white text-[10px] font-bold leading-none {{ ($count ?? 0) > 0 ? '' : 'hidden' }}">{{ $count ?? 0 }}</span>
    </button>

    <div data-mini-cart-panel
        class="hidden absolute right-0 top-full mt-2 w-[320px] max-w-[calc(100vw-2rem)] bg-white text-primary shadow-xl rounded-sm border border-neutral-1 z-[70]">
        <div class="px-4 py-3 border-b border-neutral-1 font-bold text-sm uppercase tracking-wide">
            Giỏ hàng
        </div>
        <div data-mini-cart-items class="max-h-[60vh] overflow-y-auto divide-y divide-neutral-1"></div>
        <div data-mini-cart-empty class="hidden px-4 py-8 text-center text-sm text-primary/60">
            Giỏ hàng trống
        </div>
        <div data-mini-cart-footer class="border-t border-neutral-1 px-4 py-3">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-primary/70">Tạm tính</span>
                <span data-mini-cart-subtotal class="font-bold text-secondary">0 đ</span>
            </div>
            <a href="{{ route('client.cart.index') }}"
                class="block w-full text-center py-2.5 bg-secondary text-white font-bold text-sm uppercase rounded-sm hover:bg-secondary/90 transition-colors">
                Xem giỏ hàng
            </a>
        </div>
    </div>
</div>
