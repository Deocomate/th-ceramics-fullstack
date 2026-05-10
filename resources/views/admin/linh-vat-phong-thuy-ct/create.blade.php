<x-admin.layout.app title="Thêm Linh Vật Phong Thủy" breadcrumb="Admin › DS Sản phẩm chi tiết › Thêm mới">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Thông tin Linh vật mới</h2>
        </div>
        <form method="POST" action="{{ route('admin.linh-vat-phong-thuy-ct.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- CỘT THÔNG TIN CHUNG -->
                <div class="lg:col-span-2 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tên linh vật <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mã sản phẩm (Code) <span class="text-red-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code') }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all" placeholder="VD: LV-001">
                            @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Giá (VNĐ) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price') }}" required min="0" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                            @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kích thước (Text)</label>
                            <input type="text" name="size" value="{{ old('size') }}" placeholder="VD: Cao 30cm, Rộng 15cm" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                        </div>
                    </div>

                    <!-- BLOCKS THÔNG SỐ CHUNG (DES) -->
                    <div class="bg-gray-50/80 rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between mb-4 border-b border-gray-200 pb-3">
                            <div>
                                <label class="block text-sm font-bold text-gray-800">Thông số / Ý nghĩa phong thủy</label>
                                <p class="text-xs text-gray-500 mt-0.5">Mỗi khối tương ứng với 1 gạch đầu dòng trên Website.</p>
                            </div>
                            <button type="button" onclick="addDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 hover:text-[#A31D1D] hover:border-[#A31D1D] transition-colors shadow-sm flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Thêm dòng
                            </button>
                        </div>
                        <div id="des-blocks-container" class="space-y-2.5"></div>
                    </div>

                    <!-- BLOCKS CHI TIẾT KÍCH THƯỚC (SIZE DES) -->
                    <div class="bg-blue-50/30 rounded-xl border border-blue-100 p-5">
                        <div class="flex items-center justify-between mb-4 border-b border-blue-200 pb-3">
                            <div>
                                <label class="block text-sm font-bold text-blue-800">Danh sách Kích thước chi tiết</label>
                                <p class="text-xs text-gray-500 mt-0.5">Hiển thị cạnh ảnh Kích thước/Bản vẽ bên dưới.</p>
                            </div>
                            <button type="button" onclick="addSizeDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-blue-50 hover:text-blue-700 hover:border-blue-400 transition-colors shadow-sm flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Thêm dòng
                            </button>
                        </div>
                        <div id="size-des-blocks-container" class="space-y-2.5"></div>
                    </div>
                </div>

                <!-- CỘT HÌNH ẢNH KÍCH THƯỚC -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ảnh bản vẽ / Kích thước</label>
                    <div class="aspect-square w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
                        <img id="preview-size" src="https://placehold.co/400x400?text=Chon+Ban+Ve" class="w-full h-full object-contain">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Tải ảnh lên</span>
                        </div>
                        <input type="file" name="size_image" accept="image/*" onchange="previewImage(event, 'preview-size')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('size_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- CHỌN ẢNH SẢN PHẨM -->
            <hr class="border-gray-100 my-8">
            <div class="flex flex-col h-full border border-gray-200 rounded-xl p-6 bg-gray-50/50">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh sản phẩm <span class="text-red-500">*</span></label>
                <div class="relative mb-4">
                    <input type="file" id="multipleImagesInput" name="images[]" multiple required accept="image/*" class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white" onchange="handleMultipleFiles(event)">
                </div>
                <div class="h-[250px] bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner flex flex-col">
                    <div id="multiple-preview-container" class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-8 gap-3">
                        <div id="empty-preview-state" class="col-span-full h-full min-h-[180px] flex flex-col items-center justify-center text-center text-gray-400 text-xs font-medium gap-2">
                            <span>Chưa có ảnh nào</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-6 mt-8 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.linh-vat-phong-thuy-ct.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                    Lưu Sản Phẩm
                </button>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            function previewImage(event, targetId) {
                const file = event.target.files[0];
                if (file) document.getElementById(targetId).src = URL.createObjectURL(file);
            }

            // Logic Block DES
            function addDesBlock(value = '', autoFocus = true) {
                const div = document.createElement('div');
                div.className = 'flex items-center bg-white rounded-lg border border-gray-200 shadow-sm group focus-within:border-[#A31D1D] focus-within:ring-1 focus-within:ring-[#A31D1D] transition-all overflow-hidden';
                div.innerHTML = `
                    <input type="text" name="des[]" value="${value}" placeholder="VD: Mang ý nghĩa chiêu tài..." class="flex-1 py-2.5 px-3 text-sm border-none focus:ring-0 outline-none text-gray-700 bg-transparent">
                    <button type="button" onclick="this.parentElement.remove()" class="px-3 text-red-400 hover:text-red-600">X</button>
                `;
                document.getElementById('des-blocks-container').appendChild(div);
                if(autoFocus && value === '') div.querySelector('input').focus();
            }
            addDesBlock('');

            // Logic Block SIZE DES
            function addSizeDesBlock(value = '', autoFocus = true) {
                const div = document.createElement('div');
                div.className = 'flex items-center bg-white rounded-lg border border-blue-200 shadow-sm group focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition-all overflow-hidden';
                div.innerHTML = `
                    <input type="text" name="size_des[]" value="${value}" placeholder="VD: Đường kính đế: 10cm" class="flex-1 py-2.5 px-3 text-sm border-none focus:ring-0 outline-none text-gray-700 bg-transparent">
                    <button type="button" onclick="this.parentElement.remove()" class="px-3 text-red-400 hover:text-red-600">X</button>
                `;
                document.getElementById('size-des-blocks-container').appendChild(div);
                if(autoFocus && value === '') div.querySelector('input').focus();
            }
            addSizeDesBlock('');

            // Logic Upload nhiều ảnh (Giống hệt các file khác, bạn có thể copy function handleMultipleFiles...)
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
                            div.className = 'image-preview-item relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100';
                            div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain">
                                             <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center">
                                                <button type="button" onclick="removeFile(${index})" class="text-white text-xl">×</button>
                                             </div>`;
                            previewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }
            function removeFile(index) { selectedFiles.splice(index, 1); updateFileInput(); renderPreviews(); }
            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                multipleImagesInput.files = dataTransfer.files;
            }
        </script>
    @endpush
</x-admin.layout.app>