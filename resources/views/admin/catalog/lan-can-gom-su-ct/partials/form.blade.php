@php
    $isEdit = isset($product) && $product;
@endphp

<x-admin.catalog.shared.product-ct-tabs>
<x-slot:info>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- CỘT THÔNG TIN CHUNG (Không Code, Không Giá) -->
    <div class="lg:col-span-2 space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tên dáng ngói <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required placeholder="VD: Lan Can Gốm Sứ Cỡ Nhỏ"
                    class="w-full px-4 py-2.5 text-sm border rounded-lg focus:ring-1 outline-none transition-all {{ $errors->has('name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50/50' : 'border-gray-300 focus:border-[#A31D1D] focus:ring-[#A31D1D]' }}">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <x-admin.catalog.shared.color-field :value="old('color', $product->color ?? 'Tự chọn')" />
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kích thước (Text)</label>
                <input type="text" name="size" value="{{ old('size', $product->size ?? '') }}" placeholder="VD: L200 x W200 x D20 mm"
                    class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
            </div>
        </div>

        <!-- BLOCKS THÔNG SỐ (JSON DẠNG LIST) -->
        <div class="bg-gray-50/80 rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4 border-b border-gray-200 pb-3">
                <div>
                    <label class="block text-sm font-bold text-gray-800">Danh sách Thông số / Mô tả</label>
                    <p class="text-xs text-gray-500 mt-0.5">Mỗi khối tương ứng với 1 gạch đầu dòng (bullet point) trên Website.</p>
                </div>
                <button type="button" onclick="addDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 hover:text-[#A31D1D] hover:border-[#A31D1D] transition-colors shadow-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Thêm dòng mới
                </button>
            </div>
            <div id="des-blocks-container" class="space-y-2.5"></div>
            @error('des.*') <p class="mt-2 text-xs text-red-600">Một trong các dòng mô tả bị lỗi, vui lòng kiểm tra lại độ dài.</p> @enderror
        </div>

        <!-- BLOCKS CHI TIẾT KÍCH THƯỚC (SIZE DES) -->
        <div class="bg-blue-50/30 rounded-xl border border-blue-100 p-5 mt-5">
            <div class="flex items-center justify-between mb-4 border-b border-blue-200 pb-3">
                <div>
                    <label class="block text-sm font-bold text-blue-800">Danh sách Kích thước chi tiết (Nếu có)</label>
                    <p class="text-xs text-gray-500 mt-0.5">Hiển thị thông số chi tiết bên cạnh ảnh Bản vẽ.</p>
                </div>
                <button type="button" onclick="addSizeDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-blue-50 hover:text-blue-700 hover:border-blue-400 transition-colors shadow-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Thêm dòng
                </button>
            </div>
            <div id="size-des-blocks-container" class="space-y-2.5"></div>
            @error('size_des.*') <p class="mt-2 text-xs text-red-600">Một trong các dòng kích thước chi tiết bị lỗi, vui lòng kiểm tra lại.</p> @enderror
        </div>
    </div>

    <!-- CỘT HÌNH ẢNH KÍCH THƯỚC -->
    <div class="lg:col-span-1">
        <label class="block text-sm font-semibold text-gray-700 mb-2">Ảnh bản vẽ / Kích thước</label>
        <div class="aspect-square w-full rounded-xl border-2 border-dashed bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors {{ $errors->has('size_image') ? 'border-red-500' : 'border-gray-300' }}">
            <img id="preview-size" src="{{ $isEdit && $product->size_image ? \App\Support\AssetPath::url($product->size_image) : 'https://placehold.co/400x400?text=Chon+Ban+Ve' }}" class="w-full h-full object-contain">
            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">{{ $isEdit ? 'Thay ảnh mới' : 'Tải ảnh lên' }}</span>
            </div>
            <input type="file" name="size_image" accept="image/*" onchange="previewImage(event, 'preview-size')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
        </div>
        @error('size_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

@include('admin.catalog.partials.product-journey-video-field')
</x-slot:info>

<x-slot:media>
@if($isEdit)
    @include('admin.catalog.partials.product-gallery-manager', [
        'mode' => 'edit',
        'images' => $product->images ?? [],
        'destroyUrl' => $destroyUrl ?? '',
        'uploadUrl' => $uploadUrl ?? '',
        'reorderUrl' => $reorderUrl ?? '',
    ])
@else
    @include('admin.catalog.partials.product-gallery-manager', [
        'mode' => 'create',
        'uploadField' => 'images[]',
        'videoField' => 'video_urls[]',
    ])
@endif
</x-slot:media>
</x-admin.catalog.shared.product-ct-tabs>

@push('scripts')
<script>
    const desContainer = document.getElementById('des-blocks-container');
    const sizeDesContainer = document.getElementById('size-des-blocks-container');

    function addDesBlock(value = '', autoFocus = false) {
        if (typeof window.createAdminInputBlock === 'function') {
            window.createAdminInputBlock(desContainer, 'des', value, autoFocus, 'VD: Trọng lượng: 1kg / viên');
        } else {
            const div = document.createElement('div');
            div.className = 'flex items-center bg-white rounded-lg border border-gray-200 shadow-sm group focus-within:border-[#A31D1D] focus-within:ring-1 focus-within:ring-[#A31D1D] transition-all overflow-hidden';
            div.innerHTML = `
                <div class="pl-3 pr-2 text-gray-300 cursor-move">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                </div>
                <input type="text" name="des[]" value="${value.replace(/"/g, '&quot;')}" placeholder="VD: Trọng lượng: 1kg / viên" class="flex-1 py-2.5 px-2 text-sm border-none focus:ring-0 outline-none text-gray-700 bg-transparent placeholder-gray-400">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity focus:opacity-100" title="Xóa dòng này">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            `;
            desContainer.appendChild(div);
            if (autoFocus && value === '') div.querySelector('input').focus();
        }
    }

    function addSizeDesBlock(value = '', autoFocus = false) {
        if (typeof window.createAdminInputBlock === 'function') {
            window.createAdminInputBlock(sizeDesContainer, 'size_des', value, autoFocus, 'VD: Chiều dài nóc: 300mm');
        } else {
            const div = document.createElement('div');
            div.className = 'flex items-center bg-white rounded-lg border border-blue-200 shadow-sm group focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition-all overflow-hidden';
            div.innerHTML = `
                <div class="pl-3 pr-2 text-blue-300 cursor-move">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                </div>
                <input type="text" name="size_des[]" value="${value.replace(/"/g, '&quot;')}" placeholder="VD: Chiều dài nóc: 300mm" class="flex-1 py-2.5 px-2 text-sm border-none focus:ring-0 outline-none text-gray-700 bg-transparent placeholder-gray-400">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity focus:opacity-100" title="Xóa dòng này">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            `;
            sizeDesContainer.appendChild(div);
            if (autoFocus && value === '') div.querySelector('input').focus();
        }
    }

    window.addDesBlock = addDesBlock;
    window.addSizeDesBlock = addSizeDesBlock;

    const existingDes = @json(old('des', $isEdit && is_array($product->des) ? $product->des : []));
    const existingSizeDes = @json(old('size_des', $isEdit && is_array($product->size_des) ? $product->size_des : []));

    if (existingDes && existingDes.length > 0) {
        existingDes.forEach(item => {
            let textValue = '';
            if (typeof item === 'string') textValue = item;
            else if (typeof item === 'object' && item !== null) {
                let name = item.name ? item.name.trim() : '';
                let val = item.value ? item.value.trim() : '';
                if (name && val) textValue = name + ': ' + val;
                else if (name) textValue = name;
                else if (val) textValue = val;
            }
            if (textValue.trim() !== '') addDesBlock(textValue, false);
        });
    } else {
        addDesBlock('', false);
    }

    if (existingSizeDes && existingSizeDes.length > 0) {
        existingSizeDes.forEach(item => {
            let textValue = typeof item === 'string' ? item : '';
            if (textValue.trim() !== '') addSizeDesBlock(textValue, false);
        });
    } else {
        addSizeDesBlock('', false);
    }
</script>
@endpush
