<x-admin.layouts.app title="Quản lý khách hàng" breadcrumb="Admin › Khách hàng">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-sm font-semibold text-gray-700">Danh sách khách hàng</h2>
            <p class="text-xs text-gray-400 mt-0.5">Tổng cộng {{ $customers->total() }} khách hàng</p>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide w-10">#</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tên</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Số điện thoại</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nguồn</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Xác thực email</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Ngày tạo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50 transition-colors duration-100">
                        <td class="px-5 py-3.5 text-xs text-gray-400">
                            {{ $customers->firstItem() + $loop->index }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                                     style="background:#A31D1D;">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $customer->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $customer->email }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $customer->phone ?: 'Chưa cập nhật' }}</td>
                        <td class="px-5 py-3.5">
                            @if($customer->google_id)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium text-blue-700 bg-blue-50 border border-blue-100">
                                    Google
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium text-gray-700 bg-gray-100 border border-gray-200">
                                    Form Email
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($customer->hasVerifiedEmail())
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium text-green-700 bg-green-50 border border-green-100">
                                    Đã xác thực
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium text-yellow-700 bg-yellow-50 border border-yellow-100">
                                    Chưa xác thực
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-400">
                            {{ $customer->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-400">
                            Chưa có khách hàng nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($customers->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

</x-admin.layouts.app>
