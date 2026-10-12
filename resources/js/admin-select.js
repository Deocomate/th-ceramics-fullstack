(function () {
    'use strict';

    const instances = new WeakMap();
    const searchableAfter = 8;

    function optionRows(select) {
        return Array.from(select.options).map((option, index) => ({
            value: option.value,
            label: option.textContent.trim(),
            disabled: option.disabled || (option.parentElement instanceof HTMLOptGroupElement && option.parentElement.disabled),
            selected: option.selected,
            index,
        }));
    }

    function enhance(select) {
        if (instances.has(select) || select.dataset.adminNativeSelect !== undefined || !select.isConnected) return;

        const fillsContainer = select.classList.contains('w-full');
        const initialWidth = Math.max(120, select.getBoundingClientRect().width);
        const wrapper = document.createElement('div');
        wrapper.className = 'admin-select relative min-w-0';
        wrapper.dataset.adminSelect = '';
        wrapper.style.width = fillsContainer || select.classList.contains('flex-1') ? '100%' : `${initialWidth}px`;
        if (select.classList.contains('flex-1')) wrapper.style.flex = '1 1 0%';

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'admin-select-trigger';
        button.setAttribute('role', 'combobox');
        button.setAttribute('aria-haspopup', 'listbox');
        button.setAttribute('aria-expanded', 'false');
        button.setAttribute('aria-autocomplete', 'list');
        button.disabled = select.disabled;
        button.id = select.id ? `${select.id}-trigger` : `admin-select-${Math.random().toString(36).slice(2)}`;

        const label = (select.id ? document.querySelector(`label[for="${CSS.escape(select.id)}"]`) : null)
            || select.closest('label');
        const labelText = label?.textContent?.trim() || select.getAttribute('aria-label') || select.name || 'Chọn một mục';
        button.setAttribute('aria-label', labelText);

        const value = document.createElement('span');
        value.className = 'admin-select-value truncate';
        const arrow = document.createElement('span');
        arrow.className = 'admin-select-chevron';
        arrow.setAttribute('aria-hidden', 'true');
        arrow.innerHTML = '<svg viewBox="0 0 20 20" fill="none"><path d="m5 7.5 5 5 5-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        button.append(value, arrow);

        const error = document.createElement('span');
        error.className = 'admin-select-error';
        error.setAttribute('aria-live', 'polite');

        const menu = document.createElement('div');
        menu.id = `${button.id}-listbox`;
        menu.className = 'admin-select-menu';
        menu.setAttribute('role', 'listbox');
        menu.hidden = true;
        button.setAttribute('aria-controls', menu.id);
        const search = document.createElement('input');
        search.type = 'search';
        search.className = 'admin-select-search';
        search.placeholder = 'Tìm kiếm...';
        search.setAttribute('aria-label', `Tìm trong ${labelText.toLocaleLowerCase('vi')}`);
        const options = document.createElement('div');
        options.className = 'admin-select-options';
        menu.append(search, options);

        select.parentNode.insertBefore(wrapper, select);
        wrapper.append(button, select, error);
        select.classList.add('admin-select-native');
        select.setAttribute('aria-hidden', 'true');
        select.tabIndex = -1;

        let activeIndex = -1;
        let filteredRows = [];
        let lastSelectedIndex = null;

        function sync(forceOptions = false) {
            const selected = select.options[select.selectedIndex];
            value.textContent = selected?.textContent?.trim() || 'Chọn...';
            value.classList.toggle('admin-select-placeholder', !selected || selected.value === '');
            button.disabled = select.disabled;
            wrapper.classList.toggle('is-disabled', select.disabled);
            wrapper.classList.toggle('has-error', !!select.validationMessage);
            error.textContent = select.validationMessage || '';
            if (forceOptions || lastSelectedIndex !== select.selectedIndex) {
                lastSelectedIndex = select.selectedIndex;
                renderOptions(search.value);
            }
        }

        function renderOptions(query = '') {
            const needle = query.trim().toLocaleLowerCase('vi');
            filteredRows = optionRows(select).filter(row => !needle || row.label.toLocaleLowerCase('vi').includes(needle));
            options.replaceChildren();
            activeIndex = Math.min(activeIndex, filteredRows.length - 1);

            if (!filteredRows.length) {
                const empty = document.createElement('div');
                empty.className = 'admin-select-empty';
                empty.textContent = 'Không tìm thấy lựa chọn phù hợp';
                options.append(empty);
                return;
            }

            filteredRows.forEach((row, index) => {
                const option = document.createElement('button');
                option.type = 'button';
                option.className = 'admin-select-option';
                option.textContent = row.label;
                option.setAttribute('role', 'option');
                option.setAttribute('aria-selected', row.selected ? 'true' : 'false');
                option.id = `${menu.id}-option-${row.index}`;
                option.tabIndex = -1;
                option.disabled = row.disabled;
                if (row.selected) option.classList.add('is-selected');
                if (index === activeIndex) option.classList.add('is-active');
                option.addEventListener('mouseenter', () => {
                    activeIndex = index;
                    options.querySelectorAll('.is-active').forEach(item => item.classList.remove('is-active'));
                    option.classList.add('is-active');
                });
                option.addEventListener('click', () => choose(row));
                options.append(option);
            });
        }

        function positionMenu() {
            const rect = button.getBoundingClientRect();
            const width = Math.min(Math.max(rect.width, 220), window.innerWidth - 16);
            menu.style.position = 'fixed';
            menu.style.left = `${Math.max(8, Math.min(rect.left, window.innerWidth - width - 8))}px`;
            menu.style.width = `${width}px`;
            menu.style.maxHeight = `${Math.max(160, Math.min(320, window.innerHeight - rect.bottom - 12))}px`;
            menu.style.top = `${rect.bottom + 6}px`;
            menu.style.bottom = 'auto';
            if (rect.bottom + 220 > window.innerHeight && rect.top > 220) {
                menu.style.top = 'auto';
                menu.style.bottom = `${window.innerHeight - rect.top + 6}px`;
            }
        }

        function close(focusButton = false) {
            menu.hidden = true;
            button.setAttribute('aria-expanded', 'false');
            button.removeAttribute('aria-activedescendant');
            search.value = '';
            renderOptions('');
            if (focusButton) button.focus();
        }

        function open() {
            if (select.disabled) return;
            sync();
            if (select.options.length >= searchableAfter) search.hidden = false;
            else search.hidden = true;
            activeIndex = filteredRows.findIndex(row => row.selected && !row.disabled);
            if (activeIndex < 0) activeIndex = filteredRows.findIndex(row => !row.disabled);
            renderOptions(search.value);
            menu.hidden = false;
            button.setAttribute('aria-expanded', 'true');
            positionMenu();
            if (filteredRows[activeIndex]) {
                button.setAttribute('aria-activedescendant', `${menu.id}-option-${filteredRows[activeIndex].index}`);
            }
            if (!search.hidden) search.focus();
            else options.querySelector('.is-active')?.focus();
        }

        function choose(row) {
            if (row.disabled) return;
            select.selectedIndex = row.index;
            select.dispatchEvent(new Event('input', { bubbles: true }));
            select.dispatchEvent(new Event('change', { bubbles: true }));
            sync();
            close(true);
        }

        button.addEventListener('click', () => menu.hidden ? open() : close());
        button.addEventListener('keydown', event => {
            if (event.key === 'ArrowDown' || event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                if (menu.hidden) open();
                else search.focus();
            }
            if (event.key === 'Escape') close(true);
        });
        search.addEventListener('input', () => {
            activeIndex = -1;
            renderOptions(search.value);
        });
        search.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                event.preventDefault();
                close(true);
            } else if (event.key === 'Enter' && filteredRows[activeIndex]) {
                event.preventDefault();
                choose(filteredRows[activeIndex]);
            }
        });
        menu.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                event.preventDefault();
                close(true);
                return;
            }

            if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') return;
            event.preventDefault();
            if (!filteredRows.length) return;
            const direction = event.key === 'ArrowDown' ? 1 : -1;
            let cursor = activeIndex < 0 ? (direction > 0 ? -1 : 0) : activeIndex;
            let attempts = 0;
            do {
                cursor = (cursor + direction + filteredRows.length) % filteredRows.length;
                attempts++;
            } while (filteredRows[cursor]?.disabled && attempts < filteredRows.length);

            if (filteredRows[cursor]?.disabled) return;
            activeIndex = cursor;
            renderOptions(search.value);
            button.setAttribute('aria-activedescendant', `${menu.id}-option-${filteredRows[activeIndex].index}`);
            const activeOption = options.querySelector('.is-active');
            activeOption?.scrollIntoView({ block: 'nearest' });
            if (search.hidden) activeOption?.focus();
        });
        select.addEventListener('change', sync);
        select.addEventListener('input', sync);
        select.addEventListener('invalid', event => {
            event.preventDefault();
            wrapper.classList.add('has-error');
            error.textContent = select.validationMessage;
            button.focus();
        });
        document.addEventListener('click', event => {
            if (!wrapper.contains(event.target) && !menu.contains(event.target) && !label?.contains(event.target)) close();
            sync();
        });
        label?.addEventListener('click', event => {
            event.preventDefault();
            open();
        });
        window.addEventListener('resize', () => {
            wrapper.style.width = fillsContainer || select.classList.contains('flex-1') ? '100%' : `${initialWidth}px`;
            if (!menu.hidden) positionMenu();
        });
        window.addEventListener('scroll', () => { if (!menu.hidden) positionMenu(); }, true);
        new MutationObserver(() => sync(true)).observe(select, { childList: true, subtree: true, attributes: true });

        document.body.append(menu);
        instances.set(select, { sync, menu, wrapper });
        sync();
    }

    function scan(root = document) {
        if (root instanceof HTMLSelectElement) enhance(root);
        root.querySelectorAll?.('select').forEach(enhance);
    }

    document.addEventListener('DOMContentLoaded', () => {
        scan();
        new MutationObserver(records => records.forEach(record => record.addedNodes.forEach(node => {
            if (node.nodeType === Node.ELEMENT_NODE) scan(node);
        }))).observe(document.body, { childList: true, subtree: true });
    });
})();
