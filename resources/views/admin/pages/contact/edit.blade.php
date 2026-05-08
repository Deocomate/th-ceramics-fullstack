<x-admin.layout.app title="Trang Liên Hệ" breadcrumb="Admin › Cấu Hình Trang Đơn › Trang Liên Hệ">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cấu Hình Trang Liên Hệ</h2>
        </div>

        <form method="POST" action="{{ route('admin.pages.contact.update') }}" enctype="multipart/form-data"
            class="p-6 space-y-8">
            @csrf @method('PUT')

            @if ($errors->any())
                <div
                    class="flex items-start gap-3 px-4 py-3 rounded text-sm text-red-800 bg-red-50 border border-red-200 shadow-sm">
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

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                {{-- Left Column: Text & Contact Info --}}
                <div class="lg:col-span-3 space-y-6">
                    <div>
                        <label for="hotline" class="block text-sm font-semibold text-gray-700 mb-2">Hotline</label>
                        <input type="text" id="hotline" name="hotline"
                            value="{{ old('hotline', $contactPage->hotline) }}" placeholder="0966 55 8808"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg
                                   focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none transition-all">
                        @error('hotline')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="zalo_link" class="block text-sm font-semibold text-gray-700 mb-2">Link Zalo
                            (URL)</label>
                        <input type="url" id="zalo_link" name="zalo_link"
                            value="{{ old('zalo_link', $contactPage->zalo_link) }}" placeholder="https://zalo.me/..."
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg
                                   focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none transition-all">
                        @error('zalo_link')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="form_title" class="block text-sm font-semibold text-gray-700 mb-2">Tiêu đề form liên
                            hệ</label>
                        <input type="text" id="form_title" name="form_title"
                            value="{{ old('form_title', $contactPage->form_title) }}"
                            placeholder="Hãy nói với chúng tôi những mong muốn của bạn"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg
                                   focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none transition-all">
                        @error('form_title')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Right Column: Media --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Map Image --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh bản đồ</label>
                        <div
                            class="aspect-video w-full rounded-xl border-2 border-dashed border-gray-300 bg-gray-50
                                    flex items-center justify-center overflow-hidden relative group
                                    hover:bg-gray-100 transition-colors">
                            @php
                                // Hỗ trợ hiển thị ảnh mặc định từ seeder (assets/...) hoặc ảnh upload thực tế từ storage
                                $mapUrl = $contactPage->map_image
                                    ? (str_starts_with($contactPage->map_image, 'assets/')
                                        ? asset($contactPage->map_image)
                                        : asset('storage/' . $contactPage->map_image))
                                    : 'https://placehold.co/800x450?text=Chua+co+anh+ban+do';
                            @endphp
                            <img id="preview-map_image" src="{{ $mapUrl }}" class="w-full h-full object-cover">

                            <div
                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity
                                        flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi
                                    ảnh</span>
                            </div>
                            <input type="file" name="map_image" accept="image/*"
                                onchange="previewImage(event, 'preview-map_image')"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                        @error('map_image')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end border-t border-gray-100">
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-bold text-white bg-brand-red hover:bg-brand-red-dark
                           rounded-lg shadow-sm transition-all active:scale-95">
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function previewImage(event, targetId) {
                const file = event.target.files[0];
                if (file) {
                    const objectUrl = URL.createObjectURL(file);
                    document.getElementById(targetId).src = objectUrl;
                    document.getElementById(targetId).onload = function() {
                        URL.revokeObjectURL(objectUrl);
                    };
                }
            }
        </script>
    @endpush
</x-admin.layout.app>
