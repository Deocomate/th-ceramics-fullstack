{{--
  Shared product CT gallery manager (images + YouTube + file videos).

  Required:
    $mode — 'create' | 'edit'
    $uploadField — 'images[]' | 'new_images[]'
    $videoField — 'video_urls[]' | 'new_video_urls[]'
    $videoFileField — 'videos[]' | 'new_videos[]'

  Edit:
    $images, $destroyUrl, $uploadUrl, $reorderUrl
--}}
@php
    $mode = $mode ?? 'edit';
    $uploadField = $uploadField ?? ($mode === 'create' ? 'images[]' : 'new_images[]');
    $videoField = $videoField ?? ($mode === 'create' ? 'video_urls[]' : 'new_video_urls[]');
    $videoFileField = $videoFileField ?? ($mode === 'create' ? 'videos[]' : 'new_videos[]');
    $uploadErrorKey = rtrim(str_replace('[]', '', $uploadField), '.');
    $videoErrorKey = rtrim(str_replace('[]', '', $videoField), '.');
    $videoFileErrorKey = rtrim(str_replace('[]', '', $videoFileField), '.');
    $images = $images ?? [];
    $mediaItems = \App\Support\ProductGallery::normalize($images);
    $coverPath = \App\Support\ProductGallery::firstImagePath($images);
    $oldVideoUrls = old($videoErrorKey, []);
    if (! is_array($oldVideoUrls)) {
        $oldVideoUrls = [];
    }
    $isEdit = $mode === 'edit';
    $acceptImages = '.jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp';
    $acceptVideos = '.mp4,.webm,video/mp4,video/webm';
@endphp

<div class="space-y-6" data-gallery-manager data-gallery-video-field="{{ $videoField }}" data-gallery-video-file-field="{{ $videoFileField }}">
    <div class="flex gap-2 border-b border-gray-200">
        <button type="button" data-gallery-subtab="images"
            class="gallery-subtab px-4 py-2.5 text-sm font-bold border-b-2 border-[#A31D1D] text-[#A31D1D]">
            Ảnh
        </button>
        <button type="button" data-gallery-subtab="videos"
            class="gallery-subtab px-4 py-2.5 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-800">
            Video
        </button>
    </div>

    <div data-gallery-subpanel="images">
        <div class="flex flex-col border rounded-xl p-6 bg-gray-50/50 {{ $errors->has($uploadErrorKey) || $errors->has($uploadErrorKey.'.*') ? 'border-red-300 bg-red-50/30' : 'border-gray-200' }}">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                @if($isEdit) Thêm ảnh gallery @else Ảnh gallery (chi tiết) @endif
            </label>
            <p class="text-xs text-gray-500 mb-4">
                Chỉ jpg/jpeg/png/webp · tối đa 5MB/ảnh.
                @if($isEdit)
                    Upload ngay vào thư viện · đặt ảnh bìa trên từng ảnh.
                @else
                    Ảnh đầu tiên sẽ làm ảnh bìa trên website.
                @endif
            </p>

            <div id="gallery-upload-dropzone"
                class="relative mb-4 border-2 border-dashed border-gray-300 rounded-xl bg-white p-4 transition-colors hover:border-[#A31D1D] hover:bg-red-50/30">
                <input type="file" id="multipleImagesInput"
                    @if(! $isEdit) name="{{ $uploadField }}" @endif
                    multiple accept="{{ $acceptImages }}"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    onchange="handleMultipleFiles(event)">
                <div data-gallery-drop-hint class="pointer-events-none text-center py-6 text-xs text-gray-400 font-medium">
                    <p class="text-sm text-gray-600 mb-1">Kéo thả nhiều ảnh vào đây</p>
                    <p>hoặc bấm để chọn từ máy</p>
                </div>
                <div data-gallery-progress class="hidden absolute inset-0 z-20 rounded-xl bg-white/95 flex flex-col items-center justify-center px-6 py-4">
                    <p data-progress-label class="text-sm font-semibold text-gray-800 mb-3 text-center">Đang tải lên…</p>
                    <div class="w-full max-w-sm h-2.5 bg-gray-200 rounded-full overflow-hidden">
                        <div data-progress-bar class="h-full bg-[#A31D1D] rounded-full transition-[width] duration-150" style="width: 0%"></div>
                    </div>
                    <p data-progress-percent class="mt-2 text-xs font-medium text-gray-600 tabular-nums">0%</p>
                </div>
            </div>
            <p id="gallery-upload-hint" class="mb-3 text-xs text-amber-700 hidden"></p>
            <p id="gallery-form-upload-status" class="mb-3 text-xs text-gray-500 hidden"></p>
            @error($uploadErrorKey) <p class="mb-4 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
            @error($uploadErrorKey.'.*') <p class="mb-4 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror

            @if(! $isEdit)
                <div class="h-[180px] bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner">
                    <div id="multiple-preview-container" class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-8 gap-3">
                        <div id="empty-preview-state" class="col-span-full min-h-[100px] flex flex-col items-center justify-center text-center text-gray-400 text-xs font-medium gap-2">
                            <span>Chưa chọn ảnh gallery</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div data-gallery-subpanel="videos" class="hidden space-y-6">
        <div class="flex flex-col border rounded-xl p-6 bg-gray-50/50 {{ $errors->has($videoErrorKey) || $errors->has($videoErrorKey.'.*') ? 'border-red-300 bg-red-50/30' : 'border-gray-200' }}">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Video YouTube</label>
            <p class="text-xs text-gray-500 mb-4">Dán link YouTube (watch / youtu.be / embed / shorts).@if($isEdit) Thêm ngay vào thư viện, không cần Lưu.@endif</p>

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
                                <iframe src="{{ \App\Support\ProductGallery::embedUrl($oldId) }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="flex items-center gap-3 px-3 py-2 border-t border-gray-100">
                                <span class="flex-1 text-xs text-blue-700 truncate">{{ $oldUrl }}</span>
                                <button type="button" onclick="this.closest('.gallery-video-url-item').remove(); window.syncGalleryVideoEmptyState && window.syncGalleryVideoEmptyState();" class="text-red-500 hover:text-red-700 text-xs font-bold">Xóa</button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @if(! $isEdit)
                <p id="gallery-video-empty" class="text-xs text-gray-400 {{ count(array_filter($oldVideoUrls)) ? 'hidden' : '' }}">Chưa thêm video YouTube nào.</p>
            @endif
        </div>

        <div class="flex flex-col border rounded-xl p-6 bg-gray-50/50 {{ $errors->has($videoFileErrorKey) || $errors->has($videoFileErrorKey.'.*') ? 'border-red-300 bg-red-50/30' : 'border-gray-200' }}">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload video (mp4 / webm)</label>
            <p class="text-xs text-gray-500 mb-4">Tối đa 50MB/file. Phát trực tiếp trên trang chi tiết sản phẩm.@if(! $isEdit) File lớn: lưu sản phẩm trước, rồi tải video trên trang sửa.@endif</p>

            <div id="gallery-video-file-dropzone"
                class="relative border-2 border-dashed border-gray-300 rounded-xl bg-white p-4 transition-colors hover:border-[#A31D1D] hover:bg-red-50/30">
                <input type="file" id="galleryVideoFileInput"
                    @if(! $isEdit) name="{{ $videoFileField }}" @endif
                    multiple accept="{{ $acceptVideos }}"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    onchange="handleGalleryVideoFiles(event)">
                <div data-gallery-drop-hint class="pointer-events-none text-center py-6 text-xs text-gray-400 font-medium">
                    <p class="text-sm text-gray-600 mb-1">Kéo thả video mp4/webm</p>
                    <p>hoặc bấm để chọn từ máy</p>
                </div>
                <div data-gallery-progress class="hidden absolute inset-0 z-20 rounded-xl bg-white/95 flex flex-col items-center justify-center px-6 py-4">
                    <p data-progress-label class="text-sm font-semibold text-gray-800 mb-3 text-center">Đang tải video…</p>
                    <div class="w-full max-w-sm h-2.5 bg-gray-200 rounded-full overflow-hidden">
                        <div data-progress-bar class="h-full bg-[#A31D1D] rounded-full transition-[width] duration-150" style="width: 0%"></div>
                    </div>
                    <p data-progress-percent class="mt-2 text-xs font-medium text-gray-600 tabular-nums">0%</p>
                </div>
            </div>
            <p id="gallery-video-file-status" class="mt-2 text-xs text-gray-500 hidden"></p>
            @error($videoFileErrorKey.'.*') <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror

            @if(! $isEdit)
                <div id="gallery-video-file-preview" class="mt-4 space-y-2"></div>
            @endif
        </div>
    </div>
