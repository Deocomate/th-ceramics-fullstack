<?php

namespace App\View\Components\Client\Content\Home;

use App\Domains\Content\Infrastructure\Services\AwardAchievementService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class AwardsDeck extends Component
{
    public Collection $renderAwards;

    public function __construct(
        AwardAchievementService $awardAchievementService,
        mixed $awards = null,
    ) {
        $awardItems = $awards !== null
            ? collect($awards)->values()
            : $awardAchievementService->getAll()->values();

        $this->renderAwards = collect($awardItems->all());

        while ($this->renderAwards->count() > 0 && $this->renderAwards->count() < 12) {
            $this->renderAwards = $this->renderAwards->concat($awardItems)->values();
        }
    }

    public function render(): View
    {
        return view('components.client.content.home.awards-deck');
    }
}
