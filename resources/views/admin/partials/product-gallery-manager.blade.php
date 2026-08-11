{{--
  Shared product CT gallery manager (images + YouTube videos).

  Required:
    $mode — 'create' | 'edit'
    $section — 'form' (upload+videos) | 'library' (current media + delete modal) | 'all'
    $uploadField — 'images[]' | 'new_images[]'
    $videoField — 'video_urls[]' | 'new_video_urls[]'

  Edit library section:
    $images — raw product images JSON
    $destroyUrl — route URL for DELETE gallery item
--}}
@php
    $mode = $mode ?? 'edit';
    $section = $section ?? ($mode === 'create' ? 'form' : 'all');
    $uploadField = $uploadField ?? ($mode === 'create' ? 'images[]' : 'new_images[]');
    $videoField = $videoField ?? ($mode === 'create' ? 'video_urls[]' : 'new_video_urls[]');
    $uploadErrorKey = rtrim(str_replace('[]', '', $uploadField), '.');
    $videoErrorKey = rtrim(str_replace('[]', '', $videoField), '.');
    $images = $images ?? [];
    $mediaItems = \App\Support\ProductGallery::normalize($images);
    $coverPath = \App\Support\ProductGallery::firstImagePath($images);
    $oldVideoUrls = old($videoErrorKey, []);
    if (! is_array($oldVideoUrls)) {
        $oldVideoUrls = [];
    }
    $showForm = in_array($section, ['form', 'all'], true);
    $showLibrary = $mode === 'edit' && in_array($section, ['library', 'all'], true);
@endphp

@if($showForm)
<div class="flex flex-col h-full border rounded-xl p-6 bg-gray-50/50 {{ $errors->has($uploadErrorKey) || $errors->has($uploadErrorKey.'.*') ? 'border-red-300 bg-red-50/30' : 'border-gray-200' }}">
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        @if($mode === 'create')
            Hình ảnh sản phẩm <span class="text-red-500">*</span>
        @else
            Thêm Hình Ảnh Mới
        @endif
    </label>
    @if($mode === 'create')
        <p class="text-xs text-gray-500 mb-4">Chọn hoặc kéo thả 1 hoặc nhiều ảnh (ảnh đầu tiên làm ảnh bìa nếu chưa có video đứng trước).</p>
    @else
        <p class="text-xs text-gray-500 mb-4">Chọn hoặc kéo thả nhiều ảnh cùng lúc.</p>
    @endif

    <div id="gallery-upload-dropzone"
        class="relative mb-4 border-2 border-dashed border-gray-300 rounded-xl bg-white p-4 transition-colors hover:border-[#A31D1D]/hover:bg-red-50/30">
        <input type="file" id="multipleImagesInput" name="{{ $uploadField }}" multiple accept="image/*"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
            onchange="handleMultipleFiles(event)">
        <div class="pointer-events-none text-center py-6 text-xs text-gray-400 font-medium">
            <p class="text-sm text-gray-600 mb-1">Kéo thả nhiều ảnh vào đây</p>
            <p>hoặc bấm để chọn từ máy</p>
        </div>
    </div>
    @error($uploadErrorKey) <p class="mb-4 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
    @error($uploadErrorKey.'.*') <p class="mb-4 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror

    <div class="{{ $mode === 'create' ? 'h-[250px]' : 'h-[180px]' }} bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner flex flex-col">
        <div id="multiple-preview-container" class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-8 gap-3">
            <div id="empty-preview-state" class="col-span-full h-full {{ $mode === 'create' ? 'min-h-[180px]' : 'min-h-[100px]' }} flex flex-col items-center justify-center text-center text-gray-400 text-xs font-medium gap-2">
                <span>{{ $mode === 'create' ? 'Chưa có ảnh nào' : 'Chưa chọn thêm ảnh nào' }}</span>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 flex flex-col border rounded-xl p-6 bg-gray-50/50 {{ $errors->has($videoErrorKey) || $errors->has($videoErrorKey.'.*') ? 'border-red-300 bg-red-50/30' : 'border-gray-200' }}" data-gallery-video-field="{{ $videoField }}">
    <label class="block text-sm font-semibold text-gray-700 mb-2">Thêm Video YouTube</label>
    <p class="text-xs text-gray-500 mb-4">Dán link YouTube (watch / youtu.be / embed / shorts). Video sẽ xuất hiện trong gallery cùng ảnh.</p>

    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <input type="url" id="gallery-video-url-input" placeholder="https://youtube.com/watch?v=..."
            class="flex-1 px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all"
            oninput="previewGalleryYoutube(this.value)">
        <button type="button" onclick="addGalleryVideoUrl()"
            class="px-4 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors whitespace-nowrap" style="background:#A31D1D;">
            Thêm video
        </button>
    </div>
    @error($videoErrorKey.'.*') <p class="mb-3 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror

    <div id="gallery-youtube-preview-shell" class="mb-4 aspect-video max-w-2xl rounded-xl overflow-hidden border border-gray-200 bg-gray-100 hidden">
        <iframe id="gallery-youtube-preview" src="" class="w-full h-full" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
    </div>

    <div id="gallery-video-urls-container" class="space-y-4">
        @foreach($oldVideoUrls as $oldUrl)
            @php $oldId = is_string($oldUrl) ? \App\Support\ProductGallery::extractYoutubeId($oldUrl) : null; @endphp
            @if(is_string($oldUrl) && $oldUrl !== '' && $oldId)
                <div class="gallery-video-url-item border border-gray-200 rounded-xl overflow-hidden bg-white">
                    <input type="hidden" name="{{ $videoField }}" value="{{ $oldUrl }}">
                    <div class="aspect-video bg-gray-100">
                        <iframe src="{{ \App\Support\ProductGallery::embedUrl($oldId) }}" class="w-full h-full" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                    </div>
                    <div class="flex items-center gap-3 px-3 py-2 border-t border-gray-100">
                        <span class="flex-1 text-xs text-blue-700 truncate">{{ $oldUrl }}</span>
                        <button type="button" onclick="this.closest('.gallery-video-url-item').remove(); window.syncGalleryVideoEmptyState && window.syncGalleryVideoEmptyState();" class="text-red-500 hover:text-red-700 text-xs font-bold">Xóa</button>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    <p id="gallery-video-empty" class="text-xs text-gray-400 {{ count(array_filter($oldVideoUrls)) ? 'hidden' : '' }}">Chưa thêm video nào vào lần lưu này.</p>
