@php
    $isEdit = isset($product) && $product;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">TÃªn dÃ¡ng ngÃ³i <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <x-admin.shared.color-field :value="old('color', $product->color ?? 'Tá»± chá»n')" />
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">KÃ­ch thÆ°á»›c</label>
                <input type="text" name="size" value="{{ old('size', $product->size ?? '') }}" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
            </div>
        </div>

        <div class="bg-gray-50/80 rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-4 border-b border-gray-200 pb-3">
                <div>
                    <label class="block text-sm font-bold text-gray-800">Danh sÃ¡ch ThÃ´ng sá»‘ / MÃ´ táº£</label>
                    <p class="text-xs text-gray-500 mt-0.5">Má»—i dÃ²ng tÆ°Æ¡ng á»©ng vá»›i 1 gáº¡ch Ä‘áº§u dÃ²ng trÃªn website.</p>
                </div>
                <button type="button" onclick="addDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 hover:text-[#A31D1D] hover:border-[#A31D1D] transition-colors shadow-sm">ThÃªm dÃ²ng má»›i</button>
            </div>
            <div id="des-blocks-container" class="space-y-2.5"></div>
        </div>

        <div class="bg-blue-50/30 rounded-xl border border-blue-100 p-5">
            <div class="flex items-center justify-between mb-4 border-b border-blue-200 pb-3">
                <div>
                    <label class="block text-sm font-bold text-blue-800">Danh sÃ¡ch KÃ­ch thÆ°á»›c chi tiáº¿t</label>
                    <p class="text-xs text-gray-500 mt-0.5">Hiá»ƒn thá»‹ thÃ´ng sá»‘ chi tiáº¿t bÃªn cáº¡nh áº£nh báº£n váº½.</p>
                </div>
                <button type="button" onclick="addSizeDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-blue-50 hover:text-blue-700 hover:border-blue-400 transition-colors shadow-sm">ThÃªm dÃ²ng</button>
            </div>
            <div id="size-des-blocks-container" class="space-y-2.5"></div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <label class="block text-sm font-semibold text-gray-700 mb-2">áº¢nh báº£n váº½ / KÃ­ch thÆ°á»›c</label>
        <div class="aspect-square w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
            <img id="preview-size" src="{{ $isEdit && $product->size_image ? \App\Support\AssetPath::url($product->size_image) : 'https://placehold.co/400x400?text=Chon+Ban+Ve' }}" class="w-full h-full object-contain" alt="">
            <input type="file" name="size_image" accept="image/*" onchange="previewImage(event, 'preview-size')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
        </div>
        @error('size_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<hr class="border-gray-100 my-8">
<div class="flex flex-col h-full border border-gray-200 rounded-xl p-6 bg-gray-50/50">
    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ $isEdit ? 'ThÃªm hÃ¬nh áº£nh má»›i' : 'HÃ¬nh áº£nh sáº£n pháº©m' }} @unless($isEdit)<span class="text-red-500">*</span>@endunless</label>
    <input type="file" id="multipleImagesInput" name="{{ $isEdit ? 'new_images[]' : 'images[]' }}" multiple {{ $isEdit ? '' : 'required' }} accept="image/*" class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white" onchange="handleMultipleFiles(event)">
    @error('images') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
    @error('new_images') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
    <div class="mt-4 h-[180px] bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner">
        <div id="multiple-preview-container" class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-8 gap-3">
            <div id="empty-preview-state" class="col-span-full min-h-[100px] flex flex-col items-center justify-center text-center text-gray-400 text-xs font-medium">ChÆ°a chá»n áº£nh nÃ o</div>
        </div>
    </div>
</div>

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

    let selectedFiles = [];
    const multipleImagesInput = document.getElementById('multipleImagesInput');
    const previewContainer = document.getElementById('multiple-preview-container');
    const emptyState = document.getElementById('empty-preview-state');

    function handleMultipleFiles(event) {
        const files = Array.from(event.target.files);
        if (files.length > 0) {
            selectedFiles = selectedFiles.concat(files);
            updateFileInput();
            renderPreviews();
        }
    }
    function renderPreviews() {
        previewContainer.querySelectorAll('.image-preview-item').forEach(item => item.remove());
        emptyState.style.display = selectedFiles.length === 0 ? 'flex' : 'none';
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'image-preview-item relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain"><button type="button" onclick="removeFile(${index})" class="absolute top-1 right-1 w-6 h-6 bg-red-600 text-white rounded-full text-xs">x</button>`;
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
    function removeFile(index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        renderPreviews();
    }
    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        multipleImagesInput.files = dataTransfer.files;
    }
</script>
@endpush
