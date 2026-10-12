(function () {
    'use strict';

    function normalize(value) {
        return String(value || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[đĐ]/g, 'd')
            .toLocaleLowerCase('vi')
            .trim();
    }

    function initialize(root) {
        if (root.dataset.searchReady === 'true') return;
        const input = root.querySelector('[data-admin-product-search-input]');
        const count = root.querySelector('[data-admin-product-search-count]');
        const clear = root.querySelector('[data-admin-product-search-clear]');
        const table = root.parentElement?.querySelector('table');
        const list = table?.querySelector('tbody[data-admin-product-sortable]');
        if (!input || !count || !clear || !list) return;

        root.dataset.searchReady = 'true';
        const rows = Array.from(list.querySelectorAll('tr[data-admin-product-search-row]'));
        const total = rows.length;
        const searchEmptyRow = document.createElement('tr');
        searchEmptyRow.hidden = true;
        searchEmptyRow.dataset.adminProductSearchEmptyRow = '';
        searchEmptyRow.innerHTML = `<td colspan="${table.querySelectorAll('thead th').length}" class="px-6 py-10 text-center text-gray-500">Không tìm thấy sản phẩm phù hợp.</td>`;
        list.append(searchEmptyRow);

        function filter() {
            const query = normalize(input.value);
            let visible = 0;
            rows.forEach((row) => {
                const matches = !query || normalize(row.dataset.searchText).includes(query);
                row.hidden = !matches;
                if (matches) visible++;
                const checkbox = row.querySelector('.bulk-rename-product-checkbox');
                if (checkbox?.checked) {
                    checkbox.checked = false;
                    checkbox.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });

            searchEmptyRow.hidden = !query || visible !== 0 || total === 0;
            count.textContent = query ? `Tìm thấy ${visible}/${total} sản phẩm` : `Hiển thị ${total} sản phẩm`;
            clear.classList.toggle('hidden', !input.value);
            clear.classList.toggle('flex', Boolean(input.value));
            list.dataset.searchFiltered = query ? 'true' : 'false';
            if (typeof Sortable !== 'undefined') {
                Sortable.get(list)?.option('disabled', Boolean(query));
            }

            document.dispatchEvent(new CustomEvent('admin-product-search:changed'));
        }

        input.addEventListener('input', filter);
        clear.addEventListener('click', () => {
            input.value = '';
            filter();
            input.focus();
        });
        filter();
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-admin-product-search]').forEach(initialize);
    });
})();
