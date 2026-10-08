@props([
    'productType' => '',
    'button' => true,
    'buttonText' => 'Sao chép từ sản phẩm cũ',
    'buttonClass' => '',
    'copiedProduct' => null,
])

@if($button)
    <button type="button" onclick="openProductCopyModal('{{ $productType }}')" 
        class="{{ $buttonClass ?: 'inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white border border-gray-300 text-gray-700 text-xs font-semibold rounded-lg hover:bg-red-50 hover:text-[#A31D1D] hover:border-[#A31D1D] transition-all shadow-sm focus:outline-none' }}"
        title="Sao chép toàn bộ thông số từ sản phẩm đã có">
        <svg class="w-4 h-4 text-[#A31D1D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
        </svg>
        <span>{{ $buttonText }}</span>
    </button>
@endif

{{-- MODAL CONTAINER --}}
<div id="product-copy-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs transition-opacity duration-200 hidden opacity-0" aria-modal="true" role="dialog">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[88vh] flex flex-col overflow-hidden border border-gray-100 transform transition-transform duration-200 scale-95" id="product-copy-modal-card">
        
        {{-- HEADER --}}
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-red-100 text-[#A31D1D] flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-800">Sao chép thông tin sản phẩm cũ</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Chọn sản phẩm đã có để tự động điền các thông số, kích thước, mô tả và giá bán.</p>
                </div>
            </div>
            <button type="button" onclick="closeProductCopyModal()" class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-200 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- FILTER & SEARCH BAR --}}
        <div class="p-4 bg-white border-b border-gray-100 flex flex-col sm:flex-row gap-3 flex-shrink-0">
            {{-- Chọn danh mục --}}
            <div class="w-full sm:w-56 flex-shrink-0">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Loại sản phẩm:</label>
                <select id="product-copy-type-select" onchange="onCopyTypeChange(this.value)" class="w-full px-3 py-2 text-xs font-semibold border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all bg-white text-gray-700">
                    <option value="ngoi-am-duong-ct">Ngói Âm Dương</option>
                    <option value="ngoi-hai-co-ct">Ngói Hài Cổ</option>
                    <option value="ngoi-hai-van-mieu-ct">Ngói Hài Văn Miếu</option>
                    <option value="gach-hoa-thong-gio-ct">Gạch Hoa Thông Gió</option>
                    <option value="gach-trang-tri-ct">Gạch Trang Trí</option>
                    <option value="phu-kien-ngoi-ct">Phụ Kiện Ngói</option>
                    <option value="gach-co-bat-trang-ct">Gạch Cổ Bát Tràng</option>
                    <option value="linh-vat-phong-thuy-ct">Linh Vật Phong Thủy</option>
                    <option value="lan-can-gom-su-ct">Lan Can Gốm Sứ</option>
                    <option value="den-vuon-gom-su-ct">Đèn Vườn Gốm Sứ</option>
                </select>
            </div>

            {{-- Ô tìm kiếm --}}
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Tìm kiếm sản phẩm:</label>
                <div class="relative">
                    <input type="text" id="product-copy-search-input" oninput="onCopySearchInput(this.value)" placeholder="Nhập tên sản phẩm hoặc mã (VD: NAD-001, Ngói, Gạch...)" 
                        class="w-full pl-9 pr-4 py-2 text-xs border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all placeholder-gray-400">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- BODY / PRODUCT LIST CONTAINER --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-2.5 bg-gray-50/50" id="product-copy-list">
            {{-- Danh sách sản phẩm render qua JS --}}
        </div>

        {{-- FOOTER --}}
        <div class="px-6 py-3 border-t border-gray-100 bg-white flex items-center justify-between flex-shrink-0 text-xs text-gray-500">
            <span class="flex items-center gap-1.5 text-amber-700 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200">
                <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Lưu ý: Sau khi sao chép, vui lòng tải ảnh lên và kiểm tra mã sản phẩm trước khi Lưu.
            </span>
            <button type="button" onclick="closeProductCopyModal()" class="px-4 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors">
                Đóng
            </button>
        </div>

    </div>
