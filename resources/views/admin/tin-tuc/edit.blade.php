<x-admin.layout.app title="Sửa Bài Viết" breadcrumb="Admin › Tin Tức › Sửa Bài">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8" x-data="articleBuilder()">
        
        <!-- HEADER (Đã gỡ bỏ sticky để không bị đè form) -->
        <div class="px-6 py-4 border-b border-gray-100 bg-white flex justify-between items-center rounded-t-xl">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cập nhật: <span class="text-[#A31D1D] font-normal lowercase first-letter:uppercase">{{ Str::limit($tinTuc->tieu_de, 40) }}</span></h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.tin-tuc.index') }}" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-50 border border-gray-300 rounded-lg hover:bg-gray-100">Hủy</a>
                <button type="button" @click="submitForm('draft')" class="px-4 py-2 text-sm font-bold text-[#A31D1D] bg-red-50 border border-red-200 rounded-lg hover:bg-red-100">Lưu Bản Nháp</button>
                <button type="button" @click="submitForm('published')" class="px-6 py-2 text-sm font-bold text-white rounded-lg shadow-sm" style="background:#A31D1D;">Đăng Bài Xuất Bản</button>
            </div>
        </div>

        <form id="articleForm" method="POST" action="{{ route('admin.tin-tuc.update', $tinTuc->tin_tuc_id) }}" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')
            <input type="hidden" name="trang_thai" id="input_trang_thai" value="{{ $tinTuc->trang_thai }}">

            <!-- THÔNG TIN CHUNG BÀI VIẾT -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-10">
                <div class="lg:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ảnh Đại Diện (Thumbnail) <span class="text-red-500">*</span></label>
                    <div class="w-full aspect-[4/3] rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
                        <img id="preview-thumbnail" src="{{ asset('storage/' . $tinTuc->anh_dai_dien) }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay ảnh</span>
                        </div>
                        <input type="file" name="anh_dai_dien" accept="image/*" onchange="document.getElementById('preview-thumbnail').src = window.URL.createObjectURL(this.files[0])" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                </div>
                
                <div class="lg:col-span-3 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề bài viết <span class="text-red-500">*</span></label>
                        <input type="text" name="tieu_de" value="{{ old('tieu_de', $tinTuc->tieu_de) }}" required class="w-full px-4 py-2.5 text-lg font-bold border rounded-lg focus:border-[#A31D1D] outline-none">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                            <select name="danh_muc_tin_tuc_id" required class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none bg-white">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($danhMucs as $dm)
                                    <option value="{{ $dm->danh_muc_tin_tuc_id }}" {{ $tinTuc->danh_muc_tin_tuc_id == $dm->danh_muc_tin_tuc_id ? 'selected' : '' }}>{{ $dm->ten_danh_muc }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phụ đề (Subtitle nhỏ trên cùng)</label>
                            <input type="text" name="the_loai" value="{{ old('the_loai', $tinTuc->the_loai) }}" class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả ngắn (Hiển thị ngoài list) <span class="text-red-500">*</span></label>
                        <textarea name="mo_ta_ngan" required rows="3" class="w-full px-4 py-2.5 text-sm border rounded-lg focus:border-[#A31D1D] outline-none resize-none">{{ old('mo_ta_ngan', $tinTuc->mo_ta_ngan) }}</textarea>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200 mb-10">

            <!-- TRÌNH SOẠN THẢO BLOCKS -->
            <div class="mb-5">
                <h3 class="text-xl font-bold text-gray-800">Nội Dung Bài Viết (Xây dựng bằng Blocks)</h3>
                <p class="text-sm text-gray-500 mt-1">Sử dụng các khối giao diện có sẵn để thiết kế bài viết chuẩn tạp chí kiến trúc.</p>
            </div>

            <!-- List Blocks Render -->
            <div class="space-y-6" id="blocks-container">
                <template x-for="(block, index) in blocks" :key="block.id">
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 relative group transition-all duration-300 hover:border-[#A31D1D]">
                        
                        <!-- Delete & Type Label -->
                        <div class="absolute -top-3 left-4 bg-white border border-[#A31D1D] px-3 py-1 rounded-full text-xs font-bold text-[#A31D1D] uppercase shadow-sm" x-text="getBlockLabel(block.type)"></div>
                        
                        <!-- Gọi modal xóa thay vì alert mặc định -->
                        <button type="button" @click="confirmDelete(index)" class="absolute -top-3 -right-3 w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-sm hover:bg-red-600 hover:text-white z-10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <input type="hidden" :name="`blocks[${index}][type]`" :value="block.type">
                        
                        <!-- GIỮ LẠI ĐƯỜNG DẪN ẢNH CŨ NẾU KO ĐỔI -->
                        <template x-if="block.data.image_url"><input type="hidden" :name="`blocks[${index}][data][image_url]`" :value="block.data.image_url"></template>
                        <template x-if="block.data.image_url_1"><input type="hidden" :name="`blocks[${index}][data][image_url_1]`" :value="block.data.image_url_1"></template>
                        <template x-if="block.data.image_url_2"><input type="hidden" :name="`blocks[${index}][data][image_url_2]`" :value="block.data.image_url_2"></template>

                        <!-- TYPE 1: SPLIT CONTENT (Khối chia đôi màn hình) -->
                        <template x-if="block.type === 'split_content'">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pt-4">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Vị trí ảnh</label>
                                        <select :name="`blocks[${index}][data][layout]`" x-model="block.data.layout" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2">
                                            <option value="image_left">Ảnh Nằm Bên Trái</option>
                                            <option value="image_right">Ảnh Nằm Bên Phải</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Phụ đề (Subtitle nhỏ trên cùng)</label>
                                        <input type="text" :name="`blocks[${index}][data][subtitle]`" x-model="block.data.subtitle" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Tiêu đề chính</label>
                                        <input type="text" :name="`blocks[${index}][data][title]`" x-model="block.data.title" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2 font-bold">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Đoạn văn mô tả</label>
                                        <textarea :name="`blocks[${index}][data][description]`" x-model="block.data.description" rows="5" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2"></textarea>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Ảnh minh họa</label>
                                    <div class="aspect-[4/3] rounded-lg border-2 border-dashed border-gray-300 relative overflow-hidden bg-white">
                                        <img :src="block.preview_url || getAssetUrl(block.data.image_url) || 'https://placehold.co/800x600?text=Chon+Anh'" class="w-full h-full object-cover">
                                        <input type="file" :name="`block_images[${index}][image_url]`" accept="image/*" @change="previewBlockImage($event, block)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    </div>
                                    <input type="text" :name="`blocks[${index}][data][image_alt]`" x-model="block.data.image_alt" placeholder="Thẻ Alt (Chuẩn SEO)" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2">
                                </div>
                            </div>
                        </template>

                        <!-- TYPE 2: IMAGE METADATA (Khối ảnh kèm danh sách thông tin) -->
                        <template x-if="block.type === 'image_metadata'">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pt-4">
                                <div class="space-y-4">
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Ảnh chính</label>
                                    <div class="aspect-video rounded-lg border-2 border-dashed border-gray-300 relative overflow-hidden bg-white">
                                        <img :src="block.preview_url || getAssetUrl(block.data.image_url) || 'https://placehold.co/600x800?text=Chon+Anh'" class="w-full h-full object-cover">
                                        <input type="file" :name="`block_images[${index}][image_url]`" accept="image/*" @change="previewBlockImage($event, block)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    </div>
                                    <input type="text" :name="`blocks[${index}][data][image_alt]`" x-model="block.data.image_alt" placeholder="Thẻ Alt (Chuẩn SEO)" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2">
                                </div>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-xs font-semibold text-gray-500">Danh sách thông số (Specs)</label>
                                        <button type="button" @click="addSpec(block)" class="text-xs text-blue-600 font-bold hover:underline">+ Thêm dòng</button>
                                    </div>
                                    <template x-for="(spec, sIdx) in block.data.specs" :key="sIdx">
                                        <div class="flex gap-2">
                                            <input type="text" :name="`blocks[${index}][data][specs][${sIdx}][label]`" x-model="spec.label" placeholder="Nhãn (VD: DỰ ÁN)" class="w-1/3 text-sm border-gray-300 rounded p-2">
                                            <input type="text" :name="`blocks[${index}][data][specs][${sIdx}][value]`" x-model="spec.value" placeholder="Giá trị (VD: Biệt thự ven biển)" class="flex-1 text-sm border-gray-300 rounded p-2">
                                            <button type="button" @click="block.data.specs.splice(sIdx, 1)" class="p-2 text-red-500 bg-red-50 rounded hover:bg-red-100">X</button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- TYPE 3: FULL WIDTH IMAGE (Ảnh tràn viền) -->
                        <template x-if="block.type === 'full_width_image'">
                            <div class="pt-4 space-y-4">
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Ảnh cỡ lớn (Tỷ lệ Panorama 21:9 khuyến nghị)</label>
                                <div class="aspect-[21/9] rounded-lg border-2 border-dashed border-gray-300 relative overflow-hidden bg-white">
                                    <img :src="block.preview_url || getAssetUrl(block.data.image_url) || 'https://placehold.co/1200x500?text=Chon+Anh+Bia'" class="w-full h-full object-cover">
                                    <input type="file" :name="`block_images[${index}][image_url]`" accept="image/*" @change="previewBlockImage($event, block)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                </div>
                                <input type="text" :name="`blocks[${index}][data][image_alt]`" x-model="block.data.image_alt" placeholder="Thẻ Alt (Chuẩn SEO)" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2">
                            </div>
                        </template>

                        <!-- TYPE 4: CALL TO ACTION (Kêu gọi hành động) -->
                        <template x-if="block.type === 'call_to_action'">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pt-4 items-center">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Lời kêu gọi (Title)</label>
                                        <input type="text" :name="`blocks[${index}][data][title]`" x-model="block.data.title" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2 text-xl font-bold">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Chữ trên nút (Button)</label>
                                            <input type="text" :name="`blocks[${index}][data][button_text]`" x-model="block.data.button_text" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2 bg-gray-100 font-bold uppercase">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Đường dẫn nút (Link)</label>
                                            <input type="text" :name="`blocks[${index}][data][button_link]`" x-model="block.data.button_link" placeholder="https://..." class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2">
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Ảnh minh họa bên cạnh</label>
                                    <div class="aspect-[16/9] rounded-lg border-2 border-dashed border-gray-300 relative overflow-hidden bg-white">
                                        <img :src="block.preview_url || getAssetUrl(block.data.image_url) || 'https://placehold.co/800x450?text=Chon+Anh'" class="w-full h-full object-cover">
                                        <input type="file" :name="`block_images[${index}][image_url]`" accept="image/*" @change="previewBlockImage($event, block)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    </div>
                                    <input type="text" :name="`blocks[${index}][data][image_alt]`" x-model="block.data.image_alt" placeholder="Thẻ Alt (Chuẩn SEO)" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2">
                                </div>
                            </div>
                        </template>

                        <!-- TYPE 5: TWO IMAGE CONTENT (Ảnh Nhỏ Trên - Ảnh Lớn Dưới) -->
                        <template x-if="block.type === 'two_image_content'">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pt-4">
                                <!-- Cột Ảnh -->
                                <div class="flex flex-col gap-6">
                                    <div class="space-y-2 w-3/4 md:w-2/3 self-center">
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Ảnh 1 (Nhỏ - Phía trên)</label>
                                        <div class="aspect-[4/3] rounded-lg border-2 border-dashed border-gray-300 relative overflow-hidden bg-white">
                                            <img :src="block.preview_url_1 || getAssetUrl(block.data.image_url_1) || 'https://placehold.co/400x300?text=Anh+Nho'" class="w-full h-full object-cover">
                                            <input type="file" :name="`block_images[${index}][image_url_1]`" accept="image/*" @change="previewBlockImage($event, block, 'preview_url_1')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        </div>
                                        <input type="text" :name="`blocks[${index}][data][image_alt_1]`" x-model="block.data.image_alt_1" placeholder="Thẻ Alt 1" class="w-full text-sm border-gray-300 rounded p-1.5">
                                    </div>
                                    <div class="space-y-2 w-full">
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Ảnh 2 (Lớn - Phía dưới)</label>
                                        <div class="aspect-[16/9] lg:aspect-[4/3] rounded-lg border-2 border-dashed border-gray-300 relative overflow-hidden bg-white">
                                            <img :src="block.preview_url_2 || getAssetUrl(block.data.image_url_2) || 'https://placehold.co/800x600?text=Anh+Lon'" class="w-full h-full object-cover">
                                            <input type="file" :name="`block_images[${index}][image_url_2]`" accept="image/*" @change="previewBlockImage($event, block, 'preview_url_2')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        </div>
                                        <input type="text" :name="`blocks[${index}][data][image_alt_2]`" x-model="block.data.image_alt_2" placeholder="Thẻ Alt 2" class="w-full text-sm border-gray-300 rounded p-1.5">
                                    </div>
                                </div>
                                
                                <!-- Cột Nội dung -->
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Vị trí chùm 2 ảnh</label>
                                        <select :name="`blocks[${index}][data][layout]`" x-model="block.data.layout" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2">
                                            <option value="images_left">Nằm Bên Trái</option>
                                            <option value="images_right">Nằm Bên Phải</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Phụ đề</label>
                                        <input type="text" :name="`blocks[${index}][data][subtitle]`" x-model="block.data.subtitle" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Tiêu đề chính</label>
                                        <input type="text" :name="`blocks[${index}][data][title]`" x-model="block.data.title" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2 font-bold">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 mb-1">Đoạn văn mô tả</label>
                                        <textarea :name="`blocks[${index}][data][description]`" x-model="block.data.description" rows="5" class="w-full text-sm border-gray-300 rounded focus:border-[#A31D1D] p-2"></textarea>
                                    </div>
                                    
                                    <div class="space-y-3 pt-4 border-t border-gray-200">
                                        <div class="flex justify-between items-center mb-2">
                                            <label class="block text-xs font-semibold text-gray-500">Danh sách thông số (Specs)</label>
                                            <button type="button" @click="addSpec(block)" class="text-xs text-blue-600 font-bold hover:underline">+ Thêm dòng</button>
                                        </div>
                                        <template x-for="(spec, sIdx) in block.data.specs" :key="sIdx">
                                            <div class="flex gap-2">
                                                <input type="text" :name="`blocks[${index}][data][specs][${sIdx}][label]`" x-model="spec.label" placeholder="VD: Khách hàng" class="w-1/3 text-sm border-gray-300 rounded p-2">
                                                <input type="text" :name="`blocks[${index}][data][specs][${sIdx}][value]`" x-model="spec.value" placeholder="VD: Chị Ngọc, Hạ Long" class="flex-1 text-sm border-gray-300 rounded p-2">
                                                <button type="button" @click="block.data.specs.splice(sIdx, 1)" class="p-2 text-red-500 bg-red-50 rounded hover:bg-red-100">X</button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>
                </template>
            </div>

            <!-- Thanh Công Cụ Thêm Block -->
            <div class="mt-8 bg-blue-50/50 border border-blue-200 border-dashed rounded-xl p-6 flex flex-col items-center justify-center gap-4 text-center">
                <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <h4 class="font-bold text-blue-900">Thêm Khối Giao Diện</h4>
                    <p class="text-xs text-blue-600/80 mt-1">Chọn loại layout bên dưới để thêm vào bài viết</p>
                </div>
                <div class="flex items-center gap-2 max-w-xl w-full">
                    <select x-model="selectedNewType" class="flex-1 px-4 py-3 text-sm font-medium border border-blue-300 rounded-lg outline-none text-blue-800 focus:border-blue-500 bg-white shadow-sm">
                        <option value="split_content">Khối Chia Đôi (1 Ảnh - 1 Text)</option>
                        <option value="image_metadata">Khối Ảnh + Thông số dự án (Specs)</option>
                        <option value="full_width_image">Khối Ảnh Tràn Viền (Panorama)</option>
                        <option value="two_image_content">Khối Cụm 2 Ảnh Trên Dưới + Text + Specs</option>
                        <option value="call_to_action">Khối Kêu Gọi Hành Động (CTA)</option>
                    </select>
                    <button type="button" @click="addNewBlock()" class="px-6 py-3 font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm shrink-0 transition-colors whitespace-nowrap">Thêm Vào Bài</button>
                </div>
            </div>

            <!-- MODAL XÁC NHẬN XÓA BLOCK -->
            <div x-show="showDeleteModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4" x-transition.opacity style="display: none;">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center" @click.away="showDeleteModal = false">
                    <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa khối?</h3>
                    <p class="text-sm text-gray-500 mb-6">Khối nội dung này sẽ bị xóa khỏi bài viết. Bạn không thể hoàn tác thao tác này.</p>
                    <div class="flex justify-center gap-3">
                        <button type="button" @click="showDeleteModal = false" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                        <button type="button" @click="executeDelete()" class="flex-1 px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Xóa khối</button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('articleBuilder', () => ({
                blocks: {!! json_encode(old('blocks', $tinTuc->noi_dung_blocks ??[])) !!}.map((b, i) => { b.id = Date.now() + i; return b; }),
                blockIdCounter: 100,
                selectedNewType: 'split_content',
                deleteIndex: null,
                showDeleteModal: false,

                submitForm(status) {
                    document.getElementById('input_trang_thai').value = status;
                    document.getElementById('articleForm').submit();
                },

                getBlockLabel(type) {
                    const map = {
                        'split_content': 'Chia Đôi: Ảnh - Chữ',
                        'image_metadata': 'Ảnh - Thông Số Dự Án',
                        'full_width_image': 'Ảnh Tràn Viền',
                        'call_to_action': 'Kêu Gọi Hành Động',
                        'two_image_content': 'Cụm 2 Ảnh (Trên-Dưới) - Chữ - Thông số'
                    };
                    return map[type] || 'Unknown';
                },

                getAssetUrl(path) {
                    if (!path) return null;
                    return '/storage/' + path;
                },

                addNewBlock() {
                    const newBlock = {
                        id: Date.now() + '_' + this.blockIdCounter++,
                        type: this.selectedNewType,
                        data: {},
                        preview_url: null,
                        preview_url_1: null,
                        preview_url_2: null,
                    };

                    // Khởi tạo các trường data rỗng tương ứng
                    if (this.selectedNewType === 'split_content') {
                        newBlock.data = { layout: 'image_left', subtitle: '', title: '', description: '', image_alt: '' };
                    } else if (this.selectedNewType === 'image_metadata') {
                        newBlock.data = { image_alt: '', specs: [{label: '', value: ''}] };
                    } else if (this.selectedNewType === 'full_width_image') {
                        newBlock.data = { image_alt: '' };
                    } else if (this.selectedNewType === 'call_to_action') {
                        newBlock.data = { title: '', button_text: 'XEM CHI TIẾT', button_link: '#', image_alt: '' };
                    } else if (this.selectedNewType === 'two_image_content') {
                        newBlock.data = { layout: 'images_left', subtitle: '', title: '', description: '', image_alt_1: '', image_alt_2: '', specs: [{label: '', value: ''}] };
                    }

                    this.blocks.push(newBlock);
                    
                    // Cuộn trang xuống mượt mà
                    setTimeout(() => {
                        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                    }, 100);
                },

                confirmDelete(index) {
                    this.deleteIndex = index;
                    this.showDeleteModal = true;
                },

                executeDelete() {
                    if(this.deleteIndex !== null) {
                        this.blocks.splice(this.deleteIndex, 1);
                    }
                    this.showDeleteModal = false;
                    this.deleteIndex = null;
                },

                addSpec(block) {
                    if (!block.data.specs) block.data.specs =[];
                    block.data.specs.push({label: '', value: ''});
                },

                previewBlockImage(event, block, key = 'preview_url') {
                    const file = event.target.files[0];
                    if (file) {
                        block[key] = URL.createObjectURL(file);
                    }
                }
            }))
        })
    </script>
</x-admin.layout.app>