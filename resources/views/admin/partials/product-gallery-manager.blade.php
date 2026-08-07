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
        <p class="text-xs text-gray-500 mb-4">Chọn 1 hoặc nhiều ảnh chi tiết (ảnh đầu tiên làm ảnh bìa nếu chưa có video đứng trước).</p>
    @endif
    <div class="relative mb-4">
        <input type="file" id="multipleImagesInput" name="{{ $uploadField }}" multiple accept="image/*"
            class="w-full text-sm border rounded-lg p-1.5 cursor-pointer bg-white {{ $errors->has($uploadErrorKey) || $errors->has($uploadErrorKey.'.*') ? 'border-red-500' : 'border-gray-300' }}"
            onchange="handleMultipleFiles(event)">
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

    <div id="gallery-youtube-preview-shell" class="mb-4 aspect-video max-w-md rounded-xl overflow-hidden border border-gray-200 bg-gray-100 hidden">
        <iframe id="gallery-youtube-preview" src="" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
    </div>

    <div id="gallery-video-urls-container" class="space-y-2">
        @foreach($oldVideoUrls as $oldUrl)
            @php $oldId = is_string($oldUrl) ? \App\Support\ProductGallery::extractYoutubeId($oldUrl) : null; @endphp
            @if(is_string($oldUrl) && $oldUrl !== '' && $oldId)
                <div class="gallery-video-url-item flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-3 py-2">
                    <input type="hidden" name="{{ $videoField }}" value="{{ $oldUrl }}">
                    <img src="{{ \App\Support\ProductGallery::thumbUrl($oldId) }}" alt="" class="w-14 h-10 object-cover rounded">
                    <span class="flex-1 text-xs text-blue-700 truncate">{{ $oldUrl }}</span>
                    <button type="button" onclick="this.closest('.gallery-video-url-item').remove()" class="text-red-500 hover:text-red-700 text-xs font-bold">Xóa</button>
                </div>
            @endif
        @endforeach
    </div>
    <p id="gallery-video-empty" class="text-xs text-gray-400 {{ count(array_filter($oldVideoUrls)) ? 'hidden' : '' }}">Chưa thêm video nào vào lần lưu này.</p>
</div>
@endif

@if($showLibrary)
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Thư viện media hiện tại</h2>
            <span class="text-xs font-medium text-gray-500">Đang có {{ $mediaItems->count() }} mục</span>
        </div>
        <div class="p-6">
            @if($mediaItems->isNotEmpty())
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($mediaItems as $item)
                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100">
                            @if(($item['type'] ?? '') === 'video')
                                <img src="{{ $item['thumb_url'] }}" class="w-full h-full object-cover" alt="Video">
                                <span class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                    <span class="w-10 h-10 rounded-full bg-black/60 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white ml-0.5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                    </span>
                                </span>
                                <div class="absolute top-2 left-2 bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">Video</div>
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                    <button type="button" onclick="openDeleteGalleryModal(null, @js($item['url']))" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                        Xóa video
                                    </button>
                                </div>
                            @else
                                <img src="{{ asset('storage/' . ($item['path'] ?? '')) }}" class="w-full h-full object-contain" alt="Ảnh">
                                @if(($item['path'] ?? null) === $coverPath)
                                    <div class="absolute top-2 left-2 bg-[#A31D1D] text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                                        Ảnh bìa
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                    <button type="button" onclick="openDeleteGalleryModal(@js($item['path']), null)" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                        Xóa ảnh này
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm text-center py-6">Sản phẩm này chưa có hình ảnh hoặc video nào.</p>
            @endif
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
                <form id="deleteGalleryForm" method="POST" action="{{ $destroyUrl }}" class="flex-1">
                    @csrf @method('DELETE')
                    <input type="hidden" name="image_path" id="deleteGalleryImagePath" value="">
                    <input type="hidden" name="video_url" id="deleteGalleryVideoUrl" value="">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Có, Xóa</button>
                </form>
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

    window.addGalleryVideoUrl = function() {
        const input = document.getElementById('gallery-video-url-input');
        const container = document.getElementById('gallery-video-urls-container');
        const empty = document.getElementById('gallery-video-empty');
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
        div.className = 'gallery-video-url-item flex items-center gap-3 bg-white border border-gray-200 rounded-lg px-3 py-2';
        div.innerHTML = `
            <input type="hidden" name="${fieldName}" value="${url.replace(/"/g, '&quot;')}">
            <img src="https://img.youtube.com/vi/${id}/hqdefault.jpg" alt="" class="w-14 h-10 object-cover rounded">
            <span class="flex-1 text-xs text-blue-700 truncate">${url.replace(/</g, '&lt;')}</span>
            <button type="button" class="text-red-500 hover:text-red-700 text-xs font-bold">Xóa</button>
        `;
        div.querySelector('button').addEventListener('click', () => {
            div.remove();
            if (empty && !container.querySelector('.gallery-video-url-item')) {
                empty.classList.remove('hidden');
            }
        });
        container.appendChild(div);
        if (empty) empty.classList.add('hidden');
        input.value = '';
        window.previewGalleryYoutube('');
    };

    let selectedFiles = [];
    const multipleImagesInput = document.getElementById('multipleImagesInput');
    const previewContainer = document.getElementById('multiple-preview-container');
    const emptyState = document.getElementById('empty-preview-state');

    window.handleMultipleFiles = function(event) {
        const files = Array.from(event.target.files || []);
        if (files.length > 0) {
            selectedFiles = selectedFiles.concat(files);
            updateFileInput();
            renderPreviews();
        }
    };

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

    window.openDeleteGalleryModal = function(imagePath, videoUrl) {
        const modal = document.getElementById('deleteGalleryModal');
        if (!modal) return;
        const imageInput = document.getElementById('deleteGalleryImagePath');
        const videoInput = document.getElementById('deleteGalleryVideoUrl');
        const message = document.getElementById('deleteGalleryModalMessage');
        const inner = modal.querySelector('.bg-white');

        if (imageInput) imageInput.value = imagePath || '';
        if (videoInput) videoInput.value = videoUrl || '';
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
    };

    window.openDeleteImageModal = function(imagePath) {
        window.openDeleteGalleryModal(imagePath, null);
    };
    window.closeDeleteImageModal = window.closeDeleteGalleryModal;
    window.removeFile = window.removeGalleryFile;
</script>
@endpush
@endonce
