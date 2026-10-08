<?php

namespace App\View\Components\Client\Shared;

use App\Domains\Content\Models\DuAn;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class WorksSimple extends Component
{
    public Collection $works;

    public bool $showNav;

    public function __construct($projects = null, bool $showNav = false)
    {
        if ($projects !== null) {
            $this->works = $projects instanceof Collection ? $projects : collect($projects);
        } else {
            $this->works = DuAn::query()->latest()->take(6)->get();
        }
        $this->showNav = $showNav;
    }

    public function render(): View
    {
        return view('components.client.content.shared.works-simple');
    }
}
