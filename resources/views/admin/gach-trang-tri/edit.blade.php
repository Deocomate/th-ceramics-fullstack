<x-admin.layout.app title="Gạch Trang Trí" breadcrumb="Admin › Sản Phẩm › Gạch Trang Trí">
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

        $applicationSlots = [
            'main' => ['label' => 'Khối chính bên trái', 'placeholder' => 'Tường trang trí', 'class' => 'lg:row-span-2'],
            'sub_1' => ['label' => 'Ô phụ 1', 'placeholder' => 'Lát nền', 'class' => ''],
            'sub_2' => ['label' => 'Ô phụ 2', 'placeholder' => 'Phòng khách', 'class' => ''],
            'sub_3' => ['label' => 'Ô phụ 3', 'placeholder' => 'Ngoài trời', 'class' => ''],
            'sub_4' => ['label' => 'Ô phụ 4', 'placeholder' => 'Phòng tắm', 'class' => ''],
        ];
        $applications = is_array($gachTrangTri->ung_dung_da_dang) ? $gachTrangTri->ung_dung_da_dang : [];
        $processImages = is_array($gachTrangTri->images) ? $gachTrangTri->images : [];
    @endphp

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu hình Gạch Trang Trí</h2>
        </div>

        <form method="POST" action="{{ route('admin.gach-trang-tri.update') }}" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh nền</label>
                    <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                        <img id="preview-main" src="{{ $mediaUrl($gachTrangTri->thumbnail_main) }}" class="w-full h-full object-contain" alt="Ảnh chính">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                        </div>
                        <input type="file" name="thumbnail_main" accept="image/*" onchange="previewSingle(this, 'preview-main')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('thumbnail_main') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="video" class="block text-sm font-semibold text-gray-700 mb-2">Đường dẫn Video YouTube</label>
                    <input type="url" id="video" name="video" value="{{ old('video', $gachTrangTri->video) }}" placeholder="https://youtube.com/watch?v=..." class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                    @error('video') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </section>

            <section class="border border-gray-200 rounded-xl p-6 bg-gray-50/50">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-5">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Ứng dụng đa dạng</h3>
                        <p class="text-xs text-gray-500 mt-1">Layout mô phỏng vị trí hiển thị trên trang client. Có thể cấu hình từng ô theo từng đợt.</p>
                    </div>
                    <span class="text-xs font-medium text-gray-500">1 khối chính + 4 khối phụ</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">
                    @foreach($applicationSlots as $slot => $slotConfig)
                        @php
                            $item = is_array($applications[$slot] ?? null) ? $applications[$slot] : [];
                            $image = $item['image'] ?? null;
                            $title = old("ung_dung_da_dang.$slot.title", $item['title'] ?? '');
                        @endphp

                        <div class="{{ $slotConfig['class'] }} {{ $slot === 'main' ? 'lg:col-span-2' : 'lg:col-span-1' }}">
                            <label class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-2">{{ $slotConfig['label'] }}</label>
                            <div class="relative {{ $slot === 'main' ? 'aspect-[23/25] lg:h-[520px]' : 'aspect-square' }} rounded-xl border-2 border-dashed border-gray-300 bg-white overflow-hidden group hover:border-[#A31D1D] transition-colors" data-drop-zone>
                                <img id="application-preview-{{ $slot }}" src="{{ $mediaUrl($image, 'https://placehold.co/600x600?text=Chon+Anh') }}" class="w-full h-full object-cover" alt="{{ $slotConfig['label'] }}">
                                <div class="absolute inset-0 bg-black/45 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-center px-4">
                                    <span class="text-white text-xs font-semibold px-3 py-1.5 bg-black/50 rounded-lg">Kéo thả hoặc bấm để chọn ảnh</span>
                                </div>
                                <input type="file" name="ung_dung_da_dang[{{ $slot }}][image]" accept="image/*" onchange="previewSingle(this, 'application-preview-{{ $slot }}')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" data-drop-input>
                            </div>
                            @error("ung_dung_da_dang.$slot.image") <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror

                            <input type="text" name="ung_dung_da_dang[{{ $slot }}][title]" value="{{ $title }}" placeholder="{{ $slotConfig['placeholder'] }}" class="mt-3 w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                            @error("ung_dung_da_dang.$slot.title") <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="border border-gray-200 rounded-xl p-6 bg-gray-50/50">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Hình ảnh Công đoạn chế tác</h3>
                        <p class="text-xs text-gray-500 mt-1">Kéo thả các ảnh đã lưu để đổi thứ tự hiển thị. Ảnh mới sẽ được thêm vào cuối danh sách.</p>
                    </div>
                    <span class="text-xs font-medium text-gray-500">Tổng số: {{ count($processImages) }} ảnh</span>
                </div>

                <div class="drag-drop-zone border-2 border-dashed border-gray-300 rounded-xl p-8 text-center relative hover:bg-gray-50 transition-colors bg-white">
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
                                        <button type="button" onclick="openDeleteCongDoanModal('{{ $path }}')" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm cursor-pointer">
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
                <button type="button" onclick="closeDeleteCongDoanModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteCongDoanForm" method="POST" action="{{ route('admin.gach-trang-tri.cong-doan-image.destroy') }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="image_path" id="deleteCongDoanPathInput" value="">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Có, Xóa</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
        <script>
            const selectedFilesByInput = new WeakMap();

            function previewSingle(input, targetId) {
                const file = input.files && input.files[0];
                if (!file) {
                    return;
                }

                document.getElementById(targetId).src = URL.createObjectURL(file);
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

            document.querySelectorAll('[data-drop-zone]').forEach(zone => {
                const input = zone.querySelector('[data-drop-input]');

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
                    const dataTransfer = new DataTransfer();
                    const firstImage = Array.from(event.dataTransfer.files || []).find(file => file.type.startsWith('image/'));
                    if (!firstImage) {
                        return;
                    }
                    dataTransfer.items.add(firstImage);
                    input.files = dataTransfer.files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });

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
                    previewMultiple(input, 'cong-doan-preview');
                });
            });

            const sortableEl = document.getElementById('process-sortable');
            if (sortableEl && typeof Sortable !== 'undefined') {
                Sortable.create(sortableEl, {
                    animation: 160,
                    ghostClass: 'opacity-40',
                });
            }

            const deleteCongDoanModal = document.getElementById('deleteCongDoanModal');
            const deleteCongDoanModalInner = deleteCongDoanModal.querySelector('.bg-white');

            function openDeleteCongDoanModal(imagePath) {
                document.getElementById('deleteCongDoanPathInput').value = imagePath;
                deleteCongDoanModal.classList.remove('hidden');
                deleteCongDoanModal.classList.add('flex');
                void deleteCongDoanModal.offsetWidth;
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
