<x-admin.layout.app title="Tin Tức" breadcrumb="Admin › Cấu hình trang đơn › Tin Tức">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-sm font-semibold text-gray-700">Danh sách Bài Viết</h2>
            <p class="text-xs text-gray-400 mt-0.5">Tổng cộng {{ $tinTucs->total() }} bài viết</p>
        </div>
        
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('admin.tin-tuc.index') }}" class="flex items-center">
                <select name="danh_muc_id" onchange="this.form.submit()" class="px-3 py-2 text-sm border border-gray-300 rounded-lg outline-none focus:border-[#A31D1D] bg-white">
                    <option value="">Tất cả danh mục</option>
                    @foreach($danhMucs as $dm)
                        <option value="{{ $dm->danh_muc_tin_tuc_id }}" {{ $danhMucId == $dm->danh_muc_tin_tuc_id ? 'selected' : '' }}>
                            {{ $dm->ten_danh_muc }}
                        </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('admin.tin-tuc.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg transition-colors duration-200" style="background:#A31D1D;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Viết Bài Mới
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-2/5">Tiêu đề bài viết</th>
                        <th class="px-6 py-4 font-semibold">Danh mục</th>
                        <th class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                        <th class="px-6 py-4 font-semibold text-center">Ngày đăng</th>
                        <th class="px-6 py-4 font-semibold text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tinTucs as $post)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                        <img src="{{ asset('storage/' . $post->anh_dai_dien) }}" class="w-full h-full object-contain">
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800 text-sm mb-1 line-clamp-2" title="{{ $post->tieu_de }}">{{ $post->tieu_de }}</div>
                                        <div class="text-xs text-gray-500 font-mono">Slug: {{ $post->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-[#A31D1D]">
                                {{ $post->danhMuc->ten_danh_muc ?? 'N/A' }}
                                @if($post->the_loai)
                                    <span class="block text-xs font-normal text-gray-500 mt-1">Tags: {{ $post->the_loai }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($post->trang_thai === 'published')
                                    <span class="px-2.5 py-1 bg-green-100 text-green-700 border border-green-200 rounded text-xs font-bold">Đã Đăng</span>
                                @else
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-700 border border-gray-200 rounded text-xs font-bold">Bản Nháp</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-gray-500 font-medium">
                                {{ $post->ngay_dang ? \Carbon\Carbon::parse($post->ngay_dang)->format('d/m/Y H:i') : '--' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.tin-tuc.edit', $post->tin_tuc_id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Sửa">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.tin-tuc.destroy', $post->tin_tuc_id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn bài viết này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Xóa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">Chưa có bài viết nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PHÂN TRANG --}}
        @if($tinTucs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $tinTucs->withQueryString()->links() }}
            </div>
        @endif
    </div>
</x-admin.layout.app>