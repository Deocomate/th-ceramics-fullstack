<?php

namespace App\Services;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Collection;

class FaqService
{
    public function getAll(): Collection
    {
        return Faq::query()
            ->where('is_delete', 0)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();
    }

    public function getGroupedByCategory(): Collection
    {
        return $this->getAll()->groupBy('category');
    }

    public function store(array $data): Faq
    {
        return Faq::create($data);
    }

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
