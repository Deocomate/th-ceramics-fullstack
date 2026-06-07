<x-admin.layouts.app title="Trang Xưởng Sản Xuất" breadcrumb="Admin › Cấu Hình Trang Đơn › Xưởng Sản Xuất">

    @php
        $g1 = is_string($factory->gallery_1) ? json_decode($factory->gallery_1, true) : $factory->gallery_1;
        $g2 = is_string($factory->gallery_2) ? json_decode($factory->gallery_2, true) : $factory->gallery_2;
        $ps = is_string($factory->process_slider)
            ? json_decode($factory->process_slider, true)
            : $factory->process_slider;
        $ms = is_string($factory->material_slider)
            ? json_decode($factory->material_slider, true)
            : $factory->material_slider;
        $mst = is_string($factory->material_steps)
            ? json_decode($factory->material_steps, true)
            : $factory->material_steps;

        $factoryJson = [
            'gallery_1' => is_array($g1) ? $g1 : [],
            'gallery_2' => is_array($g2) ? $g2 : [],
            'process_slider' => is_array($ps) ? $ps : [],
            'material_slider' => is_array($ms) ? $ms : [],
            'material_steps' => is_array($mst) ? $mst : [],
            'intro_description' => old('intro_description', $factory->intro_description),
            'process_description' => old('process_description', $factory->process_description),
            'process_bottom_desc' => old('process_bottom_desc', $factory->process_bottom_desc),
        ];

        // Helper xử lý URL cho các ảnh đơn (xử lý chung logic seeder 'assets/...' và upload 'storage/...')
        $heroDesktopUrl = $factory->hero_banner_desktop
            ? (str_starts_with($factory->hero_banner_desktop, 'assets/')
                ? asset($factory->hero_banner_desktop)
                : asset('storage/' . $factory->hero_banner_desktop))
            : '';
        $heroMobileUrl = $factory->hero_banner_mobile
            ? (str_starts_with($factory->hero_banner_mobile, 'assets/')
                ? asset($factory->hero_banner_mobile)
                : asset('storage/' . $factory->hero_banner_mobile))
            : '';
        $processBottomUrl = $factory->process_bottom_image
            ? (str_starts_with($factory->process_bottom_image, 'assets/')
                ? asset($factory->process_bottom_image)
                : asset('storage/' . $factory->process_bottom_image))
            : '';
    @endphp

    <div x-data="factoryPage">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu Hình Trang Xưởng Sản Xuất</h2>
            </div>

            <form method="POST" action="{{ route('admin.pages.factory.update') }}" enctype="multipart/form-data"
                class="p-6">
                @csrf @method('PUT')

                @if ($errors->any())
                    <div
                        class="flex items-start gap-3 px-4 py-3 mb-6 rounded text-sm text-red-800 bg-red-50 border border-red-200 shadow-sm">
                        <svg class="w-5 h-5 flex-shrink-0 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <strong class="font-semibold block mb-1">Vui lòng kiểm tra lại:</strong>
                            <ul class="list-disc ml-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Tab Navigation --}}
                <nav class="flex flex-wrap gap-1 border-b border-gray-200 mb-6">
                    <template x-for="tab in tabs" :key="tab">
                        <button type="button" @click="activeTab = tab"
                            :class="activeTab === tab ?
                                'text-brand-red border-b-2 border-brand-red font-semibold' :
                                'text-gray-500 hover:text-gray-700 border-b-2 border-transparent'"
                            class="px-5 py-3 text-sm transition-colors duration-200" x-text="tabLabels[tab]">
                        </button>
                    </template>
                </nav>

                {{-- ==================== TAB 1: HERO SECTION ==================== --}}
                <div x-cloak x-show="activeTab === 'hero'" class="space-y-8">
                    <h3 class="text-lg font-bold text-gray-800">Hero Banner</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Hero Banner Desktop --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Banner Desktop</label>
                            <div
                                class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
                                <img id="preview-hero-banner-desktop" src="{{ $heroDesktopUrl }}"
                                    onerror="this.src='https://placehold.co/1200x600?text=Chua+co+anh'"
                                    class="w-full h-full object-cover"
                                    style="{{ $heroDesktopUrl ? '' : 'display:none;' }}">
                                <div id="placeholder-hero-banner-desktop"
                                    class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 text-sm gap-2"
                                    style="{{ $heroDesktopUrl ? 'display:none;' : '' }}">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Nhấp để tải lên ảnh</span>
                                </div>
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay
                                        đổi ảnh</span>
                                </div>
                                <input type="file" name="hero_banner_desktop" accept="image/*"
                                    onchange="previewSingleImage(event, 'preview-hero-banner-desktop', 'placeholder-hero-banner-desktop')"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                            @error('hero_banner_desktop')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Hero Banner Mobile --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Banner Mobile</label>
                            <div
                                class="aspect-[9/16] max-w-[280px] w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors mx-auto">
                                <img id="preview-hero-banner-mobile" src="{{ $heroMobileUrl }}"
                                    onerror="this.src='https://placehold.co/600x900?text=Chua+co+anh'"
                                    class="w-full h-full object-cover"
                                    style="{{ $heroMobileUrl ? '' : 'display:none;' }}">
                                <div id="placeholder-hero-banner-mobile"
                                    class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 text-sm gap-2"
                                    style="{{ $heroMobileUrl ? 'display:none;' : '' }}">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Nhấp để tải lên ảnh</span>
                                </div>
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay
                                        đổi ảnh</span>
                                </div>
                                <input type="file" name="hero_banner_mobile" accept="image/*"
                                    onchange="previewSingleImage(event, 'preview-hero-banner-mobile', 'placeholder-hero-banner-mobile')"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                            @error('hero_banner_mobile')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ==================== TAB 2: INTRO SECTION ==================== --}}
                <div x-cloak x-show="activeTab === 'intro'" class="space-y-8">
                    <h3 class="text-lg font-bold text-gray-800">Phần Giới Thiệu</h3>

                    <div>
                        <label for="intro_title" class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề</label>
                        <input type="text" id="intro_title" name="intro_title"
                            value="{{ old('intro_title', $factory->intro_title) }}"
                            placeholder="Nhập tiêu đề giới thiệu..."
                            class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-brand-red focus:ring-1 focus:ring-brand-red">
                        @error('intro_title')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="intro_subtitle" class="block text-sm font-semibold text-gray-700 mb-2">Phụ
                            đề</label>
                        <input type="text" id="intro_subtitle" name="intro_subtitle"
                            value="{{ old('intro_subtitle', $factory->intro_subtitle) }}"
                            placeholder="Nhập phụ đề giới thiệu..."
                            class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-brand-red focus:ring-1 focus:ring-brand-red">
                        @error('intro_subtitle')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @include('admin.pages.factory.partials.block-builder', [
                        'field' => 'intro_description',
                        'label' => 'Mô tả',
                        'blocks' => $factoryJson['intro_description'],
                    ])
                </div>

                {{-- ==================== TAB 3: GALLERIES ==================== --}}
                <div x-cloak x-show="activeTab === 'gallery'" class="space-y-10">
                    <h3 class="text-lg font-bold text-gray-800">Thư Viện Ảnh</h3>

                    @include('admin.pages.factory.partials.gallery-manager', [
                        'field' => 'gallery_1',
                        'label' => 'Gallery 1',
                        'images' => $factoryJson['gallery_1'],
                    ])

                    <hr class="border-gray-100">

                    @include('admin.pages.factory.partials.gallery-manager', [
                        'field' => 'gallery_2',
                        'label' => 'Gallery 2',
                        'images' => $factoryJson['gallery_2'],
                    ])
                </div>

                {{-- ==================== TAB 4: PROCESS SECTION ==================== --}}
                <div x-cloak x-show="activeTab === 'process'" class="space-y-8" x-data="galleryManager({{ Js::from($factoryJson['process_slider']) }}, 'process_slider')">
                    <h3 class="text-lg font-bold text-gray-800">Quy Trình Sản Xuất</h3>

                    <div>
                        <label for="process_title" class="block text-sm font-semibold text-gray-700 mb-2">Tiêu
                            đề</label>
                        <textarea id="process_title" name="process_title" rows="2"
                            placeholder="Nhập tiêu đề quy trình..."
                            class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-brand-red focus:ring-1 focus:ring-brand-red resize-y">{{ old('process_title', $factory->process_title) }}</textarea>
                        @error('process_title')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @include('admin.pages.factory.partials.block-builder', [
                        'field' => 'process_description',
                        'label' => 'Mô tả',
                        'blocks' => $factoryJson['process_description'],
                    ])

                    <hr class="border-gray-100">

                    {{-- Process Slider Gallery --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Slider Quy Trình</label>
                        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-4">
                            <template x-for="(img, index) in images" :key="img.original_index">
                                <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-100"
                                    x-transition>
                                    <img :src="getImageUrl(img.url)" class="w-full h-full object-cover">
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-200
                                            flex items-center justify-center">
                                        <button type="button" @click="deleteImage(index)"
                                            class="opacity-0 group-hover:opacity-100 transition-all duration-200
                                               w-9 h-9 bg-red-500 hover:bg-red-600 text-white rounded-full
                                               flex items-center justify-center shadow-lg hover:scale-110
                                               transform transition-transform"
                                            title="Xóa ảnh">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
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
                            Chưa có ảnh nào trong slider quy trình.
                        </p>

                        <template x-for="idx in deletedIndices" :key="'del-ps-' + idx">
                            <input type="hidden" :name="'delete_' + fieldName + '[]'" :value="idx">
                        </template>

                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Thêm ảnh mới</label>
                            <input type="file" name="new_process_slider[]" multiple accept="image/*"
                                class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white">
                            @error('new_process_slider.*')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Process Bottom Section --}}
                    <h4 class="text-md font-bold text-gray-700">Phần Cuối Quy Trình</h4>

                    <div>
                        <label for="process_bottom_title" class="block text-sm font-semibold text-gray-700 mb-2">Tiêu
                            đề ảnh cuối</label>
                        <textarea id="process_bottom_title" name="process_bottom_title" rows="2"
                            placeholder="Nhập tiêu đề..."
                            class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-brand-red focus:ring-1 focus:ring-brand-red resize-y">{{ old('process_bottom_title', $factory->process_bottom_title) }}</textarea>
                        @error('process_bottom_title')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @include('admin.pages.factory.partials.block-builder', [
                        'field' => 'process_bottom_desc',
                        'label' => 'Mô tả ảnh cuối',
                        'blocks' => $factoryJson['process_bottom_desc'],
                    ])

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh cuối quy trình</label>
                        <div
                            class="aspect-video w-full max-w-lg rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
                            <img id="preview-process-bottom-image" src="{{ $processBottomUrl }}"
                                onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'"
                                class="w-full h-full object-cover"
                                style="{{ $processBottomUrl ? '' : 'display:none;' }}">
                            <div id="placeholder-process-bottom-image"
                                class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 text-sm gap-2"
                                style="{{ $processBottomUrl ? 'display:none;' : '' }}">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Nhấp để tải lên ảnh</span>
                            </div>
                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay
                                    đổi ảnh</span>
                            </div>
                            <input type="file" name="process_bottom_image" accept="image/*"
                                onchange="previewSingleImage(event, 'preview-process-bottom-image', 'placeholder-process-bottom-image')"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('process_bottom_image')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- ==================== TAB 5: MATERIAL SECTION ==================== --}}
                <div x-cloak x-show="activeTab === 'material'" class="space-y-8" x-data="materialManager({{ Js::from($factoryJson['material_slider']) }}, {{ Js::from($factoryJson['material_steps']) }})">
                    <h3 class="text-lg font-bold text-gray-800">Nguyên Liệu</h3>

                    {{-- Material Slider Gallery --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Slider Nguyên Liệu</label>
                        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-4">
                            <template x-for="(img, index) in images" :key="img.original_index">
                                <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-100"
                                    x-transition>
                                    <img :src="getImageUrl(img.url)" class="w-full h-full object-cover">
                                    <div
                                        class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-200
                                            flex items-center justify-center">
                                        <button type="button" @click="deleteImage(index)"
                                            class="opacity-0 group-hover:opacity-100 transition-all duration-200
                                               w-9 h-9 bg-red-500 hover:bg-red-600 text-white rounded-full
                                               flex items-center justify-center shadow-lg hover:scale-110
                                               transform transition-transform"
                                            title="Xóa ảnh">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
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
                            Chưa có ảnh nào trong slider nguyên liệu.
                        </p>

                        <template x-for="idx in deletedIndices" :key="'del-ms-' + idx">
                            <input type="hidden" name="delete_material_slider[]" :value="idx">
                        </template>

                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Thêm ảnh mới</label>
                            <input type="file" name="new_material_slider[]" multiple accept="image/*"
                                class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white">
                            @error('new_material_slider.*')
                                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Material Steps Repeater --}}
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <label class="text-sm font-semibold text-gray-700">Các Bước Nguyên Liệu</label>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(step, index) in steps" :key="index">
                                <div class="border border-gray-200 rounded-xl p-5 bg-gray-50/80 hover:bg-gray-50 transition-colors shadow-sm"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 transform scale-100"
                                    x-transition:leave-end="opacity-0 transform scale-95">

                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white bg-brand-red"
                                                x-text="index + 1"></span>
                                            <span class="text-sm font-semibold text-gray-700">Bước <span
                                                    x-text="step.number"></span></span>
                                        </div>
                                        <button type="button" @click="removeStep(index)"
                                            class="flex items-center gap-1 text-sm font-medium text-red-500 hover:text-red-700 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Xóa
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Số
                                                thứ tự</label>
                                            <input type="text" :name="'material_steps[' + index + '][number]'"
                                                x-model="step.number" placeholder="VD: 01"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg
                                                   focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none transition-all">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Tiêu
                                                đề</label>
                                            <input type="text" :name="'material_steps[' + index + '][title]'"
                                                x-model="step.title" placeholder="Nhập tiêu đề bước..."
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg
                                                   focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none transition-all">
                                        </div>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Mô
                                            tả</label>
                                        <textarea :name="'material_steps[' + index + '][description]'" x-model="step.description"
                                            placeholder="Nhập mô tả bước..." rows="3"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg
                                               focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none transition-all resize-none"></textarea>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <p x-show="steps.length === 0"
                            class="text-sm text-gray-400 text-center py-6 rounded-lg border border-dashed border-gray-300 mt-4">
                            Chưa có bước nguyên liệu nào. Nhấn "Thêm bước" để bắt đầu.
                        </p>

                        <button type="button" @click="addStep()"
                            class="w-full mt-4 py-3 text-sm font-semibold text-brand-red border-2 border-dashed border-brand-red/30
                               rounded-xl hover:bg-red-50 hover:border-brand-red/50 transition-all
                               flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Thêm bước
                        </button>

                        @error('material_steps.*.title')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-6 flex justify-end border-t border-gray-100 mt-6">
                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-bold text-white bg-brand-red hover:bg-brand-red-dark
                           rounded-lg shadow-sm transition-all active:scale-95">
                        Lưu cấu hình
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                const makeUid = () => {
                    if (window.crypto && typeof window.crypto.randomUUID === 'function') {
                        return window.crypto.randomUUID();
                    }

                    return `${Date.now()}-${Math.random().toString(16).slice(2)}`;
                };

                // Main Tab Manager
                Alpine.data('factoryPage', () => ({
                    activeTab: 'hero',
                    tabs: ['hero', 'intro', 'gallery', 'process', 'material'],
                    tabLabels: {
                        'hero': 'Hero Section',
                        'intro': 'Giới thiệu',
                        'gallery': 'Thư viện ảnh',
                        'process': 'Quy trình',
                        'material': 'Nguyên liệu'
                    }
                }));

                Alpine.data('blockManager', (initialBlocks, fieldName) => ({
                    blocks: Array.isArray(initialBlocks) ? initialBlocks.map((block) => ({
                        uid: makeUid(),
                        type: block.type === 'list' ? 'list' : 'paragraph',
                        content: block.content || '',
                        items: Array.isArray(block.items) ? block.items.map((item) => ({
                            uid: makeUid(),
                            title: item.title || '',
                            content: item.content || ''
                        })) : []
                    })) : [],
                    name: fieldName,
                    addParagraph() {
                        this.blocks.push({
                            uid: makeUid(),
                            type: 'paragraph',
                            content: '',
                            items: []
                        });
                    },
                    addList() {
                        this.blocks.push({
                            uid: makeUid(),
                            type: 'list',
                            content: '',
                            items: [{
                                uid: makeUid(),
                                title: '',
                                content: ''
                            }]
                        });
                    },
                    addListItem(blockIndex) {
                        this.blocks[blockIndex].items.push({
                            uid: makeUid(),
                            title: '',
                            content: ''
                        });
                    },
                    removeBlock(index) {
                        this.blocks.splice(index, 1);
                    },
                    removeListItem(blockIndex, itemIndex) {
                        this.blocks[blockIndex].items.splice(itemIndex, 1);
                    },
                    moveUp(index) {
                        if (index <= 0) return;

                        const current = this.blocks[index];
                        this.blocks[index] = this.blocks[index - 1];
                        this.blocks[index - 1] = current;
                    },
                    moveDown(index) {
                        if (index >= this.blocks.length - 1) return;

                        const current = this.blocks[index];
                        this.blocks[index] = this.blocks[index + 1];
                        this.blocks[index + 1] = current;
                    }
                }));

                // Gallery Manager (For Gallery 1, Gallery 2 & Process Slider)
                Alpine.data('galleryManager', (initialImages, fieldName) => ({
                    images: Array.isArray(initialImages) ? initialImages.map((url, index) => ({
                        url,
                        original_index: index
                    })) : [],
                    deletedIndices: [],
                    fieldName: fieldName,
                    getImageUrl(img) {
                        return img && img.startsWith('assets/') ? '/' + img : '/storage/' + img;
                    },
                    deleteImage(index) {
                        this.deletedIndices.push(this.images[index].original_index);
                        this.images.splice(index, 1);
                    }
                }));

                // Material Section Manager
                Alpine.data('materialManager', (initialImages, initialSteps) => ({
                    images: Array.isArray(initialImages) ? initialImages.map((url, index) => ({
                        url,
                        original_index: index
                    })) : [],
                    deletedIndices: [],
                    steps: Array.isArray(initialSteps) ? initialSteps : [],
                    getImageUrl(img) {
                        return img && img.startsWith('assets/') ? '/' + img : '/storage/' + img;
                    },
                    deleteImage(index) {
                        this.deletedIndices.push(this.images[index].original_index);
                        this.images.splice(index, 1);
                    },
                    addStep() {
                        this.steps.push({
                            number: String(this.steps.length + 1),
                            title: '',
                            description: ''
                        });
                        this.$nextTick(() => {
                            window.scrollTo({
                                top: document.body.scrollHeight,
                                behavior: 'smooth'
                            })
                        });
                    },
                    removeStep(index) {
                        this.steps.splice(index, 1);
                    }
                }));
            });

            function previewSingleImage(event, imgId, placeholderId) {
                const file = event.target.files[0];
                const img = document.getElementById(imgId);
                const placeholder = document.getElementById(placeholderId);

                if (file) {
                    const objectUrl = URL.createObjectURL(file);
                    img.src = objectUrl;
                    img.style.display = '';
                    placeholder.style.display = 'none';
                    img.onload = function() {
                        URL.revokeObjectURL(objectUrl);
                    };
                } else {
                    img.style.display = 'none';
                    placeholder.style.display = '';
                }
            }
        </script>
    @endpush

</x-admin.layouts.app>