</div>
@endif

@if($showLibrary)
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8" id="gallery-library-root" data-destroy-url="{{ $destroyUrl }}">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Thư viện media hiện tại</h2>
                <span id="gallery-library-count" class="text-xs font-medium text-gray-500">Đang có {{ $mediaItems->count() }} mục</span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <label class="inline-flex items-center gap-2 text-xs text-gray-600 cursor-pointer select-none">
                    <input type="checkbox" id="gallery-select-all" class="rounded border-gray-300 text-[#A31D1D] focus:ring-[#A31D1D]" onchange="toggleSelectAllGalleryItems(this.checked)">
                    Chọn tất cả
                </label>
                <button type="button" id="gallery-bulk-delete-btn" disabled onclick="openBulkDeleteGalleryModal()"
                    class="px-3 py-1.5 text-xs font-bold rounded-lg text-white bg-red-600 hover:bg-red-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                    Xóa đã chọn (<span id="gallery-selected-count">0</span>)
                </button>
            </div>
        </div>
        <div class="p-6">
            <div id="gallery-library-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 {{ $mediaItems->isEmpty() ? 'hidden' : '' }}">
                @foreach($mediaItems as $item)
                    @if(($item['type'] ?? '') === 'video')
                        <div class="gallery-library-item relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100"
                            data-media-type="video"
                            data-video-url="{{ $item['url'] }}">
                            <div class="absolute top-2 left-2 z-20 flex items-center gap-2">
                                <input type="checkbox" class="gallery-item-checkbox w-4 h-4 rounded border-gray-300 text-[#A31D1D] focus:ring-[#A31D1D] bg-white/90"
                                    onchange="syncGallerySelectionState()">
                                <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">Video</span>
                            </div>
                            <div class="aspect-video bg-black">
                                <iframe src="{{ $item['embed_url'] }}" class="w-full h-full" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                            </div>
                            <div class="flex items-center justify-between gap-2 px-3 py-2 bg-white border-t border-gray-100">
                                <span class="text-[11px] text-gray-500 truncate">{{ $item['url'] }}</span>
                                <button type="button" onclick="openDeleteGalleryModal(null, @js($item['url']))" class="shrink-0 px-2.5 py-1 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors">
                                    Xóa
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="gallery-library-item relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100"
                            data-media-type="image"
                            data-image-path="{{ $item['path'] ?? '' }}">
                            <div class="absolute top-2 left-2 z-20 flex items-center gap-2">
                                <input type="checkbox" class="gallery-item-checkbox w-4 h-4 rounded border-gray-300 text-[#A31D1D] focus:ring-[#A31D1D] bg-white/90"
                                    onchange="syncGallerySelectionState()">
                                @if(($item['path'] ?? null) === $coverPath)
                                    <span class="bg-[#A31D1D] text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">Ảnh bìa</span>
                                @endif
                            </div>
                            <img src="{{ asset('storage/' . ($item['path'] ?? '')) }}" class="w-full h-full object-contain" alt="Ảnh">
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                <button type="button" onclick="openDeleteGalleryModal(@js($item['path']), null)" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                    Xóa ảnh này
                                </button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <p id="gallery-library-empty" class="text-gray-500 text-sm text-center py-6 {{ $mediaItems->isNotEmpty() ? 'hidden' : '' }}">Sản phẩm này chưa có hình ảnh hoặc video nào.</p>
        </div>
    </div>

    <div id="deleteGalleryModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa?</h3>
            <p id="deleteGalleryModalMessage" class="text-sm text-gray-500 mb-6">Mục này sẽ bị xóa khỏi thư viện media của sản phẩm.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteGalleryModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <button type="button" id="confirmDeleteGalleryBtn" onclick="confirmDeleteGalleryItems()" class="flex-1 px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">
                    Có, Xóa
                </button>
            </div>
        </div>
    </div>
