<x-admin.layout.app title="Trang Xưởng Sản Xuất" breadcrumb="Admin › Cấu Hình Trang Đơn › Xưởng Sản Xuất">

@php
    $factoryJson = [
        'gallery_1' => $factory->gallery_1 ?? [],
        'process_slider' => $factory->process_slider ?? [],
        'material_slider' => $factory->material_slider ?? [],
        'material_steps' => $factory->material_steps ?? [],
    ];
@endphp

<div x-data="{
    activeTab: 'hero',
    tabs: ['hero', 'intro', 'gallery', 'process', 'material'],
    tabLabels: {
        'hero': 'Hero Section',
        'intro': 'Giới thiệu',
        'gallery': 'Thư viện ảnh',
        'process': 'Quy trình',
        'material': 'Nguyên liệu'
    }
}">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu Hình Trang Xưởng Sản Xuất</h2>
        </div>

        <form method="POST" action="{{ route('admin.pages.factory.update') }}" enctype="multipart/form-data" class="p-6" x-on:submit="tinymce.triggerSave()">
            @csrf @method('PUT')

            {{-- Tab Navigation --}}
            <div class="flex flex-wrap gap-1 mb-6 border-b border-gray-200 pb-0">
                <template x-for="tab in tabs" :key="tab">
                    <button type="button"
                        @click="activeTab = tab"
                        :class="activeTab === tab
                            ? 'border-b-2 text-gray-900 font-semibold bg-gray-50'
                            : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="px-5 py-3 text-sm rounded-t-lg transition-colors border-transparent"
                        style="border-color: transparent;"
                        x-bind:style="activeTab === tab ? 'border-bottom-color: #A31D1D; color: #A31D1D;' : ''"
                        x-text="tabLabels[tab]">
                    </button>
                </template>
            </div>

            {{-- ==================== TAB 1: HERO SECTION ==================== --}}
            <div x-show="activeTab === 'hero'" class="space-y-8">
                <h3 class="text-lg font-bold text-gray-800">Hero Banner</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Hero Banner Desktop --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Banner Desktop</label>
                        <div class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
                            <img id="preview-hero-banner-desktop"
                                src="{{ $factory->hero_banner_desktop ? asset('storage/' . $factory->hero_banner_desktop) : '' }}"
                                onerror="this.src='https://placehold.co/1200x600?text=Chua+co+anh'"
                                class="w-full h-full object-cover"
                                style="{{ $factory->hero_banner_desktop ? '' : 'display:none;' }}">
                            <div id="placeholder-hero-banner-desktop"
                                class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 text-sm gap-2"
                                style="{{ $factory->hero_banner_desktop ? 'display:none;' : '' }}">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Nhấp để tải lên ảnh</span>
                            </div>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                            </div>
                            <input type="file" name="hero_banner_desktop" accept="image/*"
                                onchange="previewSingleImage(event, 'preview-hero-banner-desktop', 'placeholder-hero-banner-desktop')"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('hero_banner_desktop') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Hero Banner Mobile --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Banner Mobile</label>
                        <div class="aspect-[9/16] max-w-[280px] w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors mx-auto">
                            <img id="preview-hero-banner-mobile"
                                src="{{ $factory->hero_banner_mobile ? asset('storage/' . $factory->hero_banner_mobile) : '' }}"
                                onerror="this.src='https://placehold.co/600x900?text=Chua+co+anh'"
                                class="w-full h-full object-cover"
                                style="{{ $factory->hero_banner_mobile ? '' : 'display:none;' }}">
                            <div id="placeholder-hero-banner-mobile"
                                class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 text-sm gap-2"
                                style="{{ $factory->hero_banner_mobile ? 'display:none;' : '' }}">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Nhấp để tải lên ảnh</span>
                            </div>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                            </div>
                            <input type="file" name="hero_banner_mobile" accept="image/*"
                                onchange="previewSingleImage(event, 'preview-hero-banner-mobile', 'placeholder-hero-banner-mobile')"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('hero_banner_mobile') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- ==================== TAB 2: INTRO SECTION ==================== --}}
            <div x-show="activeTab === 'intro'" class="space-y-8">
                <h3 class="text-lg font-bold text-gray-800">Phần Giới Thiệu</h3>

                <div>
                    <label for="intro_title" class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề</label>
                    <input type="text" id="intro_title" name="intro_title"
                        value="{{ old('intro_title', $factory->intro_title) }}"
                        placeholder="Nhập tiêu đề giới thiệu..."
                        class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                    @error('intro_title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="intro_subtitle" class="block text-sm font-semibold text-gray-700 mb-2">Phụ đề</label>
                    <input type="text" id="intro_subtitle" name="intro_subtitle"
                        value="{{ old('intro_subtitle', $factory->intro_subtitle) }}"
                        placeholder="Nhập phụ đề giới thiệu..."
                        class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                    @error('intro_subtitle') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="intro_description" class="block text-sm font-semibold text-gray-700 mb-2">Mô tả (Hỗ trợ HTML)</label>
                    <textarea id="intro_description" name="intro_description" rows="6"
                        placeholder="Nhập mô tả giới thiệu..."
                        class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">{{ old('intro_description', $factory->intro_description) }}</textarea>
                    @error('intro_description') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ==================== TAB 3: GALLERY 1 ==================== --}}
            <div x-show="activeTab === 'gallery'" class="space-y-8" x-data="{ images: {{ Js::from($factory->gallery_1 ?? []) }}, deletedIndices: [] }">
                <h3 class="text-lg font-bold text-gray-800">Thư Viện Ảnh</h3>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh hiện tại</label>
                    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-4">
                        <template x-for="(img, index) in images" :key="index">
                            <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                <img :src="'/storage/' + img" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button"
                                        @click="deletedIndices.push(index); images.splice(index, 1)"
                                        class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm text-sm">
                                        &times;
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <p x-show="images.length === 0" class="text-sm text-gray-400 text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        Chưa có ảnh nào trong thư viện.
                    </p>

                    <template x-for="idx in deletedIndices" :key="'del-g1-' + idx">
                        <input type="hidden" name="delete_gallery_1[]" :value="idx">
                    </template>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Thêm ảnh mới</label>
                    <input type="file" name="new_gallery_1[]" multiple accept="image/*"
                        class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white">
                    <p class="text-xs text-gray-400 mt-2">Có thể chọn nhiều ảnh cùng lúc.</p>
                    @error('new_gallery_1.*') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ==================== TAB 4: PROCESS SECTION ==================== --}}
            <div x-show="activeTab === 'process'" class="space-y-8"
                x-data="{ images: {{ Js::from($factory->process_slider ?? []) }}, deletedIndices: [] }">
                <h3 class="text-lg font-bold text-gray-800">Quy Trình Sản Xuất</h3>

                <div>
                    <label for="process_title" class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề</label>
                    <input type="text" id="process_title" name="process_title"
                        value="{{ old('process_title', $factory->process_title) }}"
                        placeholder="Nhập tiêu đề quy trình..."
                        class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                    @error('process_title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="process_description" class="block text-sm font-semibold text-gray-700 mb-2">Mô tả (Trình soạn thảo)</label>
                    <textarea id="process_description" name="process_description" class="wysiwyg" rows="8">{!! old('process_description', $factory->process_description) !!}</textarea>
                    @error('process_description') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <hr class="border-gray-100">

                {{-- Process Slider Gallery --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Slider Quy Trình</label>
                    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-4">
                        <template x-for="(img, index) in images" :key="index">
                            <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                <img :src="'/storage/' + img" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button"
                                        @click="deletedIndices.push(index); images.splice(index, 1)"
                                        class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm text-sm">
                                        &times;
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <p x-show="images.length === 0" class="text-sm text-gray-400 text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        Chưa có ảnh nào trong slider quy trình.
                    </p>

                    <template x-for="idx in deletedIndices" :key="'del-ps-' + idx">
                        <input type="hidden" name="delete_process_slider[]" :value="idx">
                    </template>

                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Thêm ảnh mới</label>
                        <input type="file" name="new_process_slider[]" multiple accept="image/*"
                            class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white">
                        @error('new_process_slider.*') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Process Bottom Section --}}
                <h4 class="text-md font-bold text-gray-700">Phần Cuối Quy Trình</h4>

                <div>
                    <label for="process_bottom_title" class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề ảnh cuối</label>
                    <input type="text" id="process_bottom_title" name="process_bottom_title"
                        value="{{ old('process_bottom_title', $factory->process_bottom_title) }}"
                        placeholder="Nhập tiêu đề..."
                        class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition-all border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                    @error('process_bottom_title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="process_bottom_desc" class="block text-sm font-semibold text-gray-700 mb-2">Mô tả ảnh cuối (Trình soạn thảo)</label>
                    <textarea id="process_bottom_desc" name="process_bottom_desc" class="wysiwyg" rows="4">{!! old('process_bottom_desc', $factory->process_bottom_desc) !!}</textarea>
                    @error('process_bottom_desc') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh cuối quy trình</label>
                    <div class="aspect-video w-full max-w-lg rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden relative group hover:bg-gray-100 transition-colors">
                        <img id="preview-process-bottom-image"
                            src="{{ $factory->process_bottom_image ? asset('storage/' . $factory->process_bottom_image) : '' }}"
                            onerror="this.src='https://placehold.co/600x400?text=Chua+co+anh'"
                            class="w-full h-full object-cover"
                            style="{{ $factory->process_bottom_image ? '' : 'display:none;' }}">
                        <div id="placeholder-process-bottom-image"
                            class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 text-sm gap-2"
                            style="{{ $factory->process_bottom_image ? 'display:none;' : '' }}">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Nhấp để tải lên ảnh</span>
                        </div>
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi ảnh</span>
                        </div>
                        <input type="file" name="process_bottom_image" accept="image/*"
                            onchange="previewSingleImage(event, 'preview-process-bottom-image', 'placeholder-process-bottom-image')"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('process_bottom_image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ==================== TAB 5: MATERIAL SECTION ==================== --}}
            <div x-show="activeTab === 'material'" class="space-y-8"
                x-data="{ images: {{ Js::from($factory->material_slider ?? []) }}, deletedIndices: [], steps: {{ Js::from($factory->material_steps ?? []) }} }">
                <h3 class="text-lg font-bold text-gray-800">Nguyên Liệu</h3>

                {{-- Material Slider Gallery --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Slider Nguyên Liệu</label>
                    <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-4">
                        <template x-for="(img, index) in images" :key="index">
                            <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                <img :src="'/storage/' + img" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <button type="button"
                                        @click="deletedIndices.push(index); images.splice(index, 1)"
                                        class="w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm text-sm">
                                        &times;
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                    <p x-show="images.length === 0" class="text-sm text-gray-400 text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        Chưa có ảnh nào trong slider nguyên liệu.
                    </p>

                    <template x-for="idx in deletedIndices" :key="'del-ms-' + idx">
                        <input type="hidden" name="delete_material_slider[]" :value="idx">
                    </template>

                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Thêm ảnh mới</label>
                        <input type="file" name="new_material_slider[]" multiple accept="image/*"
                            class="w-full text-sm border border-gray-300 rounded-lg p-1.5 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer bg-white">
                        @error('new_material_slider.*') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <hr class="border-gray-100">

                {{-- Material Steps Repeater --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <label class="text-sm font-semibold text-gray-700">Các Bước Nguyên Liệu</label>
                        <button type="button"
                            @click="steps.push({number: String(steps.length + 1), title: '', description: ''})"
                            class="bg-green-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-green-700 transition-colors font-medium">
                            + Thêm bước
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(step, index) in steps" :key="index">
                            <div class="border border-gray-200 rounded-xl p-5 bg-gray-50/50 relative">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold text-white"
                                            style="background:#A31D1D;"
                                            x-text="index + 1"></span>
                                        <span class="text-sm font-semibold text-gray-700">Bước</span>
                                    </div>
                                    <button type="button" @click="steps.splice(index, 1)"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                                        &times; Xóa
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Số thứ tự</label>
                                        <input type="text" :name="'material_steps[' + index + '][number]'"
                                            x-model="step.number"
                                            placeholder="VD: 01"
                                            class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tiêu đề</label>
                                        <input type="text" :name="'material_steps[' + index + '][title]'"
                                            x-model="step.title"
                                            placeholder="Nhập tiêu đề bước..."
                                            class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Mô tả</label>
                                    <textarea :name="'material_steps[' + index + '][description]'"
                                        x-model="step.description"
                                        placeholder="Nhập mô tả bước..."
                                        rows="3"
                                        class="w-full px-3 py-2 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all"></textarea>
                                </div>
                            </div>
                        </template>
                    </div>

                    <p x-show="steps.length === 0" class="text-sm text-gray-400 text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-300 mt-4">
                        Chưa có bước nguyên liệu nào. Nhấn "Thêm bước" để bắt đầu.
                    </p>

                    @error('material_steps.*.title') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-6 flex justify-end border-t border-gray-100 mt-6">
                <button type="submit"
                    class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors"
                    style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'"
                    onmouseout="this.style.background='#A31D1D'">
                    Lưu cấu hình
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '.wysiwyg',
        height: 300,
        menubar: false,
        plugins: 'lists link',
        toolbar: 'bold italic | bullist numlist | link',
        valid_elements: '*[*]',
        extended_valid_elements: 'p[class],span[class],ul[class],li[class],strong[class]',
    });

    document.querySelector('form').addEventListener('submit', function() {
        tinymce.triggerSave();
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

</x-admin.layout.app>
