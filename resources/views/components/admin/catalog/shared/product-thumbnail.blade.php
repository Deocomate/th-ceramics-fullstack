@props([
    'images' => [],
    'isDelete' => false,
    'alt' => '',
])

@php
    $thumb = \App\Domains\Catalog\Infrastructure\ProductGallery::firstImagePath($images);
@endphp

<div class="w-16 h-16 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0 {{ $isDelete ? 'opacity-50 grayscale' : '' }}">
    @if($thumb)
        <img src="{{ \App\Support\AssetPath::url($thumb) }}" class="w-full h-full object-contain" alt="{{ $alt }}">
    @else
        <div class="w-full h-full flex items-center justify-center text-gray-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
    @endif
</div>
