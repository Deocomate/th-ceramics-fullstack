<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Infrastructure\Services\FaqPageConfigService;
use App\Domains\Content\Infrastructure\Services\FaqService;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(FaqPageConfigService $pageService, FaqService $faqService): View
    {
        return view('clients.content.faq.index', [
            'faqPage' => $pageService->getFirstRecord(),
            'faqsGrouped' => $faqService->getGroupedByCategory(),
        ]);
    }
}
