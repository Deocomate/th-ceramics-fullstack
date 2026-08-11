@section('preview_url', route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id))

<x-admin.layouts.app title="Cập nhật Linh Vật" breadcrumb="Admin › DS Sản phẩm chi tiết › Chỉnh sửa">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cập nhật Sản Phẩm: {{ $product->name }}</h2>
        </div>
        <form method="POST" action="{{ route('admin.linh-vat-phong-thuy-ct.update', $product->linh_vat_phong_thuy_ct_id) }}" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- CỘT THÔNG TIN CHUNG -->
                <div class="lg:col-span-2 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tên linh vật <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none">
                        </div>
                        <x-admin.shared.color-field :value="$product->color ?? 'Tự chọn'" />
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mã sản phẩm <span class="text-red-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code', $product->code) }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] bg-gray-50 outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Giá (VNĐ) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kích thước</label>
                            <input type="text" name="size" value="{{ old('size', $product->size) }}" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                        </div>
                    </div>

                    <!-- BLOCKS THÔNG SỐ -->
                    <div class="bg-gray-50/80 rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between mb-4 border-b border-gray-200 pb-3">
                            <label class="block text-sm font-bold text-gray-800">Thông số / Ý nghĩa</label>
                            <button type="button" onclick="addDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:text-[#A31D1D]">Thêm dòng</button>
                        </div>
                        <div id="des-blocks-container" class="space-y-2.5"></div>
                    </div>

                    <!-- BLOCKS SIZE DES -->
                    <div class="bg-blue-50/30 rounded-xl border border-blue-100 p-5">
                        <div class="flex items-center justify-between mb-4 border-b border-blue-200 pb-3">
                            <label class="block text-sm font-bold text-blue-800">Mô tả kích thước</label>
                            <button type="button" onclick="addSizeDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:text-blue-700">Thêm dòng</button>
                        </div>
                        <div id="size-des-blocks-container" class="space-y-2.5"></div>
                    </div>
                </div>

                <!-- CỘT HÌNH ẢNH KÍCH THƯỚC -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ảnh bản vẽ / Kích thước</label>
                    <div class="aspect-square w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                        <img id="preview-size" src="{{ $product->size_image ? asset('storage/' . $product->size_image) : 'https://placehold.co/400x400?text=Chon+Ban+Ve' }}" class="w-full h-full object-contain">
                        <input type="file" name="size_image" accept="image/*" onchange="previewImage(event, 'preview-size')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                </div>
            </div>

            <hr class="border-gray-100 my-8">
            @include('admin.partials.product-journey-video-field')
            @include('admin.partials.product-gallery-manager', [
                'mode' => 'edit',
                'section' => 'form',
                'uploadField' => 'new_images[]',
                'videoField' => 'new_video_urls[]',
            ])

            <div class="pt-6 mt-8 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.linh-vat-phong-thuy-ct.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Hủy bỏ</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm" style="background:#A31D1D;">Lưu Thay Đổi</button>
            </div>
        </form>
    </div>

    @include('admin.partials.product-gallery-manager', [
        'mode' => 'edit',
        'section' => 'library',
        'images' => $product->images ?? [],
        'destroyUrl' => route('admin.linh-vat-phong-thuy-ct.image.destroy', $product->linh_vat_phong_thuy_ct_id),
        'uploadUrl' => route('admin.linh-vat-phong-thuy-ct.image.store', $product->linh_vat_phong_thuy_ct_id),
        'reorderUrl' => route('admin.linh-vat-phong-thuy-ct.gallery.reorder', $product->linh_vat_phong_thuy_ct_id),
    ])
        </div>

    @push('scripts')
    <script>
        function previewImage(event, targetId) {
            const file = event.target.files[0];
            if (file) document.getElementById(targetId).src = URL.createObjectURL(file);
        }

        // Logic Des
        const desContainer = document.getElementById('des-blocks-container');
        const existingDes = @json(is_array($product->des) ? $product->des :[]);
        function addDesBlock(value = '', autoFocus = true) {
            const div = document.createElement('div');
            div.className = 'flex items-center bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-2';
            div.innerHTML = `<input type="text" name="des[]" value="${value.replace(/"/g, '&quot;')}" class="flex-1 py-2.5 px-3 text-sm outline-none">
                             <button type="button" onclick="this.parentElement.remove()" class="px-3 text-red-400">X</button>`;
            desContainer.appendChild(div);
            if(autoFocus && value === '') div.querySelector('input').focus();
        }
        if (existingDes && existingDes.length > 0) {
            existingDes.forEach(item => addDesBlock(item, false));
        } else addDesBlock('', false);

        // Logic Size Des
        const sizeDesContainer = document.getElementById('size-des-blocks-container');
        const existingSizeDes = @json(is_array($product->size_des) ? $product->size_des :[]);
        function addSizeDesBlock(value = '', autoFocus = true) {
            const div = document.createElement('div');
            div.className = 'flex items-center bg-white rounded-lg border border-blue-200 shadow-sm overflow-hidden mb-2';
            div.innerHTML = `<input type="text" name="size_des[]" value="${value.replace(/"/g, '&quot;')}" class="flex-1 py-2.5 px-3 text-sm outline-none">
                             <button type="button" onclick="this.parentElement.remove()" class="px-3 text-red-400">X</button>`;
            sizeDesContainer.appendChild(div);
            if(autoFocus && value === '') div.querySelector('input').focus();
        }
        if (existingSizeDes && existingSizeDes.length > 0) {
            existingSizeDes.forEach(item => addSizeDesBlock(item, false));
        } else addSizeDesBlock('', false);

        // Upload Preview (Same as others)

    </script>
    @endpush
</x-admin.layouts.app>
