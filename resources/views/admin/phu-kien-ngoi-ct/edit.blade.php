@section('preview_url', $product->category_type === \App\Models\PhuKienNgoiCt::TYPE_CHU_VAN
    ? route('client.products.phu-kien-ngoi.bo-noc-chu-van.detail', $product->phu_kien_ngoi_ct_id)
    : route('client.products.phu-kien-ngoi.ngoi-bo-noc.detail', $product->phu_kien_ngoi_ct_id))

<x-admin.layout.app title="Cập nhật {{ $categoryLabel }}" breadcrumb="Admin › Phụ Kiện Ngói › Chỉnh sửa">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Cập nhật Sản Phẩm: {{ $product->name }}</h2>
        </div>
        <form method="POST" action="{{ route('admin.phu-kien-ngoi-ct.update', $product->phu_kien_ngoi_ct_id) }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="category_type" value="{{ $categoryType }}">

            @include('admin.phu-kien-ngoi-ct.partials.form', ['product' => $product, 'categoryType' => $categoryType])

            <div class="pt-6 mt-8 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.phu-kien-ngoi-ct.index', ['category_type' => $categoryType]) }}" class="px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy bỏ</a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white rounded-lg shadow-sm transition-colors" style="background:#A31D1D;">Lưu Thay Đổi</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Hình ảnh hiện tại</h2>
            @if(is_array($product->images))
                <span class="text-xs font-medium text-gray-500">Đang có {{ count($product->images) }} ảnh</span>
            @endif
        </div>
        <div class="p-6">
            @if(is_array($product->images) && count($product->images) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($product->images as $path)
                        <div class="relative group aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm bg-gray-100">
                            <img src="{{ \App\Support\AssetPath::url($path) }}" class="w-full h-full object-contain" alt="">
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                <button type="button" onclick="openDeleteImageModal('{{ $path }}')" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">Xóa ảnh này</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm text-center py-6">Sản phẩm này chưa có hình ảnh nào.</p>
            @endif
        </div>
    </div>

    <div id="deleteImageModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-2">Xác nhận xóa?</h3>
            <p class="text-sm text-gray-500 mb-6">Ảnh này sẽ bị xóa khỏi danh sách ảnh của sản phẩm.</p>
            <div class="flex justify-center gap-3">
                <button type="button" onclick="closeDeleteImageModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
                <form id="deleteImageForm" method="POST" action="{{ route('admin.phu-kien-ngoi-ct.image.destroy', $product->phu_kien_ngoi_ct_id) }}" class="flex-1">
                    @csrf @method('DELETE')
                    <input type="hidden" name="image_path" id="deleteImagePathInput" value="">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">Có, Xóa</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const deleteImageModal = document.getElementById('deleteImageModal');
        const deleteImageModalInner = deleteImageModal.querySelector('.bg-white');
        function openDeleteImageModal(imagePath) {
            document.getElementById('deleteImagePathInput').value = imagePath;
            deleteImageModal.classList.remove('hidden'); deleteImageModal.classList.add('flex');
            void deleteImageModal.offsetWidth;
            deleteImageModal.classList.remove('opacity-0'); deleteImageModalInner.classList.remove('scale-95');
        }
        function closeDeleteImageModal() {
            deleteImageModal.classList.add('opacity-0'); deleteImageModalInner.classList.add('scale-95');
            setTimeout(() => { deleteImageModal.classList.add('hidden'); deleteImageModal.classList.remove('flex'); }, 300);
        }
    </script>
    @endpush
</x-admin.layout.app>
