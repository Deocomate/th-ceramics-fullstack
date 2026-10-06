<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Infrastructure\Models\NewsArticle;
use App\Domains\Content\Infrastructure\Models\NewsCategory;
use App\Http\Controllers\Controller;
use App\Infrastructure\ViewHistoryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request, ViewHistoryService $historyService): View
    {
        $categoryId = $request->integer('category');
        $currentCategory = null;
        $categoriesWithNews = collect();
        $news = null;

        if ($categoryId > 0) {
            $currentCategory = NewsCategory::query()
                ->where('is_delete', false)
                ->findOrFail($categoryId);

            $news = NewsArticle::query()
                ->with('danhMuc')
                ->where('danh_muc_tin_tuc_id', $currentCategory->danh_muc_tin_tuc_id)
                ->whereIn('trang_thai', ['published', 'active'])
                ->latest('ngay_dang')
                ->paginate(10)
                ->withQueryString();
        } else {
            $categoriesWithNews = NewsCategory::query()
                ->where('is_delete', false)
                ->with(['tinTucs' => function ($query) {
                    $query->with('danhMuc')
                        ->whereIn('trang_thai', ['published', 'active'])
                        ->latest('ngay_dang')
                        ->limit(2);
                }])
                ->orderBy('ten_danh_muc')
                ->get()
                ->filter(fn (NewsCategory $category) => $category->tinTucs->isNotEmpty())
                ->values();
        }

        $recentArticles = $historyService->recentArticles(3);
        if ($recentArticles->isEmpty()) {
            $recentArticles = $historyService->defaultArticles(3);
        }

        $recentProducts = $historyService->recentProducts(4);
        if ($recentProducts->isEmpty()) {
            $recentProducts = $historyService->defaultProducts(4);
        }

        return view('clients.content.news.index', compact(
            'categoryId',
            'currentCategory',
            'categoriesWithNews',
            'news',
            'recentArticles',
            'recentProducts'
        ));
    }

    public function detail(string $slug, ViewHistoryService $historyService): View
    {
        $article = NewsArticle::query()
            ->with('danhMuc')
            ->where('slug', $slug)
            ->whereIn('trang_thai', ['published', 'active'])
            ->firstOrFail();

        $relatedNews = NewsArticle::query()
            ->with('danhMuc')
            ->where('danh_muc_tin_tuc_id', $article->danh_muc_tin_tuc_id)
            ->where('tin_tuc_id', '!=', $article->tin_tuc_id)
            ->whereIn('trang_thai', ['published', 'active'])
            ->latest('ngay_dang')
            ->paginate(9, ['*'], 'related_page')
            ->withQueryString();

        $historyService->trackArticle($article->tin_tuc_id);

        return view('clients.content.news.detail', compact('article', 'relatedNews'));
    }
}
