<?php

namespace App\Domains\Archive\Http;

use App\Domains\Archive\ContentArchiveService;
use App\Domains\Archive\Jobs\ContentArchiveJob;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ContentArchiveController extends Controller
{
    public function index(Request $request, ContentArchiveService $archive): View
    {
        $files = collect(File::files($archive->directory()))
            ->filter(fn ($file) => preg_match('/^database-\d{8}-\d{6}[+-]\d{4}\.zip$/', $file->getFilename()))
            ->sortByDesc(fn ($file) => $file->getMTime());
        $statuses = collect(File::files($archive->directory()))
            ->filter(fn ($file) => str_starts_with($file->getFilename(), 'status-'))
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->take(10)
            ->map(fn ($file) => json_decode((string) file_get_contents($file->getPathname()), true))
            ->filter();

        return view('admin.content-archive.index', [
            'files' => $files,
            'statuses' => $statuses,
            'preview' => $request->session()->get('content_archive_preview'),
        ]);
    }

    public function export(ContentArchiveService $archive): RedirectResponse
    {
        $this->dispatchJob($archive, 'export');

        return back()->with('success', 'Đang tạo ZIP. Tải lại trang để xem trạng thái.');
    }

    public function upload(Request $request, ContentArchiveService $archive): RedirectResponse
    {
        $request->validate(['archive' => ['required', 'file', 'mimes:zip', 'max:102400']]);
        $name = 'import-'.Str::random(32).'.zip';
        $request->file('archive')->move($archive->directory(), $name);
        $path = $archive->directory().DIRECTORY_SEPARATOR.$name;
        try {
            $report = $archive->preview($path);
        } catch (Throwable $error) {
            @unlink($path);

            return back()->withErrors(['archive' => $error->getMessage()]);
        }
        $request->session()->put('content_archive_file', $name);
        $request->session()->put('content_archive_preview', $report);

        return back()->with('success', 'Đã kiểm tra ZIP. Xem báo cáo trước khi nhập.');
    }

    public function apply(Request $request, ContentArchiveService $archive): RedirectResponse
    {
        $name = $request->session()->get('content_archive_file');
        if (! is_string($name) || ! preg_match('/^import-[A-Za-z0-9]{32}\.zip$/', $name)) {
            return back()->withErrors(['archive' => 'Chưa có ZIP đã xem trước.']);
        }
        $path = $archive->directory().DIRECTORY_SEPARATOR.$name;
        if (! is_file($path)) {
            return back()->withErrors(['archive' => 'ZIP đã tải lên không còn tồn tại.']);
        }
        $request->session()->forget(['content_archive_file', 'content_archive_preview']);
        $this->dispatchJob($archive, 'import', $path);

        return back()->with('success', 'Đang nhập nội dung. Tải lại trang để xem trạng thái.');
    }

    public function download(string $name, ContentArchiveService $archive): BinaryFileResponse
    {
        abort_unless(preg_match('/^database-\d{8}-\d{6}[+-]\d{4}\.zip$/', $name), 404);
        $path = $archive->directory().DIRECTORY_SEPARATOR.$name;
        abort_unless(is_file($path), 404);

        return response()->download($path, $name);
    }

    private function dispatchJob(ContentArchiveService $archive, string $action, ?string $file = null): void
    {
        $statusPath = $archive->directory().DIRECTORY_SEPARATOR.'status-'.Str::random(24).'.json';
        file_put_contents($statusPath, json_encode(['state' => 'pending', 'action' => $action]));
        ContentArchiveJob::dispatch($action, $statusPath, $file);
    }
}
