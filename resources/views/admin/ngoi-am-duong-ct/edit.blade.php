@section('preview_url', route('client.products.ngoi-am-duong.detail', $product->ngoi_am_duong_ct_id))

<x-admin.layouts.app title="Cáº­p nháº­t NgÃ³i Ã‚m DÆ°Æ¡ng" breadcrumb="Admin â€º DS Sáº£n pháº©m chi tiáº¿t â€º Chá»‰nh sá»­a">
    
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cáº­p nháº­t Sáº£n Pháº©m: {{ $product->name }}</h2>
            <span class="px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-md text-xs font-bold">{{ $product->code }}</span>
        </div>
        
        <form method="POST" action="{{ route('admin.ngoi-am-duong-ct.update', $product->ngoi_am_duong_ct_id) }}" enctype="multipart/form-data" class="p-6">
            @csrf @method('PUT')
            
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
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                                class="w-full px-4 py-2.5 text-sm border rounded-lg focus:ring-1 outline-none transition-all {{ $errors->has('name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50/50' : 'border-gray-300 focus:border-[#A31D1D] focus:ring-[#A31D1D]' }}">
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <x-admin.shared.color-field :value="$product->color ?? 'Tá»± chá»n'" />
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">MÃ£ sáº£n pháº©m <span class="text-red-500">*</span></label>
                            <input type="text" name="code" value="{{ old('code', $product->code) }}" required 
                                class="w-full px-4 py-2.5 text-sm border rounded-lg focus:ring-1 outline-none transition-all bg-gray-50 font-mono {{ $errors->has('code') ? 'border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50/50' : 'border-gray-300 focus:border-[#A31D1D] focus:ring-[#A31D1D]' }}">
                            @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">GiÃ¡ (VNÄ) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" 
                                class="w-full px-4 py-2.5 text-sm border rounded-lg focus:ring-1 outline-none transition-all {{ $errors->has('price') ? 'border-red-500 focus:border-red-500 focus:ring-red-500 bg-red-50/50' : 'border-gray-300 focus:border-[#A31D1D] focus:ring-[#A31D1D]' }}">
                            @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">KÃ­ch thÆ°á»›c</label>
                            <input type="text" name="size" value="{{ old('size', $product->size) }}" placeholder="VD: 20x20x2 cm" 
                                class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                        </div>
                    </div>

                    <!-- BLOCKS THÃ”NG Sá» -->
                    <div class="bg-gray-50/80 rounded-xl border border-gray-200 p-5">
                        <div class="flex items-center justify-between mb-4 border-b border-gray-200 pb-3">
                            <div>
                                <label class="block text-sm font-bold text-gray-800">Danh sÃ¡ch ThÃ´ng sá»‘ / MÃ´ táº£</label>
                                <p class="text-xs text-gray-500 mt-0.5">Má»—i khá»‘i tÆ°Æ¡ng á»©ng vá»›i 1 gáº¡ch Ä‘áº§u dÃ²ng trÃªn Website.</p>
                            </div>
                            <button type="button" onclick="addDesBlock()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-50 hover:text-[#A31D1D] hover:border-[#A31D1D] transition-colors shadow-sm flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                ThÃªm dÃ²ng má»›i
                            </button>
                        </div>
                        
                        <div id="des-blocks-container" class="space-y-2.5">
                            <!-- JS sáº½ render input vÃ o Ä‘Ã¢y -->
                        </div>
                        @error('des.*') <p class="mt-2 text-xs text-red-600">Má»™t trong cÃ¡c dÃ²ng mÃ´ táº£ bá»‹ lá»—i, vui lÃ²ng kiá»ƒm tra láº¡i Ä‘á»™ dÃ i.</p> @enderror
                    </div>
                </div>

                <!-- Cá»˜T HÃŒNH áº¢NH KÃCH THÆ¯á»šC -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">áº¢nh báº£n váº½ / KÃ­ch thÆ°á»›c</label>
                    <div class="aspect-square w-full rounded-xl border-2 border-dashed bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors {{ $errors->has('size_image') ? 'border-red-500' : 'border-gray-300' }}">
                        <img id="preview-size" src="{{ $product->size_image ? asset('storage/' . $product->size_image) : 'https://placehold.co/400x400?text=Chon+Ban+Ve' }}" class="w-full h-full object-contain">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay áº£nh má»›i</span>
                        </div>
                        <input type="file" name="size_image" accept="image/*" onchange="previewImage(event, 'preview-size')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('size_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- CHá»ŒN THÃŠM áº¢NH Má»šI (MULTIPLE) -->
            <hr class="border-gray-100 my-8">
            <div class="flex flex-col h-full border rounded-xl p-6 bg-gray-50/50 {{ $errors->has('new_images.*') ? 'border-red-300 bg-red-50/30' : 'border-gray-200' }}">
                <label class="block text-sm font-semibold text-gray-700 mb-2">ThÃªm HÃ¬nh áº¢nh Má»›i</label>
                <div class="relative mb-4">
                    <input type="file" id="multipleImagesInput" name="new_images[]" multiple accept="image/*" 
                        class="w-full text-sm border rounded-lg p-1.5 cursor-pointer bg-white {{ $errors->has('new_images.*') ? 'border-red-500' : 'border-gray-300' }}" onchange="handleMultipleFiles(event)">
                </div>
                @error('new_images.*') <p class="mb-4 text-xs text-red-600 font-bold">{{ $message }}</p> @enderror

                <div class="h-[180px] bg-white border border-gray-200 rounded-xl p-4 overflow-y-auto shadow-inner flex flex-col">
                    <div id="multiple-preview-container" class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-8 gap-3">
                        <div id="empty-preview-state" class="col-span-full h-full min-h-[100px] flex flex-col items-center justify-center text-center text-gray-400 text-xs font-medium gap-2">
                            <span>ChÆ°a chá»n thÃªm áº£nh nÃ o</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 mt-8 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.ngoi-am-duong-ct.index') }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Há»§y bá»</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                    LÆ°u Thay Äá»•i
                </button>
            </div>
        </form>
    </div>

    <!-- DANH SÃCH áº¢NH HIá»†N Táº I Äá»‚ XÃ“A -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">HÃ¬nh áº£nh sáº£n pháº©m hiá»‡n táº¡i</h2>
            @if(is_array($product->images))
                <span class="text-xs font-medium text-gray-500">Äang cÃ³ {{ count($product->images) }} áº£nh</span>
            @endif
        </div>
        <div class="p-6">
            @if(is_array($product->images) && count($product->images) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($product->images as $path)
                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100">
                            <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-contain">
                            
                            <!-- NhÃ£n "áº¢nh BÃ¬a" cho áº£nh Ä‘áº§u tiÃªn -->
                            @if($loop->first)
                                <div class="absolute top-2 left-2 bg-[#A31D1D] text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                                    áº¢nh bÃ¬a
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                <button type="button" onclick="openDeleteImageModal('{{ $path }}')" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                    XÃ³a áº£nh nÃ y
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm text-center py-6">Sáº£n pháº©m nÃ y chÆ°a cÃ³ hÃ¬nh áº£nh chi tiáº¿t nÃ o.</p>
            @endif
        </div>
    </div>

    {{-- MODAL XÃ“A áº¢NH (DÃ¹ng chung cho áº£nh thÆ° viá»‡n) --}}
    <div id="deleteImageModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">XÃ¡c nháº­n xÃ³a?</h3>
            <p class="text-sm text-gray-500 mb-6">áº¢nh nÃ y sáº½ bá»‹ xÃ³a khá»i danh sÃ¡ch áº£nh cá»§a sáº£n pháº©m.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteImageModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Há»§y</button>
                <form id="deleteImageForm" method="POST" action="{{ route('admin.ngoi-am-duong-ct.image.destroy', $product->ngoi_am_duong_ct_id) }}" class="flex-1">
                    @csrf @method('DELETE')
                    <input type="hidden" name="image_path" id="deleteImagePathInput" value="">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">CÃ³, XÃ³a</button>
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

        const desContainer = document.getElementById('des-blocks-container');

        // [QUAN TRá»ŒNG] Láº¥y dá»¯ liá»‡u old() ná»u cÃ³, náº¿u khÃ´ng láº¥y tá»« DB. 
        // ÄÃ¢y lÃ  bÆ°á»›c giÃºp khÃ´ng bá»‹ máº¥t dá»¯ liá»‡u JS do validation lá»—i!
        const existingDes = @json(old('des', is_array($product->des) ? $product->des :[]));

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

        // Render dá»¯ liá»‡u
        if (existingDes && existingDes.length > 0) {
            existingDes.forEach(item => {
                let textValue = '';
                
                // Náº¿u lÃ  string (Äá»‹nh dáº¡ng má»›i)
                if (typeof item === 'string') {
                    textValue = item;
                } 
                // Náº¿u lÃ  object (Dá»¯ liá»‡u cÅ© trÆ°á»›c khi Ä‘á»•i cáº¥u trÃºc)
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
            // Náº¿u sáº£n pháº©m chÆ°a cÃ³ mÃ´ táº£ nÃ o, táº¡o sáºµn 1 block rá»—ng
            addDesBlock('', false);
        }

        // ===== LOGIC UPLOAD NHIá»€U áº¢NH =====
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
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
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

        // ===== LOGIC MODAL XÃ“A áº¢NH =====
        const deleteImageModal = document.getElementById('deleteImageModal');
        const deleteImageModalInner = deleteImageModal.querySelector('.bg-white');

        function openDeleteImageModal(imagePath) {
            document.getElementById('deleteImagePathInput').value = imagePath;
            deleteImageModal.classList.remove('hidden');
            deleteImageModal.classList.add('flex');
            void deleteImageModal.offsetWidth;
            deleteImageModal.classList.remove('opacity-0');
            deleteImageModalInner.classList.remove('scale-95');
        }

        function closeDeleteImageModal() {
            deleteImageModal.classList.add('opacity-0');
            deleteImageModalInner.classList.add('scale-95');
            setTimeout(() => {
                deleteImageModal.classList.add('hidden');
                deleteImageModal.classList.remove('flex');
            }, 300);
        }
    </script>
    @endpush
</x-admin.layouts.app>
