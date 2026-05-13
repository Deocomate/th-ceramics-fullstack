<x-admin.layout.app title="Gạch Hoa Thông Gió" breadcrumb="Admin › Sản Phẩm › Gạch Hoa Thông Gió">
    @php
        $mediaUrl = function (?string $path, string $fallback = 'https://placehold.co/600x400?text=Chua+co+anh') {
            if (empty($path)) {
                return $fallback;
            }

            if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            if (\Illuminate\Support\Str::startsWith($path, 'assets/')) {
                return asset($path);
            }

            return asset('storage/' . $path);
        };

        $videoUrl = old('video_url', $gachHoaThongGio->video_url);
        $youtubeId = null;

        if (!empty($videoUrl) && preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([A-Za-z0-9_-]{6,})~', $videoUrl, $matches)) {
            $youtubeId = $matches[1];
        }

        $processImages = is_array($gachHoaThongGio->process_images) ? $gachHoaThongGio->process_images : [];
        $valueBgColors = ['#1D78AD', '#5A7E46', '#B28373', '#C08B5C', '#7B6B8A', '#4A7C82'];
        $valueColor = fn (?string $color, int $index) => preg_match('/^#[0-9A-Fa-f]{6}$/', $color ?? '')
            ? $color
            : $valueBgColors[$index % count($valueBgColors)];
    @endphp

    {{-- BLOCK 1: THƯ VIỆN COLLAGE --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between gap-4 mb-4">
            <h2 class="text-sm font-bold uppercase tracking-wide text-gray-800">1. Thư viện ảnh nghệ thuật (Collage Carousel)</h2>
            <span class="text-xs font-medium text-gray-500">Tổng số: {{ $gachHoaThongGio->anh->count() }} ảnh</span>
        </div>

        <form method="POST" action="{{ route('admin.gach-hoa-thong-gio.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="drag-drop-zone border-2 border-dashed border-gray-300 rounded-xl p-8 text-center relative hover:bg-gray-50 transition-colors">
                <input id="newImagesInput" type="file" name="new_images[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewMultiple(this, 'collage-preview')">
                <div class="pointer-events-none">
                    <p class="text-sm font-semibold text-gray-700">Kéo thả ảnh vào đây</p>
                    <p class="mt-1 text-xs text-gray-500">Hoặc bấm để chọn nhiều ảnh cùng lúc.</p>
                </div>
                <div id="collage-preview" class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 mt-5 hidden relative z-20 text-left"></div>
            </div>
            @error('new_images.*')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
            <div class="mt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-[#A31D1D] hover:bg-[#8A1818] rounded-lg shadow-sm transition-colors">Lưu ảnh Collage</button>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-100">
            @if($gachHoaThongGio->anh->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                    @foreach ($gachHoaThongGio->anh as $anh)
                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-50">
                            <img src="{{ $mediaUrl($anh->image) }}" class="w-full h-full object-contain" alt="Ảnh Collage">
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                <button type="button" onclick="openDeleteModal('{{ route('admin.gach-hoa-thong-gio.anh.destroy', $anh->gach_hoa_thong_gio_anh_id) }}')" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">Xóa ảnh</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-6">Chưa có ảnh Collage nào được lưu.</p>
            @endif
        </div>
    </div>

    {{-- BLOCK 2: VIDEO HÀNH TRÌNH --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-8">
        <h2 class="text-sm font-bold uppercase tracking-wide text-gray-800 mb-4">2. Hành trình chế tác</h2>
        <form method="POST" action="{{ route('admin.gach-hoa-thong-gio.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh đại diện cạnh Video</label>
                    <div class="relative aspect-[4/3] rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 overflow-hidden group">
                        <img id="thumb-preview" src="{{ $mediaUrl($gachHoaThongGio->video_thumbnail) }}" onerror="this.src='https://placehold.co/600x450?text=Chua+co+anh'" class="w-full h-full object-cover" alt="Ảnh đại diện video">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-semibold px-3 py-1.5 bg-black/50 rounded-lg">Thay ảnh</span>
                        </div>
                        <input type="file" name="video_thumbnail" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewSingle(this, 'thumb-preview')">
                    </div>
                    @error('video_thumbnail')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="video_url" class="block text-sm font-semibold text-gray-700 mb-3">Link Youtube</label>
                    <input id="video_url" type="url" name="video_url" value="{{ $videoUrl }}" placeholder="https://youtube.com/watch?v=..." class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all" oninput="updateYoutubePreview(this.value)">
                    @error('video_url')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <div id="youtube-preview-shell" class="mt-4 aspect-video rounded-xl overflow-hidden border border-gray-200 bg-gray-100 {{ $youtubeId ? '' : 'hidden' }}">
                        <iframe id="youtube-preview" src="{{ $youtubeId ? 'https://www.youtube.com/embed/' . $youtubeId : '' }}" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div id="youtube-empty-state" class="mt-4 aspect-video rounded-xl border border-dashed border-gray-300 bg-gray-50 flex items-center justify-center text-sm text-gray-400 {{ $youtubeId ? 'hidden' : '' }}">
                        Chưa có video preview
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-[#A31D1D] hover:bg-[#8A1818] rounded-lg shadow-sm transition-colors">Lưu hành trình</button>
            </div>
        </form>
    </div>

    {{-- BLOCK 3: GIÁ TRỊ VƯỢT TRỘI --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-8">
        <h2 class="text-sm font-bold uppercase tracking-wide text-gray-800 mb-6">3. Giá trị vượt trội</h2>

        <div class="mb-8 p-5 bg-blue-50/40 rounded-xl border border-blue-100">
            <h3 class="text-sm font-bold text-blue-800 mb-5 uppercase">Thêm giá trị mới</h3>
            <form method="POST" action="{{ route('admin.gach-hoa-thong-gio.gia-tri.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Màu nền <span class="text-red-500">*</span></label>
                        <div class="rounded-xl border border-blue-100 bg-white p-4">
                            <div id="preview-new-bg" class="aspect-square rounded-lg shadow-inner border border-gray-200" style="background:#1D78AD"></div>
                            <div class="mt-4 flex items-center gap-3">
                                <input type="color" name="background" value="#1D78AD" required oninput="previewColor(this, 'preview-new-bg')" class="h-10 w-14 cursor-pointer rounded border border-gray-300 bg-white p-1">
                                <span class="text-xs font-medium text-gray-500">Chọn màu nền phía sau ảnh</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Hình ảnh <span class="text-red-500">*</span></label>
                        <div class="relative aspect-square rounded-xl border-2 border-dashed border-blue-300 bg-white overflow-hidden group">
                            <img id="preview-new-img" src="https://placehold.co/400x400?text=Chon+Anh" class="w-full h-full object-contain" alt="Ảnh minh họa">
                            <input type="file" name="image" accept="image/*" required onchange="previewSingle(this, 'preview-new-img')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                    </div>
                    <div class="lg:col-span-2 flex flex-col gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required maxlength="50" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div class="flex flex-col flex-1">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả <span class="text-red-500">*</span></label>
                            <textarea name="desscription" required class="w-full flex-1 min-h-[120px] px-4 py-3 text-sm border rounded-lg border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all resize-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-5 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors">Thêm giá trị</button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-0 lg:px-10">
            @foreach($gachHoaThongGio->giaTri as $giaTri)
                @php
                    $backgroundColor = $valueColor($giaTri->background, $loop->index);
                    $isTopImage = $loop->odd;
                @endphp
                <div class="group relative flex flex-col bg-white border border-gray-200 overflow-hidden pb-6 hover:shadow-xl transition-all duration-300">
                    @if($isTopImage)
                        <div class="w-full flex justify-center mb-10 lg:mb-20">
                            <div class="relative w-full aspect-[10/9]">
                                <div class="absolute top-0 left-0 w-full h-[70%]" style="background: {{ $backgroundColor }}"></div>
                                <div class="absolute top-[20%] left-[18%] right-[18%] bottom-[-15%] shadow-lg bg-black/5 overflow-hidden">
                                    <img src="{{ $mediaUrl($giaTri->image) }}" class="w-full h-full object-cover" alt="{{ $giaTri->title }}">
                                </div>
                            </div>
                        </div>
                        <div class="px-6 text-center flex-1">
                            <h4 class="text-[22px] font-bold text-[#333] leading-snug mb-3">{{ $giaTri->title }}</h4>
                            <p class="text-sm font-medium text-gray-700 leading-relaxed max-w-[290px] mx-auto">{{ $giaTri->desscription }}</p>
                        </div>
                    @else
                        <div class="px-6 text-center flex-1 mb-8">
                            <h4 class="text-[22px] font-bold text-[#333] leading-snug mb-3">{{ $giaTri->title }}</h4>
                            <p class="text-sm font-medium text-gray-700 leading-relaxed max-w-[290px] mx-auto">{{ $giaTri->desscription }}</p>
                        </div>
                        <div class="w-full flex justify-center mt-8">
                            <div class="relative w-full aspect-[10/9]">
                                <div class="absolute bottom-0 left-0 w-full h-[70%]" style="background: {{ $backgroundColor }}"></div>
                                <div class="absolute bottom-[20%] left-[18%] right-[18%] top-[-15%] shadow-lg bg-black/5 overflow-hidden">
                                    <img src="{{ $mediaUrl($giaTri->image) }}" class="w-full h-full object-cover" alt="{{ $giaTri->title }}">
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="absolute inset-0 z-20 bg-black/65 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center gap-3 transition-opacity duration-300 backdrop-blur-[2px]">
                        <button type="button"
                            data-title="{{ $giaTri->title }}"
                            data-desc="{{ $giaTri->desscription }}"
                            data-bg="{{ $backgroundColor }}"
                            data-img="{{ $mediaUrl($giaTri->image) }}"
                            onclick="openEditModal(this, '{{ route('admin.gach-hoa-thong-gio.gia-tri.update', $giaTri->gia_tri_gach_hoa_thong_gio_id) }}')"
                            class="w-32 px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Sửa</button>
                        <button type="button" onclick="openDeleteModal('{{ route('admin.gach-hoa-thong-gio.gia-tri.destroy', $giaTri->gia_tri_gach_hoa_thong_gio_id) }}')" class="w-32 px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">Xóa</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- BLOCK 4: HÌNH ẢNH ỨNG DỤNG / CÔNG ĐOẠN --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between gap-4 mb-4">
            <h2 class="text-sm font-bold uppercase tracking-wide text-gray-800">4. Hình ảnh ứng dụng / Công đoạn chế tác</h2>
            <span class="text-xs font-medium text-gray-500">Tổng số: {{ count($processImages) }} ảnh</span>
        </div>

        <form method="POST" action="{{ route('admin.gach-hoa-thong-gio.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="drag-drop-zone border-2 border-dashed border-gray-300 rounded-xl p-8 text-center relative hover:bg-gray-50 transition-colors">
                <input id="processImagesInput" type="file" name="process_images[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewMultiple(this, 'process-preview')">
                <div class="pointer-events-none">
                    <p class="text-sm font-semibold text-gray-700">Kéo thả ảnh công đoạn vào đây</p>
                    <p class="mt-1 text-xs text-gray-500">Ảnh sẽ hiển thị tại section công đoạn chế tác ở frontend.</p>
                </div>
                <div id="process-preview" class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 mt-5 hidden relative z-20 text-left"></div>
            </div>
            @error('process_images.*')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
            @enderror
            <div class="mt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-[#A31D1D] hover:bg-[#8A1818] rounded-lg shadow-sm transition-colors">Lưu ảnh công đoạn</button>
            </div>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-100">
            @if(count($processImages) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                    @foreach($processImages as $path)
                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-50">
                            <img src="{{ $mediaUrl($path) }}" class="w-full h-full object-contain" alt="Ảnh công đoạn">
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                <button type="button" onclick="openDeleteProcessModal('{{ $path }}')" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">Xóa ảnh</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500 text-center py-6">Chưa có ảnh công đoạn nào được lưu.</p>
            @endif
        </div>
    </div>

    {{-- MODAL SỬA GIÁ TRỊ --}}
    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden p-6">
            <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
                <h3 class="font-bold text-gray-800 text-lg">Cập nhật Giá trị</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Sửa màu nền</label>
                        <div class="rounded-xl border border-gray-200 bg-white p-4">
                            <div id="preview-edit-bg" class="aspect-square rounded-lg shadow-inner border border-gray-200" style="background:#1D78AD"></div>
                            <div class="mt-4 flex items-center gap-3">
                                <input id="edit_background" type="color" name="background" value="#1D78AD" required oninput="previewColor(this, 'preview-edit-bg')" class="h-10 w-14 cursor-pointer rounded border border-gray-300 bg-white p-1">
                                <span class="text-xs font-medium text-gray-500">Màu nền phía sau ảnh</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Sửa Ảnh minh họa</label>
                        <div class="relative aspect-square rounded-xl border-2 border-dashed border-gray-300 overflow-hidden">
                            <img id="preview-edit-img" src="" class="w-full h-full object-contain" alt="Sửa ảnh">
                            <input type="file" name="image" accept="image/*" onchange="previewSingle(this, 'preview-edit-img')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                    </div>
                    <div class="lg:col-span-2 flex flex-col gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_title" name="title" required maxlength="50" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-blue-500 outline-none transition-all">
                        </div>
                        <div class="flex-1 flex flex-col">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả <span class="text-red-500">*</span></label>
                            <textarea id="edit_description" name="desscription" required class="w-full flex-1 min-h-[150px] px-4 py-3 text-sm border rounded-lg border-gray-300 focus:border-blue-500 outline-none transition-all resize-none"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3 pt-5 border-t border-gray-100">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy bỏ</button>
                    <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL XÓA CHUNG --}}
    <div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Hành động này không thể hoàn tác.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Có, xóa</button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL XÓA ẢNH CÔNG ĐOẠN --}}
    <div id="deleteProcessModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa ảnh?</h3>
            <p class="text-sm text-gray-500 mb-6">Ảnh này sẽ bị xóa khỏi danh sách công đoạn chế tác.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteProcessModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form method="POST" action="{{ route('admin.gach-hoa-thong-gio.process-image.destroy') }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="image_path" id="deleteProcessPathInput" value="">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Có, xóa</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const selectedFilesByInput = new WeakMap();

            function previewSingle(input, targetId) {
                const file = input.files && input.files[0];
                if (!file) {
                    return;
                }

                document.getElementById(targetId).src = URL.createObjectURL(file);
            }

            function previewColor(input, targetId) {
                document.getElementById(targetId).style.background = input.value;
            }

            function previewMultiple(input, targetId) {
                const selectedFiles = selectedFilesByInput.get(input) || [];
                const files = Array.from(input.files || []);
                selectedFilesByInput.set(input, selectedFiles.concat(files));
                syncInputFiles(input);
                renderMultiplePreview(input, targetId);
            }

            function removePreviewFile(inputId, targetId, index) {
                const input = document.getElementById(inputId);
                const selectedFiles = selectedFilesByInput.get(input) || [];
                selectedFiles.splice(index, 1);
                selectedFilesByInput.set(input, selectedFiles);
                syncInputFiles(input);
                renderMultiplePreview(input, targetId);
            }

            function syncInputFiles(input) {
                const dataTransfer = new DataTransfer();
                (selectedFilesByInput.get(input) || []).forEach(file => dataTransfer.items.add(file));
                input.files = dataTransfer.files;
            }

            function renderMultiplePreview(input, targetId) {
                const target = document.getElementById(targetId);
                const files = selectedFilesByInput.get(input) || [];
                target.innerHTML = '';
                target.classList.toggle('hidden', files.length === 0);

                files.forEach((file, index) => {
                    const item = document.createElement('div');
                    item.className = 'relative group aspect-square rounded-lg overflow-hidden border border-gray-200 bg-gray-50 shadow-sm';
                    item.innerHTML = `
                        <img src="${URL.createObjectURL(file)}" class="w-full h-full object-contain" alt="">
                        <div class="absolute inset-0 bg-black/55 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button type="button" class="w-8 h-8 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors flex items-center justify-center shadow-sm" onclick="removePreviewFile('${input.id}', '${targetId}', ${index})" title="Bỏ ảnh">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    `;
                    target.appendChild(item);
                });
            }

            function extractYoutubeId(url) {
                const match = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{6,})/);
                return match ? match[1] : null;
            }

            function updateYoutubePreview(url) {
                const id = extractYoutubeId(url);
                const shell = document.getElementById('youtube-preview-shell');
                const empty = document.getElementById('youtube-empty-state');
                const iframe = document.getElementById('youtube-preview');

                if (!id) {
                    iframe.src = '';
                    shell.classList.add('hidden');
                    empty.classList.remove('hidden');
                    return;
                }

                iframe.src = `https://www.youtube.com/embed/${id}`;
                shell.classList.remove('hidden');
                empty.classList.add('hidden');
            }

            document.querySelectorAll('.drag-drop-zone').forEach(zone => {
                const input = zone.querySelector('input[type="file"]');
                const previewId = input.getAttribute('onchange').match(/'([^']+)'/)[1];

                zone.addEventListener('dragover', event => {
                    event.preventDefault();
                    zone.classList.add('border-[#A31D1D]', 'bg-red-50');
                });

                zone.addEventListener('dragleave', () => {
                    zone.classList.remove('border-[#A31D1D]', 'bg-red-50');
                });

                zone.addEventListener('drop', event => {
                    event.preventDefault();
                    zone.classList.remove('border-[#A31D1D]', 'bg-red-50');
                    input.files = event.dataTransfer.files;
                    previewMultiple(input, previewId);
                });
            });

            function openEditModal(btn, actionUrl) {
                document.getElementById('editForm').action = actionUrl;
                document.getElementById('edit_title').value = btn.dataset.title;
                document.getElementById('edit_description').value = btn.dataset.desc;
                document.getElementById('edit_background').value = btn.dataset.bg;
                document.getElementById('preview-edit-bg').style.background = btn.dataset.bg;
                document.getElementById('preview-edit-img').src = btn.dataset.img;
                document.getElementById('editModal').classList.remove('hidden');
                document.getElementById('editModal').classList.add('flex');
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
                document.getElementById('editModal').classList.remove('flex');
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

            const deleteProcessModal = document.getElementById('deleteProcessModal');
            const deleteProcessModalInner = deleteProcessModal.querySelector('.bg-white');

            function openDeleteProcessModal(imagePath) {
                document.getElementById('deleteProcessPathInput').value = imagePath;
                deleteProcessModal.classList.remove('hidden');
                deleteProcessModal.classList.add('flex');
                void deleteProcessModal.offsetWidth;
                deleteProcessModal.classList.remove('opacity-0');
                deleteProcessModalInner.classList.remove('scale-95');
            }

            function closeDeleteProcessModal() {
                deleteProcessModal.classList.add('opacity-0');
                deleteProcessModalInner.classList.add('scale-95');
                setTimeout(() => {
                    deleteProcessModal.classList.add('hidden');
                    deleteProcessModal.classList.remove('flex');
                }, 300);
            }
        </script>
    @endpush
</x-admin.layout.app>
