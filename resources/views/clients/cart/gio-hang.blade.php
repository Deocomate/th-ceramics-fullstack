@inject('cartService', 'App\Services\CartService')
@php
    $subtotal = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
    $currentCoupon = $cartService->getCouponCode();
    $currentDiscount = $cartService->getDiscountAmount();
@endphp

<x-client.layouts.main title="Giỏ hàng" data-page="cart" main-class="bg-background-secondary min-h-screen pt-12 pb-4 md:pt-10 md:pb-10">

<div class="w-[85%] max-w-[1320px] mx-auto pb-12 md:pb-16">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-primary mb-8 font-archivo">
        <a href="{{ route('client.home') }}" class="hover:text-secondary transition-colors">Trang chủ</a>
        <span>&gt;</span>
        <span class="text-primary font-medium underline">Giỏ hàng</span>
    </nav>

    <h1 class="text-[36px] font-arima text-primary mb-8 leading-tight">Giỏ hàng</h1>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <!-- Left: Cart Items -->
        <div class="w-full lg:w-2/3 bg-white md:bg-[#FEF9F5] md:border border-gray-300 rounded-md shadow-lg md:shadow-lg p-4 md:p-6 lg:p-10 font-archivo">
            <div class="space-y-6 md:space-y-8">
                @forelse($cartItems as $item)
                <div class="relative pb-6 md:pt-8 border-b md:border-b-0 md:border-t border-gray-300 last:border-0" data-row-id="{{ $item['row_id'] }}">
                    <div class="flex gap-4 md:gap-6">
                        <div class="w-20 h-20 lg:w-28 lg:h-28 flex-shrink-0 border border-gray-300 rounded-md overflow-hidden">
                            <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('assets/images/ngoi-van-mieu-detail-2.png') }}" alt="{{ $item['name'] }}"
                                class="w-full h-full object-contain bg-white">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col md:flex-row justify-between gap-2 md:gap-4 h-full">
                                <div class="flex flex-col justify-between">
                                    <div class="space-y-0.5 md:space-y-1">
                                        <h3 class="text-sm md:text-lg lg:text-xl font-bold text-primary md:leading-normal leading-tight">{{ $item['name'] }}</h3>
                                        @if($item['sku'])<p class="text-[11px] md:text-[13px] text-primary/40 uppercase">MSP: {{ $item['sku'] }}</p>@endif
                                        @if($item['variant_name'])<p class="text-[11px] md:text-[13px] text-primary/40">Phân loại: {{ $item['variant_name'] }}</p>@endif
                                        <div class="flex items-center gap-1 md:hidden">
                                            <span class="text-[11px] text-primary">Đơn giá:</span>
                                            <span class="text-[11px] font-semibold text-primary">{{ number_format($item['price'], 0, ',', '.') }} đ</span>
                                        </div>
                                        <p class="text-[10px] lg:text-[13px] text-primary/40 hidden md:block">x{{ $item['quantity'] }}</p>
                                    </div>
                                    <div class="hidden md:flex items-center gap-4 mt-4 text-[12px] font-bold">
                                        <button class="text-primary/40 hover:text-secondary transition-colors remove-item-btn" data-id="{{ $item['row_id'] }}">Xóa</button>
                                    </div>
                                </div>

                                <div class="hidden md:flex items-start gap-8 mt-4 md:mt-0">
                                    <div class="space-y-2 text-center md:text-left">
                                        <p class="text-[10px] lg:text-xs font-bold uppercase text-primary">Đơn giá</p>
                                        <p class="text-[13px] lg:text-base">{{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="space-y-2 text-center md:text-left">
                                        <p class="text-[10px] lg:text-xs font-bold uppercase text-primary">Số lượng</p>
                                        <div class="flex items-center border border-gray-300 rounded bg-[#FEF9F5] overflow-hidden">
                                            <button class="px-2 py-1 text-primary/40 hover:text-secondary hover:bg-gray-100 transition-colors border-r border-gray-300 qty-decrease">-</button>
                                            <input type="text" value="{{ $item['quantity'] }}" class="w-10 text-center text-sm bg-transparent focus:outline-none qty-input" data-id="{{ $item['row_id'] }}">
                                            <button class="px-2 py-1 text-primary/40 hover:text-secondary hover:bg-gray-100 transition-colors border-l border-gray-300 qty-increase">+</button>
                                        </div>
                                    </div>
                                    <div class="space-y-2 text-right">
                                        <p class="text-[10px] lg:text-xs font-bold uppercase text-primary">Tổng</p>
                                        <p class="text-[13px] lg:text-base font-bold item-total">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Quantity & Actions -->
                    <div class="flex md:hidden flex-col items-end gap-3 mt-[-55px]">
                        <div class="flex items-center justify-center rounded overflow-hidden h-[23px] gap-1">
                            <button class="w-[18px] h-full flex items-center justify-center text-primary/40 text-xl qty-decrease">-</button>
                            <input type="text" value="{{ $item['quantity'] }}" class="w-10 text-center text-xs bg-[#FEF9F5] border-gray-300 border rounded py-[2px] qty-input" data-id="{{ $item['row_id'] }}">
                            <button class="w-[18px] h-full flex items-center justify-center text-primary/40 text-xl qty-increase">+</button>
                        </div>
                        <div class="flex items-center gap-4 text-xs font-bold">
                            <button class="text-primary/40 remove-item-btn" data-id="{{ $item['row_id'] }}">Xóa</button>
                        </div>
                    </div>

                    <!-- Mobile Subtotal -->
                    <div class="flex md:hidden items-center justify-between mt-4">
                        <span class="text-[10px] font-bold uppercase text-primary">Tổng</span>
                        <span class="text-[13px] font-bold text-black item-total">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <p class="text-lg text-primary/60">Giỏ hàng trống.</p>
                    <a href="{{ route('client.products.ngoi-am-duong.index') }}" class="text-secondary underline mt-4 inline-block">Tiếp tục mua sắm</a>
                </div>
                @endforelse
            </div>

            <!-- Cart Footer Summary -->
            <div class="mt-4 md:mt-8 pt-6 border-t border-gray-300 flex justify-between items-center md:items-start">
                <div>
                    <span class="text-sm lg:text-base font-bold text-primary cart-count">Số lượng: {{ count($cartItems) }}</span>
                </div>
                <div class="text-right flex flex-col items-end">
                    <p class="text-base md:text-sm lg:text-base font-bold text-primary cart-total-amount">{{ number_format($total, 0, ',', '.') }}</p>
                    <p class="text-xs lg:text-sm font-bold text-primary leading-tight">VND</p>
                </div>
            </div>
        </div>

        <!-- Right: Summary & Checkout -->
        <div class="w-full lg:w-1/3 space-y-6 mb-10 md:mb-0">
            <div class="bg-[#FEF9F5] border border-gray-300 rounded-md p-6 md:p-8 font-archivo shadow-lg relative">
                <div class="space-y-6">
                    
                    <!-- Coupon Design -->
                    <div class="space-y-2 pt-2">
                        <p class="text-[12px] text-primary/40 font-archivo">Nhập mã tại đây</p>
                        <div class="flex" id="coupon-input-area">
                            <input type="text" id="coupon-code" 
                                   placeholder="Mã ưu đãi" 
                                   value="{{ $currentCoupon ?? '' }}"
                                   class="flex-1 bg-[#FEF9F5] border border-secondary border-r-0 px-4 py-3 text-sm focus:outline-none uppercase font-archivo">
                            <button type="button" id="apply-coupon-btn" 
                                    class="bg-secondary text-white px-6 py-3 text-xs font-bold uppercase hover:bg-opacity-90 transition-all font-archivo whitespace-nowrap">
                                Áp dụng
                            </button>
                        </div>
                        
                        <p id="coupon-message" class="text-sm hidden font-archivo pt-1"></p>

                        <!-- Discount row display -->
                        <div id="coupon-discount-row" class="pt-2 {{ ($currentCoupon ?? false) ? '' : 'hidden' }}">
                            <div class="flex justify-between items-center text-sm font-archivo bg-secondary/5 border border-secondary/20 p-3">
                                <span class="text-[#C76E00] font-bold uppercase tracking-[0.5px] text-xs">Ưu đãi giảm giá:</span>
                                <div class="flex items-center gap-4">
                                    <span id="discount-amount" class="text-[#C76E00] font-bold">
                                        -{{ number_format($currentDiscount ?? 0, 0, ',', '.') }} đ
                                    </span>
                                    <button id="remove-coupon-btn" type="button"
                                            class="text-gray-400 hover:text-red-500 text-[11px] underline font-archivo uppercase">
                                        Xóa mã
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="space-y-4 pt-6 border-t border-gray-300">
                        <div class="flex justify-between text-sm">
                            <span class="text-primary/60">Tạm tính:</span>
                            <span class="text-primary font-medium" id="subtotal-amount">{{ number_format($subtotal, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="flex justify-between items-baseline pt-2">
                            <p class="text-xl lg:text-2xl font-bold text-primary leading-none">Tổng:</p>
                            <div class="text-right">
                                <p class="text-xl lg:text-2xl font-bold text-primary leading-none cart-total-amount">{{ number_format($total, 0, ',', '.') }}</p>
                                <p class="text-[10px] lg:text-xs font-bold text-primary mt-1">VND</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <a href="{{ route('client.cart.checkout') }}"
                            class="inline-block text-center w-full bg-secondary text-white py-4 text-sm font-bold uppercase tracking-[1px] hover:bg-opacity-90 transition-all mt-2 rounded-sm shadow-sm">
                            Thanh toán
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-client.shared.recommendations :related-products="collect()" />
<x-client.shared.faq-contact />

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    
    // --- Coupon Elements ---
    const couponInput = document.getElementById('coupon-code');
    const applyBtn = document.getElementById('apply-coupon-btn');
    const removeBtn = document.getElementById('remove-coupon-btn');
    const couponMessage = document.getElementById('coupon-message');
    const discountRow = document.getElementById('coupon-discount-row');
    const discountAmount = document.getElementById('discount-amount');

    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount);
    }

    // --- Qty & Remove Logic ---
    function checkAndReloadIfCouponActive() {
        // Nếu có mã giảm giá đang active, reload trang để Backend tự check lại điều kiện (min_order_value)
        const hasActiveCoupon = !discountRow.classList.contains('hidden');
        if(hasActiveCoupon) {
            window.location.reload();
            return true;
        }
        return false;
    }

    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            const rowId = this.dataset.id;
            const qty = parseInt(this.value);
            if (qty < 1 || isNaN(qty)) { this.value = 1; return; }

            fetch('{{ route("client.cart.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ row_id: rowId, qty: qty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if(checkAndReloadIfCouponActive()) return;

                    const row = document.querySelector(`[data-row-id="${rowId}"]`);
                    if (row) {
                        row.querySelectorAll('.item-total').forEach(el => {
                            el.textContent = formatVND(data.item_total);
                        });
                    }
                    document.querySelectorAll('.cart-total-amount').forEach(el => {
                        el.textContent = formatVND(data.cart_total);
                    });
                    document.getElementById('subtotal-amount').textContent = formatVND(data.cart_total) + ' đ';
                }
            });
        });
    });

    document.querySelectorAll('.qty-decrease').forEach(btn => {
        btn.addEventListener('click', function() {
            const container = this.closest('[data-row-id]') || this.parentElement.parentElement;
            const input = container.querySelector('.qty-input') || this.parentElement.querySelector('.qty-input');
            if (input) {
                input.value = Math.max(1, parseInt(input.value) - 1);
                input.dispatchEvent(new Event('change'));
            }
        });
    });

    document.querySelectorAll('.qty-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            const container = this.closest('[data-row-id]') || this.parentElement.parentElement;
            const input = container.querySelector('.qty-input') || this.parentElement.querySelector('.qty-input');
            if (input) {
                input.value = parseInt(input.value) + 1;
                input.dispatchEvent(new Event('change'));
            }
        });
    });

    document.querySelectorAll('.remove-item-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const rowId = this.dataset.id;
            if (!confirm('Xóa sản phẩm khỏi giỏ hàng?')) return;

            fetch('{{ route("client.cart.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ row_id: rowId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if(checkAndReloadIfCouponActive()) return;

                    const row = document.querySelector(`[data-row-id="${rowId}"]`);
                    if (row) row.remove();
                    
                    document.querySelectorAll('.cart-total-amount').forEach(el => {
                        el.textContent = formatVND(data.cart_total);
                    });
                    document.getElementById('subtotal-amount').textContent = formatVND(data.cart_total) + ' đ';
                    
                    const cartCountEl = document.querySelector('.cart-count');
                    if (cartCountEl) cartCountEl.textContent = 'Số lượng: ' + data.cart_count;
                    if (data.cart_count === 0) location.reload();
                }
            });
        });
    });

    // --- Coupon Logic ---
    function showCouponMessage(msg, type) {
        couponMessage.textContent = msg;
        couponMessage.className = 'text-sm font-archivo pt-1 ' + (type === 'error' ? 'text-red-600' : 'text-[#C76E00]');
        couponMessage.classList.remove('hidden');
    }

    async function handleApplyCoupon() {
        const code = couponInput.value.trim();
        if (!code) {
            showCouponMessage('Vui lòng nhập mã giảm giá.', 'error');
            return;
        }

        applyBtn.disabled = true;
        applyBtn.textContent = 'ĐANG XỬ LÝ...';

        try {
            const res = await fetch('{{ route("client.cart.coupon.apply") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ code }),
            });

            const data = await res.json();

            if (data.success) {
                // Tải lại trang luôn để ăn khớp tất cả các biến subtotal, total tránh chênh lệch DOM
                window.location.reload();
            } else {
                showCouponMessage(data.message, 'error');
                applyBtn.textContent = 'ÁP DỤNG';
            }
        } catch (err) {
            showCouponMessage('Có lỗi xảy ra, vui lòng thử lại.', 'error');
            applyBtn.textContent = 'ÁP DỤNG';
        } finally {
            applyBtn.disabled = false;
        }
    }

    async function handleRemoveCoupon() {
        try {
            const res = await fetch('{{ route("client.cart.coupon.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            const data = await res.json();

            if (data.success) {
                // Reload trang khi xóa mã thành công
                window.location.reload();
            }
        } catch (err) {
            showCouponMessage('Có lỗi xảy ra, vui lòng thử lại.', 'error');
        }
    }

    if (applyBtn) applyBtn.addEventListener('click', handleApplyCoupon);
    if (couponInput) {
        couponInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleApplyCoupon();
            }
        });
        couponInput.addEventListener('input', () => {
            couponInput.value = couponInput.value.toUpperCase();
        });
    }
    if (removeBtn) removeBtn.addEventListener('click', handleRemoveCoupon);
});
</script>

</x-client.layouts.main>
