<?php

namespace App\Domains\Content\Http\Admin;

use App\Domains\Content\Http\Requests\FactoryPageRequest;
use App\Domains\Content\Infrastructure\Services\FactoryPageConfigService;
use App\Http\Controllers\Controller;
use App\Support\AssetPath;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FactoryPageController extends Controller
{
    public function __construct(private readonly FactoryPageConfigService $service) {}

    public function edit(): View
    {
        $factory = $this->service->getFirstRecord();
        $g1 = is_string($factory->gallery_1) ? json_decode($factory->gallery_1, true) : $factory->gallery_1;
        $g2 = is_string($factory->gallery_2) ? json_decode($factory->gallery_2, true) : $factory->gallery_2;
        $ps = is_string($factory->process_slider) ? json_decode($factory->process_slider, true) : $factory->process_slider;
        $ms = is_string($factory->material_slider) ? json_decode($factory->material_slider, true) : $factory->material_slider;
        $mst = is_string($factory->material_steps) ? json_decode($factory->material_steps, true) : $factory->material_steps;

        $factoryJson = [
            'gallery_1' => is_array($g1) ? $g1 : [],
            'gallery_2' => is_array($g2) ? $g2 : [],
            'process_slider' => is_array($ps) ? $ps : [],
            'material_slider' => is_array($ms) ? $ms : [],
            'material_steps' => is_array($mst) ? $mst : [],
            'intro_description' => old('intro_description', $factory->intro_description),
            'process_description' => old('process_description', $factory->process_description),
            'process_bottom_desc' => old('process_bottom_desc', $factory->process_bottom_desc),
        ];

        return view('admin.content.pages.factory.edit', [
            'factory' => $factory,
            'factoryJson' => $factoryJson,
            'heroDesktopUrl' => $factory->hero_banner_desktop ? AssetPath::url($factory->hero_banner_desktop) : '',
            'heroMobileUrl' => $factory->hero_banner_mobile ? AssetPath::url($factory->hero_banner_mobile) : '',
            'processBottomUrl' => $factory->process_bottom_image ? AssetPath::url($factory->process_bottom_image) : '',
        ]);
    }

    public function update(FactoryPageRequest $request): RedirectResponse
    {
        $this->service->update($request->validated());

        return back()->with('success', 'Cập nhật trang Xưởng sản xuất thành công.');
    }
}
