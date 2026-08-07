@php
    $isEdit = isset($product) && $product;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tên dáng ngói <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <x-admin.shared.color-field :value="old('color', $product->color ?? 'Tự chọn')" />
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kích thước</label>
                <input type="text" name="size" value="{{ old('size', $product->size ?? '') }}" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
            </div>
        </div>

        <div class="bg-gray-50/80 rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4 border-b border-gray-200 pb-3">
                <div>
                    <label class="block text-sm font-bold text-gray-800">Danh sách Thông số / Mô tả</label>
                    <p class="text-xs text-gray-500 mt-0.5">Mỗi dòng tương ứng với 1 gạch đầu dòng trên website.</p>
                </div>
                <button type="button" onclick="addDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 hover:text-[#A31D1D] hover:border-[#A31D1D] transition-colors shadow-sm">Thêm dòng mới</button>
            </div>
            <div id="des-blocks-container" class="space-y-2.5"></div>
        </div>

        <div class="bg-blue-50/30 rounded-xl border border-blue-100 p-5">
            <div class="flex items-center justify-between mb-4 border-b border-blue-200 pb-3">
                <div>
                    <label class="block text-sm font-bold text-blue-800">Danh sách Kích thước chi tiết</label>
                    <p class="text-xs text-gray-500 mt-0.5">Hiển thị thông số chi tiết bên cạnh ảnh bản vẽ.</p>
                </div>
                <button type="button" onclick="addSizeDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-blue-50 hover:text-blue-700 hover:border-blue-400 transition-colors shadow-sm">Thêm dòng</button>
            </div>
            <div id="size-des-blocks-container" class="space-y-2.5"></div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Ảnh bản vẽ / Kích thước</label>
        <div class="aspect-square w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
            <img id="preview-size" src="{{ $isEdit && $product->size_image ? \App\Support\AssetPath::url($product->size_image) : 'https://placehold.co/400x400?text=Chon+Ban+Ve' }}" class="w-full h-full object-contain" alt="">
            <input type="file" name="size_image" accept="image/*" onchange="previewImage(event, 'preview-size')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
        </div>
        @error('size_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<hr class="border-gray-100 my-8">

@if($isEdit)
    @include('admin.partials.product-gallery-manager', [
        'mode' => 'edit',
        'section' => 'form',
        'uploadField' => 'new_images[]',
        'videoField' => 'new_video_urls[]',
    ])
@else
    @include('admin.partials.product-gallery-manager', [
        'mode' => 'create',
        'section' => 'form',
        'uploadField' => 'images[]',
        'videoField' => 'video_urls[]',
    ])
@endif

@push('scripts')
<script>
    function previewImage(event, targetId) {
        const file = event.target.files[0];
        if (file) document.getElementById(targetId).src = URL.createObjectURL(file);
    }

    function addInputBlock(container, name, value = '', autoFocus = true) {
        const div = document.createElement('div');
        div.className = 'flex items-center bg-white rounded-lg border border-gray-200 shadow-sm group focus-within:border-[#A31D1D] focus-within:ring-1 focus-within:ring-[#A31D1D] transition-all overflow-hidden';
        div.innerHTML = `
            <input type="text" name="${name}[]" value="${String(value).replace(/"/g, '&quot;')}" class="flex-1 py-2.5 px-3 text-sm border-none focus:ring-0 outline-none text-gray-700 bg-transparent">
            <button type="button" onclick="this.parentElement.remove()" class="px-3 text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity focus:opacity-100">X</button>
        `;
        container.appendChild(div);
        if (autoFocus && value === '') div.querySelector('input').focus();
    }

    const desContainer = document.getElementById('des-blocks-container');
    const sizeDesContainer = document.getElementById('size-des-blocks-container');
    const existingDes = @json(old('des', $product->des ?? []));
    const existingSizeDes = @json(old('size_des', $product->size_des ?? []));

    function addDesBlock(value = '', autoFocus = true) { addInputBlock(desContainer, 'des', value, autoFocus); }
    function addSizeDesBlock(value = '', autoFocus = true) { addInputBlock(sizeDesContainer, 'size_des', value, autoFocus); }

    (Array.isArray(existingDes) && existingDes.length ? existingDes : ['']).forEach(item => addDesBlock(item, false));
    (Array.isArray(existingSizeDes) && existingSizeDes.length ? existingSizeDes : ['']).forEach(item => addSizeDesBlock(item, false));

</script>
@endpush
