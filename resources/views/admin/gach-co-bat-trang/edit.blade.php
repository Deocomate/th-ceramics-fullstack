<x-admin.layout.app title="Gạch Cổ Bát Tràng" breadcrumb="Admin › Sản Phẩm › Gạch Cổ Bát Tràng">
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

        $defaultColors = ['#A98467', '#B22222', '#5D5FEF'];
        $sectionConfigs = [
            'section_bat' => ['tab' => 'bat', 'label' => 'Gạch Bát', 'title' => 'GẠCH BÁT', 'subtitle' => 'Phối sắc tự nhiên - Hiện diện một nền'],
            'section_that' => ['tab' => 'that', 'label' => 'Gạch Thất & Xây', 'title' => 'Gạch Thất & Gạch Xây', 'subtitle' => 'Phối sắc tự nhiên - Hiện diện một nền'],
            'section_the' => ['tab' => 'the', 'label' => 'Gạch Thẻ', 'title' => 'GẠCH THẺ', 'subtitle' => 'Phối sắc tự nhiên - Hiện diện một nền'],
        ];
        $sectionData = [];

        foreach ($sectionConfigs as $key => $sectionConfig) {
            $stored = is_array($gachCoBatTrang->{$key}) ? $gachCoBatTrang->{$key} : [];
            $sectionData[$key] = [
                'title' => old("$key.title", $stored['title'] ?? $sectionConfig['title']),
                'subtitle' => old("$key.subtitle", $stored['subtitle'] ?? $sectionConfig['subtitle']),
                'description' => old("$key.description", $stored['description'] ?? 'Sự không đồng màu này không phải là lỗi kỹ thuật, mà chính là "chứng chỉ" cho nghệ thuật chế tác thủ công.'),
                'colors' => old("$key.colors", is_array($stored['colors'] ?? null) ? array_values($stored['colors']) : $defaultColors),
                'gallery' => is_array($stored['gallery'] ?? null) ? array_values($stored['gallery']) : [],
            ];
            $sectionData[$key]['colors'] = array_slice(array_pad($sectionData[$key]['colors'], 3, null), 0, 3);
        }

        $processImages = is_array($gachCoBatTrang->images) ? $gachCoBatTrang->images : [];
    @endphp

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8" x-data="{ activeTab: 'chung' }">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu hình Gạch Cổ Bát Tràng</h2>
            <nav class="flex flex-wrap gap-2">
                @foreach([['key' => 'chung', 'label' => 'Cấu hình chung'], ['key' => 'bat', 'label' => 'Gạch Bát'], ['key' => 'that', 'label' => 'Gạch Thất & Xây'], ['key' => 'the', 'label' => 'Gạch Thẻ']] as $tab)
                    <button type="button" @click="activeTab = '{{ $tab['key'] }}'"
                        :class="activeTab === '{{ $tab['key'] }}' ? 'border-brand-red bg-red-50 text-brand-red' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                        class="rounded-lg border px-4 py-2 text-xs font-bold uppercase tracking-wide transition-colors">
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </nav>
        </div>

        <form method="POST" action="{{ route('admin.gach-co-bat-trang.update') }}" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="flex items-start gap-3 px-4 py-3 rounded text-sm text-red-800 bg-red-50 border border-red-200 shadow-sm">
                    <div>
                        <strong class="font-semibold block mb-1">Vui lòng kiểm tra lại các thông tin sau:</strong>
                        <ul class="list-disc ml-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <section x-cloak x-show="activeTab === 'chung'" class="space-y-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh nền</label>
                        <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                            <img id="preview-main" src="{{ $mediaUrl($gachCoBatTrang->thumbnail_main) }}" class="w-full h-full object-contain" alt="Ảnh chính">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                            </div>
                            <input type="file" name="thumbnail_main" accept="image/*" onchange="previewSingle(this, 'preview-main')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('thumbnail_main') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="video" class="block text-sm font-semibold text-gray-700 mb-2">Đường dẫn Video YouTube</label>
                        <input type="url" id="video" name="video" value="{{ old('video', $gachCoBatTrang->video) }}" placeholder="https://youtube.com/watch?v=..." class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                        @error('video') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <section class="border border-gray-200 rounded-xl p-6 bg-gray-50/50">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Hình ảnh Công đoạn chế tác</h3>
                            <p class="text-xs text-gray-500 mt-1">Kéo thả ảnh đã lưu để đổi thứ tự. Ảnh mới sẽ được thêm vào cuối danh sách.</p>
                        </div>
                        <span class="text-xs font-medium text-gray-500">Tổng số: {{ count($processImages) }} ảnh</span>
                    </div>

                    <div class="drag-drop-zone border-2 border-dashed border-gray-300 rounded-xl p-8 text-center relative hover:bg-gray-50 transition-colors bg-white" data-preview-target="cong-doan-preview">
                        <input id="congDoanInput" type="file" name="cong_doan_images[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewMultiple(this, 'cong-doan-preview')">
                        <div class="pointer-events-none">
                            <p class="text-sm font-semibold text-gray-700">Kéo thả ảnh công đoạn vào đây</p>
                            <p class="mt-1 text-xs text-gray-500">Hoặc bấm để chọn nhiều ảnh cùng lúc.</p>
                        </div>
                        <div id="cong-doan-preview" class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 mt-5 hidden relative z-20 text-left"></div>
                    </div>
                    @error('cong_doan_images.*') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        @if(count($processImages) > 0)
                            <div id="process-sortable" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                                @foreach($processImages as $path)
                                    <div class="process-sortable-item relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-white cursor-move" data-path="{{ $path }}">
                                        <input type="hidden" name="cong_doan_order[]" value="{{ $path }}">
                                        <img src="{{ $mediaUrl($path) }}" class="w-full h-full object-contain pointer-events-none" alt="Ảnh công đoạn">
                                        <div class="absolute left-2 top-2 px-2 py-1 rounded bg-black/55 text-white text-[11px] font-semibold">Kéo</div>
                                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                            <button type="button" onclick="openDeleteCongDoanModal({{ Js::from($path) }})" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm cursor-pointer">
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
                </section>
            </section>

            @foreach($sectionConfigs as $sectionKey => $sectionConfig)
                @php
                    $section = $sectionData[$sectionKey];
                    $gallery = $section['gallery'];
                @endphp
                <section x-cloak x-show="activeTab === '{{ $sectionConfig['tab'] }}'" class="space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề</label>
                                    <input type="text" name="{{ $sectionKey }}[title]" value="{{ $section['title'] }}" class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                                    @error("$sectionKey.title") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Subtitle</label>
                                    <input type="text" name="{{ $sectionKey }}[subtitle]" value="{{ $section['subtitle'] }}" class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                                    @error("$sectionKey.subtitle") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả</label>
                                <textarea name="{{ $sectionKey }}[description]" rows="5" class="w-full px-4 py-3 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">{{ $section['description'] }}</textarea>
                                @error("$sectionKey.description") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-xl p-5 bg-gray-50/60">
                            <label class="block text-sm font-bold text-gray-800 mb-4">Bảng màu hiển thị</label>
                            <div class="space-y-4">
                                @foreach($section['colors'] as $color)
                                    <label class="flex items-center gap-3">
                                        <input type="color" name="{{ $sectionKey }}[colors][]" value="{{ $color ?: $defaultColors[$loop->index] }}" class="h-11 w-16 rounded border border-gray-300 bg-white p-1">
                                        <span class="text-xs font-medium text-gray-500">Màu {{ $loop->iteration }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error("$sectionKey.colors") <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <section class="border border-gray-200 rounded-xl p-6 bg-gray-50/50">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-4">
                            <div>
                                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Gallery {{ $sectionConfig['label'] }}</h3>
                                <p class="text-xs text-gray-500 mt-1">Gallery này được ưu tiên hiển thị trên client trước ảnh sản phẩm.</p>
                            </div>
                            <span class="text-xs font-medium text-gray-500">Tổng số: {{ count($gallery) }} ảnh</span>
                        </div>

                        <div class="drag-drop-zone border-2 border-dashed border-gray-300 rounded-xl p-8 text-center relative hover:bg-gray-50 transition-colors bg-white" data-preview-target="{{ $sectionKey }}-preview">
                            <input id="{{ $sectionKey }}Input" type="file" name="{{ $sectionKey }}_new_images[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewMultiple(this, '{{ $sectionKey }}-preview')">
                            <div class="pointer-events-none">
                                <p class="text-sm font-semibold text-gray-700">Kéo thả ảnh {{ $sectionConfig['label'] }} vào đây</p>
                                <p class="mt-1 text-xs text-gray-500">Hoặc bấm để chọn nhiều ảnh cùng lúc.</p>
                            </div>
                            <div id="{{ $sectionKey }}-preview" class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 mt-5 hidden relative z-20 text-left"></div>
                        </div>
                        @error($sectionKey . '_new_images.*') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror

                        <div class="mt-6 pt-6 border-t border-gray-100">
                            @if(count($gallery) > 0)
                                <div id="{{ $sectionKey }}-sortable" class="section-sortable grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                                    @foreach($gallery as $path)
                                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-white cursor-move" data-path="{{ $path }}">
                                            <input type="hidden" name="{{ $sectionKey }}_gallery_order[]" value="{{ $path }}">
                                            <img src="{{ $mediaUrl($path) }}" class="w-full h-full object-contain pointer-events-none" alt="Ảnh gallery">
                                            <div class="absolute left-2 top-2 px-2 py-1 rounded bg-black/55 text-white text-[11px] font-semibold">Kéo</div>
                                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                                <button type="button" onclick="openDeleteSectionImageModal('{{ $sectionKey }}', {{ Js::from($path) }})" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm cursor-pointer">
                                                    Xóa ảnh
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm text-center py-6">Chưa có ảnh gallery cho {{ $sectionConfig['label'] }}.</p>
                            @endif
                        </div>
                    </section>
                </section>
            @endforeach

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm bg-[#A31D1D] hover:bg-[#8A1818] transition-colors">
                    Lưu cấu hình
                </button>
            </div>
        </form>
    </div>

    <div id="deleteCongDoanModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Ảnh này sẽ bị xóa vĩnh viễn khỏi hệ thống.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeModal('deleteCongDoanModal')" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteCongDoanForm" method="POST" action="{{ route('admin.gach-co-bat-trang.cong-doan-image.destroy') }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="image_path" id="deleteCongDoanPathInput" value="">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Có, Xóa</button>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteSectionImageModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xóa ảnh gallery?</h3>
            <p class="text-sm text-gray-500 mb-6">Ảnh này sẽ bị xóa khỏi phân khu và khỏi storage.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeModal('deleteSectionImageModal')" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteSectionImageForm" method="POST" action="{{ route('admin.gach-co-bat-trang.section-image.destroy') }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="section" id="deleteSectionImageSectionInput" value="">
                    <input type="hidden" name="image_path" id="deleteSectionImagePathInput" value="">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Có, Xóa</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            const selectedFilesByInput = new WeakMap();

            function previewSingle(input, targetId) {
                const file = input.files && input.files[0];
                if (file) {
                    document.getElementById(targetId).src = URL.createObjectURL(file);
                }
            }

            function previewMultiple(input, targetId) {
                const selectedFiles = selectedFilesByInput.get(input) || [];
                selectedFilesByInput.set(input, selectedFiles.concat(Array.from(input.files || [])));
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

            document.querySelectorAll('.drag-drop-zone').forEach(zone => {
                const input = zone.querySelector('input[type="file"]');

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
                    previewMultiple(input, zone.dataset.previewTarget);
                });
            });

            ['process-sortable', 'section_bat-sortable', 'section_that-sortable', 'section_the-sortable'].forEach(id => {
                const sortableEl = document.getElementById(id);
                if (sortableEl && typeof Sortable !== 'undefined') {
                    Sortable.create(sortableEl, {
                        animation: 160,
                        ghostClass: 'opacity-40',
                    });
                }
            });

            function showModal(id) {
                const modal = document.getElementById(id);
                const inner = modal.querySelector('.bg-white');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                void modal.offsetWidth;
                modal.classList.remove('opacity-0');
                inner.classList.remove('scale-95');
            }

            function closeModal(id) {
                const modal = document.getElementById(id);
                const inner = modal.querySelector('.bg-white');
                modal.classList.add('opacity-0');
                inner.classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }

            function openDeleteCongDoanModal(imagePath) {
                document.getElementById('deleteCongDoanPathInput').value = imagePath;
                showModal('deleteCongDoanModal');
            }

            function openDeleteSectionImageModal(sectionKey, imagePath) {
                document.getElementById('deleteSectionImageSectionInput').value = sectionKey;
                document.getElementById('deleteSectionImagePathInput').value = imagePath;
                showModal('deleteSectionImageModal');
            }
        </script>
    @endpush
</x-admin.layout.app>
