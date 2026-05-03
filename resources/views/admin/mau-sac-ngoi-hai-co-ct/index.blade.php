<x-admin.layout.app title="Màu Sắc Ngói Hài Cổ (SKU)" breadcrumb="Admin › DS Sản phẩm chi tiết › Ngói Hài Cổ › Màu Sắc">
    
    {{-- THÔNG BÁO LỖI VALIDATION --}}
    @if ($errors->any())
        <div class="mb-6 flex items-start gap-3 px-4 py-3 rounded-lg text-sm text-red-800 bg-red-50 border border-red-200 shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>
                <strong class="font-semibold block mb-1">Thao tác không thành công. Vui lòng kiểm tra lại:</strong>
                <ul class="list-disc ml-4 text-red-700">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- HEADER & BỘ LỌC --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            @if(isset($selectedProduct))
                <a href="{{ route('admin.ngoi-hai-co-ct.index') }}" class="p-2 bg-white border border-gray-200 rounded-lg text-gray-500 hover:text-[#A31D1D] hover:bg-red-50 transition-colors shadow-sm" title="Quay lại danh sách sản phẩm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="text-sm font-semibold text-gray-500">Quản lý Màu sắc (SKU) của dáng ngói:</h2>
                    <p class="text-[#A31D1D] text-xl font-bold uppercase tracking-wide mt-0.5">{{ $selectedProduct->name }}</p>
                </div>
            @else
                <div>
                    <h2 class="text-sm font-semibold text-gray-700">Tất cả Biến thể (SKU) Màu Sắc</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Quản lý toàn bộ màu sắc của các dáng Ngói Hài Cổ</p>
                </div>
            @endif
        </div>

        <form method="GET" action="{{ route('admin.mau-sac-ngoi-hai-co-ct.index') }}" class="flex items-center gap-2">
            @if(isset($selectedProduct))
                <input type="hidden" name="product_id" value="{{ $selectedProduct->ngoi_hai_co_ct_id }}">
            @endif
            <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 text-sm font-medium border border-gray-300 rounded-lg outline-none focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] bg-white cursor-pointer shadow-sm transition-all">
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Màu sắc đang bán</option>
                <option value="deleted" {{ $status === 'deleted' ? 'selected' : '' }}>Màu sắc đã ẩn</option>
            </select>
        </form>
    </div>

    {{-- FORM THÊM MỚI --}}
    <div class="mb-8 p-6 bg-blue-50/40 rounded-xl border border-blue-100 shadow-[0_0_15px_rgba(59,130,246,0.03)] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-bl-full -z-10"></div>
        <h3 class="text-sm font-bold text-blue-800 mb-5 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            THÊM MÀU SẮC (SKU) MỚI
        </h3>
        
        <form action="{{ route('admin.mau-sac-ngoi-hai-co-ct.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-8 items-start">
                <!-- Upload Ảnh -->
                <div class="xl:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ảnh màu sắc <span class="text-red-500">*</span></label>
                    <div class="aspect-square w-full max-w-[250px] mx-auto rounded-xl border-2 border-dashed border-blue-300 bg-white flex items-center justify-center overflow-hidden relative group hover:bg-blue-50 transition-colors">
                        <img id="preview-new" src="https://placehold.co/400x400?text=Chon+Anh" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Chọn ảnh tải lên</span>
                        </div>
                        <input type="file" name="image" accept="image/*" required onchange="previewImage(event, 'preview-new')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                </div>

                <!-- Fields -->
                <div class="xl:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Thuộc Sản phẩm (Dáng ngói) <span class="text-red-500">*</span></label>
                        
                        @if(isset($selectedProduct))
                            {{-- Khi đang xem theo 1 sản phẩm cụ thể: Khóa ô select (Chỉ đọc) --}}
                            <input type="hidden" name="ngoi_hai_co_ct_id" value="{{ $selectedProduct->ngoi_hai_co_ct_id }}">
                            <select disabled class="w-full px-4 py-2.5 text-sm font-medium border rounded-lg border-gray-300 bg-gray-100 text-gray-500 cursor-not-allowed">
                                <option selected>{{ $selectedProduct->name }}</option>
                            </select>
                        @else
                            {{-- Nếu xem Tất cả sản phẩm: Được phép chọn --}}
                            <select name="ngoi_hai_co_ct_id" required class="w-full px-4 py-2.5 text-sm font-medium border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none bg-white transition-all">
                                <option value="">-- Chọn sản phẩm cha --</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->ngoi_hai_co_ct_id }}" {{ old('ngoi_hai_co_ct_id') == $prod->ngoi_hai_co_ct_id ? 'selected' : '' }}>
                                        {{ $prod->name }}
                                    </option>
                                @endforeach
                            </select>
                        @endif

                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tên màu sắc <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="VD: Đỏ đun, Xanh ngọc..." class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mã Sản phẩm (Mã Code) <span class="text-red-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code') }}" required placeholder="VD: NHC-DD-01" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all font-mono">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Giá tiền (VNĐ) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price') }}" required min="0" placeholder="VD: 5500" class="w-full px-4 py-2.5 text-sm border rounded-lg border-gray-300 focus:border-[#A31D1D] focus:ring-1 focus:ring-[#A31D1D] outline-none transition-all">
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-6 pt-5 border-t border-blue-100/50">
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors flex items-center gap-2" style="background:#A31D1D;" onmouseover="this.style.background='#8A1818'" onmouseout="this.style.background='#A31D1D'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Lưu Biến Thể
                </button>
            </div>
        </form>
    </div>

    {{-- BẢNG DANH SÁCH --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-1/4">Sản Phẩm Cha (Dáng)</th>
                        <th class="px-6 py-4 font-semibold">Tên / Ảnh Màu</th>
                        <th class="px-6 py-4 font-semibold">Mã Code</th>
                        <th class="px-6 py-4 font-semibold">Giá bán</th>
                        <th class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                        <th class="px-6 py-4 font-semibold text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($mauSacs as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors {{ $item->is_delete ? 'bg-red-50/30' : '' }}">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-700 {{ $item->is_delete ? 'text-gray-400 line-through' : '' }}">
                                    {{ $item->product->name ?? 'Không xác định' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg border border-gray-200 shadow-sm overflow-hidden flex-shrink-0 {{ $item->is_delete ? 'opacity-50 grayscale' : '' }}">
                                        <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-full object-cover">
                                    </div>
                                    <span class="font-bold text-gray-800 {{ $item->is_delete ? 'text-gray-400 line-through' : '' }}">{{ $item->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-700 border border-gray-200 rounded text-xs font-bold tracking-wide font-mono">
                                    {{ $item->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-[#A31D1D] {{ $item->is_delete ? 'text-gray-400' : '' }}">
                                    {{ number_format($item->price, 0, ',', '.') }} VNĐ
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->is_delete)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-700">Đã ẩn</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-700">Đang bán</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    @if(!$item->is_delete)
                                        <button type="button" title="Sửa màu sắc"
                                            data-id="{{ $item->mau_sac_ngoi_hai_co_ct_id }}" 
                                            data-pid="{{ $item->ngoi_hai_co_ct_id }}" 
                                            data-name="{{ $item->name }}" 
                                            data-code="{{ $item->code }}" 
                                            data-price="{{ $item->price }}" 
                                            data-img="{{ asset('storage/' . $item->image) }}"
                                            onclick="openEditModal(this, '{{ route('admin.mau-sac-ngoi-hai-co-ct.update', $item->mau_sac_ngoi_hai_co_ct_id) }}')" 
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button type="button" title="Ẩn màu sắc"
                                            onclick="openDeleteModal('{{ route('admin.mau-sac-ngoi-hai-co-ct.destroy', $item->mau_sac_ngoi_hai_co_ct_id) }}')" 
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('admin.mau-sac-ngoi-hai-co-ct.restore', $item->mau_sac_ngoi_hai_co_ct_id) }}" class="inline">
                                            @csrf @method('PUT')
                                            <button class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 hover:bg-green-100 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                                Khôi phục
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-gray-500">
                                @if(isset($selectedProduct))
                                    Sản phẩm này chưa có biến thể màu sắc nào.
                                @else
                                    Hệ thống chưa có biến thể màu sắc nào.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="editModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Sửa Màu Sắc (SKU)
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ảnh</label>
                        <div class="aspect-square w-full rounded-xl border-2 border-dashed border-gray-300 relative group flex items-center justify-center bg-gray-50 overflow-hidden">
                            <img id="preview-edit" src="" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-xs font-medium px-3 py-1.5 bg-black/50 rounded-lg">Đổi ảnh</span>
                            </div>
                            <input type="file" name="image" accept="image/*" onchange="previewImage(event, 'preview-edit')" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        </div>
                    </div>
                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sản phẩm cha <span class="text-red-500">*</span></label>
                            {{-- Field này bị khóa (disabled) để user không được đổi SP cha lúc sửa --}}
                            <input type="hidden" name="ngoi_hai_co_ct_id" id="edit_pid_hidden">
                            <select id="edit_pid" disabled class="w-full px-4 py-2.5 text-sm font-medium border rounded-lg outline-none bg-gray-100 text-gray-500 cursor-not-allowed">
                                @foreach($products as $prod) 
                                    <option value="{{ $prod->ngoi_hai_co_ct_id }}">{{ $prod->name }}</option> 
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tên màu <span class="text-red-500">*</span></label>
                            <input type="text" id="edit_name" name="name" required class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Mã Code <span class="text-red-500">*</span></label>
                                <input type="text" id="edit_code" name="code" required class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none font-mono focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Giá bán <span class="text-red-500">*</span></label>
                                <input type="number" id="edit_price" name="price" required min="0" class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-gray-100">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy bỏ</button>
                    <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors">Lưu cập nhật</button>
                </div>
            </form>
        </div>
    </div>
    
    {{-- MODAL DELETE --}}
    <div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center transform scale-95 transition-transform duration-300">
            <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Tạm ẩn biến thể?</h3>
            <p class="text-sm text-gray-500 mb-6">Màu sắc này sẽ không còn hiển thị trên Website, nhưng bạn vẫn có thể khôi phục sau.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-sm transition-colors">Đồng ý</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewImage(event, targetId) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById(targetId).src = URL.createObjectURL(file);
            }
        }

        const editModal = document.getElementById('editModal');
        const editModalInner = editModal.querySelector('.bg-white');

        function openEditModal(btn, actionUrl) {
            document.getElementById('editForm').action = actionUrl;
            
            // Gán data cho các fields edit
            document.getElementById('edit_pid').value = btn.getAttribute('data-pid');
            document.getElementById('edit_pid_hidden').value = btn.getAttribute('data-pid'); // Field ẩn để submit
            
            document.getElementById('edit_name').value = btn.getAttribute('data-name');
            document.getElementById('edit_code').value = btn.getAttribute('data-code');
            document.getElementById('edit_price').value = btn.getAttribute('data-price');
            document.getElementById('preview-edit').src = btn.getAttribute('data-img');
            
            editModal.classList.remove('hidden'); 
            editModal.classList.add('flex');
            void editModal.offsetWidth; // Trigger reflow
            editModal.classList.remove('opacity-0'); 
            editModalInner.classList.remove('scale-95');
        }

        function closeEditModal() {
            editModal.classList.add('opacity-0'); 
            editModalInner.classList.add('scale-95');
            setTimeout(() => { 
                editModal.classList.add('hidden'); 
                editModal.classList.remove('flex'); 
            }, 300);
        }

        const deleteModal = document.getElementById('deleteModal');
        const deleteModalInner = deleteModal.querySelector('.bg-white');

        function openDeleteModal(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            deleteModal.classList.remove('hidden'); 
            deleteModal.classList.add('flex');
            void deleteModal.offsetWidth; // Trigger reflow
            deleteModal.classList.remove('opacity-0'); 
            deleteModalInner.classList.remove('scale-95');
        }

        function closeDeleteModal() {
            deleteModal.classList.add('opacity-0'); 
            deleteModalInner.classList.add('scale-95');
            setTimeout(() => { 
                deleteModal.classList.add('hidden'); 
                deleteModal.classList.remove('flex'); 
            }, 300);
        }
    </script>
    @endpush
</x-admin.layout.app>