<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\FaqPageService;
use App\Services\FaqService;

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
