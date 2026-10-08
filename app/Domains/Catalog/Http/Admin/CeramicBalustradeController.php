<?php

namespace App\Domains\Catalog\Http\Admin;

use App\Domains\Catalog\Infrastructure\Services\CeramicBalustradeService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CeramicBalustradeController extends Controller
{
    public function __construct(private readonly CeramicBalustradeService $service) {}

    public function index(): View
    {
        $lanCanGomXu = $this->service->getFirstRecord();

        return view('admin.catalog.lan-can-gom-xu.edit', compact('lanCanGomXu'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'thumbnail_main' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title1' => ['required', 'string', 'max:50'],
            'thumbnail1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title2' => ['required', 'string', 'max:50'],
            'thumbnail2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title3' => ['required', 'string', 'max:50'],
            'thumbnail3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'video' => ['nullable', 'string', 'max:500'],
        ]);

        $this->service->update($data);

        return back()->with('success', 'Cập nhật thông tin Lan Can Gốm Sứ thành công.');
    }
}
