{{-- Shared journey-video URL field for product CT create/edit forms. --}}
@php
    $journeyVideoValue = old('video', ($product ?? null)?->video);
    $journeyYoutubeId = is_string($journeyVideoValue)
        ? \App\Support\ProductGallery::extractYoutubeId($journeyVideoValue)
        : null;
@endphp
<div class="mb-6" data-journey-video-field>
    <label for="journey-video" class="block text-sm font-semibold text-gray-700 mb-2">
        Video hành trình (YouTube)
    </label>
    <input
        type="url"
        id="journey-video"
        name="video"
        value="{{ $journeyVideoValue }}"
        placeholder="https://youtube.com/watch?v=... (để trống = dùng video chung của danh mục)"
        class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all {{ $errors->has('video') ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500 bg-red-50/50' : 'border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]' }}"
        oninput="previewJourneyYoutube(this.value)"
    >
    <p class="mt-1.5 text-xs text-gray-500">
        Tùy chọn cho sản phẩm này. Nếu để trống, trang chi tiết sẽ dùng video global của danh mục.
    </p>
    @error('video')
        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror

    <div id="journey-youtube-preview-shell" class="mt-4 aspect-video max-w-2xl rounded-xl overflow-hidden border border-gray-200 bg-gray-100 {{ $journeyYoutubeId ? '' : 'hidden' }}">
        <iframe
            id="journey-youtube-preview"
            src="{{ $journeyYoutubeId ? \App\Support\ProductGallery::embedUrl($journeyYoutubeId) : '' }}"
            class="w-full h-full"
            frameborder="0"
            allowfullscreen
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        ></iframe>
    </div>
    <div id="journey-youtube-empty-state" class="mt-4 aspect-video max-w-2xl rounded-xl border border-dashed border-gray-300 bg-gray-50 flex items-center justify-center text-sm text-gray-400 {{ $journeyYoutubeId ? 'hidden' : '' }}">
        Chưa có video preview
    </div>
</div>

@once
@push('scripts')
<script>
    window.previewJourneyYoutube = function(url) {
        const id = (function(value) {
            if (!value) return null;
            const match = String(value).match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/|live\/)|youtu\.be\/)([A-Za-z0-9_-]{6,})/i);
            return match ? match[1] : null;
        })(url);

        const shell = document.getElementById('journey-youtube-preview-shell');
        const empty = document.getElementById('journey-youtube-empty-state');
        const iframe = document.getElementById('journey-youtube-preview');
        if (!shell || !iframe) return;

        if (id) {
            shell.classList.remove('hidden');
            empty?.classList.add('hidden');
            iframe.src = 'https://www.youtube.com/embed/' + id;
        } else {
            shell.classList.add('hidden');
            empty?.classList.remove('hidden');
            iframe.src = '';
        }
    };
</script>
@endpush
@endonce
