<x-admin.layouts.app title="Sao lưu nội dung" breadcrumb="Sao lưu nội dung">
    <div class="max-w-5xl space-y-6">
        <div class="bg-white rounded-lg border p-6 space-y-4">
            <h1 class="text-xl font-semibold">Xuất và nhập nội dung website</h1>
            <p class="text-sm text-gray-600">ZIP gồm nội dung, media và SQL. Tài khoản, đơn hàng, mã giảm giá và yêu cầu tư vấn không nằm trong bản xuất.</p>
            @if ($errors->any())
                <div class="text-red-700 text-sm">{{ $errors->first() }}</div>
            @endif
            @if (session('success'))
                <div class="text-green-700 text-sm">{{ session('success') }}</div>
            @endif
            <form method="post" action="{{ route('admin.content-archive.export') }}">
                @csrf
                <button class="px-4 py-2 rounded bg-red-800 text-white">Tạo ZIP mới</button>
            </form>
        </div>

        <div class="bg-white rounded-lg border p-6 space-y-3">
            <h2 class="font-semibold">Nhập ZIP</h2>
            <form method="post" action="{{ route('admin.content-archive.upload') }}" enctype="multipart/form-data" class="flex gap-3 items-center">
                @csrf
                <input type="file" name="archive" accept=".zip,application/zip" required>
                <button class="px-4 py-2 rounded bg-gray-800 text-white">Tải lên và xem trước</button>
            </form>
            @if ($preview)
                <div class="text-sm space-y-2">
                    <p>Thêm: {{ $preview['add'] }} · Cập nhật: {{ $preview['update'] }} · Không đổi: {{ $preview['unchanged'] }} · Xung đột: {{ $preview['conflict'] }}</p>
                    @if (! empty($preview['conflicts']))
                        <details class="text-amber-800"><summary>Xem các bản ghi xung đột (tối đa 100)</summary>
                            <ul class="list-disc pl-5">@foreach ($preview['conflicts'] as $conflict)
                                <li>{{ $conflict['table'] }} #{{ $conflict['id'] }}:
                                    {{ match ($conflict['reason']) {
                                        'destination_newer' => 'Bản đích mới hơn',
                                        'different_source_same_id' => 'Trùng ID từ nguồn khác',
                                        'unmapped_parent' => 'Bản ghi cha bị xung đột hoặc chưa được nhập',
                                        'unmapped_legacy_record' => 'Bản ghi gốc bị xung đột',
                                        'duplicate_slug' => 'Slug đã thuộc bản ghi khác',
                                        'duplicate_sku', 'duplicate_code' => 'Mã hàng đã thuộc bản ghi khác',
                                        default => 'Xung đột dữ liệu',
                                    } }}
                                </li>
                            @endforeach</ul>
                        </details>
                    @endif
                    @if (! empty($preview['missing_media']))
                        <p class="text-red-700">ZIP thiếu {{ count($preview['missing_media']) }} file media được tham chiếu. Cần tạo lại ZIP trước khi nhập.</p>
                        <ul class="list-disc pl-5 text-red-700">@foreach (array_slice($preview['missing_media'], 0, 20) as $missing)
                            <li>{{ $missing }}</li>
                        @endforeach</ul>
                    @endif
                    <p>Xuất lúc: {{ $preview['manifest']['exported_at_utc'] }}</p>
                    @if (empty($preview['missing_media']))
                        <form method="post" action="{{ route('admin.content-archive.apply') }}">
                            @csrf
                            <button class="px-4 py-2 rounded bg-red-800 text-white">Xác nhận nhập</button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg border p-6 space-y-2">
            <h2 class="font-semibold">Bản xuất đã tạo</h2>
            @forelse ($files as $file)
                <div class="flex justify-between text-sm border-b py-2">
                    <span>{{ $file->getFilename() }} · {{ number_format($file->getSize() / 1048576, 1) }} MB</span>
                    <a class="text-red-800" href="{{ route('admin.content-archive.download', $file->getFilename()) }}">Tải xuống</a>
                </div>
            @empty
                <p class="text-sm text-gray-500">Chưa có bản xuất.</p>
            @endforelse
        </div>
        <div class="bg-white rounded-lg border p-6 space-y-2">
            <h2 class="font-semibold">Tác vụ gần đây</h2>
            @foreach ($statuses as $status)
                <p class="text-sm">{{ $status['state'] ?? '' }} · {{ is_array($status['result'] ?? null) ? json_encode($status['result']) : ($status['result'] ?? $status['error'] ?? $status['action'] ?? '') }}</p>
            @endforeach
        </div>
    </div>
</x-admin.layouts.app>
