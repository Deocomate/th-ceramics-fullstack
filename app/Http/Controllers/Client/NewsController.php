<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DanhMucTinTuc;
use App\Models\TinTuc;
use App\Services\ViewHistoryService;
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
            $currentCategory = DanhMucTinTuc::query()
                ->where('is_delete', false)
                ->findOrFail($categoryId);

            $news = TinTuc::query()
                ->with('danhMuc')
                ->where('danh_muc_tin_tuc_id', $currentCategory->danh_muc_tin_tuc_id)
                ->whereIn('trang_thai', ['published', 'active'])
                ->latest('ngay_dang')
                ->paginate(10)
                ->withQueryString();
        } else {
            $categoriesWithNews = DanhMucTinTuc::query()
                ->where('is_delete', false)
                ->with(['tinTucs' => function ($query) {
                    $query->with('danhMuc')
                        ->whereIn('trang_thai', ['published', 'active'])
                        ->latest('ngay_dang')
                        ->limit(2);
                }])
                ->orderBy('ten_danh_muc')
                ->get()
                ->filter(fn (DanhMucTinTuc $category) => $category->tinTucs->isNotEmpty())
                ->values();
        }

        $recentArticles = $historyService->recentArticles(3);
        $recentProducts = $historyService->recentProducts(4);

        return view('clients.news.index', compact(
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
        $article = TinTuc::query()
            ->with('danhMuc')
            ->where('slug', $slug)
            ->whereIn('trang_thai', ['published', 'active'])
            ->firstOrFail();

        $relatedNews = TinTuc::query()
            ->with('danhMuc')
            ->where('danh_muc_tin_tuc_id', $article->danh_muc_tin_tuc_id)
            ->where('tin_tuc_id', '!=', $article->tin_tuc_id)
            ->whereIn('trang_thai', ['published', 'active'])
            ->latest('ngay_dang')
            ->take(4)
            ->get();

        $historyService->trackArticle($article->tin_tuc_id);

        return view('clients.news.detail', compact('article', 'relatedNews'));
    }
}
