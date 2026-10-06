<?php

namespace App\Domains\Content\Http\Client;

use App\Domains\Content\Infrastructure\Models\Project;
use App\Domains\Content\Infrastructure\Models\ProjectCategory;
use App\Domains\Content\Infrastructure\Services\ProjectPageConfigService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectPageConfigService $pageConfigService) {}

    public function index(Request $request): View
    {
        $categories = ProjectCategory::where('is_delete', 0)->get();

        $categorySlug = $request->query('category');
        $query = Project::with('danhMuc');

        if ($categorySlug) {
            $matchedCategory = $categories->first(fn ($cat) => Str::slug($cat->ten_danh_muc) === $categorySlug);

            if ($matchedCategory) {
                $query->where('danh_muc_du_an_id', $matchedCategory->danh_muc_du_an_id);
            }
        }

        $projects = $query->latest()->paginate(8)->appends($request->query());
        $pageConfig = $this->pageConfigService->getFirstRecord();

        return view('clients.content.projects.index', compact('categories', 'projects', 'pageConfig'));
    }

    public function detail(string $slug): View
    {
        $project = Project::where('slug', $slug)->with('danhMuc')->firstOrFail();

        $relatedProjects = Project::where('danh_muc_du_an_id', $project->danh_muc_du_an_id)
            ->where('du_an_id', '!=', $project->du_an_id)
            ->latest()
            ->limit(4)
            ->get();

        return view('clients.content.projects.detail', compact('project', 'relatedProjects'));
    }
}
