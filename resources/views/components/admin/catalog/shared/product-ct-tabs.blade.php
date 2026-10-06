@props([
    'defaultTab' => 'info',
])

@php
    $mediaErrorKeys = [
        'cover_image', 'images', 'images.*', 'new_images', 'new_images.*',
        'video_urls', 'video_urls.*', 'new_video_urls', 'new_video_urls.*',
        'videos', 'videos.*', 'new_videos', 'new_videos.*',
    ];
    $hasMediaErrors = collect($mediaErrorKeys)->contains(fn ($key) => $errors->has($key));
    $activeTab = $hasMediaErrors ? 'media' : $defaultTab;
@endphp

<div data-product-ct-tabs data-active-tab="{{ $activeTab }}">
    <div class="mb-6 bg-gray-50 rounded-xl p-1.5 flex flex-wrap gap-1 border border-gray-200">
        <button type="button" data-ct-tab-btn="info"
            class="ct-tab-btn flex-1 min-w-[140px] px-4 py-2.5 rounded-lg text-sm font-bold transition-all {{ $activeTab === 'info' ? 'bg-white text-[#A31D1D] shadow-sm' : 'text-gray-600 hover:bg-white/70' }}">
            Thông tin sản phẩm
        </button>
        <button type="button" data-ct-tab-btn="media"
            class="ct-tab-btn flex-1 min-w-[140px] px-4 py-2.5 rounded-lg text-sm font-bold transition-all {{ $activeTab === 'media' ? 'bg-white text-[#A31D1D] shadow-sm' : 'text-gray-600 hover:bg-white/70' }}">
            Thư viện media
        </button>
    </div>

    <div data-ct-tab-panel="info" class="{{ $activeTab === 'info' ? '' : 'hidden' }}">
        {{ $info }}
    </div>
    <div data-ct-tab-panel="media" class="{{ $activeTab === 'media' ? '' : 'hidden' }}">
        {{ $media }}
    </div>
</div>

@once
@push('scripts')
<script>
    document.querySelectorAll('[data-product-ct-tabs]').forEach((root) => {
        const buttons = root.querySelectorAll('[data-ct-tab-btn]');
        const panels = root.querySelectorAll('[data-ct-tab-panel]');
        buttons.forEach((btn) => {
            btn.addEventListener('click', () => {
                const tab = btn.getAttribute('data-ct-tab-btn');
                buttons.forEach((b) => {
                    const on = b.getAttribute('data-ct-tab-btn') === tab;
                    b.classList.toggle('bg-white', on);
                    b.classList.toggle('text-[#A31D1D]', on);
                    b.classList.toggle('shadow-sm', on);
                    b.classList.toggle('text-gray-600', !on);
                    b.classList.toggle('hover:bg-white/70', !on);
                });
                panels.forEach((panel) => {
                    panel.classList.toggle('hidden', panel.getAttribute('data-ct-tab-panel') !== tab);
                });
            });
        });
    });
</script>
@endpush
@endonce
