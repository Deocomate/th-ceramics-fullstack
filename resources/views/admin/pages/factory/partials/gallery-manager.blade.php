@php
    $images = is_array($images ?? null) ? $images : [];
    $newErrorKey = "new_{$field}.*";
@endphp

<div class="space-y-6" x-data="galleryManager({{ Js::from($images) }}, {{ Js::from($field) }})">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-3">{{ $label }}</label>
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-4">
            <template x-for="(img, index) in images" :key="img.original_index">
                <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-100"
                    x-transition>
                    <img :src="getImageUrl(img.url)" class="w-full h-full object-cover">
                    <div
                        class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-200 flex items-center justify-center">
                        <button type="button" @click="deleteImage(index)"
                            class="opacity-0 group-hover:opacity-100 transition-all duration-200 w-9 h-9 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 transform transition-transform"
                            title="Xóa ảnh">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
        <p x-show="images.length === 0"
            class="text-sm text-gray-400 text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
            {{ $emptyText ?? 'Chưa có ảnh nào trong thư viện.' }}
        </p>

        <template x-for="idx in deletedIndices" :key="`del-${fieldName}-${idx}`">
            <input type="hidden" :name="`delete_${fieldName}[]`" :value="idx">
        </template>
    </div>

    <div class="border-t border-gray-100 pt-6">
        <label class="block text-sm font-semibold text-gray-700 mb-3">{{ $uploadLabel ?? 'Thêm ảnh mới' }}</label>
        <input type="file" name="new_{{ $field }}[]" multiple accept="image/*"
            class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white">
        <p class="text-xs text-gray-400 mt-2">Có thể chọn nhiều ảnh cùng lúc.</p>
        @error($newErrorKey)
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
