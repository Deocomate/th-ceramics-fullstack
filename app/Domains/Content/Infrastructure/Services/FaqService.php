<?php

namespace App\Domains\Content\Infrastructure\Services;

use App\Domains\Content\Infrastructure\Models\Faq;
use Illuminate\Database\Eloquent\Collection;

class FaqService
{
    /** @return Collection<int, Faq> */
    public function getAll(): Collection
    {
        return Faq::query()
            ->where('is_delete', 0)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();
    }

    /** @return \Illuminate\Support\Collection<string, Collection<int, Faq>> */
    public function getGroupedByCategory(): \Illuminate\Support\Collection
    {
        return $this->getAll()->groupBy('category');
    }

    /** @param array<string, mixed> $data */
    public function store(array $data): Faq
    {
        return Faq::create($data);
    }

    /** @param array<string, mixed> $data */
    public function update(Faq $faq, array $data): Faq
    {
        $faq->update($data);

        return $faq->fresh();
    }

    public function destroy(Faq $faq): void
    {
        $faq->update(['is_delete' => 1]);
    }
}
