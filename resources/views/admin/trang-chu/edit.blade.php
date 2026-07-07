<x-admin.layouts.app title="Trang chủ" breadcrumb="Admin › Cấu hình trang đơn › Trang chủ">
    <form method="POST" action="{{ route('admin.trang_chu.update') }}" enctype="multipart/form-data" class="space-y-8 pb-10">
        @csrf @method('PUT')

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl text-sm font-sans mb-6">
                <div class="flex items-center gap-2 font-bold text-red-800 mb-2">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Có lỗi xảy ra, vui lòng kiểm tra lại:</span>
                </div>
                <ul class="list-disc pl-7 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- E-commerce mode toggle -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Chế độ bán hàng</h2>
            </div>
            <div class="p-6">
                <input type="hidden" name="is_ecommerce_enabled" value="0">
                <label class="flex items-start gap-4 cursor-pointer">
                    <input type="checkbox" name="is_ecommerce_enabled" value="1"
                        class="mt-1 w-5 h-5 text-[#A31D1D] border-gray-300 rounded focus:ring-[#A31D1D]"
                        @checked(old('is_ecommerce_enabled', $trangChu->is_ecommerce_enabled ?? true))>
                    <div>
                        <span class="text-sm font-semibold text-gray-800">Chế độ bán hàng trực tuyến (Giỏ hàng &amp; Thanh toán)</span>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                            Khi tắt, website chuyển sang chế độ trưng bày B2B: ẩn giỏ hàng, chặn thanh toán, và nút sản phẩm mở form yêu cầu tư vấn / báo giá.
                        </p>
                    </div>
                </label>
            </div>
        </div>

        <!-- 1. Banner Trang Chủ -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Banner Trang Chủ</h2>
            </div>
            <div class="p-6">
                @if(is_array($trangChu->banner) && count($trangChu->banner) > 0)
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-2">Ảnh hiện tại (Đánh dấu để xóa):</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($trangChu->banner as $idx => $path)
                            <div class="relative group aspect-video rounded-lg border border-gray-200 overflow-hidden bg-gray-50">
                                <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-contain">
                                <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                                    <input type="checkbox" name="delete_banner[]" value="{{ $idx }}" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                    <span class="text-white text-xs font-bold ml-2">Xóa ảnh này</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="relative">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Thêm ảnh Banner mới</label>
                    <input type="file" id="input-new-banner" name="new_banner[]" multiple accept="image/*" onchange="previewMultipleImages(event, 'preview-new-banner', 'cover')" class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white">
                    <div id="preview-new-banner"></div> <!-- Container cho preview ảnh mới -->
                </div>
            </div>
        </div>

        <!-- 2. Khách hàng đối tác -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Khách Hàng - Đối Tác</h2>
            </div>
            <div class="p-6">
                @if(is_array($trangChu->khach_hang_doi_tac) && count($trangChu->khach_hang_doi_tac) > 0)
                <div class="mb-4">
                    <p class="text-xs text-gray-500 mb-2">Ảnh hiện tại (Đánh dấu để xóa):</p>
                    <div class="grid grid-cols-3 md:grid-cols-5 lg:grid-cols-8 gap-4">
                        @foreach($trangChu->khach_hang_doi_tac as $idx => $path)
                            <div class="relative group aspect-square rounded-lg border border-gray-200 overflow-hidden bg-white p-2 flex items-center justify-center">
                                <img src="{{ asset('storage/' . $path) }}" class="max-w-full max-h-full object-contain">
                                <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                                    <input type="checkbox" name="delete_khach_hang[]" value="{{ $idx }}" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                    <span class="text-white text-xs font-bold ml-2">Xóa</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="relative">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Thêm Logo đối tác mới</label>
                    <input type="file" id="input-new-khach-hang" name="new_khach_hang[]" multiple accept="image/*" onchange="previewMultipleImages(event, 'preview-new-khach-hang', 'contain')" class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white">
                    <div id="preview-new-khach-hang"></div> <!-- Container cho preview ảnh mới -->
                </div>
            </div>
        </div>

        <!-- 3. Lời Tri Ân -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Lời Tri Ân</h2>
            </div>
            <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Ảnh -->
                <div class="lg:col-span-1 flex flex-col items-center">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 text-center w-full">Hình ảnh Lời Tri Ân</label>
                    <div class="aspect-[3/4] w-full max-w-[240px] mx-auto rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
                        <img id="preview-loi-tri-an" src="{{ $trangChu->loi_tri_an_anh ? asset('storage/' . $trangChu->loi_tri_an_anh) : 'https://placehold.co/600x800?text=Chon+Anh' }}" class="w-full h-full object-contain">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi</span>
                        </div>
                        <input type="file" name="loi_tri_an_anh" accept="image/*" onchange="previewImage(event, 'preview-loi-tri-an')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                </div>
                <!-- Texts -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-bold text-gray-800">Các đoạn văn Lời Tri Ân</label>
                        <button type="button" onclick="addLoiTriAnBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 hover:text-[#A31D1D] hover:border-[#A31D1D] transition-colors shadow-sm flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Thêm đoạn
                        </button>
                    </div>
                    <div id="loi-tri-an-container" class="space-y-3">
                        <!-- JS render -->
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Về Chúng Tôi (Video & Logo) -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Về Chúng Tôi (Phần giới thiệu nhanh)</h2>
            </div>
            <div class="p-6 space-y-8">
                <!-- Video -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Đường dẫn Video YouTube</label>
                    <input type="url" name="video" value="{{ old('video', $trangChu->video) }}" placeholder="https://youtube.com/watch?v=..." class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                </div>
                <!-- Logo list -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-4">Danh sách Logo (Đài báo, Tạp chí, Đài truyền hình...)</label>
                    @if(is_array($trangChu->ve_chung_toi_logo) && count($trangChu->ve_chung_toi_logo) > 0)
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-2">Ảnh hiện tại (Đánh dấu để xóa):</p>
                        <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
                            @foreach($trangChu->ve_chung_toi_logo as $idx => $path)
                                <div class="relative group aspect-square rounded-lg border border-gray-200 overflow-hidden bg-white p-2 flex items-center justify-center">
                                    <img src="{{ asset('storage/' . $path) }}" class="max-w-full max-h-full object-contain">
                                    <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                                        <input type="checkbox" name="delete_ve_chung_toi_logo[]" value="{{ $idx }}" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                        <span class="text-white text-xs font-bold ml-2">Xóa</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div class="relative">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Thêm Logo mới</label>
                        <input type="file" id="input-new-ve-chung-toi-logo" name="new_ve_chung_toi_logo[]" multiple accept="image/*" onchange="previewMultipleImages(event, 'preview-new-ve-chung-toi-logo', 'contain')" class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white">
                        <div id="preview-new-ve-chung-toi-logo"></div> <!-- Container cho preview ảnh mới -->
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Những con số -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Những Con Số Ấn Tượng</h2>
                <button type="button" onclick="addNhungConSoBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 hover:text-[#A31D1D] hover:border-[#A31D1D] transition-colors shadow-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Thêm ô
                </button>
            </div>
            <div class="p-6">
                <!-- Chuyển xl:grid-cols-4 thành xl:grid-cols-5 để 5 ô 1 hàng -->
                <div id="nhung-con-so-container" class="grid grid-cols-1 sm:grid-cols-3 xl:grid-cols-5 gap-4">
                    <!-- JS render -->
                </div>
            </div>
        </div>

        <!-- 6. Showroom -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Showroom</h2>
            </div>
            <div class="p-6 space-y-8">
                <!-- Nội dung Showroom -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nội dung giới thiệu Showroom</label>
                    <textarea name="showroom_noidung" rows="4" placeholder="Nhập nội dung giới thiệu showroom..." class="w-full px-4 py-3 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">{{ old('showroom_noidung', $trangChu->showroom_noidung) }}</textarea>
                </div>
                <!-- Gallery UI cho Showroom -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-4">Hình ảnh Showroom</label>
                    @if(is_array($trangChu->showroom_images) && count($trangChu->showroom_images) > 0)
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-2">Ảnh hiện tại (Đánh dấu để xóa):</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach($trangChu->showroom_images as $idx => $path)
                                <div class="relative group aspect-square rounded-lg border border-gray-200 overflow-hidden bg-gray-50">
                                    <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-contain">
                                    <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                                        <input type="checkbox" name="delete_showroom_images[]" value="{{ $idx }}" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                        <span class="text-white text-xs font-bold ml-2">Xóa ảnh này</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div class="relative">
                        <label class="block text-xs font-semibold text-gray-500 mb-2">Thêm ảnh Showroom mới</label>
                        <input type="file" id="input-new-showroom" name="new_showroom_images[]" multiple accept="image/*" onchange="previewMultipleImages(event, 'preview-new-showroom', 'cover')" class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white">
                        <div id="preview-new-showroom"></div> <!-- Container cho preview ảnh mới -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end gap-3 sticky bottom-0 bg-white p-4 border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] -mx-6 -mb-6 z-50">
            <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                Lưu Thay Đổi Trang Chủ
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        // Validate file size helper (client-side)
        function validateFileSizes(input, maxSizeMB = 5) {
            const maxSizeBytes = maxSizeMB * 1024 * 1024;
            const files = Array.from(input.files);
            const oversizedFiles = files.filter(file => file.size > maxSizeBytes);

            if (oversizedFiles.length > 0) {
                const fileNames = oversizedFiles.map(file => `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`).join('\n');
                alert(`Lỗi: Các ảnh sau đây vượt quá dung lượng tối đa cho phép (${maxSizeMB}MB):\n\n${fileNames}\n\nVui lòng chọn ảnh khác có dung lượng nhỏ hơn.`);

                // Clear the input
                input.value = "";
                return false;
            }
            return true;
        }

        // Preview ảnh đơn lẻ (Lời Tri Ân)
        function previewImage(event, targetId) {
            const input = event.target;
            if (!validateFileSizes(input, 5)) {
                return;
            }
            const file = input.files[0];
            if (file) {
                const objectUrl = URL.createObjectURL(file);
                document.getElementById(targetId).src = objectUrl;
                document.getElementById(targetId).onload = function() {
                    URL.revokeObjectURL(objectUrl);
                }
            }
        }

        // --- BỘ QUẢN LÝ ẢNH PREVIEW CÓ THỂ XOÁ ĐƯỢC ---
        function previewMultipleImages(event, containerId, objectFit = 'cover') {
            const input = event.target;
            if (!validateFileSizes(input, 5)) {
                document.getElementById(containerId).innerHTML = '';
                document.getElementById(containerId).className = '';
                return;
            }
            const container = document.getElementById(containerId);
            renderPreviews(input, container, objectFit);
        }

        function renderPreviews(input, container, objectFit) {
            container.innerHTML = '';
            const files = Array.from(input.files);

            if (files.length > 0) {
                container.className = 'mt-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 p-4 bg-gray-50 border border-gray-200 border-dashed rounded-lg';
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group aspect-square rounded-lg border border-gray-300 overflow-hidden bg-white shadow-sm';
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

            // Push lại các file không bị xoá
            files.forEach((file, index) => {
                if (index !== indexToRemove) {
                    dt.items.add(file);
                }
            });

            // Ghi đè lại input files
            input.files = dt.files;

            // Render lại giao diện
            renderPreviews(input, container, objectFit);
        }
        // ----------------------------------------------

        // JS Lời tri ân
        const loiTriAnContainer = document.getElementById('loi-tri-an-container');
        const existingLoiTriAn = @json(is_array($trangChu->loi_tri_an) ? $trangChu->loi_tri_an :[]);

        function addLoiTriAnBlock(value = '') {
            const div = document.createElement('div');
            div.className = 'flex items-start gap-2';
            div.innerHTML = `
                <textarea name="loi_tri_an[]" rows="2" placeholder="Nhập nội dung đoạn văn..." class="flex-1 px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none transition-all resize-y">${value.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;')}</textarea>
                <button type="button" onclick="this.parentElement.remove()" class="p-2 text-red-400 hover:text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors" title="Xóa đoạn này">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            `;
            loiTriAnContainer.appendChild(div);
        }

        if (existingLoiTriAn.length > 0) {
            existingLoiTriAn.forEach(val => addLoiTriAnBlock(val));
        } else {
            addLoiTriAnBlock('');
        }

        // JS Những con số
        const nhungConSoContainer = document.getElementById('nhung-con-so-container');
        const existingNhungConSo = @json(is_array($trangChu->nhung_con_so) ? $trangChu->nhung_con_so :[]);
        let ncsIndex = 0;

        function addNhungConSoBlock(head = '', body = '') {
            const div = document.createElement('div');
            div.className = 'bg-gray-50 border border-gray-200 rounded-xl p-4 relative group';
            div.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm hover:bg-red-600 hover:text-white" title="Xóa ô này">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Số lượng / Head</label>
                        <input type="text" name="nhung_con_so[${ncsIndex}][head]" value="${head.replace(/"/g, '&quot;')}" placeholder="VD: 500" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none font-bold text-center text-xl text-[#A31D1D]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Mô tả / Body</label>
                        <input type="text" name="nhung_con_so[${ncsIndex}][body]" value="${body.replace(/"/g, '&quot;')}" placeholder="VD: Công trình lớn nhỏ" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none text-center">
                    </div>
                </div>
            `;
            nhungConSoContainer.appendChild(div);
            ncsIndex++;
        }

        if (existingNhungConSo.length > 0) {
            existingNhungConSo.forEach(item => addNhungConSoBlock(item.head || '', item.body || ''));
        } else {
            addNhungConSoBlock();
        }
    </script>
    @endpush
</x-admin.layouts.app>
