@section('preview_url', route('client.projects.detail', $duAn->slug))

<x-admin.layouts.app title="Cập nhật Dự Án" breadcrumb="Admin › Dự Án › Chỉnh sửa">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cập nhật Dự Án: {{ $duAn->ten_du_an }}</h2>
            <span class="text-xs font-mono text-gray-500 bg-gray-200 px-2 py-1 rounded">Slug: {{ $duAn->slug }}</span>
        </div>
        <form method="POST" action="{{ route('admin.du-an.update', $duAn->du_an_id) }}" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')
            @include('admin.content.du-an.partials.form', ['duAn' => $duAn, 'danhMucs' => $danhMucs])
        </form>
    </div>

    <!-- DANH SÁCH ẢNH HIỆN TẠI -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Hình ảnh dự án hiện tại</h2>
        </div>
        <div class="p-6">
            @if(is_array($duAn->images) && count($duAn->images) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($duAn->images as $path)
                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                            <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-contain">
                            @if($loop->first)
                                <div class="absolute top-2 left-2 bg-[#A31D1D] text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">Ảnh bìa</div>
                            @endif
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                <button type="button" onclick="openDeleteImageModal('{{ $path }}')" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">Xóa ảnh này</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm text-center py-6">Dự án này chưa có hình ảnh chi tiết nào.</p>
            @endif
        </div>
    </div>

    {{-- MODAL XÓA ẢNH --}}
    <div id="deleteImageModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center transform scale-95 transition-transform duration-300">
            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2 mt-4">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Ảnh này sẽ bị xóa khỏi danh sách ảnh của dự án.</p>
            <div class="flex justify-center gap-3 mt-6">
                <button type="button" onclick="closeDeleteImageModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Hủy</button>
                <form id="deleteImageForm" method="POST" action="{{ route('admin.du-an.image.destroy', $duAn->du_an_id) }}" class="flex-1">
                    @csrf @method('DELETE')
                    <input type="hidden" name="image_path" id="deleteImagePathInput" value="">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">Có, Xóa</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // ====== LOGIC MODAL XÓA ẢNH ======
        const deleteImageModal = document.getElementById('deleteImageModal');
        const deleteImageModalInner = deleteImageModal ? deleteImageModal.querySelector('.bg-white') : null;

        function openDeleteImageModal(imagePath) {
            document.getElementById('deleteImagePathInput').value = imagePath;
            if (deleteImageModal) {
                deleteImageModal.classList.remove('hidden');
                deleteImageModal.classList.add('flex');
                setTimeout(() => {
                    deleteImageModal.classList.remove('opacity-0');
                    if (deleteImageModalInner) deleteImageModalInner.classList.remove('scale-95');
                }, 10);
            }
        }

        function closeDeleteImageModal() {
            if (deleteImageModal) {
                deleteImageModal.classList.add('opacity-0');
                if (deleteImageModalInner) deleteImageModalInner.classList.add('scale-95');
                setTimeout(() => {
                    deleteImageModal.classList.add('hidden');
                    deleteImageModal.classList.remove('flex');
                }, 300);
            }
        }
    </script>
    @endpush
</x-admin.layouts.app>
