<x-admin.layout.app title="Ngói Hài Văn Miếu" breadcrumb="Admin › Sản Phẩm › Ngói Hài Văn Miếu">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu hình Ngói Hài Văn Miếu</h2>
        </div>
        <form method="POST" action="{{ route('admin.ngoi-hai-van-mieu.update') }}" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf @method('PUT')
            <!-- Ảnh chính -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh nền</label>
                <div class="aspect-[21/9] max-w-4xl w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                    <img id="preview-main" src="{{ asset('storage/' . $ngoiHaiVanMieu->thumbnail_main) }}" onerror="this.src='https://placehold.co/1200x500?text=Chua+co+anh'" class="w-full h-full object-contain" alt="Ảnh chính">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="text-white text-sm font-medium px-4 py-2 bg-black/50 rounded-lg">Click để tải ảnh mới lên</span>
                    </div>
                    <input type="file" name="thumbnail_main" accept="image/*" onchange="previewImage(event, 'preview-main')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                </div>
                @error('thumbnail_main') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <hr class="border-gray-100">
            <!-- Các Block Nội dung -->
            <div>
                <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Các dự án / Hình ảnh tiêu biểu</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Block 1 -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tiêu đề dự án 1 <span class="text-red-500">*</span></label>
                            <input type="text" name="title1" value="{{ old('title1', $ngoiHaiVanMieu->title1) }}" required class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                            @error('title1') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Hình ảnh dự án 1</label>
                            <div class="aspect-video w-full rounded-lg border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-1" src="{{ asset('storage/' . $ngoiHaiVanMieu->thumbnail1) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-contain">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium">Thay ảnh</span>
                                </div>
                                <input type="file" name="thumbnail1" accept="image/*" onchange="previewImage(event, 'preview-1')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                            @error('thumbnail1') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <!-- Block 2 -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tiêu đề dự án 2 <span class="text-red-500">*</span></label>
                            <input type="text" name="title2" value="{{ old('title2', $ngoiHaiVanMieu->title2) }}" required class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                            @error('title2') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Hình ảnh dự án 2</label>
                            <div class="aspect-video w-full rounded-lg border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-2" src="{{ asset('storage/' . $ngoiHaiVanMieu->thumbnail2) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-contain">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium">Thay ảnh</span>
                                </div>
                                <input type="file" name="thumbnail2" accept="image/*" onchange="previewImage(event, 'preview-2')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                            @error('thumbnail2') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <!-- Block 3 -->
                    <div class="bg-gray-50 rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tiêu đề dự án 3 <span class="text-red-500">*</span></label>
                            <input type="text" name="title3" value="{{ old('title3', $ngoiHaiVanMieu->title3) }}" required class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                            @error('title3') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Hình ảnh dự án 3</label>
                            <div class="aspect-video w-full rounded-lg border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-3" src="{{ asset('storage/' . $ngoiHaiVanMieu->thumbnail3) }}" onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'" class="w-full h-full object-contain">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium">Thay ảnh</span>
                                </div>
                                <input type="file" name="thumbnail3" accept="image/*" onchange="previewImage(event, 'preview-3')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                            @error('thumbnail3') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>
            <hr class="border-gray-100">
            <div>
                <label for="video" class="block text-sm font-semibold text-gray-700 mb-2">Đường dẫn Video YouTube (Tùy chọn)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    </span>
                    <input type="url" id="video" name="video" value="{{ old('video', $ngoiHaiVanMieu->video) }}" placeholder="https://youtube.com/watch?v=..." class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-lg outline-none transition-colors border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                </div>
                @error('video') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
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

            <div class="pt-4 flex justify-end border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                    Lưu cấu hình
                </button>
            </div>
        </form>
    </div>

    {{-- Hiển thị Ảnh Công đoạn chế tác đã lưu --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Hình ảnh Công đoạn chế tác đã lưu</h2>
            @if(is_array($ngoiHaiVanMieu->images))
                <span class="text-xs text-gray-500 font-medium">Tổng số: {{ count($ngoiHaiVanMieu->images) }} ảnh</span>
            @endif
        </div>
        <div class="p-6">
            @if(is_array($ngoiHaiVanMieu->images) && count($ngoiHaiVanMieu->images) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                    @foreach($ngoiHaiVanMieu->images as $path)
                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                            <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-contain">
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
                <form id="deleteCongDoanForm" method="POST" action="{{ route('admin.ngoi-hai-van-mieu.cong-doan-image.destroy') }}" class="flex-1">
                    @csrf @method('DELETE')
                    <input type="hidden" name="image_path" id="deleteCongDoanPathInput" value="">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Có, Xóa</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Script xử lý Image Preview --}}
    @push('scripts')
    <script>
        function previewImage(event, targetId) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(targetId).src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
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
                            <img src="${e.target.result}" class="w-full h-full object-contain">
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