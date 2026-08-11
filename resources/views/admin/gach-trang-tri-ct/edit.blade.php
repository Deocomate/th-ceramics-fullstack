@section('preview_url', route('client.products.gach-trang-tri.detail', $product->gach_trang_tri_ct_id))

<x-admin.layouts.app title="C?p nh?t G?ch Trang Trí" breadcrumb="Admin › DS S?n ph?m chi ti?t › Ch?nh s?a">
    
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">C?p nh?t S?n Ph?m: {{ $product->name }}</h2>
            <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-md text-xs font-bold">{{ $product->code }}</span>
        </div>
        
        <form method="POST" action="{{ route('admin.gach-trang-tri-ct.update', $product->gach_trang_tri_ct_id) }}" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- C?T THÔNG TIN CHUNG -->
                <div class="lg:col-span-2 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tên s?n ph?m <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <x-admin.shared.color-field :value="$product->color ?? 'T? ch?n'" />
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mã s?n ph?m <span class="text-red-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code', $product->code) }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all bg-gray-50">
                            @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Giá (VNÐ) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                            @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kích thu?c</label>
                            <input type="text" name="size" value="{{ old('size', $product->size) }}" placeholder="VD: 20x20x2 cm" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                        </div>
                    </div>

                    <!-- BLOCKS THÔNG S? (JSON D?NG LIST) -->
                    <div class="bg-gray-50/80 rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between mb-4 border-b border-gray-200 pb-3">
                            <div>
                                <label class="block text-sm font-bold text-gray-800">Danh sách Thông s? / Mô t?</label>
                                <p class="text-xs text-gray-500 mt-0.5">M?i kh?i tuong ?ng v?i 1 g?ch d?u dòng trên Website.</p>
                            </div>
                            <button type="button" onclick="addDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 hover:text-[#A31D1D] hover:border-[#A31D1D] transition-colors shadow-sm flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Thêm dòng m?i
                            </button>
                        </div>
                        
                        <div id="des-blocks-container" class="space-y-2.5">
                            <!-- JS s? render input vào dây -->
                        </div>
                    </div>
                </div>

                <!-- C?T HÌNH ?NH KÍCH THU?C -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">?nh b?n v? / Kích thu?c</label>
                    <div class="aspect-square w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
                        <img id="preview-size" src="{{ $product->size_image ? asset('storage/' . $product->size_image) : 'https://placehold.co/400x400?text=Chon+Ban+Ve' }}" class="w-full h-full object-contain">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay ?nh m?i</span>
                        </div>
                        <input type="file" name="size_image" accept="image/*" onchange="previewImage(event, 'preview-size')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('size_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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
                <a href="{{ route('admin.gach-trang-tri-ct.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">H?y b?</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                    Luu Thay Ð?i
                </button>
            </div>
        </form>
    </div>

    @include('admin.partials.product-gallery-manager', [
        'mode' => 'edit',
        'section' => 'library',
        'images' => $product->images ?? [],
        'destroyUrl' => route('admin.gach-trang-tri-ct.image.destroy', $product->gach_trang_tri_ct_id),
        'uploadUrl' => route('admin.gach-trang-tri-ct.image.store', $product->gach_trang_tri_ct_id),
        'reorderUrl' => route('admin.gach-trang-tri-ct.gallery.reorder', $product->gach_trang_tri_ct_id),
    ])

    @push('scripts')
    <script>
        function previewImage(event, targetId) {
            const file = event.target.files[0];
            if (file) document.getElementById(targetId).src = URL.createObjectURL(file);
        }

        // ===== LOGIC BLOCK EDITOR CHO THÔNG S? (EDIT) =====
        const desContainer = document.getElementById('des-blocks-container');
        
        // Data d?ng m?ng t? DB
        const existingDes = @json(is_array($product->des) ? $product->des :[]);

        function addDesBlock(value = '', autoFocus = true) {
            const div = document.createElement('div');
            div.className = 'flex items-center bg-white rounded-lg border border-gray-200 shadow-sm group focus-within:border-[#A31D1D] focus-within:ring-1 focus-within:ring-[#A31D1D] transition-all overflow-hidden';
            div.innerHTML = `
                <div class="pl-3 pr-2 text-gray-300 cursor-move">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                </div>
                <input type="text" name="des[]" value="${value.replace(/"/g, '&quot;')}" placeholder="VD: Tr?ng lu?ng: 1kg / viên" class="flex-1 py-2.5 px-2 text-sm border-none focus:ring-0 outline-none text-gray-700 bg-transparent placeholder-gray-400">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity focus:opacity-100" title="Xóa dòng này">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            `;
            desContainer.appendChild(div);
            
            if(autoFocus && value === '') {
                div.querySelector('input').focus();
            }
        }

        // Kh?i t?o các block cu t? Database (Có logic tuong thích ngu?c)
        if (existingDes && existingDes.length > 0) {
            existingDes.forEach(item => {
                let textValue = '';
                
                // N?u là string (Ð?nh d?ng m?i)
                if (typeof item === 'string') {
                    textValue = item;
                } 
                // N?u là object (D? li?u cu tru?c khi d?i c?u trúc)
                else if (typeof item === 'object' && item !== null) {
                    let name = item.name ? item.name.trim() : '';
                    let val = item.value ? item.value.trim() : '';
                    if (name && val) textValue = name + ': ' + val;
                    else if (name) textValue = name;
                    else if (val) textValue = val;
                }

                if (textValue.trim() !== '') {
                    addDesBlock(textValue, false);
                }
            });
        } else {
            // N?u s?n ph?m chua có mô t? nào, t?o s?n 1 block r?ng
            addDesBlock('', false);
        }

        // ===== LOGIC UPLOAD NHI?U ?NH =====

    </script>
    @endpush
</x-admin.layouts.app>
