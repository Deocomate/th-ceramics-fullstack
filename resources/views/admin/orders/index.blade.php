<x-admin.layout.app title="Quản lý đơn hàng">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-sm font-semibold text-gray-700">Danh sách đơn hàng</h2>
            <p class="text-xs text-gray-400 mt-0.5">Tổng cộng {{ $orders->total() }} đơn hàng</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Mã ĐH</th>
                        <th class="px-4 py-3 font-semibold">Khách hàng</th>
                        <th class="px-4 py-3 font-semibold">Tổng tiền</th>
                        <th class="px-4 py-3 font-semibold">Trạng thái</th>
                        <th class="px-4 py-3 font-semibold">Thanh toán</th>
                        <th class="px-4 py-3 font-semibold">Ngày đặt</th>
                        <th class="px-4 py-3 font-semibold text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-gray-800 hover:text-[#A31D1D] transition-colors">
                                    {{ $order->order_code }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                <div class="font-medium">{{ $order->customer_name }}</div>
                                <div class="text-xs text-gray-400">{{ $order->phone }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 font-medium">
                                {{ number_format($order->total_amount, 0, ',', '.') }}đ
                            </td>
                            <td class="px-4 py-3">
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
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs uppercase">
                                {{ $order->payment_method === 'cod' ? 'COD' : 'Chuyển khoản' }}
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white rounded-lg transition-colors duration-200"
                                   style="background:#A31D1D;">
                                    Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">
                                Chưa có đơn hàng nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</x-admin.layout.app>