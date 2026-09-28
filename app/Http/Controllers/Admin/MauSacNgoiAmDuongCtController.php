<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Catalog\Models\ProductDisplayOption;
use App\Domains\Catalog\ProductWriter;
use App\Domains\Media\FileUploadHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MauSacNgoiAmDuongCtController extends Controller
{
    private const TYPE_KEY = 'ngoi_am_duong_ct';
    private const IMAGE_DIR = 'ngoi_am_duong_ct/colors';

    public function __construct(private readonly ProductWriter $writer) {}

    public function index(): View
    {
        $mauSacs = ProductDisplayOption::where('type_key', self::TYPE_KEY)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.mau-sac-ngoi-am-duong-ct.index', compact('mauSacs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = FileUploadHelper::upload($request->file('image'), self::IMAGE_DIR);
        }

        $this->writer->saveDisplayOption(self::TYPE_KEY, $data);

        return back()->with('success', 'Thêm màu sắc thành công.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $option = ProductDisplayOption::findByPublicId(self::TYPE_KEY, $id);
        abort_if(! $option, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($request->hasFile('image')) {
            if ($option->image) {
                FileUploadHelper::delete($option->image);
            }
            $data['image'] = FileUploadHelper::upload($request->file('image'), self::IMAGE_DIR);
        }

        $this->writer->saveDisplayOption(self::TYPE_KEY, $data, $option);

        return back()->with('success', 'Cập nhật màu sắc thành công.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $option = ProductDisplayOption::findByPublicId(self::TYPE_KEY, $id);
        abort_if(! $option, 404);

        if ($option->image) {
            FileUploadHelper::delete($option->image);
        }

        $this->writer->deleteDisplayOption($option);

        return back()->with('success', 'Xóa màu sắc thành công.');
    }
}
