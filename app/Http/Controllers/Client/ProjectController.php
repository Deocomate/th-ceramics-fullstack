<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DanhMucDuAn;
use App\Models\DuAn;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $categories = DanhMucDuAn::where('is_delete', 0)->get();

        $categorySlug = $request->query('category');
        $query = DuAn::with('danhMuc');

        if ($categorySlug) {
            $matchedCategory = $categories->first(fn ($cat) => Str::slug($cat->ten_danh_muc) === $categorySlug);

            if ($matchedCategory) {
                $query->where('danh_muc_du_an_id', $matchedCategory->danh_muc_du_an_id);
            }
        }

        $projects = $query->latest()->paginate(8)->appends($request->query());

        return view('clients.projects.index', compact('categories', 'projects'));
    }

    public function detail($slug)
    {
        $project = DuAn::where('slug', $slug)->with('danhMuc')->firstOrFail();

        $relatedProjects = DuAn::where('danh_muc_du_an_id', $project->danh_muc_du_an_id)
            ->where('du_an_id', '!=', $project->du_an_id)
            ->latest()
            ->limit(4)
            ->get();

        return view('clients.projects.detail', compact('project', 'relatedProjects'));
    }
}
