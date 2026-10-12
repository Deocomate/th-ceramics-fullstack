(function () {
    'use strict';

    function initialize(list) {
        if (list.dataset.canReorder !== 'true' || typeof Sortable === 'undefined' || list.dataset.sortableReady === 'true') return;
        list.dataset.sortableReady = 'true';

        const rows = () => Array.from(list.querySelectorAll('tr[data-priority-id]'));
        rows().forEach(row => {
            const cell = row.querySelector('td');
            if (!cell || cell.querySelector('.priority-drag-handle')) return;
            const contents = Array.from(cell.childNodes);
            const controls = document.createElement('div');
            controls.className = 'priority-row-controls';
            const handle = document.createElement('button');
            handle.type = 'button';
            handle.className = 'priority-drag-handle';
            handle.setAttribute('aria-label', 'Kéo để đổi thứ tự sản phẩm');
            handle.title = 'Kéo để đổi thứ tự';
            handle.innerHTML = '<svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><circle cx="7" cy="5" r="1.3"/><circle cx="13" cy="5" r="1.3"/><circle cx="7" cy="10" r="1.3"/><circle cx="13" cy="10" r="1.3"/><circle cx="7" cy="15" r="1.3"/><circle cx="13" cy="15" r="1.3"/></svg>';
            controls.append(handle);
            contents.forEach(node => controls.append(node));
            cell.append(controls);
        });

        const status = document.createElement('p');
        status.className = 'priority-save-status';
        status.setAttribute('role', 'status');
        status.setAttribute('aria-live', 'polite');
        status.textContent = 'Kéo biểu tượng ⋮⋮ để đổi thứ tự ưu tiên.';
        list.closest('.bg-white')?.insertAdjacentElement('beforebegin', status);

        let savedIds = rows().map(row => row.dataset.priorityId);
        const sortable = Sortable.create(list, {
            animation: 180,
            handle: '.priority-drag-handle',
            draggable: 'tr[data-priority-id]',
            ghostClass: 'priority-row-ghost',
            chosenClass: 'priority-row-chosen',
            onStart() { status.textContent = 'Đang đổi thứ tự…'; },
            async onEnd() {
                const nextIds = rows().map(row => row.dataset.priorityId);
                if (nextIds.every((id, index) => id === savedIds[index])) {
                    status.textContent = 'Kéo biểu tượng ⋮⋮ để đổi thứ tự ưu tiên.';
                    return;
                }

                status.textContent = 'Đang lưu thứ tự…';
                status.classList.remove('is-error');
                list.setAttribute('aria-busy', 'true');
                try {
                    const response = await fetch(list.dataset.priorityEndpoint, {
                        method: 'PUT',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        },
                        body: JSON.stringify({
                            ids: nextIds,
                            ...(list.dataset.categoryType ? { category_type: list.dataset.categoryType } : {}),
                        }),
                    });
                    const result = await response.json().catch(() => ({}));
                    if (!response.ok) throw new Error(result.message || Object.values(result.errors || {}).flat()[0] || 'Chưa lưu được thứ tự.');
                    savedIds = nextIds;
                    status.textContent = 'Đã lưu thứ tự ưu tiên.';
                } catch (error) {
                    sortable.sort(savedIds, true);
                    status.textContent = `${error.message} Thứ tự cũ đã được khôi phục.`;
                    status.classList.add('is-error');
                } finally {
                    list.removeAttribute('aria-busy');
                }
            },
        });
    }

    function scan(root = document) {
        root.querySelectorAll?.('[data-admin-product-sortable]').forEach(initialize);
        if (root.matches?.('[data-admin-product-sortable]')) initialize(root);
    }

    document.addEventListener('DOMContentLoaded', () => scan());
})();
