(function () {
    'use strict';

    window.previewImage = function (event, targetId) {
        const file = event.target?.files?.[0];
        if (file) {
            const target = document.getElementById(targetId);
            if (target) {
                target.src = URL.createObjectURL(file);
            }
        }
    };

    window.createAdminInputBlock = function (container, name, value = '', autoFocus = false, placeholder = '') {
        if (!container) return;
        const div = document.createElement('div');
        div.className = 'flex items-center bg-white rounded-lg border border-gray-200 shadow-sm group focus-within:border-[#A31D1D] focus-within:ring-1 focus-within:ring-[#A31D1D] transition-all overflow-hidden';

        const escaped = String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        div.innerHTML = `
            <div class="pl-3 pr-2 text-gray-300 cursor-move">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
            </div>
            <input type="text" name="${name}[]" value="${escaped}" placeholder="${placeholder}" class="flex-1 py-2.5 px-2 text-sm border-none focus:ring-0 outline-none text-gray-700 bg-transparent placeholder-gray-400">
            <button type="button" onclick="this.parentElement.remove()" class="px-3 text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity focus:opacity-100" title="Xóa dòng này">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        `;
        container.appendChild(div);
        if (autoFocus && value === '') {
            const input = div.querySelector('input');
            if (input) input.focus();
        }
    };
})();
