<?php

namespace App\Domains\Content\Http\Admin;

use App\Domains\Content\Infrastructure\Services\HomePageConfigService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingsController extends Controller
{
    public function __construct(private readonly HomePageConfigService $service) {}

    public function edit(): View
    {
        $settings = $this->service->getFirstRecord();

        return view('admin.content.site-settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'is_ecommerce_enabled' => ['sometimes', 'boolean'],
            'is_content_protection_enabled' => ['sometimes', 'boolean'],
            'is_devtools_guard_enabled' => ['sometimes', 'boolean'],
        ]);

        $this->service->updateSiteSettings($data);

        return back()->with('success', 'Cập nhật cài đặt website thành công.');
    }
}