@endif

@once
@push('scripts')
<script>
    window.__galleryVideoField = @json($videoField);

    window.extractGalleryYoutubeId = function(url) {
        if (!url) return null;
        const match = String(url).match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([A-Za-z0-9_-]{6,})/i);
        return match ? match[1] : null;
    };

    window.previewGalleryYoutube = function(url) {
        const id = window.extractGalleryYoutubeId(url);
        const shell = document.getElementById('gallery-youtube-preview-shell');
        const iframe = document.getElementById('gallery-youtube-preview');
        if (!shell || !iframe) return;
        if (id) {
            shell.classList.remove('hidden');
            iframe.src = 'https://www.youtube.com/embed/' + id;
        } else {
            shell.classList.add('hidden');
            iframe.src = '';
        }
    };

    window.syncGalleryVideoEmptyState = function() {
        const container = document.getElementById('gallery-video-urls-container');
        const empty = document.getElementById('gallery-video-empty');
        if (!empty || !container) return;
        empty.classList.toggle('hidden', !!container.querySelector('.gallery-video-url-item'));
    };

    window.addGalleryVideoUrl = function() {
        const input = document.getElementById('gallery-video-url-input');
        const container = document.getElementById('gallery-video-urls-container');
        if (!input || !container) return;

        const url = (input.value || '').trim();
        const id = window.extractGalleryYoutubeId(url);
        if (!id) {
            alert('Link YouTube không hợp lệ.');
            return;
        }

        const fieldName = document.querySelector('[data-gallery-video-field]')?.dataset.galleryVideoField
            || window.__galleryVideoField
            || 'new_video_urls[]';

        const div = document.createElement('div');
        div.className = 'gallery-video-url-item border border-gray-200 rounded-xl overflow-hidden bg-white';
        div.innerHTML = `
            <input type="hidden" name="${fieldName}" value="${url.replace(/"/g, '&quot;')}">
            <div class="aspect-video bg-gray-100">
                <iframe src="https://www.youtube.com/embed/${id}" class="w-full h-full" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
            </div>
            <div class="flex items-center gap-3 px-3 py-2 border-t border-gray-100">
                <span class="flex-1 text-xs text-blue-700 truncate">${url.replace(/</g, '&lt;')}</span>
                <button type="button" class="text-red-500 hover:text-red-700 text-xs font-bold">Xóa</button>
            </div>
        `;
        div.querySelector('button').addEventListener('click', () => {
            div.remove();
            window.syncGalleryVideoEmptyState();
        });
        container.appendChild(div);
        window.syncGalleryVideoEmptyState();
        input.value = '';
        window.previewGalleryYoutube('');
    };

    let selectedFiles = [];
    const multipleImagesInput = document.getElementById('multipleImagesInput');
    const previewContainer = document.getElementById('multiple-preview-container');
    const emptyState = document.getElementById('empty-preview-state');
    const uploadDropzone = document.getElementById('gallery-upload-dropzone');

    window.handleMultipleFiles = function(event) {
        const files = Array.from(event.target.files || []).filter((file) => file.type.startsWith('image/'));
        if (files.length > 0) {
            selectedFiles = selectedFiles.concat(files);
            updateFileInput();
            renderPreviews();
        }
    };

    function appendDroppedFiles(fileList) {
        const files = Array.from(fileList || []).filter((file) => file.type.startsWith('image/'));
        if (files.length === 0) return;
        selectedFiles = selectedFiles.concat(files);
        updateFileInput();
        renderPreviews();
    }

    if (uploadDropzone) {
        ['dragenter', 'dragover'].forEach((evt) => {
            uploadDropzone.addEventListener(evt, (event) => {
                event.preventDefault();
                event.stopPropagation();
                uploadDropzone.classList.add('border-[#A31D1D]', 'bg-red-50');
            }, true);
        });
        uploadDropzone.addEventListener('dragleave', (event) => {
            event.preventDefault();
            event.stopPropagation();
            if (!uploadDropzone.contains(event.relatedTarget)) {
                uploadDropzone.classList.remove('border-[#A31D1D]', 'bg-red-50');
            }
        }, true);
        uploadDropzone.addEventListener('drop', (event) => {
            event.preventDefault();
            event.stopPropagation();
            uploadDropzone.classList.remove('border-[#A31D1D]', 'bg-red-50');
            appendDroppedFiles(event.dataTransfer?.files);
        }, true);
    }

    function renderPreviews() {
        if (!previewContainer || !emptyState) return;
        previewContainer.querySelectorAll('.image-preview-item').forEach((item) => item.remove());

        if (selectedFiles.length === 0) {
            emptyState.style.display = 'flex';
            return;
        }

        emptyState.style.display = 'none';
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'image-preview-item relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-contain">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                        <button type="button" onclick="removeGalleryFile(${index})" class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                `;
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    window.removeGalleryFile = function(index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        renderPreviews();
    };

    function updateFileInput() {
        if (!multipleImagesInput) return;
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach((file) => dataTransfer.items.add(file));
        multipleImagesInput.files = dataTransfer.files;
    }

    let pendingDelete = { imagePaths: [], videoUrls: [], elements: [] };

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function destroyUrl() {
        return document.getElementById('gallery-library-root')?.dataset.destroyUrl || '';
    }

    function getSelectedLibraryItems() {
        return Array.from(document.querySelectorAll('.gallery-library-item')).filter((el) => {
            const checkbox = el.querySelector('.gallery-item-checkbox');
            return checkbox && checkbox.checked;
        });
    }

    window.syncGallerySelectionState = function() {
        const selected = getSelectedLibraryItems();
        const countEl = document.getElementById('gallery-selected-count');
        const bulkBtn = document.getElementById('gallery-bulk-delete-btn');
        const selectAll = document.getElementById('gallery-select-all');
        const all = document.querySelectorAll('.gallery-library-item .gallery-item-checkbox');

        if (countEl) countEl.textContent = String(selected.length);
        if (bulkBtn) bulkBtn.disabled = selected.length === 0;
        if (selectAll && all.length) {
            selectAll.checked = selected.length === all.length;
            selectAll.indeterminate = selected.length > 0 && selected.length < all.length;
        }
    };

    window.toggleSelectAllGalleryItems = function(checked) {
        document.querySelectorAll('.gallery-library-item .gallery-item-checkbox').forEach((cb) => {
            cb.checked = !!checked;
        });
        window.syncGallerySelectionState();
    };

    function updateLibraryCount(remaining) {
        const countEl = document.getElementById('gallery-library-count');
        if (countEl && typeof remaining === 'number') {
            countEl.textContent = `Đang có ${remaining} mục`;
        }
        const empty = document.getElementById('gallery-library-empty');
        const grid = document.getElementById('gallery-library-grid');
        const left = document.querySelectorAll('.gallery-library-item').length;
        if (empty) empty.classList.toggle('hidden', left > 0);
        if (grid && left === 0) grid.classList.add('hidden');
    }

    window.openDeleteGalleryModal = function(imagePath, videoUrl) {
        const modal = document.getElementById('deleteGalleryModal');
        if (!modal) return;
        const message = document.getElementById('deleteGalleryModalMessage');
        const inner = modal.querySelector('.bg-white');

        const elements = Array.from(document.querySelectorAll('.gallery-library-item')).filter((el) => {
            if (imagePath && el.dataset.imagePath === imagePath) return true;
            if (videoUrl && el.dataset.videoUrl === videoUrl) return true;
            return false;
        });

        pendingDelete = {
            imagePaths: imagePath ? [imagePath] : [],
            videoUrls: videoUrl ? [videoUrl] : [],
            elements,
        };

        if (message) {
            message.textContent = videoUrl
                ? 'Video này sẽ bị xóa khỏi thư viện media của sản phẩm.'
                : 'Ảnh này sẽ bị xóa khỏi danh sách ảnh của sản phẩm.';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        inner?.classList.remove('scale-95');
    };

    window.openBulkDeleteGalleryModal = function() {
        const selected = getSelectedLibraryItems();
        if (!selected.length) return;

        const imagePaths = [];
        const videoUrls = [];
        selected.forEach((el) => {
            if (el.dataset.mediaType === 'video' && el.dataset.videoUrl) {
                videoUrls.push(el.dataset.videoUrl);
            } else if (el.dataset.imagePath) {
                imagePaths.push(el.dataset.imagePath);
            }
        });

        pendingDelete = { imagePaths, videoUrls, elements: selected };

        const modal = document.getElementById('deleteGalleryModal');
        const message = document.getElementById('deleteGalleryModalMessage');
        const inner = modal?.querySelector('.bg-white');
        if (message) {
            message.textContent = `Bạn sắp xóa ${selected.length} mục khỏi thư viện media. Thao tác không thể hoàn tác.`;
        }
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        inner?.classList.remove('scale-95');
    };

    window.closeDeleteGalleryModal = function() {
        const modal = document.getElementById('deleteGalleryModal');
        if (!modal) return;
        const inner = modal.querySelector('.bg-white');
        modal.classList.add('opacity-0');
        inner?.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
        pendingDelete = { imagePaths: [], videoUrls: [], elements: [] };
    };

    window.confirmDeleteGalleryItems = async function() {
        const url = destroyUrl();
        if (!url) {
            alert('Không tìm thấy URL xóa media.');
            return;
        }

        const { imagePaths, videoUrls, elements } = pendingDelete;
        if (!imagePaths.length && !videoUrls.length) {
            window.closeDeleteGalleryModal();
            return;
        }

        const btn = document.getElementById('confirmDeleteGalleryBtn');
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Đang xóa...';
        }

        const body = new URLSearchParams();
        imagePaths.forEach((path) => body.append('image_paths[]', path));
        videoUrls.forEach((videoUrl) => body.append('video_urls[]', videoUrl));
        if (imagePaths.length === 1 && videoUrls.length === 0) {
            body.append('image_path', imagePaths[0]);
        }
        if (videoUrls.length === 1 && imagePaths.length === 0) {
            body.append('video_url', videoUrls[0]);
        }

        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: body.toString(),
            });

            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                const msg = data.message || data.errors?.image_path?.[0] || 'Xóa media thất bại.';
                throw new Error(msg);
            }

            elements.forEach((el) => el.remove());
            updateLibraryCount(data.remaining_count);
            window.syncGallerySelectionState();
            window.closeDeleteGalleryModal();
        } catch (error) {
            alert(error?.message || 'Xóa media thất bại.');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.textContent = 'Có, Xóa';
            }
        }
    };

    window.openDeleteImageModal = function(imagePath) {
        window.openDeleteGalleryModal(imagePath, null);
    };
    window.closeDeleteImageModal = window.closeDeleteGalleryModal;
    window.removeFile = window.removeGalleryFile;

    window.syncGallerySelectionState();
</script>
@endpush
@endonce
