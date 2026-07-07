<x-admin.layouts.app title="Chi tiết yêu cầu tư vấn #{{ $consultationRequest->id }}">

    <div class="flex items-center justify-between mb-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.consultation-requests.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-sm font-semibold text-gray-700">
                Yêu cầu tư vấn <span class="text-gray-400 font-normal">#{{ $consultationRequest->id }}</span>
            </h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Thông tin khách hàng</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400 text-xs">Họ tên</span>
                        <p class="text-gray-800 font-medium">{{ $consultationRequest->customer_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs">Số điện thoại</span>
                        <p class="text-gray-800 font-medium">{{ $consultationRequest->phone }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs">Email</span>
                        <p class="text-gray-800 font-medium">{{ $consultationRequest->email ?: '—' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs">Ngày gửi</span>
                        <p class="text-gray-800 font-medium">{{ $consultationRequest->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if ($consultationRequest->note)
                        <div class="col-span-2">
                            <span class="text-gray-400 text-xs">Ghi chú</span>
                            <p class="text-gray-800 whitespace-pre-line">{{ $consultationRequest->note }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Sản phẩm quan tâm</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400 text-xs">Tên sản phẩm</span>
                        <p class="text-gray-800 font-medium">{{ $consultationRequest->product_name ?: '—' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs">Phân loại</span>
                        <p class="text-gray-800 font-medium">{{ $consultationRequest->variant_name ?: '—' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs">Loại sản phẩm</span>
                        <p class="text-gray-800 font-medium">{{ $consultationRequest->product_type ?: '—' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs">ID sản phẩm (snapshot)</span>
                        <p class="text-gray-800 font-medium">{{ $consultationRequest->product_id ?: '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Trạng thái</h3>
                <p class="mb-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $consultationRequest->status === 'processed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ \App\Models\ConsultationRequest::statusLabel($consultationRequest->status) }}
                    </span>
                </p>
                <form method="POST" action="{{ route('admin.consultation-requests.update-status', $consultationRequest) }}" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2">
                        <option value="pending" @selected($consultationRequest->status === 'pending')>Chờ xử lý</option>
                        <option value="processed" @selected($consultationRequest->status === 'processed')>Đã xử lý</option>
                    </select>
                    <button type="submit" class="w-full px-4 py-2 text-sm font-semibold text-white rounded-lg" style="background:#A31D1D;">
                        Cập nhật trạng thái
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('admin.consultation-requests.destroy', $consultationRequest) }}"
                  onsubmit="return confirm('Bạn có chắc muốn xóa yêu cầu này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 text-sm font-semibold text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100">
                    Xóa yêu cầu
                </button>
            </form>
        </div>
    </div>
</x-admin.layouts.app>
