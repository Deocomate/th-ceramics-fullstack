<x-admin.layout.app title="Gạch Hoa Thông Gió" breadcrumb="Admin › Sản Phẩm › Gạch Hoa Thông Gió">
    {{-- CẤU HÌNH HÌNH ẢNH & THƯ VIỆN --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu Hình Hình Ảnh & Thư Viện</h2>
        </div>
        <form method="POST" action="{{ route('admin.gach-hoa-thong-gio.update') }}" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                {{-- CỘT TRÁI: BACKGROUND & VIDEO --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh nền</label>
                        <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                            <img id="preview-main" src="{{ asset('storage/' . $gachHoaThongGio->image) }}"
                                onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'"
                                class="w-full h-full object-cover" alt="Ảnh chính">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi Background</span>
                            </div>
                            <input type="file" name="image" accept="image/*"
                                onchange="previewImage(event, 'preview-main')"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('image')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="video" class="block text-sm font-semibold text-gray-700 mb-2">Đường dẫn Video YouTube (Tùy chọn)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            </span>
                            <input type="url" id="video" name="video"
                                value="{{ old('video', $gachHoaThongGio->video) }}"
                                placeholder="https://youtube.com/watch?v=..."
                                class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                        </div>
                        @error('video')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                {{-- CỘT PHẢI: CHỌN NHIỀU ẢNH THƯ VIỆN --}}
                <div class="flex flex-col h-full border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Thư viện ảnh (Chọn nhiều ảnh cùng lúc)</label>
                    <p class="text-xs text-gray-500 mb-4">Các ảnh này sẽ được thêm vào thư viện sản phẩm khi bạn lưu.</p>
                    <div class="relative mb-4">
                        <input type="file" id="multipleImagesInput" name="new_images[]" multiple accept="image/*"
                            class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white"
                            onchange="handleMultipleFiles(event)">
                    </div>
                    @error('new_images.*')
                        <p class="mb-4 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <!-- Vùng chứa Preview Ảnh -->
                    <div class="h-[400px] bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner flex flex-col">
                        <div id="multiple-preview-container" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                            <!-- State rỗng mặc định -->
                            <div id="empty-preview-state" class="col-span-full h-full min-h-[250px] flex flex-col items-center justify-center text-center text-gray-400 text-xs font-medium gap-2">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Chưa có ảnh nào được chọn tải lên</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hình ảnh Công đoạn chế tác -->
            <hr class="border-gray-100 my-8">
            <div class="flex flex-col h-full border border-gray-200 rounded-xl p-6 bg-gray-50/50">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh Công đoạn chế tác</label>
                <p class="text-xs text-gray-500 mb-4">Các ảnh này sẽ được lưu và hiển thị ở phần công đoạn chế tác.</p>
                <div class="relative mb-4">
                    <input type="file" id="congDoanInput" name="cong_doan_images[]" multiple accept="image/*" class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white" onchange="handleCongDoanFiles(event)">
                </div>
                @error('cong_doan_images.*') <p class="mb-4 text-xs text-red-600">{{ $message }}</p> @enderror
                
                <div class="h-[250px] bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner flex flex-col">
                    <div id="cong-doan-preview-container" class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                        <div id="empty-cong-doan-state" class="col-span-full h-full min-h-[180px] flex flex-col items-center justify-center text-center text-gray-400 text-xs font-medium gap-2">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Chưa có ảnh nào được chọn tải lên</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 mt-8 flex justify-end border-t border-gray-100">
                <button type="submit"
                    class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors"
                    style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'"
                    onmouseout="this.style.background='#A31D1D'">
                    Lưu cấu hình & Upload ảnh
                </button>
            </div>
        </form>
    </div>

    {{-- Hiển thị Ảnh Công đoạn chế tác đã lưu --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Hình ảnh Công đoạn chế tác đã lưu</h2>
            @if(is_array($gachHoaThongGio->images))
                <span class="text-xs text-gray-500 font-medium">Tổng số: {{ count($gachHoaThongGio->images) }} ảnh</span>
            @endif
        </div>
        <div class="p-6">
            @if(is_array($gachHoaThongGio->images) && count($gachHoaThongGio->images) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                    @foreach($gachHoaThongGio->images as $path)
                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                            <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                <button type="button" onclick="openDeleteCongDoanModal('{{ $path }}')" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                    Xóa ảnh
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm text-center py-6">Chưa có ảnh công đoạn nào được lưu.</p>
            @endif
        </div>
    </div>

    {{-- MODAL XÓA ẢNH CÔNG ĐOẠN --}}
    <div id="deleteCongDoanModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Ảnh này sẽ bị xóa vĩnh viễn khỏi hệ thống.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteCongDoanModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteCongDoanForm" method="POST" action="{{ route('admin.gach-hoa-thong-gio.cong-doan-image.destroy') }}" class="flex-1">
                    @csrf @method('DELETE')
                    <input type="hidden" name="image_path" id="deleteCongDoanPathInput" value="">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Có, Xóa</button>
                </form>
            </div>
        </div>
    </div>

    {{-- DANH SÁCH ẢNH ĐÃ LƯU TRƯỚC ĐÓ (Thư viện) --}}
    @if (count($gachHoaThongGio->anh) > 0)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Thư viện ảnh đã lưu</h2>
                <span class="text-xs text-gray-500 font-medium">Tổng số: {{ count($gachHoaThongGio->anh) }} ảnh</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                    @foreach ($gachHoaThongGio->anh as $anh)
                        <div
                            class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                            <img src="{{ asset('storage/' . $anh->image) }}" class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <form action="{{ route('admin.gach-hoa-thong-gio.anh.destroy', $anh) }}" method="POST"
                                    onsubmit="return confirm('Xóa ảnh thư viện này?')">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                        onclick="openDeleteModal('{{ route('admin.gach-hoa-thong-gio.anh.destroy', $anh) }}')"
                                        class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                        Xóa ảnh
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- QUẢN LÝ GIÁ TRỊ --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Giá trị sản phẩm</h2>
        </div>
        <div class="p-6">
            {{-- FORM THÊM MỚI --}}
            <div class="mb-10 p-6 bg-blue-50/30 rounded-xl border border-blue-100">
                <h3 class="text-sm font-bold text-blue-800 mb-5">THÊM GIÁ TRỊ MỚI</h3>
                <form method="POST" action="{{ route('admin.gach-hoa-thong-gio.gia-tri.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                        <div class="lg:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh nền<span
                                    class="text-red-500">*</span></label>
                            <div
                                class="aspect-square w-full rounded-xl border-2 border-dashed border-blue-300 bg-white flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-new-bg" src="https://placehold.co/400x400?text=Chon+Background"
                                    class="w-full h-full object-cover">
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span
                                        class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Chọn
                                        file</span>
                                </div>
                                <input type="file" name="background" accept="image/*" required
                                    onchange="previewImage(event, 'preview-new-bg')"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <div class="lg:col-span-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Hình ảnh<span
                                    class="text-red-500">*</span></label>
                            <div
                                class="aspect-square w-full rounded-xl border-2 border-dashed border-blue-300 bg-white flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-new-img" src="https://placehold.co/400x400?text=Chon+Anh"
                                    class="w-full h-full object-cover">
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span
                                        class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Chọn
                                        file</span>
                                </div>
                                <input type="file" name="image" accept="image/*" required
                                    onchange="previewImage(event, 'preview-new-img')"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <div class="lg:col-span-2 flex flex-col gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="title" required
                                    class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                            </div>
                            <div class="flex-1 flex flex-col">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả <span
                                        class="text-red-500">*</span></label>
                                <textarea name="desscription" required
                                    class="w-full flex-1 min-h-[100px] px-4 py-3 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all resize-none"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end pt-4 border-t border-blue-100">
                        <button type="submit"
                            class="px-8 py-2.5 text-sm font-bold text-white rounded-lg bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">Thêm
                            giá trị</button>
                    </div>
                </form>
            </div>
            {{-- DANH SÁCH GIÁ TRỊ (Chia 3 cột, có padding 2 bên ép vào giữa) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8 px-4 lg:px-16">
                @foreach($gachHoaThongGio->giaTri as $giaTri)
                    <div class="group relative flex flex-col items-center bg-[#F3EFEA] rounded-xl border border-gray-200 overflow-hidden pb-8 hover:shadow-xl transition-all duration-300">
                        <div class="w-full flex justify-center pt-10 pb-12">
                            <div class="relative w-[65%] aspect-[10/9]">
                                <div class="absolute top-0 left-0 w-full h-[75%] overflow-hidden rounded shadow-sm">
                                    <img src="{{ asset('storage/' . $giaTri->background) }}" class="w-full h-full object-cover">
                                </div>
                                <div class="absolute top-[25%] left-[15%] right-[15%] bottom-[-20%] shadow-xl bg-white overflow-hidden rounded border-2 border-white">
                                    <img src="{{ asset('storage/' . $giaTri->image) }}" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-6 px-6 flex-grow flex flex-col justify-start">
                            <h4 class="font-bold text-[#333] text-lg lg:text-xl mb-3 leading-snug">{{ $giaTri->title }}</h4>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $giaTri->desscription }}</p>
                        </div>
                        <div class="absolute inset-0 z-20 bg-black/60 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center gap-3 transition-opacity duration-300 backdrop-blur-[2px]">
                            <button type="button" 
                                data-title="{{ $giaTri->title }}"
                                data-desc="{{ $giaTri->desscription }}"
                                data-bg="{{ asset('storage/' . $giaTri->background) }}"
                                data-img="{{ asset('storage/' . $giaTri->image) }}"
                                onclick="openEditModal(this, '{{ route('admin.gach-hoa-thong-gio.gia-tri.update', $giaTri) }}')"
                                class="w-32 px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Sửa
                            </button>
                            <button type="button" 
                                onclick="openDeleteModal('{{ route('admin.gach-hoa-thong-gio.gia-tri.destroy', $giaTri) }}')"
                                class="w-32 px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Xóa
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MODAL SỬA GIÁ TRỊ --}}
    <div id="editModal"
        class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden p-6">
            <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
                <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Cập nhật Giá trị
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500"><svg
                        class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg></button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <div class="lg:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Sửa Background</label>
                        <div
                            class="aspect-square w-full rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden relative group">
                            <img id="preview-edit-bg" src="" class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Đổi
                                    ảnh</span>
                            </div>
                            <input type="file" name="background" accept="image/*"
                                onchange="previewImage(event, 'preview-edit-bg')"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                    </div>
                    <div class="lg:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Sửa Ảnh minh họa</label>
                        <div
                            class="aspect-square w-full rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden relative group">
                            <img id="preview-edit-img" src="" class="w-full h-full object-cover">
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Đổi
                                    ảnh</span>
                            </div>
                            <input type="file" name="image" accept="image/*"
                                onchange="previewImage(event, 'preview-edit-img')"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                    </div>
                    <div class="lg:col-span-2 flex flex-col gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="edit_title" name="title" required
                                class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 outline-none transition-all">
                        </div>
                        <div class="flex-1 flex flex-col">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả <span
                                    class="text-red-500">*</span></label>
                            <textarea id="edit_description" name="desscription" required
                                class="w-full flex-1 min-h-[150px] px-4 py-3 text-sm border rounded-lg border-gray-300 focus:border-blue-500 outline-none transition-all resize-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3 pt-5 border-t border-gray-100">
                    <button type="button" onclick="closeEditModal()"
                        class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy
                        bỏ</button>
                    <button type="submit"
                        class="px-8 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm">Lưu
                        thay đổi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL XÓA (Sử dụng chung cho cả Xóa Ảnh và Xóa Giá trị) --}}
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
            // 1. Preview ảnh đơn
            function previewImage(event, targetId) {
                const file = event.target.files[0];
                if (file) {
                    document.getElementById(targetId).src = URL.createObjectURL(file);
                }
            }

            // 2. Modal Chỉnh Sửa
            function openEditModal(btn, actionUrl) {
                document.getElementById('editForm').action = actionUrl;
                document.getElementById('edit_title').value = btn.getAttribute('data-title');
                document.getElementById('edit_description').value = btn.getAttribute('data-desc');
                document.getElementById('preview-edit-bg').src = btn.getAttribute('data-bg');
                document.getElementById('preview-edit-img').src = btn.getAttribute('data-img');
                document.getElementById('editModal').classList.remove('hidden');
                document.getElementById('editModal').classList.add('flex');
            }
            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
                document.getElementById('editModal').classList.remove('flex');
            }

            // 3. Hệ thống preview nhiều ảnh (Multiple Images JS)
            let selectedFiles =[];
            const multipleImagesInput = document.getElementById('multipleImagesInput');
            const previewContainer = document.getElementById('multiple-preview-container');
            const emptyState = document.getElementById('empty-preview-state');

            function handleMultipleFiles(event) {
                const files = Array.from(event.target.files);
                if (files.length > 0) {
                    selectedFiles = selectedFiles.concat(files);
                    updateFileInput();
                    renderPreviews();
                }
            }
            function renderPreviews() {
                const existingPreviews = previewContainer.querySelectorAll('.image-preview-item');
                existingPreviews.forEach(item => item.remove());
                if (selectedFiles.length === 0) {
                    emptyState.style.display = 'flex';
                } else {
                    emptyState.style.display = 'none';
                    selectedFiles.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className =
                                'image-preview-item relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100';
                            div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                <button type="button" onclick="removeFile(${index})" class="w-8 h-8 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors flex items-center justify-center shadow-sm" title="Xóa ảnh này">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        `;
                            previewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }
            function removeFile(index) {
                selectedFiles.splice(index, 1);
                updateFileInput();
                renderPreviews();
            }
            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => {
                    dataTransfer.items.add(file);
                });
                multipleImagesInput.files = dataTransfer.files;
            }

            // 4. Xử lý Modal Delete
            const deleteModal = document.getElementById('deleteModal');
            const deleteModalInner = deleteModal.querySelector('.bg-white');
            function openDeleteModal(actionUrl) {
                document.getElementById('deleteForm').action = actionUrl;
                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('flex');
                void deleteModal.offsetWidth; // Trigger reflow
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

            // ====== LOGIC UPLOAD ẢNH CÔNG ĐOẠN CHẾ TÁC ======
            let selectedCongDoanFiles =[];
            const congDoanInput = document.getElementById('congDoanInput');
            const congDoanPreviewContainer = document.getElementById('cong-doan-preview-container');
            const emptyCongDoanState = document.getElementById('empty-cong-doan-state');

            function handleCongDoanFiles(event) {
                const files = Array.from(event.target.files);
                if (files.length > 0) {
                    selectedCongDoanFiles = selectedCongDoanFiles.concat(files);
                    updateCongDoanInput();
                    renderCongDoanPreviews();
                }
            }

            function renderCongDoanPreviews() {
                const existing = congDoanPreviewContainer.querySelectorAll('.congdoan-preview-item');
                existing.forEach(item => item.remove());
                
                if (selectedCongDoanFiles.length === 0) {
                    emptyCongDoanState.style.display = 'flex';
                } else {
                    emptyCongDoanState.style.display = 'none';
                    selectedCongDoanFiles.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'congdoan-preview-item relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100';
                            div.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                    <button type="button" onclick="removeCongDoanFile(${index})" class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm" title="Bỏ ảnh này">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            `;
                            congDoanPreviewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }

            function removeCongDoanFile(index) {
                selectedCongDoanFiles.splice(index, 1);
                updateCongDoanInput();
                renderCongDoanPreviews();
            }

            function updateCongDoanInput() {
                const dataTransfer = new DataTransfer();
                selectedCongDoanFiles.forEach(file => dataTransfer.items.add(file));
                congDoanInput.files = dataTransfer.files;
            }

            // ====== LOGIC MODAL XÓA ẢNH CÔNG ĐOẠN ======
            const deleteCongDoanModal = document.getElementById('deleteCongDoanModal');
            const deleteCongDoanModalInner = deleteCongDoanModal.querySelector('.bg-white');

            function openDeleteCongDoanModal(imagePath) {
                document.getElementById('deleteCongDoanPathInput').value = imagePath;
                deleteCongDoanModal.classList.remove('hidden');
                deleteCongDoanModal.classList.add('flex');
                void deleteCongDoanModal.offsetWidth; // trigger reflow
                deleteCongDoanModal.classList.remove('opacity-0');
                deleteCongDoanModalInner.classList.remove('scale-95');
            }

            function closeDeleteCongDoanModal() {
                deleteCongDoanModal.classList.add('opacity-0');
                deleteCongDoanModalInner.classList.add('scale-95');
                setTimeout(() => {
                    deleteCongDoanModal.classList.add('hidden');
                    deleteCongDoanModal.classList.remove('flex');
                }, 300);
            }
        </script>
    @endpush
</x-admin.layout.app>