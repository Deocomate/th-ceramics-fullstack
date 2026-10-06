@props([
    'deleteTitle' => 'Tạm ẩn sản phẩm?',
    'deleteMessage' => 'Sản phẩm sẽ được đưa vào danh sách đã ẩn. Bạn vẫn có thể khôi phục lại bất cứ lúc nào.',
    'deleteConfirmText' => 'Đồng ý',
    'restoreTitle' => 'Khôi phục sản phẩm?',
    'restoreMessage' => 'Sản phẩm này sẽ xuất hiện lại trên Website.',
])

{{-- MODAL XÓA SẢN PHẨM --}}
<div id="deleteModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
        <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $deleteTitle }}</h3>
        @if(isset($deleteAlert))
            {{ $deleteAlert }}
        @else
            <p class="text-sm text-gray-500 mb-6">{!! $deleteMessage !!}</p>
        @endif
        <div class="flex justify-center gap-3">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm">{{ $deleteConfirmText }}</button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL KHÔI PHỤC SẢN PHẨM --}}
<div id="restoreModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm px-4 opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden transform scale-95 transition-transform duration-300 p-6 text-center">
        <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $restoreTitle }}</h3>
        <p class="text-sm text-gray-500 mb-6">{!! $restoreMessage !!}</p>
        <div class="flex justify-center gap-3">
            <button type="button" onclick="closeRestoreModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">Hủy</button>
            <form id="restoreForm" method="POST" class="flex-1">
                @csrf @method('PUT')
                <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors shadow-sm">Khôi phục</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function() {
        const deleteModal = document.getElementById('deleteModal');
        const deleteModalInner = deleteModal ? deleteModal.querySelector('.bg-white') : null;
        window.openDeleteModal = function(actionUrl) {
            const form = document.getElementById('deleteForm');
            if (form) form.action = actionUrl;
            if (deleteModal) {
                deleteModal.classList.remove('hidden'); deleteModal.classList.add('flex');
                void deleteModal.offsetWidth;
                deleteModal.classList.remove('opacity-0');
                if (deleteModalInner) deleteModalInner.classList.remove('scale-95');
            }
        };
        window.closeDeleteModal = function() {
            if (!deleteModal) return;
            deleteModal.classList.add('opacity-0');
            if (deleteModalInner) deleteModalInner.classList.add('scale-95');
            setTimeout(() => { deleteModal.classList.add('hidden'); deleteModal.classList.remove('flex'); }, 300);
        };

        const restoreModal = document.getElementById('restoreModal');
        const restoreModalInner = restoreModal ? restoreModal.querySelector('.bg-white') : null;
        window.openRestoreModal = function(actionUrl) {
            const form = document.getElementById('restoreForm');
            if (form) form.action = actionUrl;
            if (restoreModal) {
                restoreModal.classList.remove('hidden'); restoreModal.classList.add('flex');
                void restoreModal.offsetWidth;
                restoreModal.classList.remove('opacity-0');
                if (restoreModalInner) restoreModalInner.classList.remove('scale-95');
            }
        };
        window.closeRestoreModal = function() {
            if (!restoreModal) return;
            restoreModal.classList.add('opacity-0');
            if (restoreModalInner) restoreModalInner.classList.add('scale-95');
            setTimeout(() => { restoreModal.classList.add('hidden'); restoreModal.classList.remove('flex'); }, 300);
        };
    })();
</script>
@endpush
