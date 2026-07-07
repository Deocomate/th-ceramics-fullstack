<x-admin.layouts.app title="Yêu cầu tư vấn">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-sm font-semibold text-gray-700">Danh sách yêu cầu tư vấn</h2>
            <p class="text-xs text-gray-400 mt-0.5">
                Tổng cộng {{ $requests->total() }} yêu cầu
                @if ($pendingCount > 0)
                    · <span class="text-[#A31D1D] font-medium">{{ $pendingCount }} chờ xử lý</span>
                @endif
            </p>
        </div>
    </div>

    <div class="flex gap-2 mb-4">
        @foreach ([null => 'Tất cả', 'pending' => 'Chờ xử lý', 'processed' => 'Đã xử lý'] as $value => $label)
            <a href="{{ route('admin.consultation-requests.index', $value ? ['status' => $value] : []) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ ($status ?? null) === $value ? 'bg-[#A31D1D] text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Thời gian</th>
                        <th class="px-4 py-3 font-semibold">Sản phẩm</th>
                        <th class="px-4 py-3 font-semibold">Khách hàng</th>
                        <th class="px-4 py-3 font-semibold">Liên hệ</th>
                        <th class="px-4 py-3 font-semibold">Trạng thái</th>
                        <th class="px-4 py-3 font-semibold text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $request)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                                {{ $request->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                <div class="font-medium">{{ $request->product_name ?: '—' }}</div>
                                @if ($request->variant_name)
                                    <div class="text-xs text-gray-400">{{ $request->variant_name }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-700 font-medium">{{ $request->customer_name }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                <div>{{ $request->phone }}</div>
                                @if ($request->email)
                                    <div class="text-xs text-gray-400">{{ $request->email }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $request->status === 'processed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ \App\Models\ConsultationRequest::statusLabel($request->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.consultation-requests.show', $request) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white rounded-lg"
                                   style="background:#A31D1D;">
                                    Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">
                                Chưa có yêu cầu tư vấn nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</x-admin.layouts.app>
