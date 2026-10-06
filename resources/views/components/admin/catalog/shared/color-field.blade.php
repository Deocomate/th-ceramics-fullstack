@props([
    'value' => 'Tự chọn',
])

@php
    $colorValue = $value;
@endphp

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Màu sắc <span class="text-xs font-normal text-gray-400">(Mặc định: Tự chọn)</span>
    </label>
    <input
        type="text"
        name="color"
        value="{{ old('color', $colorValue) }}"
        class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all"
    >
    @error('color') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>
