<x-admin.layout.app title="Về Chúng Tôi" breadcrumb="Admin › Cấu hình trang đơn › Về Chúng Tôi">
    <div class="space-y-6 pb-10">
        
        <!-- ==================== TAB NAVIGATION (Đã bỏ sticky) ==================== -->
        <div class="bg-white rounded-xl shadow-sm p-2 flex flex-wrap gap-2 border border-gray-200 mb-6">
            <button type="button" onclick="switchTab('banner')" id="btn-banner" class="tab-btn whitespace-nowrap px-6 py-3 rounded-lg font-bold text-sm transition-all bg-[#A31D1D] text-white shadow-md">
                1. Banner Đầu Trang
            </button>
            <button type="button" onclick="switchTab('gom-su')" id="btn-gom-su" class="tab-btn whitespace-nowrap px-6 py-3 rounded-lg font-bold text-sm transition-all bg-gray-50 text-gray-600 hover:bg-gray-100 border border-transparent">
                2. Về Gốm Sứ Thanh Hải
            </button>
            <button type="button" onclick="switchTab('che-tac')" id="btn-che-tac" class="tab-btn whitespace-nowrap px-6 py-3 rounded-lg font-bold text-sm transition-all bg-gray-50 text-gray-600 hover:bg-gray-100 border border-transparent">
                3. Nghệ Thuật Thủ Công
            </button>
        </div>

        <!-- Tùy chọn hiển thị thông báo nhắc nhở -->
        <div id="tab-notice" class="hidden bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl text-sm">
            <span class="font-bold">📌 Lưu ý:</span> Khu vực này có nhiều khối dữ liệu. Hãy bấm nút <span class="font-bold text-[#A31D1D]">"Lưu"</span> tương ứng ở góc phải của mỗi khối sau khi bạn thay đổi xong nhé.
        </div>

        <!-- ==================== TAB 1: BANNER ==================== -->
        <div id="tab-banner" class="tab-content block transition-all duration-300">
            <form method="POST" action="{{ route('admin.pages.ve_chung_toi.update',['section' => 'banner']) }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                @csrf @method('PUT')
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Khu Vực Banner Đầu Trang</h2>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">Lưu Banner</button>
                </div>
                <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cột Ảnh -->
                    <div class="lg:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh Banner</label>
                        <div class="aspect-[16/9] w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
                            <img id="preview-banner" src="{{ $veChungToi->banner ? asset('storage/' . $veChungToi->banner) : 'https://placehold.co/800x450?text=Chon+Anh' }}" class="w-full h-full object-contain">
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
            </form>
        </div>

        <!-- ==================== TAB 2: VỀ GỐM SỨ THANH HẢI ==================== -->
        <div id="tab-gom-su" class="tab-content hidden space-y-8 transition-all duration-300">
            <!-- KHỐI 2.1 -->
            <form method="POST" action="{{ route('admin.pages.ve_chung_toi.update',['section' => 'gom_su_1']) }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                @csrf @method('PUT')
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Điểm Nhấn & Giá Trị Cốt Lõi</h2>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">Lưu Khối Này</button>
                </div>
                <div class="p-6 space-y-6">
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-2">
                            <h3 class="text-sm font-bold text-gray-700">Điểm Nhấn </h3>
                            <button type="button" onclick="addJsonBlock('gs_head', 'gs-head-container')" class="text-xs px-3 py-1 bg-white border border-blue-200 text-blue-600 rounded-md font-bold hover:bg-blue-50 transition-colors">+ Thêm Điểm Nhấn</button>
                        </div>
                        <div id="gs-head-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4"></div>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-2">
                            <h3 class="text-sm font-bold text-gray-700">Giá Trị Cốt Lõi </h3>
                            <button type="button" onclick="addJsonBlock('gs_gia_tri', 'gs-gia-tri-container')" class="text-xs px-3 py-1 bg-white border border-blue-200 text-blue-600 rounded-md font-bold hover:bg-blue-50 transition-colors">+ Thêm Giá Trị</button>
                        </div>
                        <div id="gs-gia-tri-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-4"></div>
                    </div>
                </div>
            </form>

            <!-- KHỐI 2.2 -->
            <form method="POST" action="{{ route('admin.pages.ve_chung_toi.update',['section' => 'gom_su_2']) }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                @csrf @method('PUT')
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Lịch Sử & Giải Thưởng</h2>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">Lưu Khối Này</button>
                </div>
                <div class="p-6 space-y-6">
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-2">
                            <h3 class="text-sm font-bold text-gray-700">Hành Trình Lịch Sử </h3>
                            <button type="button" onclick="addJsonBlock('gs_hanh_trinh', 'gs-hanh-trinh-container', 'Năm (VD: 2000)')" class="text-xs px-3 py-1 bg-white border border-blue-200 text-blue-600 rounded-md font-bold hover:bg-blue-50 transition-colors">+ Thêm Mốc Lịch Sử</button>
                        </div>
                        <div id="gs-hanh-trinh-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4"></div>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-2">
                            <h3 class="text-sm font-bold text-gray-700">Giải Thưởng</h3>
                            <button type="button" onclick="addJsonBlockImageOnly('gs_giai_thuong', 'gs-giai-thuong-container', 'Năm (VD: 2024)')" class="text-xs px-3 py-1 bg-white border border-blue-200 text-blue-600 rounded-md font-bold hover:bg-blue-50 transition-colors">+ Thêm Giải Thưởng</button>
                        </div>
                        <div id="gs-giai-thuong-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4"></div>
                    </div>
                </div>
            </form>

            <!-- KHỐI 2.3 -->
            <form method="POST" action="{{ route('admin.pages.ve_chung_toi.update',['section' => 'gom_su_3']) }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                @csrf @method('PUT')
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Người Sáng Lập</h2>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">Lưu Khối Này</button>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    <div class="md:col-span-1 lg:col-span-1">
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Ảnh Người Sáng Lập</label>
                        <div class="aspect-square w-full rounded-xl border border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group hover:opacity-90">
                            <img id="preview-nsl" src="{{ $veChungToi->gs_nguoi_sang_lap_anh ? asset('storage/' . $veChungToi->gs_nguoi_sang_lap_anh) : 'https://placehold.co/400x400' }}" class="w-full h-full object-contain">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                            </div>
                            <input type="file" name="gs_nguoi_sang_lap_anh" accept="image/*" onchange="previewImage(event, 'preview-nsl')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                    </div>
                    <div class="md:col-span-3 lg:col-span-4">
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Nội dung câu chuyện</label>
                        <textarea name="gs_nguoi_sang_lap_noi_dung" rows="6" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">{{ old('gs_nguoi_sang_lap_noi_dung', $veChungToi->gs_nguoi_sang_lap_noi_dung) }}</textarea>
                    </div>
                </div>
            </form>
        </div>

        <!-- ==================== TAB 3: NGHỆ THUẬT THỦ CÔNG ==================== -->
        <div id="tab-che-tac" class="tab-content hidden space-y-8 transition-all duration-300">
            <form method="POST" action="{{ route('admin.pages.ve_chung_toi.update',['section' => 'che_tac']) }}" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                @csrf @method('PUT')
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Thông tin Nghệ Thuật Chế Tác</h2>
                    <button type="submit" class="px-6 py-2 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">Lưu Toàn Bộ Tab 3</button>
                </div>
                
                <div class="p-6 space-y-8">
                    <!-- Chung -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 bg-gray-50 p-5 rounded-xl border border-gray-200">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Tiêu đề Chính</label>
                            <input type="text" name="nt_head" value="{{ old('nt_head', $veChungToi->nt_head) }}" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Mô tả Chính</label>
                            <textarea name="nt_body" rows="2" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">{{ old('nt_body', $veChungToi->nt_body) }}</textarea>
                        </div>
                    </div>

                    <!-- NT Ngôn ngữ (Cố định 1 Item dàn ngang nhỏ gọn) -->
                    <div class="bg-white rounded-xl border border-gray-200">
                        <div class="px-5 py-3 border-b border-gray-100">
                            <h3 class="text-sm font-bold text-gray-700">Ngôn Ngữ Vật Liệu</h3>
                        </div>
                        <div class="p-5">
                            @php
                                $ngonNgu = $veChungToi->nt_ngon_ngu[0] ?? ['head' => '', 'body' => '', 'image' => ''];
                            @endphp
                            <div class="flex flex-col md:flex-row gap-6 items-start">
                                <!-- Box Ảnh Gọn Nhàng -->
                                <div class="w-full md:w-1/3 lg:w-1/4 shrink-0">
                                    <label class="block text-[11px] font-semibold text-gray-500 mb-2">Ảnh minh họa</label>
                                    <div class="aspect-[4/3] w-full bg-gray-50 rounded-lg border border-dashed border-gray-300 relative overflow-hidden flex items-center justify-center group hover:bg-gray-100 transition-colors">
                                        <img id="preview_nt_ngon_ngu" src="{{ !empty($ngonNgu['image']) ? asset('storage/' . $ngonNgu['image']) : 'https://placehold.co/400x300?text=Chon+Anh' }}" class="w-full h-full object-contain">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="text-white text-[10px] font-medium px-2 py-1 bg-black/50 rounded">Thay đổi ảnh</span>
                                        </div>
                                        <input type="file" name="nt_ngon_ngu[0][new_image]" accept="image/*" onchange="previewImage(event, 'preview_nt_ngon_ngu')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <input type="hidden" name="nt_ngon_ngu[0][old_image]" value="{{ $ngonNgu['image'] ?? '' }}">
                                    </div>
                                </div>
                                <!-- Nội Dung Nhập -->
                                <div class="w-full md:w-2/3 lg:w-3/4 space-y-4">
                                    <div>
                                        <label class="block text-[11px] font-semibold text-gray-500 mb-1">Tiêu đề</label>
                                        <input type="text" name="nt_ngon_ngu[0][head]" value="{{ $ngonNgu['head'] ?? '' }}" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg outline-none focus:border-[#A31D1D] font-bold text-gray-800">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-gray-500 mb-1">Mô tả chi tiết</label>
                                        <textarea name="nt_ngon_ngu[0][body]" rows="4" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg outline-none focus:border-[#A31D1D] text-gray-600">{{ $ngonNgu['body'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- NT vật liệu-->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <h3 class="text-sm font-bold text-gray-700 mb-4 border-b border-gray-200 pb-2">Khâu Chế Tác</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 mb-1">Tiêu đề</label>
                                <input type="text" name="nt_che_tac_head" value="{{ old('nt_che_tac_head', $veChungToi->nt_che_tac_head) }}" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 outline-none focus:border-[#A31D1D]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 mb-1">Mô tả</label>
                                <input type="text" name="nt_che_tac_body" value="{{ old('nt_che_tac_body', $veChungToi->nt_che_tac_body) }}" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 outline-none focus:border-[#A31D1D]">
                            </div>
                        </div>
                        @if(is_array($veChungToi->nt_che_tac_anh) && count($veChungToi->nt_che_tac_anh) > 0)
                            <div class="mb-4 bg-white p-3 rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-500 mb-2 font-semibold">Ảnh hiện tại (Đánh dấu để xóa):</p>
                                <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                    @foreach($veChungToi->nt_che_tac_anh as $idx => $path)
                                        <div class="relative group aspect-[4/3] rounded-lg border overflow-hidden bg-white shadow-sm hover:border-red-400">
                                            <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-contain">
                                            <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center cursor-pointer transition-opacity">
                                                <input type="checkbox" name="delete_nt_che_tac_anh[]" value="{{ $idx }}" class="w-5 h-5 text-red-600 rounded cursor-pointer">
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-2">Thêm ảnh vật liệumới</label>
                            <input type="file" id="input-nt-che-tac" name="new_nt_che_tac_anh[]" multiple accept="image/*" onchange="previewMultipleImages(event, 'preview-nt-che-tac', 'cover')" class="w-full text-sm border rounded-lg bg-white p-1">
                            <div id="preview-nt-che-tac"></div>
                        </div>
                    </div>

                    <!-- NT Luyện Đất -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-2">
                            <h3 class="text-sm font-bold text-gray-700">Luyện Đất</h3>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 mb-1">Tiêu đề</label>
                                <input type="text" name="nt_luyen_dat_head" value="{{ old('nt_luyen_dat_head', $veChungToi->nt_luyen_dat_head) }}" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 outline-none focus:border-[#A31D1D]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 mb-1">Mô tả</label>
                                <input type="text" name="nt_luyen_dat_body" value="{{ old('nt_luyen_dat_body', $veChungToi->nt_luyen_dat_body) }}" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 outline-none focus:border-[#A31D1D]">
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-6 mb-4">
                            <span class="text-sm font-bold text-gray-600 border-l-4 border-[#A31D1D] pl-2">Kỹ thuật luyện đất</span>
                            <button type="button" onclick="addJsonBlock('nt_luyen_dat_item', 'nt-luyen-dat-container')" class="text-xs px-3 py-1 bg-white border border-blue-200 text-blue-600 rounded-md font-bold hover:bg-blue-50 transition-colors">+ Thêm Bước</button>
                        </div>
                        <div id="nt-luyen-dat-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-4"></div>
                    </div>

                    <!-- NT Đun Lò -->
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <h3 class="text-sm font-bold text-gray-700 mb-4 border-b border-gray-200 pb-2">Đun Lò</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 mb-1">Tiêu đề</label>
                                <input type="text" name="nt_dun_lo_head" value="{{ old('nt_dun_lo_head', $veChungToi->nt_dun_lo_head) }}" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 outline-none focus:border-[#A31D1D]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 mb-1">Mô tả</label>
                                <input type="text" name="nt_dun_lo_body" value="{{ old('nt_dun_lo_body', $veChungToi->nt_dun_lo_body) }}" class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 outline-none focus:border-[#A31D1D]">
                            </div>
                        </div>
                        @if(is_array($veChungToi->nt_dun_lo_anh) && count($veChungToi->nt_dun_lo_anh) > 0)
                            <div class="mb-4 bg-white p-3 rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-500 mb-2 font-semibold">Ảnh hiện tại (Đánh dấu để xóa):</p>
                                <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                    @foreach($veChungToi->nt_dun_lo_anh as $idx => $path)
                                        <div class="relative group aspect-[4/3] rounded-lg border overflow-hidden bg-white shadow-sm hover:border-red-400">
                                            <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-contain">
                                            <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center cursor-pointer transition-opacity">
                                                <input type="checkbox" name="delete_nt_dun_lo_anh[]" value="{{ $idx }}" class="w-5 h-5 text-red-600 rounded cursor-pointer">
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-2">Thêm ảnh đun lò mới</label>
                            <input type="file" id="input-nt-dun-lo" name="new_nt_dun_lo_anh[]" multiple accept="image/*" onchange="previewMultipleImages(event, 'preview-nt-dun-lo', 'cover')" class="w-full text-sm border rounded-lg bg-white p-1">
                            <div id="preview-nt-dun-lo"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // ==================== TAB LOGIC ====================
        function switchTab(tabId) {
            // Ẩn tất cả nội dung
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.remove('block');
                el.classList.add('hidden');
            });
            // Reset tất cả các nút
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('bg-[#A31D1D]', 'text-white', 'shadow-md');
                el.classList.add('bg-gray-50', 'text-gray-600', 'border-transparent');
                el.classList.remove('border-[#A31D1D]');
            });

            // Hiển thị nội dung của tab được chọn
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            document.getElementById('tab-' + tabId).classList.add('block');

            // Làm nổi bật nút được chọn
            const activeBtn = document.getElementById('btn-' + tabId);
            activeBtn.classList.remove('bg-gray-50', 'text-gray-600', 'border-transparent');
            activeBtn.classList.add('bg-[#A31D1D]', 'text-white', 'shadow-md');

            // Hiển thị thông báo nhắc nhở nếu ở Tab 2
            const noticeBox = document.getElementById('tab-notice');
            if(tabId === 'gom-su') {
                noticeBox.classList.remove('hidden');
            } else {
                noticeBox.classList.add('hidden');
            }

            // Lưu trạng thái vào localStorage để khi F5 hoặc Save vẫn ở Tab đó
            localStorage.setItem('activeVeChungToiTab', tabId);
        }

        // Tự động khôi phục Tab sau khi tải lại trang
        document.addEventListener('DOMContentLoaded', () => {
            const savedTab = localStorage.getItem('activeVeChungToiTab') || 'banner';
            switchTab(savedTab);
        });

        // ==================== DỮ LIỆU CŨ ====================
        const existingData = {
            gs_head: @json($veChungToi->gs_head ??[]),
            gs_gia_tri: @json($veChungToi->gs_gia_tri ??[]),
            gs_hanh_trinh: @json($veChungToi->gs_hanh_trinh ??[]),
            gs_giai_thuong: @json($veChungToi->gs_giai_thuong ??[]),
            nt_luyen_dat_item: @json($veChungToi->nt_luyen_dat_item ??[]),
        };
        // Bỏ nt_ngon_ngu ra khỏi mảng generate JS vì đã tạo template cố định bằng HTML.
        let blockIndexes = {
            gs_head: 0, gs_gia_tri: 0, gs_hanh_trinh: 0, gs_giai_thuong: 0, nt_luyen_dat_item: 0
        };

        // ==================== IMAGE PREVIEW LOGIC ====================
        function previewImage(event, targetId) {
            const file = event.target.files[0];
            if (file) {
                const objectUrl = URL.createObjectURL(file);
                document.getElementById(targetId).src = objectUrl;
                document.getElementById(targetId).onload = function() { URL.revokeObjectURL(objectUrl); }
            }
        }
        function previewMultipleImages(event, containerId, objectFit = 'cover') {
            const input = event.target;
            const container = document.getElementById(containerId);
            renderPreviews(input, container, objectFit);
        }
        function renderPreviews(input, container, objectFit) {
            container.innerHTML = '';
            const files = Array.from(input.files);
            if (files.length > 0) {
                container.className = 'mt-4 grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 p-3 bg-white border border-gray-200 rounded-lg shadow-sm';
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative group aspect-[4/3] rounded-lg border border-gray-200 overflow-hidden bg-white shadow-sm';
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

        // ==================== DYNAMIC BLOCKS LOGIC ====================
        function addJsonBlock(fieldName, containerId, headLabel = 'Tiêu đề ', bodyLabel = 'Mô tả ') {
            const container = document.getElementById(containerId);
            const idx = blockIndexes[fieldName]++;
            const uniqueId = fieldName + '_' + idx;
            const div = document.createElement('div');
            div.className = 'bg-white border border-gray-200 rounded-xl p-4 shadow-sm relative group flex flex-col gap-3 hover:border-blue-300 transition-colors';
            div.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm hover:bg-red-600 hover:text-white z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="aspect-[4/3] w-full bg-gray-50 rounded-lg border border-dashed border-gray-300 relative overflow-hidden flex items-center justify-center group-hover:bg-gray-100 transition-colors">
                    <img id="preview_${uniqueId}" src="https://placehold.co/400x300?text=Chon+Anh" class="w-full h-full object-contain">
                    <input type="file" name="${fieldName}[${idx}][new_image]" accept="image/*" onchange="previewImage(event, 'preview_${uniqueId}')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1">${headLabel}</label>
                    <input type="text" name="${fieldName}[${idx}][head]" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg outline-none focus:border-[#A31D1D] font-bold text-gray-800">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1">${bodyLabel}</label>
                    <textarea name="${fieldName}[${idx}][body]" rows="3" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg outline-none focus:border-[#A31D1D] text-gray-600"></textarea>
                </div>
            `;
            container.appendChild(div);
            return div;
        }

        function addJsonBlockImageOnly(fieldName, containerId, headLabel = 'Tiêu đề ') {
            const container = document.getElementById(containerId);
            const idx = blockIndexes[fieldName]++;
            const uniqueId = fieldName + '_' + idx;
            const div = document.createElement('div');
            div.className = 'bg-white border border-gray-200 rounded-xl p-4 shadow-sm relative group flex flex-col gap-3 hover:border-blue-300 transition-colors';
            div.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm hover:bg-red-600 hover:text-white z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="aspect-[4/3] w-full bg-gray-50 rounded-lg border border-dashed border-gray-300 relative overflow-hidden flex items-center justify-center group-hover:bg-gray-100 transition-colors">
                    <img id="preview_${uniqueId}" src="https://placehold.co/400x300?text=Chon+Anh" class="w-full h-full object-contain">
                    <input type="file" name="${fieldName}[${idx}][new_image]" accept="image/*" onchange="previewImage(event, 'preview_${uniqueId}')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-gray-500 mb-1">${headLabel}</label>
                    <input type="text" name="${fieldName}[${idx}][head]" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg outline-none focus:border-[#A31D1D] font-bold text-center text-gray-800">
                </div>
            `;
            container.appendChild(div);
            return div;
        }

        function populateJsonBlocks(fieldName, containerId, defaultCount, isImageOnly = false, headLabel = 'Tiêu đề ', bodyLabel = 'Mô tả ') {
            const data = existingData[fieldName];
            if (data && data.length > 0) {
                data.forEach(item => {
                    const block = isImageOnly ? addJsonBlockImageOnly(fieldName, containerId, headLabel) : addJsonBlock(fieldName, containerId, headLabel, bodyLabel);
                    const idx = blockIndexes[fieldName] - 1;
                    const imgEl = block.querySelector('img');
                    if (item.image) {
                        imgEl.src = '/storage/' + item.image;
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
        populateJsonBlocks('nt_luyen_dat_item', 'nt-luyen-dat-container', 3);
    </script>
    @endpush
</x-admin.layout.app>