</div>

<div id="galleryFileLimitModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300" role="dialog" aria-modal="true" aria-labelledby="galleryFileLimitTitle">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
        <div class="w-16 h-16 mx-auto bg-amber-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>
        <h3 id="galleryFileLimitTitle" class="text-xl font-bold text-gray-800 mb-2">File quá dung lượng</h3>
        <p id="galleryFileLimitLead" class="text-sm text-gray-600 mb-4">File vượt quá giới hạn nên không được tải lên.</p>
        <ul id="galleryFileLimitList" class="mb-4 max-h-48 overflow-y-auto space-y-2 text-left text-sm bg-amber-50/80 rounded-xl p-3 border border-amber-100"></ul>
        <p class="text-xs text-gray-500 mb-5">Ảnh tối đa <span class="font-semibold text-gray-700">5MB</span> · Video tối đa <span class="font-semibold text-gray-700">50MB</span><br>Định dạng: jpg/jpeg/png/webp hoặc mp4/webm</p>
        <button type="button" onclick="closeGalleryFileLimitModal()" class="w-full px-4 py-2.5 text-sm font-bold text-white rounded-lg transition-colors" style="background:#A31D1D;">Đã hiểu</button>
    </div>
</div>

@if($isEdit)
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-6"
        id="gallery-library-root"
        data-destroy-url="{{ $destroyUrl ?? '' }}"
        data-upload-url="{{ $uploadUrl ?? '' }}"
        data-reorder-url="{{ $reorderUrl ?? '' }}"
        data-cover-path="{{ $coverPath ?? '' }}">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-col gap-3">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
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
            <p class="text-xs text-gray-500">Kéo thả để đổi thứ tự · Đặt làm ảnh bìa trên từng ảnh</p>
        </div>

        <div class="p-6">
            <div id="gallery-library-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 {{ $mediaItems->isEmpty() ? 'hidden' : '' }}">
                @foreach($mediaItems as $item)
                    @php
                        $isVideo = ($item['type'] ?? '') === 'video';
                        $isFileVideo = \App\Support\ProductGallery::isFileVideo($item);
                        $token = \App\Support\ProductGallery::mediaToken($item);
                        $isCover = ! $isVideo && ($item['path'] ?? null) === $coverPath;
                    @endphp
                    @if($isFileVideo)
                        <div class="gallery-library-item relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100"
                            data-media-type="video"
                            data-video-source="file"
                            data-token="{{ $token }}"
                            data-video-path="{{ $item['path'] ?? '' }}">
                            <div class="absolute top-2 left-2 z-20 flex items-center gap-2">
                                <span class="gallery-drag-handle cursor-grab bg-white/90 rounded px-1.5 py-1 text-gray-500 shadow-sm">⋮⋮</span>
                                <input type="checkbox" class="gallery-item-checkbox w-4 h-4 rounded border-gray-300" onchange="syncGallerySelectionState()">
                                <span class="bg-emerald-600 text-white text-[10px] font-bold px-2 py-1 rounded">Video file</span>
                            </div>
                            <div class="aspect-square bg-black">
                                <video src="{{ $item['display_url'] ?? '' }}" class="w-full h-full object-cover" muted preload="metadata" playsinline></video>
                            </div>
                            <div class="flex items-center justify-between gap-2 px-3 py-2 bg-white border-t">
                                <span class="text-[11px] text-gray-500 truncate">{{ basename((string) ($item['path'] ?? '')) }}</span>
                                <button type="button" onclick="openDeleteGalleryModal(null, null, @js($item['path'] ?? ''))" class="shrink-0 px-2.5 py-1 bg-red-600 text-white text-xs font-bold rounded-lg">Xóa</button>
                            </div>
                        </div>
                    @elseif($isVideo)
                        <div class="gallery-library-item relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100"
                            data-media-type="video"
                            data-video-source="youtube"
                            data-token="{{ $token }}"
                            data-video-url="{{ $item['url'] ?? '' }}">
                            <div class="absolute top-2 left-2 z-20 flex items-center gap-2">
                                <span class="gallery-drag-handle cursor-grab bg-white/90 rounded px-1.5 py-1 text-gray-500 shadow-sm">⋮⋮</span>
                                <input type="checkbox" class="gallery-item-checkbox w-4 h-4 rounded border-gray-300" onchange="syncGallerySelectionState()">
                                <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded">YouTube</span>
                            </div>
                            <div class="aspect-video bg-black">
                                <iframe src="{{ $item['embed_url'] ?? '' }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <div class="flex items-center justify-between gap-2 px-3 py-2 bg-white border-t">
                                <span class="text-[11px] text-gray-500 truncate">{{ $item['url'] ?? '' }}</span>
                                <button type="button" onclick="openDeleteGalleryModal(null, @js($item['url'] ?? ''))" class="shrink-0 px-2.5 py-1 bg-red-600 text-white text-xs font-bold rounded-lg">Xóa</button>
                            </div>
                        </div>
                    @else
                        <div class="gallery-library-item relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100"
                            data-media-type="image"
                            data-token="{{ $token }}"
                            data-image-path="{{ $item['path'] ?? '' }}">
                            <div class="absolute top-2 left-2 z-20 flex items-center gap-2">
                                <span class="gallery-drag-handle cursor-grab bg-white/90 rounded px-1.5 py-1 text-gray-500 shadow-sm">⋮⋮</span>
                                <input type="checkbox" class="gallery-item-checkbox w-4 h-4 rounded border-gray-300" onchange="syncGallerySelectionState()">
                                <span class="gallery-cover-badge bg-[#A31D1D] text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm {{ $isCover ? '' : 'hidden' }}">Ảnh bìa</span>
                            </div>
                            <img src="{{ asset('storage/' . ($item['path'] ?? '')) }}" class="w-full h-full object-contain" alt="Ảnh">
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 flex-wrap p-2">
                                <button type="button" onclick="setGalleryCover(@js($item['path']))" class="px-3 py-1.5 bg-white text-gray-800 text-xs font-bold rounded-lg">Đặt làm ảnh bìa</button>
                                <button type="button" onclick="openDeleteGalleryModal(@js($item['path']), null)" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg">Xóa</button>
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
                <button type="button" id="confirmDeleteGalleryBtn" onclick="confirmDeleteGalleryItems()" class="flex-1 px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Có, Xóa</button>
            </div>
        </div>
    </div>
