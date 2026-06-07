<x-admin.layouts.app title="Trang FAQ" breadcrumb="Admin › Cấu Hình Trang Đơn › Trang FAQ">
    {{-- ========== BANNER FORM ========== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Banner Trang FAQ</h2>
        </div>

        <form method="POST" action="{{ route('admin.pages.faq.update') }}" enctype="multipart/form-data"
            class="p-6 space-y-6">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Ảnh banner</label>
                <div
                    class="aspect-video w-full max-w-2xl rounded-xl border-2 border-dashed border-gray-300 bg-white flex items-center justify-center overflow-hidden relative group hover:bg-gray-50 transition-colors">
                    @php
                        // Hỗ trợ hiển thị ảnh mặc định từ seeder (assets/...) hoặc ảnh upload thực tế từ storage
                        $bannerUrl = $faqPage->banner_image
                            ? (str_starts_with($faqPage->banner_image, 'assets/')
                                ? asset($faqPage->banner_image)
                                : asset('storage/' . $faqPage->banner_image))
                            : 'https://placehold.co/800x450?text=Chua+co+banner';
                    @endphp
                    <img id="preview-banner" src="{{ $bannerUrl }}" class="w-full h-full object-cover">

                    <div
                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Thay đổi
                            ảnh</span>
                    </div>
                    <input type="file" name="banner_image" accept="image/*"
                        onchange="previewImage(event, 'preview-banner')"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                </div>
                @error('banner_image')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 flex justify-end border-t border-gray-100">
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-bold text-white bg-brand-red hover:bg-brand-red-dark
                           rounded-lg shadow-sm transition-all active:scale-95">
                    Cập nhật banner
                </button>
            </div>
        </form>
    </div>

    {{-- ========== FAQ ITEMS CRUD ========== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-data="faqManager">

        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between flex-wrap gap-3">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Danh sách câu hỏi thường gặp</h2>
            <div class="flex items-center gap-3">
                <select x-model="selectedCategory"
                    class="text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none">
                    <option value="">Tất cả danh mục</option>
                    @foreach (\App\Models\Faq::CATEGORIES as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <button type="button" @click="openCreateModal()"
                    class="px-4 py-2.5 text-sm font-bold text-white bg-brand-red hover:bg-brand-red-dark
                           rounded-lg shadow-sm transition-all active:scale-95">
                    + Thêm câu hỏi mới
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr
                        class="sticky top-0 bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider
                               border-b border-gray-200">
                        <th class="px-5 py-3">Danh mục</th>
                        <th class="px-5 py-3">Câu hỏi</th>
                        <th class="px-5 py-3 text-center">Thứ tự</th>
                        <th class="px-5 py-3 text-center">Trạng thái</th>
                        <th class="px-5 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-gray-50/70 transition-colors duration-150 faq-row"
                            x-show="!selectedCategory || selectedCategory === '{{ $faq->category }}'">
                            <td class="px-5 py-3">
                                <span
                                    class="inline-block px-2.5 py-1 rounded-full text-xs font-medium border
                                    {{ $faq->category === 'sản-phẩm' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                    {{ $faq->category === 'báo-giá' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}
                                    {{ $faq->category === 'vận-chuyển' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                    {{ $faq->category === 'lắp-đặt' ? 'bg-purple-50 text-purple-700 border-purple-200' : '' }}
                                    {{ $faq->category === 'đổi-trả' ? 'bg-rose-50 text-rose-700 border-rose-200' : '' }}
                                    {{ !in_array($faq->category, ['sản-phẩm', 'báo-giá', 'vận-chuyển', 'lắp-đặt', 'đổi-trả']) ? 'bg-gray-100 text-gray-700 border-gray-200' : '' }}">
                                    {{ \App\Models\Faq::CATEGORIES[$faq->category] ?? $faq->category }}
                                </span>
                            </td>
                            <td class="px-5 py-3 max-w-[300px]">
                                <span class="block truncate"
                                    title="{{ $faq->question }}">{{ Str::limit($faq->question, 60) }}</span>
                            </td>
                            <td class="px-5 py-3 text-center text-gray-500">{{ $faq->sort_order }}</td>
                            <td class="px-5 py-3 text-center">
                                @if ($faq->is_active)
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Đang hiện
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-500 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Không hiện
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                        @click='openEditModal({{ Js::from(['faq_id' => $faq->faq_id, 'category' => $faq->category, 'question' => $faq->question, 'answer' => $faq->answer, 'sort_order' => $faq->sort_order, 'is_active' => $faq->is_active]) }})'
                                        class="px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors">
                                        Sửa
                                    </button>
                                    <form method="POST" action="{{ route('admin.pages.faqs.destroy', $faq->faq_id) }}"
                                        onsubmit="return confirm('Xóa câu hỏi FAQ này?');">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1.5 text-xs font-medium rounded-lg bg-red-50 text-red-700 hover:bg-red-100 transition-colors">
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                                Chưa có câu hỏi FAQ nào. Nhấn "Thêm câu hỏi mới" để bắt đầu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal --}}
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center" x-cloak
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            {{-- Blur backdrop --}}
            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="closeModal()"></div>

            {{-- Modal container --}}
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl mx-4 max-h-[85vh] overflow-y-auto"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                {{-- Modal header --}}
                <div
                    class="sticky top-0 bg-white z-10 px-6 py-4 border-b border-gray-100 flex items-center justify-between rounded-t-2xl">
                    <h3 class="text-lg font-bold text-gray-800" x-text="editingId ? 'Sửa câu hỏi' : 'Thêm câu hỏi mới'">
                    </h3>
                    <button type="button" @click="closeModal()"
                        class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400
                               hover:text-gray-600 hover:bg-gray-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal body (form) --}}
                <form method="POST" :action="modalUrl" class="p-6 space-y-5">
                    @csrf
                    <template x-if="editingId">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Danh mục <span class="text-red-500">*</span>
                        </label>
                        <select name="category" x-model="editingCategory" required
                            class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg
                                   focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none transition-all bg-white">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach (\App\Models\Faq::CATEGORIES as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Question --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Câu hỏi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="question" x-model="editingQuestion" required maxlength="1000"
                            class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg
                                   focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none transition-all">
                    </div>

                    {{-- Answer with Auto-Resize --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Câu trả lời <span class="text-red-500">*</span>
                        </label>
                        <textarea name="answer" x-model="editingAnswer" required x-data x-init="$el.style.height = $el.scrollHeight + 'px'"
                            @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                            class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg
                                   focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none
                                   transition-all resize-none overflow-hidden min-h-[100px] leading-relaxed"></textarea>
                        <p class="text-xs text-gray-400 mt-1">Hỗ trợ HTML: &lt;b&gt;in đậm&lt;/b&gt;, &lt;br&gt; xuống
                            dòng</p>
                    </div>

                    {{-- Sort Order + Active Toggle (2-col grid) --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Thứ tự hiển thị</label>
                            <input type="number" name="sort_order" x-model="editingSortOrder" min="0"
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg
                                       focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none transition-all">
                        </div>
                        <div class="flex items-end pb-1">
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" x-model="editingIsActive"
                                    class="w-4 h-4 rounded border-gray-300 text-brand-red focus:ring-brand-red">
                                <span class="text-sm font-medium text-gray-700">Hiển thị</span>
                            </label>
                        </div>
                    </div>

                    {{-- Modal footer --}}
                    <div class="flex justify-end gap-3 pt-5 border-t border-gray-100">
                        <button type="button" @click="closeModal()"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200
                                   rounded-lg transition-colors">
                            Hủy
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 text-sm font-bold text-white bg-brand-red hover:bg-brand-red-dark
                                   rounded-lg shadow-sm transition-all active:scale-95">
                            Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            // Tách riêng script của AlpineJS ra khỏi view (Tránh lỗi Quotes conflicts với thẻ HTML)
            document.addEventListener('alpine:init', () => {
                Alpine.data('faqManager', () => ({
                    showModal: false,
                    editingId: null,
                    modalUrl: '{{ route('admin.pages.faqs.store') }}',
                    selectedCategory: '',
                    editingCategory: '',
                    editingQuestion: '',
                    editingAnswer: '',
                    editingSortOrder: 0,
                    editingIsActive: true,

                    openCreateModal() {
                        this.editingId = null;
                        this.modalUrl = '{{ route('admin.pages.faqs.store') }}';
                        this.editingCategory = '';
                        this.editingQuestion = '';
                        this.editingAnswer = '';
                        this.editingSortOrder = 0;
                        this.editingIsActive = true;
                        this.showModal = true;
                    },

                    openEditModal(faq) {
                        this.editingId = faq.faq_id;
                        this.modalUrl = '{{ route('admin.pages.faqs.update', ['faq' => '__ID__']) }}'
                            .replace('__ID__', faq.faq_id);
                        this.editingCategory = faq.category;
                        this.editingQuestion = faq.question;
                        this.editingAnswer = faq.answer;
                        this.editingSortOrder = faq.sort_order;
                        this.editingIsActive = faq.is_active == 1 || faq.is_active === true;
                        this.showModal = true;
                    },

                    closeModal() {
                        this.showModal = false;
                        this.editingId = null;
                    }
                }));
            });

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
</x-admin.layouts.app>
