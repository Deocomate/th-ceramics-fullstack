<x-admin.layout.app title="Về Chúng Tôi" breadcrumb="Admin › Cấu hình trang đơn › Về Chúng Tôi">
    <form method="POST" action="{{ route('admin.pages.ve_chung_toi.update') }}" enctype="multipart/form-data" class="space-y-8 pb-10">
        @csrf @method('PUT')

        <!-- SECTION 1: BANNER -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">1. Khu Vực Banner Đầu Trang</h2>
            </div>
            <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cột Ảnh -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh Banner</label>
                    <div class="aspect-[16/9] w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
                        <img id="preview-banner" src="{{ $veChungToi->banner ? asset('storage/' . $veChungToi->banner) : 'https://placehold.co/800x450?text=Chon+Anh' }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                        </div>
                        <input type="file" name="banner" accept="image/*" onchange="previewImage(event, 'preview-banner')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                </div>
                <!-- Cột Text -->
                <div class="lg:col-span-2 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tiêu đề Banner (Header Banner)</label>
                        <input type="text" name="header_banner" value="{{ old('header_banner', $veChungToi->header_banner) }}" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Mô tả Banner (Body Banner)</label>
                        <textarea name="body_banner" rows="4" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">{{ old('body_banner', $veChungToi->body_banner) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: GỐM SỨ & LỊCH SỬ -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">2. Về Thương Hiệu Gốm Sứ</h2>
            </div>
            
            <div class="p-6 space-y-10">
                <!-- GS Head (Ưu tiên 2) -->
                <div>
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-sm font-bold text-gray-700">Điểm Nhấn (Gs Head)</h3>
                        <button type="button" onclick="addJsonBlock('gs_head', 'gs-head-container')" class="text-xs text-blue-600 font-bold hover:underline">+ Thêm item</button>
                    </div>
                    <!-- Lưới 2 cột -->
                    <div id="gs-head-container" class="grid grid-cols-1 lg:grid-cols-2 gap-6"></div>
                </div>

                <!-- GS Giá Trị (Ưu tiên 3) -->
                <div>
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-sm font-bold text-gray-700">Giá Trị Cốt Lõi (Gs Giá trị)</h3>
                        <button type="button" onclick="addJsonBlock('gs_gia_tri', 'gs-gia-tri-container')" class="text-xs text-blue-600 font-bold hover:underline">+ Thêm item</button>
                    </div>
                    <!-- Lưới 3 cột -->
                    <div id="gs-gia-tri-container" class="grid grid-cols-1 md:grid-cols-3 gap-6"></div>
                </div>

                <!-- GS Hành Trình (Ưu tiên 5) -->
                <div>
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-sm font-bold text-gray-700">Hành Trình Lịch Sử (Gs Hành trình)</h3>
                        <button type="button" onclick="addJsonBlock('gs_hanh_trinh', 'gs-hanh-trinh-container', 'Năm (VD: 2000)')" class="text-xs text-blue-600 font-bold hover:underline">+ Thêm item</button>
                    </div>
                    <!-- Lưới 5 cột -->
                    <div id="gs-hanh-trinh-container" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4"></div>
                </div>

                <!-- GS Giải Thưởng (Ưu tiên 3, chỉ có năm và ảnh) -->
                <div>
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-sm font-bold text-gray-700">Giải Thưởng</h3>
                        <button type="button" onclick="addJsonBlockImageOnly('gs_giai_thuong', 'gs-giai-thuong-container', 'Năm (VD: 2024)')" class="text-xs text-blue-600 font-bold hover:underline">+ Thêm item</button>
                    </div>
                    <div id="gs-giai-thuong-container" class="grid grid-cols-1 md:grid-cols-3 gap-6"></div>
                </div>

                <!-- GS Người Sáng Lập -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <h3 class="text-sm font-bold text-gray-700 mb-4 border-b pb-2">Người Sáng Lập</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                        <div class="lg:col-span-1">
                            <label class="block text-xs font-semibold text-gray-600 mb-2">Ảnh Người Sáng Lập</label>
                            <div class="aspect-square w-full rounded-xl border border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group">
                                <img id="preview-nsl" src="{{ $veChungToi->gs_nguoi_sang_lap_anh ? asset('storage/' . $veChungToi->gs_nguoi_sang_lap_anh) : 'https://placehold.co/400x400' }}" class="w-full h-full object-cover">
                                <input type="file" name="gs_nguoi_sang_lap_anh" accept="image/*" onchange="previewImage(event, 'preview-nsl')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <div class="lg:col-span-3">
                            <label class="block text-xs font-semibold text-gray-600 mb-2">Nội dung câu chuyện</label>
                            <textarea name="gs_nguoi_sang_lap_noi_dung" rows="8" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">{{ old('gs_nguoi_sang_lap_noi_dung', $veChungToi->gs_nguoi_sang_lap_noi_dung) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: NGHỆ THUẬT CHẾ TÁC -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">3. Nghệ Thuật Chế Tác</h2>
            </div>
            <div class="p-6 space-y-10">
                
                <!-- Chung -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tiêu đề Chính</label>
                        <input type="text" name="nt_head" value="{{ old('nt_head', $veChungToi->nt_head) }}" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Mô tả Chính</label>
                        <textarea name="nt_body" rows="2" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">{{ old('nt_body', $veChungToi->nt_body) }}</textarea>
                    </div>
                </div>

                <!-- NT Ngôn ngữ (Ưu tiên 1) -->
                <div>
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-sm font-bold text-gray-700">Ngôn Ngữ Chế Tác (Nt Ngôn ngữ)</h3>
                        <button type="button" onclick="addJsonBlock('nt_ngon_ngu', 'nt-ngon-ngu-container')" class="text-xs text-blue-600 font-bold hover:underline">+ Thêm item</button>
                    </div>
                    <!-- 1 cột to -->
                    <div id="nt-ngon-ngu-container" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>
                </div>

                <!-- NT Chế tác -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <h3 class="text-sm font-bold text-gray-700 mb-4 border-b pb-2">Khâu Chế Tác</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                        <input type="text" name="nt_che_tac_head" value="{{ old('nt_che_tac_head', $veChungToi->nt_che_tac_head) }}" placeholder="Tiêu đề..." class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 outline-none">
                        <input type="text" name="nt_che_tac_body" value="{{ old('nt_che_tac_body', $veChungToi->nt_che_tac_body) }}" placeholder="Mô tả..." class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 outline-none">
                    </div>
                    
                    @if(is_array($veChungToi->nt_che_tac_anh) && count($veChungToi->nt_che_tac_anh) > 0)
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 mb-2">Ảnh hiện tại (Ưu tiên hiện 2 ảnh. Chọn để xóa):</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($veChungToi->nt_che_tac_anh as $idx => $path)
                                    <div class="relative group aspect-square rounded-lg border overflow-hidden bg-white">
                                        <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover">
                                        <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center cursor-pointer">
                                            <input type="checkbox" name="delete_nt_che_tac_anh[]" value="{{ $idx }}" class="w-5 h-5 text-red-600 rounded">
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Thêm ảnh chế tác mới (Ưu tiên upload 2 ảnh)</label>
                        <input type="file" id="input-nt-che-tac" name="new_nt_che_tac_anh[]" multiple accept="image/*" onchange="previewMultipleImages(event, 'preview-nt-che-tac', 'cover')" class="w-full text-sm border rounded-lg bg-white p-1">
                        <div id="preview-nt-che-tac"></div>
                    </div>
                </div>

                <!-- NT Luyện Đất (Ưu tiên 3) -->
                <div>
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-sm font-bold text-gray-700">Luyện Đất</h3>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                        <input type="text" name="nt_luyen_dat_head" value="{{ old('nt_luyen_dat_head', $veChungToi->nt_luyen_dat_head) }}" placeholder="Tiêu đề..." class="w-full px-3 py-2 text-sm border rounded-lg outline-none">
                        <input type="text" name="nt_luyen_dat_body" value="{{ old('nt_luyen_dat_body', $veChungToi->nt_luyen_dat_body) }}" placeholder="Mô tả..." class="w-full px-3 py-2 text-sm border rounded-lg outline-none">
                    </div>
                    <div class="flex justify-between items-center mt-6 mb-2">
                        <span class="text-xs font-bold text-gray-600">Các bước luyện đất (Nt Luyện đất item)</span>
                        <button type="button" onclick="addJsonBlock('nt_luyen_dat_item', 'nt-luyen-dat-container')" class="text-xs text-blue-600 font-bold hover:underline">+ Thêm item</button>
                    </div>
                    <div id="nt-luyen-dat-container" class="grid grid-cols-1 md:grid-cols-3 gap-6"></div>
                </div>

                <!-- NT Đun Lò -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <h3 class="text-sm font-bold text-gray-700 mb-4 border-b pb-2">Đun Lò</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                        <input type="text" name="nt_dun_lo_head" value="{{ old('nt_dun_lo_head', $veChungToi->nt_dun_lo_head) }}" placeholder="Tiêu đề..." class="w-full px-3 py-2 text-sm border rounded-lg outline-none">
                        <input type="text" name="nt_dun_lo_body" value="{{ old('nt_dun_lo_body', $veChungToi->nt_dun_lo_body) }}" placeholder="Mô tả..." class="w-full px-3 py-2 text-sm border rounded-lg outline-none">
                    </div>
                    
                    @if(is_array($veChungToi->nt_dun_lo_anh) && count($veChungToi->nt_dun_lo_anh) > 0)
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 mb-2">Ảnh hiện tại (Ưu tiên hiện 2 ảnh. Chọn để xóa):</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($veChungToi->nt_dun_lo_anh as $idx => $path)
                                    <div class="relative group aspect-square rounded-lg border overflow-hidden bg-white">
                                        <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover">
                                        <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center cursor-pointer">
                                            <input type="checkbox" name="delete_nt_dun_lo_anh[]" value="{{ $idx }}" class="w-5 h-5 text-red-600 rounded">
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Thêm ảnh đun lò mới (Ưu tiên upload 2 ảnh)</label>
                        <input type="file" id="input-nt-dun-lo" name="new_nt_dun_lo_anh[]" multiple accept="image/*" onchange="previewMultipleImages(event, 'preview-nt-dun-lo', 'cover')" class="w-full text-sm border rounded-lg bg-white p-1">
                        <div id="preview-nt-dun-lo"></div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end gap-3 sticky bottom-0 bg-white p-4 border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] -mx-6 -mb-6 z-50">
            <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                Lưu Thay Đổi Trang
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        // Cấu hình dữ liệu hiện tại từ Laravel sang JS
        const existingData = {
            gs_head: @json($veChungToi->gs_head ??[]),
            gs_gia_tri: @json($veChungToi->gs_gia_tri ??[]),
            gs_hanh_trinh: @json($veChungToi->gs_hanh_trinh ??[]),
            gs_giai_thuong: @json($veChungToi->gs_giai_thuong ??[]),
            nt_ngon_ngu: @json($veChungToi->nt_ngon_ngu ??[]),
            nt_luyen_dat_item: @json($veChungToi->nt_luyen_dat_item ??[]),
        };

        let blockIndexes = {
            gs_head: 0, gs_gia_tri: 0, gs_hanh_trinh: 0, gs_giai_thuong: 0, nt_ngon_ngu: 0, nt_luyen_dat_item: 0
        };

        // Hàm preview ảnh đơn giản
        function previewImage(event, targetId) {
            const file = event.target.files[0];
            if (file) {
                const objectUrl = URL.createObjectURL(file);
                document.getElementById(targetId).src = objectUrl;
                document.getElementById(targetId).onload = function() { URL.revokeObjectURL(objectUrl); }
            }
        }

        // --- BỘ QUẢN LÝ ẢNH PREVIEW MULTIPLE ---
        function previewMultipleImages(event, containerId, objectFit = 'cover') {
            const input = event.target;
            const container = document.getElementById(containerId);
            renderPreviews(input, container, objectFit);
        }

        function renderPreviews(input, container, objectFit) {
            container.innerHTML = '';
            const files = Array.from(input.files);
            if (files.length > 0) {
                container.className = 'mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-gray-50 border border-dashed rounded-lg';
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group aspect-square rounded-lg border overflow-hidden bg-white shadow-sm';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-${objectFit}">
                            <div class="absolute top-1 right-1 bg-green-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow z-10">Mới</div>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-20">
                                <button type="button" class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm" onclick="removePendingFile('${input.id}', '${container.id}', ${index}, '${objectFit}')" title="Xóa ảnh này">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        `;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            } else {
                container.className = '';
            }
        }

        function removePendingFile(inputId, containerId, indexToRemove, objectFit) {
            const input = document.getElementById(inputId);
            const container = document.getElementById(containerId);
            const dt = new DataTransfer();
            const files = Array.from(input.files);
            files.forEach((file, index) => { if (index !== indexToRemove) dt.items.add(file); });
            input.files = dt.files;
            renderPreviews(input, container, objectFit);
        }

        // --- BỘ QUẢN LÝ DYNAMIC JSON BLOCK (Đầy đủ: Ảnh, Head, Body) ---
        function addJsonBlock(fieldName, containerId, headLabel = 'Tiêu đề (Head)', bodyLabel = 'Mô tả (Body)') {
            const container = document.getElementById(containerId);
            const idx = blockIndexes[fieldName]++;
            const uniqueId = fieldName + '_' + idx;

            const div = document.createElement('div');
            div.className = 'bg-white border border-gray-200 rounded-xl p-4 shadow-sm relative group flex flex-col gap-3';
            div.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm hover:bg-red-600 hover:text-white z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="aspect-[4/3] w-full bg-gray-100 rounded-lg border border-dashed border-gray-300 relative overflow-hidden flex items-center justify-center">
                    <img id="preview_${uniqueId}" src="https://placehold.co/400x300?text=Chon+Anh" class="w-full h-full object-cover">
                    <input type="file" name="${fieldName}[${idx}][new_image]" accept="image/*" onchange="previewImage(event, 'preview_${uniqueId}')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1">${headLabel}</label>
                    <input type="text" name="${fieldName}[${idx}][head]" class="w-full px-2 py-1.5 text-sm border rounded outline-none focus:border-[#A31D1D] font-bold">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1">${bodyLabel}</label>
                    <textarea name="${fieldName}[${idx}][body]" rows="3" class="w-full px-2 py-1.5 text-sm border rounded outline-none focus:border-[#A31D1D]"></textarea>
                </div>
            `;
            container.appendChild(div);
            return div;
        }

        // --- BỘ QUẢN LÝ DYNAMIC JSON BLOCK (Chỉ có Ảnh, Head) dùng cho Giải thưởng ---
        function addJsonBlockImageOnly(fieldName, containerId, headLabel = 'Tiêu đề (Head)') {
            const container = document.getElementById(containerId);
            const idx = blockIndexes[fieldName]++;
            const uniqueId = fieldName + '_' + idx;

            const div = document.createElement('div');
            div.className = 'bg-white border border-gray-200 rounded-xl p-4 shadow-sm relative group flex flex-col gap-3';
            div.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm hover:bg-red-600 hover:text-white z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="aspect-[4/3] w-full bg-gray-100 rounded-lg border border-dashed border-gray-300 relative overflow-hidden flex items-center justify-center">
                    <img id="preview_${uniqueId}" src="https://placehold.co/400x300?text=Chon+Anh" class="w-full h-full object-cover">
                    <input type="file" name="${fieldName}[${idx}][new_image]" accept="image/*" onchange="previewImage(event, 'preview_${uniqueId}')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1">${headLabel}</label>
                    <input type="text" name="${fieldName}[${idx}][head]" class="w-full px-2 py-1.5 text-sm border rounded outline-none focus:border-[#A31D1D] font-bold text-center">
                </div>
            `;
            container.appendChild(div);
            return div;
        }

        // Hàm populate dữ liệu có sẵn từ CSDL
        function populateJsonBlocks(fieldName, containerId, defaultCount, isImageOnly = false, headLabel = 'Tiêu đề (Head)', bodyLabel = 'Mô tả (Body)') {
            const data = existingData[fieldName];
            if (data && data.length > 0) {
                data.forEach(item => {
                    const block = isImageOnly ? addJsonBlockImageOnly(fieldName, containerId, headLabel) : addJsonBlock(fieldName, containerId, headLabel, bodyLabel);
                    const idx = blockIndexes[fieldName] - 1; // Vì hàm add đã tăng idx
                    
                    // Điền dữ liệu
                    const imgEl = block.querySelector('img');
                    if (item.image) {
                        imgEl.src = '/storage/' + item.image;
                        // Thêm input hidden chứa path cũ
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = `${fieldName}[${idx}][old_image]`;
                        hiddenInput.value = item.image;
                        block.appendChild(hiddenInput);
                    }

                    if(item.head) block.querySelector(`input[name="${fieldName}[${idx}][head]"]`).value = item.head;
                    if(!isImageOnly && item.body) block.querySelector(`textarea[name="${fieldName}[${idx}][body]"]`).value = item.body;
                });
            } else {
                // Nếu chưa có, tạo mặc định dựa trên yêu cầu ưu tiên
                for (let i = 0; i < defaultCount; i++) {
                    isImageOnly ? addJsonBlockImageOnly(fieldName, containerId, headLabel) : addJsonBlock(fieldName, containerId, headLabel, bodyLabel);
                }
            }
        }

        // Chạy khởi tạo
        populateJsonBlocks('gs_head', 'gs-head-container', 2);
        populateJsonBlocks('gs_gia_tri', 'gs-gia-tri-container', 3);
        populateJsonBlocks('gs_hanh_trinh', 'gs-hanh-trinh-container', 5, false, 'Năm (VD: 2000)');
        populateJsonBlocks('gs_giai_thuong', 'gs-giai-thuong-container', 3, true, 'Năm (VD: 2024)');
        populateJsonBlocks('nt_ngon_ngu', 'nt-ngon-ngu-container', 1);
        populateJsonBlocks('nt_luyen_dat_item', 'nt-luyen-dat-container', 3);

    </script>
    @endpush
</x-admin.layout.app>