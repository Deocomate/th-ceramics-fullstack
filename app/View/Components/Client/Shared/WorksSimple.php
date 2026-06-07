<?php

namespace App\View\Components\Client\Shared;

use App\Models\DuAn;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class WorksSimple extends Component
{
    public Collection $works;

    public bool $showNav;

    public function __construct($projects = null, bool $showNav = false)
    {
        $this->works = $projects instanceof Collection
            ? $projects
            : DuAn::query()->latest()->take(6)->get();
        $this->showNav = $showNav;
    }

    public function render(): View
    {
        return view('components.client.shared.works-simple');
    }
}
