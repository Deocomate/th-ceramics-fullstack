@php
    $blocks = is_array($blocks ?? null) ? $blocks : [];
@endphp

<div x-data="blockManager({{ Js::from($blocks) }}, {{ Js::from($field) }})" class="space-y-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <label class="block text-sm font-semibold text-gray-700">{{ $label }}</label>
            <p class="text-xs text-gray-400 mt-1">{{ $hint ?? 'Tách nội dung thành đoạn văn hoặc danh sách, không cần nhập HTML.' }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" @click="addParagraph()"
                class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold text-brand-red border border-brand-red/30 rounded-lg hover:bg-red-50 transition-colors">
                <span class="text-base leading-none">+</span>
                Thêm đoạn văn
            </button>
            <button type="button" @click="addList()"
                class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                <span class="text-base leading-none">+</span>
                Thêm danh sách
            </button>
        </div>
    </div>

    <div class="space-y-4">
        <template x-for="(block, index) in blocks" :key="block.uid">
            <div class="border border-gray-200 rounded-xl bg-gray-50/80 p-4 shadow-sm">
                <input type="hidden" :name="`${name}[${index}][type]`" x-model="block.type">

                <div class="flex items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-2">
                        <span
                            class="w-7 h-7 rounded-full bg-brand-red text-white text-xs font-bold flex items-center justify-center"
                            x-text="index + 1"></span>
                        <span class="text-sm font-semibold text-gray-700"
                            x-text="block.type === 'list' ? 'Danh sách' : 'Đoạn văn'"></span>
                    </div>

                    <div class="flex items-center gap-1">
                        <button type="button" @click="moveUp(index)" :disabled="index === 0"
                            class="w-8 h-8 rounded-lg border border-gray-200 bg-white text-gray-600 hover:text-brand-red disabled:opacity-40 disabled:hover:text-gray-600 transition-colors"
                            title="Chuyển lên">
                            ↑
                        </button>
                        <button type="button" @click="moveDown(index)" :disabled="index === blocks.length - 1"
                            class="w-8 h-8 rounded-lg border border-gray-200 bg-white text-gray-600 hover:text-brand-red disabled:opacity-40 disabled:hover:text-gray-600 transition-colors"
                            title="Chuyển xuống">
                            ↓
                        </button>
                        <button type="button" @click="removeBlock(index)"
                            class="w-8 h-8 rounded-lg border border-red-100 bg-white text-red-500 hover:bg-red-50 transition-colors"
                            title="Xóa block">
                            ×
                        </button>
                    </div>
                </div>

                <div x-show="block.type === 'paragraph'">
                    <textarea :name="`${name}[${index}][content]`" x-model="block.content" rows="4"
                        placeholder="Nhập nội dung đoạn văn..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none transition-all resize-y"></textarea>
                    <p class="text-xs text-gray-400 mt-1">Có thể nhấn Enter để xuống dòng trong đoạn văn.</p>
                </div>

                <div x-show="block.type === 'list'" class="space-y-3">
                    <template x-for="(item, itemIndex) in block.items" :key="item.uid">
                        <div class="grid grid-cols-1 md:grid-cols-[minmax(0,260px)_1fr_auto] gap-3 items-start">
                            <input type="text" :name="`${name}[${index}][items][${itemIndex}][title]`"
                                x-model="item.title" placeholder="Tiêu đề in đậm"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none transition-all">
                            <textarea :name="`${name}[${index}][items][${itemIndex}][content]`" x-model="item.content"
                                rows="2" placeholder="Nội dung mục..."
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-brand-red focus:ring-1 focus:ring-brand-red outline-none transition-all resize-y"></textarea>
                            <button type="button" @click="removeListItem(index, itemIndex)"
                                class="w-9 h-9 rounded-lg border border-red-100 bg-white text-red-500 hover:bg-red-50 transition-colors"
                                title="Xóa mục">
                                ×
                            </button>
                        </div>
                    </template>

                    <button type="button" @click="addListItem(index)"
                        class="inline-flex items-center gap-2 px-3 py-2 text-xs font-semibold text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                        <span class="text-base leading-none">+</span>
                        Thêm mục
                    </button>
                </div>
            </div>
        </template>
    </div>

    <p x-show="blocks.length === 0"
        class="text-sm text-gray-400 text-center py-6 bg-gray-50 rounded-lg border border-dashed border-gray-300">
        Chưa có nội dung. Nhấn "Thêm đoạn văn" hoặc "Thêm danh sách" để bắt đầu.
    </p>

    @error($field)
        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
