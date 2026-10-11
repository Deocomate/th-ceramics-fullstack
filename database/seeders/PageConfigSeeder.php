<?php

namespace Database\Seeders;

use App\Domains\Content\Infrastructure\Models\ContactPageConfig;
use App\Domains\Content\Infrastructure\Models\FactoryPageConfig;
use App\Domains\Content\Infrastructure\Models\Faq;
use App\Domains\Content\Infrastructure\Models\FaqPageConfig;
use Database\Seeders\Support\SeederDataContract;
use Database\Seeders\Support\SeedsFromSqlData;
use Illuminate\Database\Seeder;

class PageConfigSeeder extends Seeder
{
    use SeedsFromSqlData;

    public function run(): void
    {
        $this->truncateTables('faqs', 'page_factory', 'page_contact', 'page_faq');

        $factory = $this->withoutTimestamps($this->seederDataFirst('page_factory') ?? []);
        $galleryPool = [
            'assets/images/trang-tri-slide-01.webp',
            'assets/images/factory-01.webp',
            'assets/images/factory-04.webp',
            'assets/images/trang-tri-slide-02.webp',
            'assets/images/factory-02.webp',
        ];
        $sliderPool = [
            'assets/images/factory-02.webp',
            'assets/images/den-gom-01.png',
            'assets/images/factory-03.webp',
            'assets/images/factory-04.webp',
            'assets/images/trang-tri-slide-03.jpg',
        ];
        $materialPool = [
            'assets/images/factory-03.webp',
            'assets/images/factory-04.webp',
            'assets/images/trang-tri-slide-04.webp',
            'assets/images/gach-co-work-1.webp',
            'assets/images/gach-co-work-2.jpg',
        ];

        foreach (['gallery_1', 'gallery_2', 'process_slider', 'material_slider'] as $field) {
            if (! isset($factory[$field]) || ! is_array($factory[$field])) {
                continue;
            }
            $pool = match ($field) {
                'process_slider' => $sliderPool,
                'material_slider' => $materialPool,
                default => $galleryPool,
            };
            $factory[$field] = SeederDataContract::expandGallery($factory[$field], $pool, 5);
            SeederDataContract::assertGallery($factory[$field], "page_factory.{$field}");
        }

        FactoryPageConfig::create($factory);
        $this->seedFromData('page_contact', ContactPageConfig::class);
        $this->seedFromData('page_faq', FaqPageConfig::class);
        $this->seedFromData('faqs', Faq::class);
    }
}
