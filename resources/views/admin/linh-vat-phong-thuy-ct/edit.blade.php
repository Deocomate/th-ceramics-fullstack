@section('preview_url', route('client.products.linh-vat-phong-thuy.detail', $product->linh_vat_phong_thuy_ct_id))

<x-admin.layouts.app title="Cáº­p nháº­t Linh Váº­t" breadcrumb="Admin â€º DS Sáº£n pháº©m chi tiáº¿t â€º Chá»‰nh sá»­a">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cáº­p nháº­t Sáº£n Pháº©m: {{ $product->name }}</h2>
        </div>
        <form method="POST" action="{{ route('admin.linh-vat-phong-thuy-ct.update', $product->linh_vat_phong_thuy_ct_id) }}" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cá»˜T THÃ”NG TIN CHUNG -->
                <div class="lg:col-span-2 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">TÃªn linh váº­t <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none">
                        </div>
                        <x-admin.shared.color-field :value="$product->color ?? 'Tá»± chá»n'" />
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">MÃ£ sáº£n pháº©m <span class="text-red-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code', $product->code) }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] bg-gray-50 outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">GiÃ¡ (VNÄ) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">KÃ­ch thÆ°á»›c</label>
                            <input type="text" name="size" value="{{ old('size', $product->size) }}" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] outline-none">
                        </div>
                    </div>

                    <!-- BLOCKS THÃ”NG Sá» -->
                    <div class="bg-gray-50/80 rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between mb-4 border-b border-gray-200 pb-3">
                            <label class="block text-sm font-bold text-gray-800">ThÃ´ng sá»‘ / Ã nghÄ©a</label>
                            <button type="button" onclick="addDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:text-[#A31D1D]">ThÃªm dÃ²ng</button>
                        </div>
                        <div id="des-blocks-container" class="space-y-2.5"></div>
                    </div>

                    <!-- BLOCKS SIZE DES -->
                    <div class="bg-blue-50/30 rounded-xl border border-blue-100 p-5">
                        <div class="flex items-center justify-between mb-4 border-b border-blue-200 pb-3">
                            <label class="block text-sm font-bold text-blue-800">MÃ´ táº£ kÃ­ch thÆ°á»›c</label>
                            <button type="button" onclick="addSizeDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:text-blue-700">ThÃªm dÃ²ng</button>
                        </div>
                        <div id="size-des-blocks-container" class="space-y-2.5"></div>
                    </div>
                </div>

                <!-- Cá»˜T HÃŒNH áº¢NH KÃCH THÆ¯á»šC -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">áº¢nh báº£n váº½ / KÃ­ch thÆ°á»›c</label>
                    <div class="aspect-square w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                        <img id="preview-size" src="{{ $product->size_image ? asset('storage/' . $product->size_image) : 'https://placehold.co/400x400?text=Chon+Ban+Ve' }}" class="w-full h-full object-contain">
                        <input type="file" name="size_image" accept="image/*" onchange="previewImage(event, 'preview-size')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- CHá»ŒN THÃŠM áº¢NH Má»šI -->
            <hr class="border-gray-100 my-8">
            <div class="flex flex-col h-full border border-gray-200 rounded-xl p-6 bg-gray-50/50">
                <label class="block text-sm font-semibold text-gray-700 mb-2">ThÃªm HÃ¬nh áº¢nh Má»›i</label>
                <div class="relative mb-4">
                    <input type="file" id="multipleImagesInput" name="new_images[]" multiple accept="image/*" class="w-full text-sm border border-gray-300 rounded-lg p-1.5 cursor-pointer bg-white" onchange="handleMultipleFiles(event)">
                </div>
                <div class="h-[180px] bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner flex flex-col">
                    <div id="multiple-preview-container" class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-8 gap-3">
                        <div id="empty-preview-state" class="col-span-full h-full min-h-[100px] flex items-center justify-center text-gray-400 text-xs">ChÆ°a chá»n thÃªm áº£nh nÃ o</div>
                    </div>
                </div>
            </div>
            <div class="pt-6 mt-8 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.linh-vat-phong-thuy-ct.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Há»§y bá»</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm" style="background:#A31D1D;">LÆ°u Thay Äá»•i</button>
            </div>
        </form>
    </div>

    <!-- DANH SÃCH áº¢NH HIá»†N Táº I (Y Há»‡t file edit khÃ¡c, copy sang) -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800">HÃ¬nh áº£nh sáº£n pháº©m hiá»‡n táº¡i</h2>
        </div>
        <div class="p-6">
            @if(is_array($product->images) && count($product->images) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($product->images as $path)
                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                            <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-contain">
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center">
                                <button type="button" onclick="openDeleteImageModal('{{ $path }}')" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700">XÃ³a áº£nh</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL XÃ“A áº¢NH --}}
    <div id="deleteImageModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center transform scale-95 transition-transform duration-300">
            <h3 class="text-xl font-bold text-gray-800 mb-2">XÃ¡c nháº­n xÃ³a?</h3>
            <div class="flex justify-center gap-3 mt-4">
                <button type="button" onclick="closeDeleteImageModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 rounded-lg">Há»§y</button>
                <form id="deleteImageForm" method="POST" action="{{ route('admin.linh-vat-phong-thuy-ct.image.destroy', $product->linh_vat_phong_thuy_ct_id) }}" class="flex-1">
                    @csrf @method('DELETE')
                    <input type="hidden" name="image_path" id="deleteImagePathInput" value="">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg">XÃ³a</button>
                </form>
            </div>
        </div>
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
        let selectedFiles =[];
        const multipleImagesInput = document.getElementById('multipleImagesInput');
        const previewContainer = document.getElementById('multiple-preview-container');
        const emptyState = document.getElementById('empty-preview-state');
        function handleMultipleFiles(event) {
            const files = Array.from(event.target.files);
            if (files.length > 0) {
                selectedFiles = selectedFiles.concat(files);
                updateFileInput(); renderPreviews();
            }
        }
        function renderPreviews() {
            previewContainer.querySelectorAll('.image-preview-item').forEach(i => i.remove());
            if (selectedFiles.length === 0) emptyState.style.display = 'flex';
            else {
                emptyState.style.display = 'none';
                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'image-preview-item relative aspect-square rounded-lg overflow-hidden bg-gray-100';
                        div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain">
                                         <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                            <button type="button" onclick="removeFile(${index})" class="text-white">X</button>
                                         </div>`;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }
        function removeFile(index) { selectedFiles.splice(index, 1); updateFileInput(); renderPreviews(); }
        function updateFileInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(f => dt.items.add(f));
            multipleImagesInput.files = dt.files;
        }

        // Modal Logic
        const deleteImageModal = document.getElementById('deleteImageModal');
        const deleteImageModalInner = deleteImageModal.querySelector('.bg-white');
        function openDeleteImageModal(imagePath) {
            document.getElementById('deleteImagePathInput').value = imagePath;
            deleteImageModal.classList.remove('hidden'); deleteImageModal.classList.add('flex');
            setTimeout(() => { deleteImageModal.classList.remove('opacity-0'); deleteImageModalInner.classList.remove('scale-95'); }, 10);
        }
        function closeDeleteImageModal() {
            deleteImageModal.classList.add('opacity-0'); deleteImageModalInner.classList.add('scale-95');
            setTimeout(() => { deleteImageModal.classList.add('hidden'); deleteImageModal.classList.remove('flex'); }, 300);
        }
    </script>
    @endpush
</x-admin.layouts.app>
