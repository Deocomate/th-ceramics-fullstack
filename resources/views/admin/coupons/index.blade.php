<x-admin.layout.app title="Quản lý mã giảm giá">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-sm font-semibold text-gray-700">Danh sách mã giảm giá</h2>
            <p class="text-xs text-gray-400 mt-0.5">Tổng cộng {{ $coupons->total() }} mã đang hoạt động</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg transition-colors duration-200"
           style="background:#A31D1D;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Thêm mã mới
        </a>
    </div>

    {{-- Active Coupons Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Mã giảm giá đang hoạt động</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 font-semibold">ID</th>
                        <th class="px-4 py-3 font-semibold">Tiêu đề</th>
                        <th class="px-4 py-3 font-semibold">Mã</th>
                        <th class="px-4 py-3 font-semibold">Loại giảm</th>
                        <th class="px-4 py-3 font-semibold">Giá trị</th>
                        <th class="px-4 py-3 font-semibold">Lượt dùng</th>
                        <th class="px-4 py-3 font-semibold">Thời gian</th>
                        <th class="px-4 py-3 font-semibold">Banner</th>
                        <th class="px-4 py-3 font-semibold">Kích hoạt</th>
                        <th class="px-4 py-3 font-semibold text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $coupon->id }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ $coupon->title }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-indigo-100 text-indigo-700 font-mono">
                                    {{ $coupon->code }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($coupon->discount_type === 'percent')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">%</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">VNĐ</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                @if($coupon->discount_type === 'percent')
                                    {{ $coupon->discount_value }}%
                                @else
                                    {{ number_format($coupon->discount_value, 0, ',', '.') }}đ
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                @if($coupon->usage_limit)
                                    {{ $coupon->used_count }} / {{ $coupon->usage_limit }}
                                @else
                                    <span class="text-gray-400">Không giới hạn</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">
                                <div>{{ $coupon->start_date->format('d/m/Y H:i') }}</div>
                                @if($coupon->end_date)
                                    <div class="text-gray-400">→ {{ $coupon->end_date->format('d/m/Y H:i') }}</div>
                                @else
                                    <div class="text-gray-400">→ Không giới hạn</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($coupon->show_banner && $coupon->banner_image)
                                    <span class="text-green-600 text-xs font-medium">Có</span>
                                @else
                                    <span class="text-gray-400 text-xs">Không</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($coupon->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Có</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Không</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Sửa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-gray-500">Không có dữ liệu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($coupons->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $coupons->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- Deleted Coupons Section --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wide">Đã xóa gần đây</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 text-gray-400 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 font-semibold">ID</th>
                        <th class="px-4 py-3 font-semibold">Tiêu đề</th>
                        <th class="px-4 py-3 font-semibold">Mã</th>
                        <th class="px-4 py-3 font-semibold">Ngày xóa</th>
                        <th class="px-4 py-3 font-semibold text-right">Khôi phục</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($deletedCoupons as $coupon)
                        <tr class="hover:bg-gray-50/50 transition-colors opacity-60">
                            <td class="px-4 py-3 text-gray-400 font-mono text-xs">{{ $coupon->id }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $coupon->title }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-gray-100 text-gray-500 font-mono">
                                    {{ $coupon->code }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $coupon->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('admin.coupons.restore', $coupon->id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition-colors"
                                            title="Khôi phục">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Khôi phục
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">Không có dữ liệu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($deletedCoupons->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $deletedCoupons->withQueryString()->links() }}
            </div>
        @endif
    </div>

</x-admin.layout.app>
