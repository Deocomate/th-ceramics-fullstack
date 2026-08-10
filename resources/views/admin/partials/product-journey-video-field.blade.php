{{-- Shared journey-video URL field for product CT create/edit forms. --}}
@php
    $journeyVideoValue = old('video', ($product ?? null)?->video);
@endphp
<div class="mb-6">
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
    >
    <p class="mt-1.5 text-xs text-gray-500">
        Tùy chọn cho sản phẩm này. Nếu để trống, trang chi tiết sẽ dùng video global của danh mục.
    </p>
    @error('video')
        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
