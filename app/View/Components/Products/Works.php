<?php

namespace App\View\Components\Products;

use App\Models\DuAn;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Works extends Component
{
    public Collection $works;

    public function __construct($projects = null)
    {
        $this->works = $projects instanceof Collection
            ? $projects
            : DuAn::query()->latest()->take(6)->get();
    }

    public function render(): View
    {
        return view('components.products.works');
    }
}
