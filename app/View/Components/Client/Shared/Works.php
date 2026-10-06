<?php

namespace App\View\Components\Client\Shared;

use App\Domains\Content\Models\DuAn;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Works extends Component
{
    public Collection $works;

    public function __construct($projects = null)
    {
        if ($projects !== null) {
            $this->works = $projects instanceof Collection ? $projects : collect($projects);
        } else {
            $this->works = DuAn::query()->latest()->take(6)->get();
        }
    }

    public function render(): View
    {
        return view('components.client.content.shared.works');
    }
}
