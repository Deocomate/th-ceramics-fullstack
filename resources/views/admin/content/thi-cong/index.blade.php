<x-admin.layouts.app title="Quản lý Video Thi Công" breadcrumb="Admin › Cấu Hình Chung › Video Thi Công">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Danh Sách Video Thi Công</h2>
            <p class="text-xs text-gray-400 mt-0.5">Tổng cộng {{ $thiCongs->total() }} video</p>
        </div>
        <div class="p-6">
            @if ($errors->any())
                <div class="mb-8 flex items-start gap-3 px-4 py-3 rounded text-sm text-red-800 bg-red-50 border border-red-200 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <strong class="font-semibold block mb-1">Thao tác không thành công. Vui lòng kiểm tra lại:</strong>
                        <ul class="list-disc ml-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="mb-10 p-6 bg-blue-50/30 rounded-xl border border-blue-100 shadow-[0_0_15px_rgba(59,130,246,0.03)]">
                <h3 class="text-sm font-bold text-blue-800 mb-5 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    THÊM VIDEO MỚI
                </h3>
                <form method="POST" action="{{ route('admin.thi-cong.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                        <div class="lg:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh đại diện video <span class="text-red-500">*</span></label>
                            <div class="w-full aspect-[4/3] mx-auto rounded-xl border-2 border-dashed border-blue-300 bg-white flex items-center justify-center overflow-hidden relative group hover:bg-blue-50/50 transition-colors">
                                <img id="preview-new-thicong" src="https://placehold.co/800x600?text=Chon+Anh" class="w-full h-full object-contain" alt="Preview">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Chọn ảnh</span>
                                </div>
                                <input type="file" name="anh" accept="image/*" required onchange="previewImage(event, 'preview-new-thicong')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <div class="lg:col-span-3 flex flex-col gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề video <span class="text-red-500">*</span></label>
                                <input type="text" name="tieu_de" required placeholder="Nhập tiêu đề video..." class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Link YouTube</label>
                                <input type="url" name="link_youtube" placeholder="VD: https://youtube.com/watch?v=..." class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end pt-4 border-t border-blue-100">
                        <button type="submit" class="px-6 py-2.5 flex items-center gap-2 text-sm font-bold text-white rounded-lg bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                            Thêm video
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($thiCongs as $item)
                    <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow bg-white flex flex-col group relative">
                        <div class="w-full aspect-[4/3] relative bg-gray-100 flex-shrink-0 overflow-hidden border-b border-gray-100">
                            <img src="{{ asset('storage/' . $item->anh) }}" onerror="this.src='https://placehold.co/800x600?text=Loi+Anh'" class="w-full h-full object-contain">
                            
                            <!-- Icon Video play giả lập -->
                            @if($item->link_youtube)
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="w-12 h-12 bg-black/50 rounded-full flex items-center justify-center pl-1">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                            @endif

                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-3 backdrop-blur-[2px]">
                                <button type="button" 
                                    data-id="{{ $item->thi_cong }}"
                                    data-name="{{ $item->tieu_de }}"
                                    data-youtube="{{ $item->link_youtube }}"
                                    data-img="{{ asset('storage/' . $item->anh) }}"
                                    onclick="openEditModal(this, '{{ route('admin.thi-cong.update', $item->thi_cong) }}')"
                                    class="flex items-center gap-1.5 px-6 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm w-32 justify-center">
                                    Sửa
                                </button>
                                <button type="button" 
                                    onclick="openDeleteModal('{{ route('admin.thi-cong.destroy', $item->thi_cong) }}')"
                                    class="flex items-center gap-1.5 px-6 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm w-32 justify-center">
                                    Xóa
                                </button>
                            </div>
                        </div>
                        <div class="p-4 w-full flex flex-col flex-1 text-center bg-white">
                            <h4 class="font-bold text-gray-800 text-sm mb-1 truncate" title="{{ $item->tieu_de }}">{{ $item->tieu_de }}</h4>
                            <p class="text-xs text-blue-600 truncate"><a href="{{ $item->link_youtube }}" target="_blank" class="hover:underline">{{ $item->link_youtube ?? 'Không có link' }}</a></p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                        <p class="text-sm font-medium text-gray-500">Chưa có video thi công nào được cấu hình.</p>
                    </div>
                @endforelse
            </div>

            {{-- PHÂN TRANG --}}
            @if($thiCongs->hasPages())
                <div class="mt-8 border-t border-gray-100 pt-6">
                    {{ $thiCongs->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL CHỈNH SỬA --}}
    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center shrink-0">
                <h3 class="font-bold text-gray-800 text-lg">Cập nhật Video Thi Công</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh đại diện</label>
                            <div class="w-full aspect-[4/3] max-w-[280px] mx-auto rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-edit-img" src="" class="w-full h-full object-contain" alt="Preview">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Đổi ảnh</span>
                                </div>
                                <input type="file" name="anh" accept="image/*" onchange="previewImage(event, 'preview-edit-img')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề video <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_name" name="tieu_de" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Link YouTube</label>
                            <input type="url" id="edit_youtube" name="link_youtube" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-gray-100">
                        <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy bỏ</button>
                        <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL XÓA --}}
    <div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Hành động này không thể hoàn tác. Dữ liệu sẽ bị xóa vĩnh viễn khỏi hệ thống.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Có, Xóa ngay</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewImage(event, targetId) {
            const file = event.target.files[0];
            if (file) {
                const objectUrl = URL.createObjectURL(file);
                document.getElementById(targetId).src = objectUrl;
                document.getElementById(targetId).onload = function() {
                    URL.revokeObjectURL(objectUrl);
                }
            }
        }

        const editModal = document.getElementById('editModal');
        const editModalInner = editModal.querySelector('.bg-white');
        
        function openEditModal(btnElement, actionUrl) {
            document.getElementById('editForm').action = actionUrl;
            document.getElementById('edit_name').value = btnElement.getAttribute('data-name');
            document.getElementById('edit_youtube').value = btnElement.getAttribute('data-youtube');
            document.getElementById('preview-edit-img').src = btnElement.getAttribute('data-img');
            
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
            void editModal.offsetWidth; 
            editModal.classList.remove('opacity-0');
            editModalInner.classList.remove('scale-95');
        }

        function closeEditModal() {
            editModal.classList.add('opacity-0');
            editModalInner.classList.add('scale-95');
            setTimeout(() => {
                editModal.classList.add('hidden');
                editModal.classList.remove('flex');
            }, 300);
        }

        const deleteModal = document.getElementById('deleteModal');
        const deleteModalInner = deleteModal.querySelector('.bg-white');
        
        function openDeleteModal(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');
            void deleteModal.offsetWidth;
            deleteModal.classList.remove('opacity-0');
            deleteModalInner.classList.remove('scale-95');
        }

        function closeDeleteModal() {
            deleteModal.classList.add('opacity-0');
            deleteModalInner.classList.add('scale-95');
            setTimeout(() => {
                deleteModal.classList.add('hidden');
                deleteModal.classList.remove('flex');
            }, 300);
        }
    </script>
    @endpush
</x-admin.layouts.app>