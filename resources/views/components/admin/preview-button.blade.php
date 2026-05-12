@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\View;

    $previewUrl = null;

    if (View::hasSection('preview_url')) {
        $previewUrl = trim(View::getSection('preview_url'));
    } else {
        $currentRoute = request()->route() ? request()->route()->getName() : '';

        $routeMap = [
            'admin.trang_chu.' => 'client.home',
            'admin.pages.ve_chung_toi.' => 'client.about',
            'admin.pages.factory.' => 'client.factory',
            'admin.pages.contact.' => 'client.contact',
            'admin.pages.faq' => 'client.faq',
            'admin.ngoi-am-duong' => 'client.products.ngoi-am-duong.index',
            'admin.mau-sac-ngoi-am-duong' => 'client.products.ngoi-am-duong.index',
            'admin.dinh-muc-ngoi-am-duong' => 'client.products.ngoi-am-duong.index',
            'admin.ngoi-hai-van-mieu' => 'client.products.ngoi-hai-van-mieu.index',
            'admin.mau-sac-ngoi-hai-van-mieu' => 'client.products.ngoi-hai-van-mieu.index',
            'admin.dinh-muc-ngoi-hai-van-mieu' => 'client.products.ngoi-hai-van-mieu.index',
            'admin.ngoi-hai-co' => 'client.products.ngoi-hai-van-mieu.index',
            'admin.mau-sac-ngoi-hai-co' => 'client.products.ngoi-hai-van-mieu.index',
            'admin.dinh-muc-ngoi-hai-co' => 'client.products.ngoi-hai-van-mieu.index',
            'admin.gach-hoa-thong-gio' => 'client.products.gach-hoa-thong-gio.index',
            'admin.dinh-muc-gach-hoa-thong-gio' => 'client.products.gach-hoa-thong-gio.index',
            'admin.gach-trang-tri' => 'client.products.gach-trang-tri.index',
            'admin.dinh-muc-gach-trang-tri' => 'client.products.gach-trang-tri.index',
            'admin.gach-co-bat-trang' => 'client.products.gach-co-bat-trang.index',
            'admin.dinh-muc-gach-co-bat-trang' => 'client.products.gach-co-bat-trang.index',
            'admin.phu-kien-ngoi' => 'client.products.phu-kien-ngoi.index',
            'admin.ngoi-bo-noc' => 'client.products.phu-kien-ngoi.index',
            'admin.phan-loai-ngoi-bo-noc' => 'client.products.phu-kien-ngoi.index',
            'admin.bo-noc-chu-van' => 'client.products.phu-kien-ngoi.index',
            'admin.phan-loai-bo-noc-chu-van' => 'client.products.phu-kien-ngoi.index',
            'admin.lan-can-gom-xu' => 'client.products.lan-can-gom-su.index',
            'admin.linh-vat-phong-thuy' => 'client.products.linh-vat-phong-thuy.index',
            'admin.den-gom-su' => 'client.products.den-gom-su.index',
            'admin.tin-tuc' => 'client.news.index',
            'admin.danh-muc-tin-tuc' => 'client.news.index',
            'admin.du-an' => 'client.projects.index',
            'admin.danh-muc-du-an' => 'client.projects.index',
            'admin.catalog' => 'client.dich-vu.tai-catalog',
        ];

        foreach ($routeMap as $prefix => $clientRoute) {
            if (Str::startsWith($currentRoute, $prefix)) {
                $previewUrl = route($clientRoute);
                break;
            }
        }
    }
@endphp

<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3">
    @if($previewUrl)
        <a href="{{ $previewUrl }}" target="_blank"
           class="p-4 bg-slate-900 text-white rounded-full shadow-lg hover:bg-slate-800 transition-all hover:scale-110 group"
           title="Xem bản Preview phía Client">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </a>
    @else
        <button onclick="showPreviewToast()"
                class="p-4 bg-slate-400 text-white rounded-full shadow-lg cursor-not-allowed group"
                title="Không có bản Preview cho trang này">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243-4.243M9.878 9.878l4.242 4.242M9.878 9.878l-4.242 4.242" />
            </svg>
        </button>
    @endif

    {{-- Toast Notification --}}
    <div id="preview-toast"
         class="fixed bottom-20 right-6 z-[60] opacity-0 translate-y-4 pointer-events-none transition-all duration-300 ease-out bg-amber-100 border-l-4 border-amber-500 text-amber-800 p-4 rounded shadow-lg flex items-center gap-3 max-w-xs">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11 14a1 1 0 10-2 0 1 1 0 002 0z" clip-rule="evenodd" />
        </svg>
        <span class="text-sm font-medium">Đây là trang quản trị nội bộ, không có bản preview phía client.</span>
    </div>

    <script>
        function showPreviewToast() {
            const toast = document.getElementById('preview-toast');
            if (!toast) return;

            toast.classList.remove('opacity-0', 'translate-y-4');
            toast.classList.add('opacity-100', 'translate-y-0');

            setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'translate-y-4');
            }, 3000);
        }
    </script>
</div>