@endif

@once
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    window.__galleryVideoField = @json($videoField);
    const ALLOWED_IMAGE_EXT = ['jpg', 'jpeg', 'png', 'webp'];
    const ALLOWED_IMAGE_MIME = ['image/jpeg', 'image/png', 'image/webp'];
    const ALLOWED_VIDEO_EXT = ['mp4', 'webm'];
    const ALLOWED_VIDEO_MIME = ['video/mp4', 'video/webm'];
    const CHUNK_SIZE = 1024 * 1024;
    const MAX_IMAGE_BYTES = 5 * 1024 * 1024;
    const MAX_VIDEO_BYTES = 50 * 1024 * 1024;
    const editUsesAjaxUpload = {{ $isEdit ? 'true' : 'false' }};
    let galleryUploadBusy = false;
    let galleryFileLimitEscHandler = null;

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    document.querySelectorAll('[data-gallery-subtab]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const tab = btn.getAttribute('data-gallery-subtab');
            document.querySelectorAll('[data-gallery-subtab]').forEach((b) => {
                const on = b.getAttribute('data-gallery-subtab') === tab;
                b.classList.toggle('border-[#A31D1D]', on);
                b.classList.toggle('text-[#A31D1D]', on);
                b.classList.toggle('border-transparent', !on);
                b.classList.toggle('text-gray-500', !on);
            });
            document.querySelectorAll('[data-gallery-subpanel]').forEach((panel) => {
                panel.classList.toggle('hidden', panel.getAttribute('data-gallery-subpanel') !== tab);
            });
        });
    });

    function isAllowedImageFormat(file) {
        if (!file) return false;
        const ext = (file.name.split('.').pop() || '').toLowerCase();
        const mimeOk = !file.type || ALLOWED_IMAGE_MIME.includes(file.type);
        return mimeOk && ALLOWED_IMAGE_EXT.includes(ext);
    }

    function isAllowedVideoFormat(file) {
        if (!file) return false;
        const ext = (file.name.split('.').pop() || '').toLowerCase();
        const mimeOk = !file.type || ALLOWED_VIDEO_MIME.includes(file.type);
        return mimeOk && ALLOWED_VIDEO_EXT.includes(ext);
    }

    function describeRejectedGalleryFile(file, kind) {
        const name = file?.name || 'unknown';
        if (kind === 'video') {
            if (!isAllowedVideoFormat(file)) {
                return { name, reason: 'Định dạng không hỗ trợ (chỉ mp4/webm).', oversized: false };
            }
            if (file.size > MAX_VIDEO_BYTES) {
                return { name, reason: 'Dung lượng ' + formatUploadBytes(file.size) + ' — tối đa 50MB/video.', oversized: true };
            }
            return null;
        }
        if (!isAllowedImageFormat(file)) {
            return { name, reason: 'Định dạng không hỗ trợ (chỉ jpg/jpeg/png/webp).', oversized: false };
        }
        if (file.size > MAX_IMAGE_BYTES) {
            return { name, reason: 'Dung lượng ' + formatUploadBytes(file.size) + ' — tối đa 5MB/ảnh.', oversized: true };
        }
        return null;
    }

    function notifyRejectedGalleryFiles(rejected, acceptedCount, hintEl, kind) {
        if (hintEl) {
            if (rejected.length) {
                const limit = kind === 'video' ? '50MB' : '5MB';
                const formats = kind === 'video' ? 'mp4/webm' : 'jpg/jpeg/png/webp';
                hintEl.textContent = `Đã bỏ ${rejected.length} file không hợp lệ (chỉ ${formats} ≤${limit}).`;
                hintEl.classList.remove('hidden');
            } else {
                hintEl.classList.add('hidden');
                hintEl.textContent = '';
            }
        }
        if (rejected.length) {
            showGalleryFileLimitModal(rejected, { kind, acceptedCount });
        }
    }

    function filterImageFiles(fileList, hintEl) {
        const files = Array.from(fileList || []);
        const accepted = [];
        const rejected = [];
        files.forEach((file) => {
            const rejection = describeRejectedGalleryFile(file, 'image');
            if (rejection) rejected.push(rejection);
            else accepted.push(file);
        });
        notifyRejectedGalleryFiles(rejected, accepted.length, hintEl, 'image');
        return accepted;
    }

    function filterVideoFiles(fileList, hintEl) {
        const files = Array.from(fileList || []);
        const accepted = [];
        const rejected = [];
        files.forEach((file) => {
            const rejection = describeRejectedGalleryFile(file, 'video');
            if (rejection) rejected.push(rejection);
            else accepted.push(file);
        });
        notifyRejectedGalleryFiles(rejected, accepted.length, hintEl, 'video');
        return accepted;
    }

    function showGalleryFileLimitModal(items, options = {}) {
        const modal = document.getElementById('galleryFileLimitModal');
        if (!modal || !items.length) return;
        const titleEl = document.getElementById('galleryFileLimitTitle');
        const leadEl = document.getElementById('galleryFileLimitLead');
        const listEl = document.getElementById('galleryFileLimitList');
        const inner = modal.querySelector('.bg-white');
        const allOversized = items.every((item) => item.oversized);
        const kind = options.kind === 'video' ? 'video' : 'ảnh';
        const limit = options.kind === 'video' ? '50MB' : '5MB';
        if (titleEl) {
            titleEl.textContent = allOversized ? 'File quá dung lượng' : 'Không thể tải file lên';
        }
        if (leadEl) {
            if (options.acceptedCount > 0) {
                leadEl.textContent = `Một số file không hợp lệ đã bị bỏ. File ${kind} hợp lệ vẫn được tải lên.`;
            } else if (allOversized) {
                leadEl.textContent = `File ${kind} vượt quá ${limit} nên không được tải lên. Hãy nén hoặc chọn file nhỏ hơn.`;
            } else {
                leadEl.textContent = `File ${kind} không hợp lệ nên không được tải lên.`;
            }
        }
        if (listEl) {
            listEl.innerHTML = items.map((item) => {
                const name = escapeHtml(item.name || 'File không xác định');
                const reason = escapeHtml(item.reason || '');
                return `<li class="rounded-lg bg-white px-3 py-2 border border-amber-100"><p class="font-semibold text-gray-800 break-all">${name}</p><p class="text-amber-800 mt-0.5">${reason}</p></li>`;
            }).join('');
        }
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        inner?.classList.remove('scale-95');
        if (galleryFileLimitEscHandler) {
            document.removeEventListener('keydown', galleryFileLimitEscHandler);
        }
        galleryFileLimitEscHandler = (event) => {
            if (event.key === 'Escape') closeGalleryFileLimitModal();
        };
        document.addEventListener('keydown', galleryFileLimitEscHandler);
    }

    window.closeGalleryFileLimitModal = function() {
        const modal = document.getElementById('galleryFileLimitModal');
        if (!modal) return;
        const inner = modal.querySelector('.bg-white');
        modal.classList.add('opacity-0');
        inner?.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
        if (galleryFileLimitEscHandler) {
            document.removeEventListener('keydown', galleryFileLimitEscHandler);
            galleryFileLimitEscHandler = null;
        }
    };

    document.getElementById('galleryFileLimitModal')?.addEventListener('click', (event) => {
        if (event.target === event.currentTarget) closeGalleryFileLimitModal();
    });

    let selectedFiles = [];
    let selectedVideoFiles = [];
    const multipleImagesInput = document.getElementById('multipleImagesInput');
    const previewContainer = document.getElementById('multiple-preview-container');
    const emptyState = document.getElementById('empty-preview-state');
    const uploadDropzone = document.getElementById('gallery-upload-dropzone');
    const uploadHint = document.getElementById('gallery-upload-hint');
    const formUploadStatus = document.getElementById('gallery-form-upload-status');

    function resolveGalleryAjaxUploadUrl() {
        return document.getElementById('gallery-library-root')?.dataset?.uploadUrl || '';
    }

    function galleryProgressEls(dropzone) {
        if (!dropzone) return {};
        return {
            root: dropzone.querySelector('[data-gallery-progress]'),
            label: dropzone.querySelector('[data-progress-label]'),
            bar: dropzone.querySelector('[data-progress-bar]'),
            percent: dropzone.querySelector('[data-progress-percent]'),
            input: dropzone.querySelector('input[type="file"]'),
        };
    }

    function showGalleryProgress(dropzone, { percent = 0, label = 'Đang tải lên…' } = {}) {
        const els = galleryProgressEls(dropzone);
        if (!els.root) return;
        els.root.classList.remove('hidden');
        const clamped = Math.max(0, Math.min(100, Math.round(percent)));
        if (els.label) els.label.textContent = label;
        if (els.bar) els.bar.style.width = clamped + '%';
        if (els.percent) els.percent.textContent = clamped + '%';
        if (els.input) els.input.disabled = true;
    }

    function hideGalleryProgress(dropzone) {
        const els = galleryProgressEls(dropzone);
        if (!els.root) return;
        els.root.classList.add('hidden');
        if (els.bar) els.bar.style.width = '0%';
        if (els.percent) els.percent.textContent = '0%';
        if (els.input) els.input.disabled = false;
    }

    function formatUploadBytes(bytes) {
        if (!bytes || bytes < 1024) return (bytes || 0) + ' B';
        if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + ' KB';
        return (Math.round(bytes / 1024 / 1024 * 10) / 10) + ' MB';
    }

    function postGalleryFormData(body, statusEl, options = {}) {
        const endpoint = resolveGalleryAjaxUploadUrl();
        if (!endpoint) return Promise.reject(new Error('Thiếu URL upload. Tải lại trang rồi thử lại.'));

        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', endpoint);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken());
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.withCredentials = true;
            xhr.timeout = 120000;

            xhr.upload.onprogress = (event) => {
                if (!event.lengthComputable) return;
                const percent = Math.round((event.loaded / event.total) * 100);
                if (typeof options.onProgress === 'function') {
                    options.onProgress(percent, event.loaded, event.total);
                }
            };

            xhr.onload = () => {
                let data = {};
                const raw = xhr.responseText || '';
                try { data = JSON.parse(raw); } catch (_) {}
                if (xhr.status === 413 || /POST data is too large/i.test(raw + (data.message || ''))) {
                    reject(Object.assign(new Error('File vượt quá giới hạn máy chủ. Ảnh tối đa 5MB, video tối đa 50MB.'), { galleryLimit: true }));
                    return;
                }
                if (xhr.status < 200 || xhr.status >= 300) {
                    const message = data.message
                        || (data.errors && Object.values(data.errors).flat().join(' '))
                        || ('Upload thất bại (HTTP ' + xhr.status + ').');
                    const limitError = /vượt quá|quá lớn|5MB|50MB/i.test(message);
                    reject(Object.assign(new Error(message), { galleryLimit: limitError }));
                    return;
                }
                if (data.items) renderLibraryFromItems(data.items, data.cover_path);
                if (statusEl && data.message) {
                    statusEl.textContent = data.message;
                    statusEl.classList.remove('hidden', 'text-red-600');
                    statusEl.classList.add('text-gray-500');
                }
                resolve(data);
            };
            xhr.ontimeout = () => reject(new Error('Hết thời gian tải lên. Kiểm tra mạng rồi thử lại.'));
            xhr.onerror = () => reject(new Error('Mất kết nối khi tải lên.'));
            xhr.onabort = () => reject(new Error('Đã hủy tải lên.'));
            xhr.send(body);
        });
    }

    function newGalleryUploadId() {
        if (window.crypto && typeof window.crypto.randomUUID === 'function') {
            return window.crypto.randomUUID();
        }
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    async function uploadFileInChunks(file, kind, dropzone, statusEl, label) {
        const totalChunks = Math.max(1, Math.ceil(file.size / CHUNK_SIZE));
        const uploadId = newGalleryUploadId();
        let lastData = null;
        for (let index = 0; index < totalChunks; index++) {
            const blob = file.slice(index * CHUNK_SIZE, (index + 1) * CHUNK_SIZE);
            const fd = new FormData();
            fd.append('chunk', blob, file.name);
            fd.append('upload_id', uploadId);
            fd.append('chunk_index', String(index));
            fd.append('total_chunks', String(totalChunks));
            fd.append('kind', kind);
            fd.append('original_name', file.name);
            lastData = await postGalleryFormData(fd, statusEl, {
                onProgress(percent) {
                    const overall = Math.round(((index + (percent / 100)) / totalChunks) * 100);
                    showGalleryProgress(dropzone, {
                        percent: overall,
                        label: percent >= 100 && index === totalChunks - 1
                            ? ('Đang xử lý ' + file.name + '…')
                            : (label + ' · phần ' + (index + 1) + '/' + totalChunks),
                    });
                },
            });
            showGalleryProgress(dropzone, {
                percent: Math.round(((index + 1) / totalChunks) * 100),
                label: (index === totalChunks - 1)
                    ? ('Đang xử lý ' + file.name + '…')
                    : (label + ' · phần ' + (index + 1) + '/' + totalChunks),
            });
        }
        return lastData;
    }

    async function uploadFilesViaAjax(fileList, statusEl) {
        const files = filterImageFiles(fileList, statusEl || uploadHint);
        if (!files.length) return;
        if (galleryUploadBusy) {
            if (statusEl) {
                statusEl.textContent = 'Đang tải file khác, vui lòng đợi xong rồi thêm tiếp.';
                statusEl.classList.remove('hidden', 'text-red-600');
                statusEl.classList.add('text-gray-500');
            }
            return;
        }
        galleryUploadBusy = true;
        if (statusEl) {
            statusEl.textContent = 'Đang upload ' + files.length + ' ảnh…';
            statusEl.classList.remove('hidden', 'text-red-600');
            statusEl.classList.add('text-gray-500');
        }
        showGalleryProgress(uploadDropzone, { percent: 0, label: 'Đang tải ' + files.length + ' ảnh…' });
        try {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const prefix = files.length > 1 ? ('Ảnh ' + (i + 1) + '/' + files.length + ' · ') : '';
                await uploadFileInChunks(file, 'image', uploadDropzone, statusEl, prefix + file.name);
                if (statusEl) statusEl.textContent = 'Đã upload ' + (i + 1) + '/' + files.length + ' ảnh…';
            }
            showGalleryProgress(uploadDropzone, { percent: 100, label: 'Đã thêm ảnh vào thư viện.' });
            window.setTimeout(() => hideGalleryProgress(uploadDropzone), 700);
        } catch (err) {
            hideGalleryProgress(uploadDropzone);
            const message = err.message || 'Upload thất bại.';
            if (err.galleryLimit || /vượt quá|quá lớn|5MB|50MB/i.test(message)) {
                showGalleryFileLimitModal([{ name: 'Ảnh vừa chọn', reason: message, oversized: true }], { kind: 'image', acceptedCount: 0 });
            }
            if (statusEl) {
                statusEl.textContent = message;
                statusEl.classList.remove('text-gray-500');
                statusEl.classList.add('text-red-600');
            } else if (!err.galleryLimit) {
                alert(message);
            }
        } finally {
            galleryUploadBusy = false;
        }
    }

    window.handleMultipleFiles = function(event) {
        const input = event.target;
        if (editUsesAjaxUpload) {
            uploadFilesViaAjax(input.files, formUploadStatus).finally(() => { input.value = ''; });
            return;
        }
        const files = filterImageFiles(input.files, uploadHint);
        if (!files.length) return;
        selectedFiles = files.slice();
        renderPreviews();
    };

    function appendDroppedFiles(fileList) {
        if (editUsesAjaxUpload) {
            uploadFilesViaAjax(fileList, formUploadStatus);
            return;
        }
        const files = filterImageFiles(fileList, uploadHint);
        if (!files.length) return;
        selectedFiles = selectedFiles.concat(files);
        updateFileInput();
        renderPreviews();
    }

    if (uploadDropzone) {
        ['dragenter', 'dragover'].forEach((evt) => {
            uploadDropzone.addEventListener(evt, (event) => {
                event.preventDefault(); event.stopPropagation();
                uploadDropzone.classList.add('border-[#A31D1D]', 'bg-red-50');
            }, true);
        });
        uploadDropzone.addEventListener('drop', (event) => {
            event.preventDefault(); event.stopPropagation();
            uploadDropzone.classList.remove('border-[#A31D1D]', 'bg-red-50');
            appendDroppedFiles(event.dataTransfer?.files);
        }, true);
    }

    function renderPreviews() {
        if (!previewContainer || !emptyState) return;
        previewContainer.querySelectorAll('.image-preview-item').forEach((item) => item.remove());
        if (selectedFiles.length === 0) { emptyState.style.display = 'flex'; return; }
        emptyState.style.display = 'none';
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'image-preview-item relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain"><div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"><button type="button" onclick="removeGalleryFile(${index})" class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button></div>`;
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
        if (!multipleImagesInput || editUsesAjaxUpload) return;
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach((file) => dataTransfer.items.add(file));
        multipleImagesInput.files = dataTransfer.files;
    }

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
        if (id) { shell.classList.remove('hidden'); iframe.src = 'https://www.youtube.com/embed/' + id; }
        else { shell.classList.add('hidden'); iframe.src = ''; }
    };

    window.syncGalleryVideoEmptyState = function() {
        const container = document.getElementById('gallery-video-urls-container');
        const empty = document.getElementById('gallery-video-empty');
        if (!empty || !container) return;
        empty.classList.toggle('hidden', !!container.querySelector('.gallery-video-url-item'));
    };

    window.addGalleryVideoUrl = async function() {
        const input = document.getElementById('gallery-video-url-input');
        const container = document.getElementById('gallery-video-urls-container');
        if (!input || !container) return;
        const url = (input.value || '').trim();
        const id = window.extractGalleryYoutubeId(url);
        if (!id) { alert('Link YouTube không hợp lệ.'); return; }

        if (editUsesAjaxUpload) {
            try {
                const fd = new FormData();
                fd.append('video_urls[]', url);
                await postGalleryFormData(fd, document.getElementById('gallery-form-upload-status'));
                input.value = '';
                window.previewGalleryYoutube('');
            } catch (err) {
                alert(err.message || 'Không thêm được video.');
            }
            return;
        }

        const fieldName = document.querySelector('[data-gallery-video-field]')?.dataset.galleryVideoField || window.__galleryVideoField || 'new_video_urls[]';
        const div = document.createElement('div');
        div.className = 'gallery-video-url-item border border-gray-200 rounded-xl overflow-hidden bg-white';
        div.innerHTML = `<input type="hidden" name="${fieldName}" value="${url.replace(/"/g, '&quot;')}"><div class="aspect-video bg-gray-100"><iframe src="https://www.youtube.com/embed/${id}" class="w-full h-full" frameborder="0" allowfullscreen></iframe></div><div class="flex items-center gap-3 px-3 py-2 border-t border-gray-100"><span class="flex-1 text-xs text-blue-700 truncate">${url.replace(/</g, '&lt;')}</span><button type="button" class="text-red-500 hover:text-red-700 text-xs font-bold">Xóa</button></div>`;
        div.querySelector('button').addEventListener('click', () => { div.remove(); window.syncGalleryVideoEmptyState(); });
        container.appendChild(div);
        window.syncGalleryVideoEmptyState();
        input.value = '';
        window.previewGalleryYoutube('');
    };

    window.handleGalleryVideoFiles = async function(event) {
        const input = event.target;
        const status = document.getElementById('gallery-video-file-status');
        const dropzone = document.getElementById('gallery-video-file-dropzone');
        const files = filterVideoFiles(input.files, status);
        if (editUsesAjaxUpload) {
            if (!files.length) {
                input.value = '';
                return;
            }
            if (galleryUploadBusy) {
                if (status) {
                    status.classList.remove('hidden', 'text-red-600');
                    status.classList.add('text-gray-500');
                    status.textContent = 'Đang tải file khác, vui lòng đợi xong rồi thêm tiếp.';
                }
                input.value = '';
                return;
            }
            galleryUploadBusy = true;
            try {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const prefix = files.length > 1
                        ? ('Video ' + (i + 1) + '/' + files.length + ' · ')
                        : '';
                    if (status) {
                        status.classList.remove('hidden', 'text-red-600');
                        status.classList.add('text-gray-500');
                        status.textContent = prefix + 'Đang tải ' + file.name + '…';
                    }
                    showGalleryProgress(dropzone, {
                        percent: 0,
                        label: prefix + file.name + ' (' + formatUploadBytes(file.size) + ')',
                    });
                    await uploadFileInChunks(
                        file,
                        'video',
                        dropzone,
                        status,
                        prefix + file.name + ' (' + formatUploadBytes(file.size) + ')'
                    );
                }
                showGalleryProgress(dropzone, { percent: 100, label: 'Đã thêm video vào thư viện.' });
                window.setTimeout(() => hideGalleryProgress(dropzone), 700);
            } catch (err) {
                hideGalleryProgress(dropzone);
                const message = err.message || 'Upload video thất bại.';
                if (err.galleryLimit || /vượt quá|quá lớn|50MB/i.test(message)) {
                    showGalleryFileLimitModal([{ name: 'Video vừa chọn', reason: message, oversized: true }], { kind: 'video', acceptedCount: 0 });
                }
                if (status) {
                    status.classList.remove('hidden', 'text-gray-500');
                    status.classList.add('text-red-600');
                    status.textContent = message;
                } else if (!err.galleryLimit) {
                    alert(message);
                }
            } finally {
                galleryUploadBusy = false;
                input.value = '';
            }
            return;
        }
        selectedVideoFiles = selectedVideoFiles.concat(files);
        const dt = new DataTransfer();
        selectedVideoFiles.forEach((f) => dt.items.add(f));
        input.files = dt.files;
        const preview = document.getElementById('gallery-video-file-preview');
        if (preview) {
            preview.innerHTML = selectedVideoFiles.map((f) => `<p class="text-xs text-gray-600">${f.name} (${Math.round(f.size / 1024 / 1024 * 10) / 10} MB)</p>`).join('');
        }
    };

    const videoDrop = document.getElementById('gallery-video-file-dropzone');
    if (videoDrop) {
        videoDrop.addEventListener('dragover', (e) => { e.preventDefault(); videoDrop.classList.add('border-[#A31D1D]', 'bg-red-50'); }, true);
        videoDrop.addEventListener('drop', (e) => {
            e.preventDefault(); e.stopPropagation();
            videoDrop.classList.remove('border-[#A31D1D]', 'bg-red-50');
            const input = document.getElementById('galleryVideoFileInput');
            if (!input) return;
            const dt = new DataTransfer();
            Array.from(e.dataTransfer?.files || []).forEach((f) => dt.items.add(f));
            input.files = dt.files;
            handleGalleryVideoFiles({ target: input });
        }, true);
    }

    function libraryRoot() { return document.getElementById('gallery-library-root'); }
    function destroyUrl() { return libraryRoot()?.dataset.destroyUrl || ''; }
    function reorderUrl() { return libraryRoot()?.dataset.reorderUrl || ''; }

    let pendingDelete = { imagePaths: [], videoUrls: [], videoPaths: [], elements: [] };

    function getSelectedLibraryItems() {
        return Array.from(document.querySelectorAll('.gallery-library-item')).filter((el) => el.querySelector('.gallery-item-checkbox')?.checked);
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
        document.querySelectorAll('.gallery-library-item .gallery-item-checkbox').forEach((cb) => { cb.checked = !!checked; });
        window.syncGallerySelectionState();
    };

    function updateLibraryCount(remaining) {
        const countEl = document.getElementById('gallery-library-count');
        if (countEl && typeof remaining === 'number') countEl.textContent = `Đang có ${remaining} mục`;
        const empty = document.getElementById('gallery-library-empty');
        const grid = document.getElementById('gallery-library-grid');
        const left = document.querySelectorAll('.gallery-library-item').length;
        if (empty) empty.classList.toggle('hidden', left > 0);
        if (grid) grid.classList.toggle('hidden', left === 0);
    }

    function applyCoverBadges(coverPath) {
        const root = libraryRoot();
        if (root) root.dataset.coverPath = coverPath || '';
        document.querySelectorAll('.gallery-library-item[data-media-type="image"]').forEach((el) => {
            const badge = el.querySelector('.gallery-cover-badge');
            if (!badge) return;
            badge.classList.toggle('hidden', el.dataset.imagePath !== coverPath);
        });
    }

    function escapeHtml(value) {
        return String(value || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
    }

    function buildLibraryCard(item) {
        const div = document.createElement('div');
        if (item.source === 'file' || (item.type === 'video' && item.path && !item.embed_url)) {
            div.className = 'gallery-library-item relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100';
            div.dataset.mediaType = 'video';
            div.dataset.videoSource = 'file';
            div.dataset.token = item.token;
            div.dataset.videoPath = item.path || '';
            const src = item.display_url || item.url || '';
            div.innerHTML = `<div class="absolute top-2 left-2 z-20 flex items-center gap-2"><span class="gallery-drag-handle cursor-grab bg-white/90 rounded px-1.5 py-1 text-gray-500 shadow-sm">⋮⋮</span><input type="checkbox" class="gallery-item-checkbox w-4 h-4 rounded border-gray-300" onchange="syncGallerySelectionState()"><span class="bg-emerald-600 text-white text-[10px] font-bold px-2 py-1 rounded">Video file</span></div><div class="aspect-square bg-black"><video src="${escapeHtml(src)}" class="w-full h-full object-cover" muted preload="metadata" playsinline></video></div><div class="flex items-center justify-between gap-2 px-3 py-2 bg-white border-t"><span class="text-[11px] text-gray-500 truncate">${escapeHtml((item.path || '').split('/').pop())}</span><button type="button" class="shrink-0 px-2.5 py-1 bg-red-600 text-white text-xs font-bold rounded-lg">Xóa</button></div>`;
            div.querySelector('button').addEventListener('click', () => openDeleteGalleryModal(null, null, item.path));
        } else if (item.type === 'video') {
            div.className = 'gallery-library-item relative group rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100';
            div.dataset.mediaType = 'video';
            div.dataset.videoSource = 'youtube';
            div.dataset.token = item.token;
            div.dataset.videoUrl = item.url || '';
            div.innerHTML = `<div class="absolute top-2 left-2 z-20 flex items-center gap-2"><span class="gallery-drag-handle cursor-grab bg-white/90 rounded px-1.5 py-1 text-gray-500 shadow-sm">⋮⋮</span><input type="checkbox" class="gallery-item-checkbox w-4 h-4 rounded border-gray-300" onchange="syncGallerySelectionState()"><span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded">YouTube</span></div><div class="aspect-video bg-black"><iframe src="${escapeHtml(item.embed_url || '')}" class="w-full h-full" frameborder="0" allowfullscreen></iframe></div><div class="flex items-center justify-between gap-2 px-3 py-2 bg-white border-t"><span class="text-[11px] text-gray-500 truncate">${escapeHtml(item.url || '')}</span><button type="button" class="shrink-0 px-2.5 py-1 bg-red-600 text-white text-xs font-bold rounded-lg">Xóa</button></div>`;
            div.querySelector('button').addEventListener('click', () => openDeleteGalleryModal(null, item.url));
        } else {
            div.className = 'gallery-library-item relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100';
            div.dataset.mediaType = 'image';
            div.dataset.token = item.token;
            div.dataset.imagePath = item.path || '';
            const src = item.url || '';
            div.innerHTML = `<div class="absolute top-2 left-2 z-20 flex items-center gap-2"><span class="gallery-drag-handle cursor-grab bg-white/90 rounded px-1.5 py-1 text-gray-500 shadow-sm">⋮⋮</span><input type="checkbox" class="gallery-item-checkbox w-4 h-4 rounded border-gray-300" onchange="syncGallerySelectionState()"><span class="gallery-cover-badge bg-[#A31D1D] text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm ${item.is_cover ? '' : 'hidden'}">Ảnh bìa</span></div><img src="${escapeHtml(src)}" class="w-full h-full object-contain" alt="Ảnh"><div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 flex-wrap p-2"><button type="button" class="set-cover-btn px-3 py-1.5 bg-white text-gray-800 text-xs font-bold rounded-lg">Đặt làm ảnh bìa</button><button type="button" class="del-btn px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg">Xóa</button></div>`;
            div.querySelector('.set-cover-btn').addEventListener('click', () => setGalleryCover(item.path));
            div.querySelector('.del-btn').addEventListener('click', () => openDeleteGalleryModal(item.path, null));
        }
        return div;
    }

    function renderLibraryFromItems(items, coverPath) {
        const grid = document.getElementById('gallery-library-grid');
        if (!grid) return;
        grid.innerHTML = '';
        (items || []).forEach((item) => grid.appendChild(buildLibraryCard(item)));
        grid.classList.toggle('hidden', !(items || []).length);
        applyCoverBadges(coverPath);
        updateLibraryCount((items || []).length);
        window.syncGallerySelectionState();
        initLibrarySortable();
    }

    async function persistGalleryOrder(tokens) {
        const url = reorderUrl();
        if (!url) return;
        const response = await fetch(url, {
            method: 'PUT',
            headers: { 'X-CSRF-TOKEN': csrfToken(), 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({ items: tokens }),
        });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(data.message || 'Không lưu được thứ tự');
        applyCoverBadges(data.cover_path);
    }

    window.setGalleryCover = async function(imagePath) {
        const url = reorderUrl();
        if (!url || !imagePath) return;
        try {
            const response = await fetch(url, {
                method: 'PUT',
                headers: { 'X-CSRF-TOKEN': csrfToken(), 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ cover_path: imagePath }),
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) throw new Error(data.message || 'Không đặt được ảnh bìa');
            applyCoverBadges(data.cover_path);
            const grid = document.getElementById('gallery-library-grid');
            const card = Array.from(document.querySelectorAll('.gallery-library-item')).find((el) => el.dataset.imagePath === imagePath);
            if (grid && card) grid.prepend(card);
        } catch (err) {
            alert(err.message || 'Không đặt được ảnh bìa');
        }
    };

    let sortableInstance = null;
    function initLibrarySortable() {
        const grid = document.getElementById('gallery-library-grid');
        if (!grid || typeof Sortable === 'undefined') return;
        if (sortableInstance) { sortableInstance.destroy(); sortableInstance = null; }
        sortableInstance = Sortable.create(grid, {
            animation: 160,
            handle: '.gallery-drag-handle',
            draggable: '.gallery-library-item',
            ghostClass: 'opacity-40',
            onEnd: async function() {
                const tokens = Array.from(grid.querySelectorAll('.gallery-library-item')).map((el) => el.dataset.token).filter(Boolean);
                try { await persistGalleryOrder(tokens); }
                catch (err) { alert(err.message || 'Không lưu được thứ tự'); }
            },
        });
    }

    window.openDeleteGalleryModal = function(imagePath, videoUrl, videoPath) {
        const modal = document.getElementById('deleteGalleryModal');
        if (!modal) return;
        const message = document.getElementById('deleteGalleryModalMessage');
        const inner = modal.querySelector('.bg-white');
        const elements = Array.from(document.querySelectorAll('.gallery-library-item')).filter((el) => {
            if (imagePath && el.dataset.imagePath === imagePath) return true;
            if (videoUrl && el.dataset.videoUrl === videoUrl) return true;
            if (videoPath && el.dataset.videoPath === videoPath) return true;
            return false;
        });
        pendingDelete = {
            imagePaths: imagePath ? [imagePath] : [],
            videoUrls: videoUrl ? [videoUrl] : [],
            videoPaths: videoPath ? [videoPath] : [],
            elements,
        };
        if (message) message.textContent = (videoUrl || videoPath) ? 'Video này sẽ bị xóa khỏi thư viện media của sản phẩm.' : 'Ảnh này sẽ bị xóa khỏi danh sách ảnh của sản phẩm.';
        modal.classList.remove('hidden'); modal.classList.add('flex'); void modal.offsetWidth; modal.classList.remove('opacity-0'); inner?.classList.remove('scale-95');
    };

    window.openBulkDeleteGalleryModal = function() {
        const selected = getSelectedLibraryItems();
        if (!selected.length) return;
        const imagePaths = [], videoUrls = [], videoPaths = [];
        selected.forEach((el) => {
            if (el.dataset.videoPath) videoPaths.push(el.dataset.videoPath);
            else if (el.dataset.mediaType === 'video' && el.dataset.videoUrl) videoUrls.push(el.dataset.videoUrl);
            else if (el.dataset.imagePath) imagePaths.push(el.dataset.imagePath);
        });
        pendingDelete = { imagePaths, videoUrls, videoPaths, elements: selected };
        const modal = document.getElementById('deleteGalleryModal');
        const message = document.getElementById('deleteGalleryModalMessage');
        const inner = modal?.querySelector('.bg-white');
        if (message) message.textContent = `Bạn sắp xóa ${selected.length} mục khỏi thư viện media.`;
        if (!modal) return;
        modal.classList.remove('hidden'); modal.classList.add('flex'); void modal.offsetWidth; modal.classList.remove('opacity-0'); inner?.classList.remove('scale-95');
    };

    window.closeDeleteGalleryModal = function() {
        const modal = document.getElementById('deleteGalleryModal');
        if (!modal) return;
        const inner = modal.querySelector('.bg-white');
        modal.classList.add('opacity-0'); inner?.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        pendingDelete = { imagePaths: [], videoUrls: [], videoPaths: [], elements: [] };
    };

    window.confirmDeleteGalleryItems = async function() {
        const url = destroyUrl();
        if (!url) { alert('Không tìm thấy URL xóa media.'); return; }
        const { imagePaths, videoUrls, videoPaths, elements } = pendingDelete;
        if (!imagePaths.length && !videoUrls.length && !videoPaths.length) { window.closeDeleteGalleryModal(); return; }
        const btn = document.getElementById('confirmDeleteGalleryBtn');
        if (btn) { btn.disabled = true; btn.textContent = 'Đang xóa...'; }
        const body = new URLSearchParams();
        imagePaths.forEach((path) => body.append('image_paths[]', path));
        videoUrls.forEach((videoUrl) => body.append('video_urls[]', videoUrl));
        videoPaths.forEach((path) => body.append('video_paths[]', path));
        if (imagePaths.length === 1 && videoUrls.length === 0 && videoPaths.length === 0) body.append('image_path', imagePaths[0]);
        if (videoUrls.length === 1 && imagePaths.length === 0 && videoPaths.length === 0) body.append('video_url', videoUrls[0]);
        if (videoPaths.length === 1 && imagePaths.length === 0 && videoUrls.length === 0) body.append('video_path', videoPaths[0]);
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken(), 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'Content-Type': 'application/x-www-form-urlencoded' },
                body: body.toString(),
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok) throw new Error(data.message || 'Xóa media thất bại.');
            elements.forEach((el) => el.remove());
            updateLibraryCount(data.remaining_count);
            window.syncGallerySelectionState();
            window.closeDeleteGalleryModal();
        } catch (error) {
            alert(error?.message || 'Xóa media thất bại.');
        } finally {
            if (btn) { btn.disabled = false; btn.textContent = 'Có, Xóa'; }
        }
    };

    window.openDeleteImageModal = function(imagePath) { window.openDeleteGalleryModal(imagePath, null); };
    window.closeDeleteImageModal = window.closeDeleteGalleryModal;
    window.removeFile = window.removeGalleryFile;

    window.syncGallerySelectionState();
    initLibrarySortable();
</script>
@endpush
@endonce