</div>

{{-- TOAST NOTIFICATION CONTAINER --}}
<div id="product-copy-toast" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 bg-gray-900 text-white rounded-xl shadow-2xl transition-all duration-300 transform translate-y-12 opacity-0 pointer-events-none">
    <div class="w-7 h-7 rounded-lg bg-green-500 text-white flex items-center justify-center flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
    </div>
    <div class="text-xs">
        <div class="font-bold text-white" id="product-copy-toast-title">Sao chép thành công</div>
        <div class="text-gray-300" id="product-copy-toast-msg">Đã điền thông tin sản phẩm vào form.</div>
    </div>
</div>

@once
@push('scripts')
<script>
    let currentCopyType = '{{ $productType ?: "ngoi-am-duong-ct" }}';
    let copySearchDebounceTimer = null;
    let cachedProducts = [];

    function openProductCopyModal(type) {
        if (type) currentCopyType = type;
        const select = document.getElementById('product-copy-type-select');
        if (select) select.value = currentCopyType;

        const modal = document.getElementById('product-copy-modal');
        const card = document.getElementById('product-copy-modal-card');
        if (!modal) return;

        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            card.classList.remove('scale-95');
        });

        // Focus search input
        const searchInput = document.getElementById('product-copy-search-input');
        if (searchInput) {
            searchInput.value = '';
            setTimeout(() => searchInput.focus(), 150);
        }

        loadProductsForCopy(currentCopyType, '');
    }

    function closeProductCopyModal() {
        const modal = document.getElementById('product-copy-modal');
        const card = document.getElementById('product-copy-modal-card');
        if (!modal) return;

        modal.classList.add('opacity-0');
        card.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    function onCopyTypeChange(type) {
        currentCopyType = type;
        const searchInput = document.getElementById('product-copy-search-input');
        const q = searchInput ? searchInput.value.trim() : '';
        loadProductsForCopy(type, q);
    }

    function onCopySearchInput(keyword) {
        clearTimeout(copySearchDebounceTimer);
        copySearchDebounceTimer = setTimeout(() => {
            loadProductsForCopy(currentCopyType, keyword.trim());
        }, 280);
    }

    async function loadProductsForCopy(type, keyword) {
        const listContainer = document.getElementById('product-copy-list');
        if (!listContainer) return;

        listContainer.innerHTML = `
            <div class="py-12 flex flex-col items-center justify-center text-gray-400">
                <svg class="w-8 h-8 animate-spin text-[#A31D1D]" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span class="mt-3 text-xs font-medium text-gray-500">Đang tải danh sách sản phẩm...</span>
            </div>
        `;

        try {
            const url = new URL('{{ route("admin.product-copy.list") }}', window.location.origin);
            url.searchParams.set('type', type);
            if (keyword) url.searchParams.set('q', keyword);

            const res = await fetch(url.toString(), {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const result = await res.json();

            if (!result.success || !result.data || result.data.length === 0) {
                listContainer.innerHTML = `
                    <div class="py-12 flex flex-col items-center justify-center text-center text-gray-400">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-2">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-600">Không tìm thấy sản phẩm phù hợp</p>
                        <p class="text-xs text-gray-400 mt-1">Thử thay đổi từ khóa hoặc chọn loại sản phẩm khác bên trên.</p>
                    </div>
                `;
                return;
            }

            cachedProducts = result.data;
            renderProductItems(result.data, type);
        } catch (err) {
            console.error(err);
            listContainer.innerHTML = `
                <div class="py-8 text-center text-red-500 text-xs">
                    Có lỗi xảy ra khi tải danh sách sản phẩm. Vui lòng thử lại.
                </div>
            `;
        }
    }

    function renderProductItems(products, type) {
        const listContainer = document.getElementById('product-copy-list');
        if (!listContainer) return;

        let html = '';
        const safeType = escapeHtml(type);
        products.forEach(p => {
            const thumb = p.thumbnail_url 
                ? `<img src="${escapeHtml(p.thumbnail_url)}" class="w-full h-full object-contain" alt="">`
                : `<div class="w-full h-full flex items-center justify-center text-gray-300"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>`;

            html += `
                <div class="bg-white rounded-xl border border-gray-200 p-3 flex items-center justify-between gap-4 hover:border-[#A31D1D]/40 hover:shadow-md transition-all group">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <div class="w-14 h-14 rounded-lg bg-gray-50 border border-gray-200 overflow-hidden flex-shrink-0 flex items-center justify-center">
                            ${thumb}
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-sm font-bold text-gray-800 truncate group-hover:text-[#A31D1D] transition-colors">${escapeHtml(p.name)}</h4>
                                ${p.code && p.code !== 'N/A' ? `<span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-gray-100 text-gray-700 border border-gray-200">${escapeHtml(p.code)}</span>` : ''}
                            </div>
                            <div class="flex items-center gap-3 text-xs text-gray-500 mt-1 flex-wrap">
                                <span class="font-bold text-[#A31D1D]">${escapeHtml(p.formatted_price)}</span>
                                <span class="text-gray-300">•</span>
                                <span>Màu: <strong class="text-gray-700">${escapeHtml(p.color || 'Tự chọn')}</strong></span>
                                ${p.size && p.size !== 'N/A' ? `<span class="text-gray-300">•</span><span>KT: <strong class="text-gray-700">${escapeHtml(p.size)}</strong></span>` : ''}
                                ${p.des_count > 0 ? `<span class="text-gray-300">•</span><span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[10px] font-semibold border border-blue-100">${p.des_count} dòng mô tả</span>` : ''}
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="selectProductToCopy('${safeType}', ${parseInt(p.id, 10)})" 
                        class="px-3.5 py-2 bg-red-50 text-[#A31D1D] border border-red-200 text-xs font-bold rounded-lg hover:bg-[#A31D1D] hover:text-white transition-all shadow-xs flex-shrink-0 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>Sao chép</span>
                    </button>
                </div>
            `;
        });

        listContainer.innerHTML = html;
    }

    async function selectProductToCopy(type, id) {
        try {
            const url = `{{ url('admin/api/product-copy/detail') }}/${type}/${id}`;
            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const result = await res.json();

            if (!result.success || !result.data) {
                alert('Không thể lấy thông tin sản phẩm. Vui lòng thử lại.');
                return;
            }

            populateProductForm(result.data);
            closeProductCopyModal();
            showCopyToast(`Đã sao chép "${result.data.name}"`, 'Các trường thông số đã được điền tự động. Vui lòng tải ảnh lên trước khi Lưu.');
        } catch (err) {
            console.error(err);
            alert('Có lỗi xảy ra khi lấy thông tin chi tiết sản phẩm.');
        }
    }

    function populateProductForm(data) {
        if (!data) return;

        // 1. Tên sản phẩm
        const nameInput = document.querySelector('input[name="name"]');
        if (nameInput) {
            nameInput.value = data.name || '';
            triggerEvent(nameInput, 'input');
        }

        // 2. Màu sắc
        const colorInput = document.querySelector('input[name="color"]');
        if (colorInput) {
            colorInput.value = data.color || 'Tự chọn';
            triggerEvent(colorInput, 'input');
        }

        // 3. Mã sản phẩm (Code)
        const codeInput = document.querySelector('input[name="code"]');
        if (codeInput) {
            codeInput.value = data.suggested_code || (data.code ? data.code + '-COPY' : '');
            triggerEvent(codeInput, 'input');
            // Hiệu ứng highlight nhẹ để nhắc người dùng đổi mã
            codeInput.classList.add('ring-2', 'ring-amber-500', 'bg-amber-50');
            setTimeout(() => {
                codeInput.classList.remove('ring-2', 'ring-amber-500', 'bg-amber-50');
            }, 3000);
        }

        // 4. Giá
        const priceInput = document.querySelector('input[name="price"]');
        if (priceInput && data.price !== undefined && data.price !== null) {
            priceInput.value = data.price;
            triggerEvent(priceInput, 'input');
        }

        // 5. Kích thước (Text)
        const sizeInput = document.querySelector('input[name="size"]');
        if (sizeInput) {
            sizeInput.value = data.size || '';
            triggerEvent(sizeInput, 'input');
        }

        // 6. Phân loại (category_type nếu có)
        const catSelect = document.querySelector('select[name="category_type"]');
        if (catSelect && data.category_type) {
            catSelect.value = data.category_type;
            triggerEvent(catSelect, 'change');
        }

        // 7. Định mức & Khối lượng (Gạch Cổ Bát Tràng)
        const dinhMucInput = document.querySelector('input[name="dinh_muc"]');
        if (dinhMucInput && data.dinh_muc) {
            dinhMucInput.value = data.dinh_muc;
            triggerEvent(dinhMucInput, 'input');
        }
        const weightInput = document.querySelector('input[name="weight"]');
        if (weightInput && data.weight) {
            weightInput.value = data.weight;
            triggerEvent(weightInput, 'input');
        }

        // 8. Video (Hành trình sản phẩm / video)
        const videoInput = document.querySelector('input[name="video"]');
        if (videoInput && data.video) {
            videoInput.value = data.video;
            triggerEvent(videoInput, 'input');
        }

        // 9. Danh sách Thông số / Mô tả (des[])
        const desContainer = document.getElementById('des-blocks-container');
        if (desContainer && typeof window.addDesBlock === 'function') {
            desContainer.innerHTML = '';
            if (Array.isArray(data.des) && data.des.length > 0) {
                data.des.forEach(val => window.addDesBlock(val));
            } else {
                window.addDesBlock('');
            }
        }

        // 10. Danh sách Kích thước chi tiết (size_des[] nếu có)
        const sizeDesContainer = document.getElementById('size-des-blocks-container');
        if (sizeDesContainer && typeof window.addSizeDesBlock === 'function') {
            sizeDesContainer.innerHTML = '';
            if (Array.isArray(data.size_des) && data.size_des.length > 0) {
                data.size_des.forEach(val => window.addSizeDesBlock(val));
            }
        }
    }

    function showCopyToast(title, msg) {
        const toast = document.getElementById('product-copy-toast');
        const titleEl = document.getElementById('product-copy-toast-title');
        const msgEl = document.getElementById('product-copy-toast-msg');
        if (!toast) return;

        if (titleEl) titleEl.textContent = title;
        if (msgEl) msgEl.textContent = msg;

        toast.classList.remove('translate-y-12', 'opacity-0', 'pointer-events-none');
        setTimeout(() => {
            toast.classList.add('translate-y-12', 'opacity-0', 'pointer-events-none');
        }, 4500);
    }

    function triggerEvent(el, eventType) {
        if (!el) return;
        const event = new Event(eventType, { bubbles: true });
        el.dispatchEvent(event);
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Đóng modal khi nhấn Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeProductCopyModal();
    });

    // Tự động populate nếu server truyền $copiedProduct sang (khi mở create?copy_from=...)
    @if(isset($copiedProduct) && $copiedProduct)
        document.addEventListener('DOMContentLoaded', () => {
            try {
                const preloadedProduct = @json($copiedProduct);
                populateProductForm(preloadedProduct);
                showCopyToast('Đã nạp dữ liệu bản sao', 'Thông tin từ sản phẩm gốc đã được điền sẵn. Vui lòng kiểm tra mã và tải ảnh mới.');
            } catch (e) {
                console.error('Error preloading copied product:', e);
            }
        });
    @endif
</script>
@endpush
@endonce
