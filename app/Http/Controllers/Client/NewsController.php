<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DanhMucTinTuc;
use App\Models\TacGia;
use App\Models\TinTuc;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $categories = DanhMucTinTuc::query()
            ->where('is_delete', false)
            ->orderBy('ten_danh_muc')
            ->get();

        $newsQuery = TinTuc::query()
            ->with('danhMuc')
            ->whereHas('danhMuc', fn (Builder $query) => $query->where('is_delete', false))
            ->whereIn('trang_thai', ['published', 'active']);

        if ($request->filled('category')) {
            $newsQuery->where('danh_muc_tin_tuc_id', (int) $request->integer('category'));
        }

        $featuredNews = (clone $newsQuery)
            ->latest('ngay_dang')
            ->take(3)
            ->get();

        $news = $newsQuery
            ->latest('ngay_dang')
            ->paginate(9)
            ->withQueryString();

        return view('clients.news.index', compact('categories', 'featuredNews', 'news'));
    }

    public function detail(string $slug): View
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

        $author = TacGia::query()->latest()->first();

        return view('clients.news.detail', compact('article', 'relatedNews', 'author'));
    }
}
