<x-admin.layouts.app title="ThÃªm Gáº¡ch Hoa ThÃ´ng GiÃ³" breadcrumb="Admin â€º DS Sáº£n pháº©m chi tiáº¿t â€º ThÃªm má»›i">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">ThÃ´ng tin sáº£n pháº©m má»›i</h2>
        </div>
        <form method="POST" action="{{ route('admin.gach-hoa-thong-gio-ct.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf

            <!-- HIá»‚N THá»Š Lá»–I CHUNG -->
            @if ($errors->any())
                <div class="mb-6 flex items-start gap-3 px-4 py-3 rounded text-sm text-red-800 bg-red-50 border border-red-200 shadow-sm">
                    <div>
                        <strong class="font-semibold block mb-1">Vui lÃ²ng kiá»ƒm tra láº¡i cÃ¡c thÃ´ng tin sau:</strong>
                        <ul class="list-disc ml-4">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cá»˜T THÃ”NG TIN CHUNG -->
                <div class="lg:col-span-2 space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">TÃªn sáº£n pháº©m <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2.5 text-sm border rounded-lg focus:ring-1 outline-none transition-all {{ $errors->has('name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50/50' : 'border-gray-300 focus:border-[#A31D1D] focus:ring-[#A31D1D]' }}">
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <x-admin.shared.color-field />
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">MÃ£ sáº£n pháº©m (Code) <span class="text-red-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code') }}" required placeholder="VD: GHTG-001"
                                class="w-full px-4 py-2.5 text-sm border rounded-lg focus:ring-1 outline-none transition-all font-mono {{ $errors->has('code') ? 'border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50/50' : 'border-gray-300 focus:border-[#A31D1D] focus:ring-[#A31D1D]' }}">
                            @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">GiÃ¡ (VNÄ) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price') }}" required min="0"
                                class="w-full px-4 py-2.5 text-sm border rounded-lg focus:ring-1 outline-none transition-all {{ $errors->has('price') ? 'border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50/50' : 'border-gray-300 focus:border-[#A31D1D] focus:ring-[#A31D1D]' }}">
                            @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">KÃ­ch thÆ°á»›c (Text)</label>
                            <input type="text" name="size" value="{{ old('size') }}" placeholder="VD: L200 x W200 x D20 mm"
                                class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                        </div>
                    </div>

                    <!-- BLOCKS THÃ”NG Sá» (JSON Dáº NG LIST) -->
                    <div class="bg-gray-50/80 rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between mb-4 border-b border-gray-200 pb-3">
                            <div>
                                <label class="block text-sm font-bold text-gray-800">Danh sÃ¡ch ThÃ´ng sá»‘ / MÃ´ táº£</label>
                            </div>
                            <button type="button" onclick="addDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:text-[#A31D1D] transition-colors shadow-sm flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> ThÃªm dÃ²ng má»›i
                            </button>
                        </div>
                        <div id="des-blocks-container" class="space-y-2.5"></div>
                    </div>
                </div>

                <!-- Cá»˜T HÃŒNH áº¢NH KÃCH THÆ¯á»šC -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">áº¢nh báº£n váº½ / KÃ­ch thÆ°á»›c</label>
                    <div class="aspect-square w-full rounded-xl border-2 border-dashed bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors {{ $errors->has('size_image') ? 'border-red-500' : 'border-gray-300' }}">
                        <img id="preview-size" src="https://placehold.co/400x400?text=Chon+Ban+Ve" class="w-full h-full object-contain">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Táº£i áº£nh lÃªn</span>
                        </div>
                        <input type="file" name="size_image" accept="image/*" onchange="previewImage(event, 'preview-size')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('size_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- CHá»ŒN áº¢NH Sáº¢N PHáº¨M CHUNG -->
            <hr class="border-gray-100 my-8">
            <div class="flex flex-col h-full border rounded-xl p-6 bg-gray-50/50 {{ $errors->has('images') ? 'border-red-300 bg-red-50/30' : 'border-gray-200' }}">
                <label class="block text-sm font-semibold text-gray-700 mb-2">HÃ¬nh áº£nh sáº£n pháº©m <span class="text-red-500">*</span></label>
                <div class="relative mb-4">
                    <input type="file" id="multipleImagesInput" name="images[]" multiple required accept="image/*"
                        class="w-full text-sm border rounded-lg p-1.5 cursor-pointer bg-white {{ $errors->has('images') ? 'border-red-500' : 'border-gray-300' }}" onchange="handleMultipleFiles(event)">
                </div>
                @error('images') <p class="mb-4 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror
                
                <div class="h-[250px] bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner flex flex-col">
                    <div id="multiple-preview-container" class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-8 gap-3">
                        <div id="empty-preview-state" class="col-span-full h-full min-h-[180px] flex flex-col items-center justify-center text-center text-gray-400 text-xs font-medium gap-2">
                            <span>ChÆ°a cÃ³ áº£nh nÃ o</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 mt-8 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.gach-hoa-thong-gio-ct.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Há»§y</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;">LÆ°u Sáº£n Pháº©m</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function previewImage(event, targetId) {
                const file = event.target.files[0];
                if (file) document.getElementById(targetId).src = URL.createObjectURL(file);
            }

            const desContainer = document.getElementById('des-blocks-container');

            function addDesBlock(value = '', autoFocus = false) {
                const div = document.createElement('div');
                div.className = 'flex items-center bg-white rounded-lg border border-gray-200 shadow-sm group focus-within:border-[#A31D1D] focus-within:ring-1 focus-within:ring-[#A31D1D] transition-all overflow-hidden';
                div.innerHTML = `
                    <div class="pl-3 pr-2 text-gray-300 cursor-move">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                    </div>
                    <input type="text" name="des[]" value="${value.replace(/"/g, '&quot;')}" placeholder="VD: Trá»ng lÆ°á»£ng: 1kg / viÃªn" class="flex-1 py-2.5 px-2 text-sm border-none focus:ring-0 outline-none text-gray-700 bg-transparent placeholder-gray-400">
                    <button type="button" onclick="this.parentElement.remove()" class="px-3 text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity focus:opacity-100" title="XÃ³a dÃ²ng nÃ y">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                `;
                desContainer.appendChild(div);
                
                if(autoFocus && value === '') {
                    div.querySelector('input').focus();
                }
            }

            const existingDes = @json(old('des'));

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

            let selectedFiles =[];
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
                const existingPreviews = previewContainer.querySelectorAll('.image-preview-item');
                existingPreviews.forEach(item => item.remove());

                if (selectedFiles.length === 0) {
                    emptyState.style.display = 'flex';
                } else {
                    emptyState.style.display = 'none';
                    selectedFiles.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'image-preview-item relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100';
                            div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-contain">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <button type="button" onclick="removeFile(${index})" class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        `;
                            previewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                }
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
</x-admin.layouts.app>
