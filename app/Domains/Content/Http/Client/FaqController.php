<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Services\FaqPageService;
use App\Domains\Content\Services\FaqService;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function index(FaqPageService $pageService, FaqService $faqService)
    {
        return view('clients.faq.index', [
            'faqPage' => $pageService->getFirstRecord(),
            'faqsGrouped' => $faqService->getGroupedByCategory(),
        ]);
    }
}
