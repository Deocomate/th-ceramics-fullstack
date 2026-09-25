@php
    $bulkRenameRoute = route('admin.products.bulk-rename', ['type' => $type]);
@endphp

<div data-bulk-rename-root>
<div class="flex flex-col gap-3 border-b border-gray-100 bg-gray-50/70 px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-5" data-bulk-rename data-route="{{ $bulkRenameRoute }}" data-category-type="{{ $categoryType ?? '' }}">
    <div class="flex min-w-0 items-center gap-3">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-500 shadow-sm">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9 2 2 4-4"/></svg>
        </span>
        <div class="min-w-0">
            <p class="text-sm font-semibold text-gray-800" data-selected-count>Chưa chọn sản phẩm</p>
            <p class="text-xs text-gray-500" data-selection-help>Tích chọn các dòng cần đổi tên.</p>
        </div>
    </div>
    <button type="button" data-open-bulk-rename disabled class="inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-lg bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-400 transition-colors disabled:cursor-not-allowed sm:w-auto">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M4 12h10M4 17h7m5-3 2.5 2.5L21 13"/></svg>
        Đổi tên đã chọn
    </button>
</div>

<div data-bulk-rename-modal class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 py-6" role="dialog" aria-modal="true" aria-labelledby="bulk-rename-title">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-full overflow-hidden flex flex-col">
        <div class="p-6 border-b border-gray-100">
            <h3 id="bulk-rename-title" class="text-xl font-bold text-gray-800">Đổi tên nhiều sản phẩm</h3>
            <p class="mt-1 text-sm text-gray-500">Tên mới sẽ được đánh số theo thứ tự hiển thị trong bảng.</p>
        </div>
        <form method="POST" data-bulk-rename-form class="flex min-h-0 flex-col">
            @csrf
            @if(isset($categoryType))
                <input type="hidden" name="category_type" value="{{ $categoryType }}">
            @endif
            <div class="p-6 space-y-4 overflow-y-auto">
                <label class="block text-sm font-semibold text-gray-700" for="bulk-rename-base-name">Tên sản phẩm</label>
                <input id="bulk-rename-base-name" name="base_name" type="text" maxlength="255" required value="{{ old('base_name') }}" placeholder="Ví dụ: Ngói âm dương sen tỏa" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg outline-none focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D]">
                <p class="text-xs text-gray-500" data-rename-preview-title>Nhập tên để xem trước.</p>
                <ol class="max-h-48 overflow-y-auto list-decimal list-inside rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700 space-y-1" data-rename-preview></ol>
                <div data-rename-error class="hidden text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg px-3 py-2" role="alert"></div>
            </div>
            <div class="flex justify-end gap-3 p-6 pt-0">
                <button type="button" data-close-bulk-rename class="px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Hủy</button>
                <button type="submit" data-submit-bulk-rename disabled class="px-4 py-2.5 text-sm font-bold text-white bg-[#A31D1D] hover:bg-[#8A1818] rounded-lg disabled:opacity-40 disabled:cursor-not-allowed">Xác nhận đổi tên</button>
            </div>
            <div data-rename-hidden-ids></div>
        </form>
    </div>
</div>
</div>

@push('scripts')
<script>
    (() => {
        const root = document.querySelector('[data-bulk-rename-root]');
        const toolbar = root?.querySelector('[data-bulk-rename]');
        if (!toolbar) return;

        const modal = root.querySelector('[data-bulk-rename-modal]');
        const checkboxes = () => Array.from(document.querySelectorAll('.bulk-rename-product-checkbox'));
        const selected = () => checkboxes().filter((checkbox) => checkbox.checked);
        const selectAll = document.querySelector('[data-bulk-rename-select-all]');
        const openButton = toolbar.querySelector('[data-open-bulk-rename]');
        const countLabel = toolbar.querySelector('[data-selected-count]');
        const selectionHelp = toolbar.querySelector('[data-selection-help]');
        const form = modal.querySelector('[data-bulk-rename-form]');
        const nameInput = modal.querySelector('[name="base_name"]');
        const preview = modal.querySelector('[data-rename-preview]');
        const previewTitle = modal.querySelector('[data-rename-preview-title]');
        const submitButton = modal.querySelector('[data-submit-bulk-rename]');
        const hiddenIds = modal.querySelector('[data-rename-hidden-ids]');
        const error = modal.querySelector('[data-rename-error]');

        form.action = toolbar.dataset.route;

        function syncSelection() {
            const items = selected();
            openButton.disabled = items.length === 0;
            countLabel.textContent = items.length ? `Đã chọn ${items.length} sản phẩm` : 'Chưa chọn sản phẩm';
            selectionHelp.textContent = items.length ? 'Sẽ đổi tên theo thứ tự hiển thị trong bảng.' : 'Tích chọn các dòng cần đổi tên.';
            openButton.classList.toggle('bg-gray-200', items.length === 0);
            openButton.classList.toggle('text-gray-400', items.length === 0);
            openButton.classList.toggle('bg-[#A31D1D]', items.length > 0);
            openButton.classList.toggle('text-white', items.length > 0);
            openButton.classList.toggle('hover:bg-[#8A1818]', items.length > 0);
            if (selectAll) {
                selectAll.checked = checkboxes().length > 0 && items.length === checkboxes().length;
                selectAll.indeterminate = items.length > 0 && items.length < checkboxes().length;
            }
            checkboxes().forEach((checkbox) => {
                const row = checkbox.closest('tr');
                row?.classList.toggle('ring-1', checkbox.checked);
                row?.classList.toggle('ring-inset', checkbox.checked);
                row?.classList.toggle('ring-[#A31D1D]/25', checkbox.checked);
            });
            renderPreview();
        }

        function renderPreview() {
            const items = selected();
            const baseName = nameInput.value.trim();
            preview.replaceChildren();
            items.forEach((_, index) => {
                const item = document.createElement('li');
                item.textContent = baseName ? `${baseName} ${index + 1}` : 'Nhập tên để xem trước';
                preview.append(item);
            });
            previewTitle.textContent = `Sẽ đổi tên ${items.length} sản phẩm theo thứ tự trong bảng.`;
            const largestSuffix = ` ${items.length}`;
            const validLength = baseName.length > 0 && Array.from(baseName + largestSuffix).length <= 255;
            submitButton.disabled = items.length === 0 || !validLength;
            error.classList.toggle('hidden', validLength || baseName.length === 0);
            error.textContent = validLength || baseName.length === 0 ? '' : 'Tên dài quá 255 ký tự sau khi thêm số thứ tự.';
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            root.append(modal);
        }

        openButton.addEventListener('click', () => {
            hiddenIds.replaceChildren();
            selected().forEach((checkbox) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = checkbox.value;
                hiddenIds.append(input);
            });
            nameInput.value = '';
            error.classList.add('hidden');
            document.body.append(modal);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            renderPreview();
            nameInput.focus();
        });

        root.querySelector('[data-close-bulk-rename]').addEventListener('click', closeModal);
        modal.addEventListener('click', (event) => { if (event.target === modal) closeModal(); });
        nameInput.addEventListener('input', renderPreview);
        checkboxes().forEach((checkbox) => checkbox.addEventListener('change', syncSelection));
        if (selectAll) {
            selectAll.addEventListener('change', () => {
                checkboxes().forEach((checkbox) => { checkbox.checked = selectAll.checked; });
                syncSelection();
            });
        }
        syncSelection();
    })();
</script>
@endpush
