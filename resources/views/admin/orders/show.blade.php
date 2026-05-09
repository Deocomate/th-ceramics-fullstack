<x-admin.layout.app title="Chi tiết đơn hàng #{{ $order->order_code }}">

    <div class="flex items-center justify-between mb-5">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h2 class="text-sm font-semibold text-gray-700">Chi tiết đơn hàng <span class="text-gray-400 font-normal">#{{ $order->order_code }}</span></h2>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Customer + Items --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Customer Info --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Thông tin khách hàng</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400 text-xs">Họ tên</span>
                        <p class="text-gray-800 font-medium">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs">Số điện thoại</span>
                        <p class="text-gray-800 font-medium">{{ $order->phone }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs">Email</span>
                        <p class="text-gray-800 font-medium">{{ $order->email ?: '—' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs">Tài khoản</span>
                        <p class="text-gray-800 font-medium">{{ $order->user?->name ?? 'Khách vãng lai' }}</p>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-400 text-xs">Địa chỉ</span>
                        <p class="text-gray-800 font-medium">{{ $order->address }}</p>
                    </div>
                    @if($order->note)
                    <div class="col-span-2">
                        <span class="text-gray-400 text-xs">Ghi chú</span>
                        <p class="text-gray-800">{{ $order->note }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Sản phẩm ({{ $order->items->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Sản phẩm</th>
                                <th class="px-4 py-3 font-semibold">SKU</th>
                                <th class="px-4 py-3 font-semibold">Phân loại</th>
                                <th class="px-4 py-3 font-semibold text-center">SL</th>
                                <th class="px-4 py-3 font-semibold text-right">Đơn giá</th>
                                <th class="px-4 py-3 font-semibold text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-4 py-3 text-gray-800 font-medium">{{ $item->product_name }}</td>
                                <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $item->sku ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $item->variant_name ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-700 text-center">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-gray-700 text-right">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                <td class="px-4 py-3 text-gray-700 text-right font-medium">{{ number_format($item->total, 0, ',', '.') }}đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end">
                    <div class="text-sm space-y-1 text-right">
                        <div class="flex justify-between gap-8 text-gray-500">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        @if($order->discount > 0)
                        <div class="flex justify-between gap-8 text-[#C76E00]">
                            <span>Giảm giá:</span>
                            <span>-{{ number_format($order->discount, 0, ',', '.') }}đ</span>
                        </div>
                        @endif
                        <div class="flex justify-between gap-8 text-gray-500">
                            <span>Phí vận chuyển:</span>
                            <span>{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . 'đ' : 'Miễn phí' }}</span>
                        </div>
                        <div class="flex justify-between gap-8 font-bold text-gray-800 pt-2 border-t border-gray-200">
                            <span>Tổng cộng:</span>
                            <span>{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Order Summary + Status Update --}}
        <div class="space-y-6">
            {{-- Order Summary --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Thông tin đơn hàng</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Mã đơn</span>
                        <span class="text-gray-800 font-mono font-medium">{{ $order->order_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Ngày đặt</span>
                        <span class="text-gray-800">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Thanh toán</span>
                        <span class="text-gray-800 uppercase text-xs font-medium">{{ $order->payment_method === 'cod' ? 'COD' : 'Chuyển khoản' }}</span>
                    </div>
                    @if($order->coupon_code)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Mã giảm giá</span>
                        <span class="text-[#C76E00] font-mono font-medium">{{ $order->coupon_code }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between pt-2 border-t border-gray-100">
                        <span class="text-gray-400">Trạng thái</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ match($order->status) {
                            'pending_payment' => 'bg-yellow-100 text-yellow-800',
                            'processing' => 'bg-blue-100 text-blue-800',
                            'shipping' => 'bg-indigo-100 text-indigo-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'canceled' => 'bg-red-100 text-red-800',
                            'returned' => 'bg-orange-100 text-orange-800',
                            default => 'bg-gray-100 text-gray-800',
                        } }}">
                            {{ \App\Models\Order::statusLabel($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Status Update Form --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Cập nhật trạng thái</h3>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="status"
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#A31D1D]/20 focus:border-[#A31D1D] bg-white text-gray-700 mb-3">
                        @foreach(['pending_payment','processing','shipping','completed','canceled','returned'] as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>
                                {{ \App\Models\Order::statusLabel($status) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="w-full py-2.5 text-sm font-semibold text-white rounded-lg transition-colors duration-200 hover:opacity-90"
                            style="background:#A31D1D;">
                        Cập nhật trạng thái
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-admin.layout.app>
