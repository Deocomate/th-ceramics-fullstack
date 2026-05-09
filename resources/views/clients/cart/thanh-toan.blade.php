<x-layouts.client title="Thanh toán" data-page="checkout" main-class="bg-background-secondary min-h-screen pt-12 pb-4 md:pt-10 md:pb-10">

<div class="w-[85%] max-w-[1320px] mx-auto pb-8 md:pb-16">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-primary mb-6 md:mb-8 font-archivo">
        <a href="{{ route('client.home') }}" class="hover:text-secondary transition-colors">Trang chủ</a>
        <span class="text-xs">&gt;</span>
        <a href="{{ route('client.cart.index') }}" class="hover:text-secondary transition-colors">Giỏ hàng</a>
        <span class="text-xs">&gt;</span>
        <span class="text-primary font-medium underline underline-offset-4">Thanh toán</span>
    </nav>

    <h1 class="text-[36px] font-arima text-primary mb-8 md:mb-10 leading-tight">Thanh toán</h1>

    @if ($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md text-red-700 text-sm font-archivo">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('client.cart.checkout.process') }}" method="POST">
        @csrf

        <div class="flex flex-col lg:flex-row gap-6 md:gap-12 items-start font-archivo">
            <!-- Left: Checkout Forms -->
            <div class="w-full lg:w-[55%] space-y-10 md:space-y-8">
                <!-- Shipping Address -->
                <section class="space-y-4 md:space-y-4">
                    <h2 class="text-base font-bold text-primary uppercase tracking-[0.8px] font-archivo">Địa chỉ giao hàng</h2>
                    <div class="space-y-4">
                        <input type="text" name="customer_name" placeholder="Họ và tên" value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                            class="w-full h-[54px] bg-white border border-gray-200 rounded-md px-5 text-sm focus:outline-none focus:border-secondary transition-colors placeholder:text-[#9CA3AF] font-archivo" required>

                        <input type="tel" name="phone" placeholder="Số điện thoại" value="{{ old('phone', auth()->user()->phone ?? '') }}"
                            class="w-full h-[54px] bg-white border border-gray-200 rounded-md px-5 text-sm focus:outline-none focus:border-secondary transition-colors placeholder:text-[#9CA3AF] font-archivo" required>

                        <input type="email" name="email" placeholder="Email" value="{{ old('email', auth()->user()->email ?? '') }}"
                            class="w-full h-[54px] bg-white border border-gray-200 rounded-md px-5 text-sm focus:outline-none focus:border-secondary transition-colors placeholder:text-[#9CA3AF] font-archivo">

                        <textarea name="address" placeholder="Địa chỉ đầy đủ (Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố)"
                            class="w-full h-[120px] bg-white border border-gray-200 rounded-md px-5 py-4 text-sm focus:outline-none focus:border-secondary transition-colors placeholder:text-[#9CA3AF] font-archivo resize-none" required>{{ old('address') }}</textarea>
                    </div>
                </section>

                <!-- Note -->
                <section class="space-y-4">
                    <h2 class="text-base font-bold text-primary uppercase tracking-[0.8px] font-archivo">Ghi chú</h2>
                    <textarea name="note" placeholder="Ghi chú đơn hàng (không bắt buộc)"
                        class="w-full h-[100px] bg-white border border-gray-200 rounded-md px-5 py-4 text-sm focus:outline-none focus:border-secondary transition-colors placeholder:text-[#9CA3AF] font-archivo resize-none">{{ old('note') }}</textarea>
                </section>

                <!-- Payment Method -->
                <section class="space-y-4">
                    <h2 class="text-base font-bold text-primary uppercase tracking-[0.8px] font-archivo">Phương thức thanh toán</h2>
                    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
                        <label class="flex items-center gap-4 px-6 h-[61px] cursor-pointer hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0 group @if(old('payment_method', 'cod') === 'banking') bg-gray-50/30 @endif">
                            <input type="radio" name="payment_method" value="banking" class="w-5 h-5 accent-secondary" @if(old('payment_method') === 'banking') checked @endif>
                            <span class="text-sm font-medium text-primary font-archivo">Chuyển khoản ngân hàng</span>
                        </label>
                        <label class="flex items-center gap-4 px-6 h-[61px] cursor-pointer hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0 group @if(old('payment_method', 'cod') === 'cod') bg-gray-50/30 @endif">
                            <input type="radio" name="payment_method" value="cod" class="w-5 h-5 accent-secondary" @if(old('payment_method', 'cod') === 'cod') checked @endif>
                            <span class="text-sm font-medium text-primary font-archivo">Thanh toán khi nhận hàng (COD)</span>
                        </label>
                    </div>
                </section>

                <div class="flex justify-center pt-2">
                    <button type="submit"
                        class="w-full h-[52px] md:h-auto lg:w-[320px] bg-secondary text-white py-4 text-sm font-bold uppercase tracking-[1.4px] hover:bg-opacity-90 transition-all rounded-md shadow-md font-archivo">
                        Hoàn tất đơn hàng
                    </button>
                </div>
            </div>

            <!-- Right: Order Summary Card -->
            <div class="w-full lg:w-[45%] mt-0">
                <div class="bg-[#FEF9F5] border border-gray-300 rounded-md p-6 lg:p-10 shadow-[0_4px_6px_-4px_rgba(0,0,0,0.1),0_10px_15px_-3px_rgba(0,0,0,0.1)] relative">
                    <div class="space-y-6 md:space-y-8">
                        @foreach($cartItems as $item)
                        <div class="flex items-start gap-5 lg:gap-6 pt-2 @if(!$loop->first) border-t border-gray-100 md:border-none @endif">
                            <div class="relative w-[80px] h-[80px] lg:w-24 lg:h-24 flex-shrink-0 border border-gray-300 rounded-md bg-white p-2">
                                <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('assets/images/ngoi-van-mieu-detail-2.png') }}" alt="{{ $item['name'] }}"
                                    class="w-full h-full object-contain">
                                <span class="absolute -top-1.5 md:-top-3 -right-1.5 md:-right-3 bg-[#C76E00] text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-[2px] z-10 font-archivo">{{ $item['quantity'] }}</span>
                            </div>
                            <div class="flex-1 flex justify-between h-full py-0.5">
                                <div class="space-y-0.5 md:space-y-1">
                                    <h4 class="text-sm lg:text-base font-bold text-primary font-archivo leading-tight md:leading-normal">{{ $item['name'] }}</h4>
                                    @if($item['sku'])<p class="text-[12px] lg:text-xs text-primary/40 uppercase font-archivo">MSP: {{ $item['sku'] }}</p>@endif
                                    @if($item['variant_name'])<p class="text-[12px] lg:text-xs text-primary/40 font-archivo">Phân loại: {{ $item['variant_name'] }}</p>@endif
                                </div>
                                <div class="text-right flex flex-col md:justify-start justify-end">
                                    <p class="text-[10px] font-bold uppercase text-primary mb-1 font-archivo">Tổng</p>
                                    <p class="text-sm lg:text-base font-medium text-primary font-archivo">{{ number_format($item['price'] * $item['quantity']) }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Coupon -->
                    <div class="space-y-2 pt-4">
                        <p class="text-[12px] text-primary/40">Nhập mã tại đây</p>
                        <div class="flex">
                            <input type="text" placeholder="Mã ưu đãi"
                                class="flex-1 bg-[#FEF9F5] border border-secondary border-r-0 px-4 py-3 text-sm focus:outline-none">
                            <button type="button"
                                class="bg-secondary text-white px-6 py-3 text-xs font-bold uppercase hover:bg-opacity-90 transition-all">Áp dụng</button>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="mt-8 pt-8 border-t border-gray-300 space-y-2 md:space-y-2">
                        <div class="flex justify-between text-xs lg:text-sm font-archivo">
                            <span class="text-primary">Tạm tính</span>
                            <span class="text-primary font-medium">{{ number_format($total) }} đ</span>
                        </div>
                        <div class="flex justify-between text-xs lg:text-sm font-archivo">
                            <span class="text-primary">Phí vận chuyển</span>
                            <span class="text-primary font-medium">MIỄN PHÍ</span>
                        </div>
                        <div class="flex justify-between items-baseline pt-4 font-archivo">
                            <span class="text-sm lg:text-base font-bold text-primary">Tổng</span>
                            <span class="text-sm lg:text-base font-bold text-primary tracking-tight">{{ number_format($total) }} đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<x-faq-faq-contact />

</x-layouts.client>
