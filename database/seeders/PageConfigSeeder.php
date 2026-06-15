<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\PageContact;
use App\Models\PageFactory;
use App\Models\PageFaq;
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
            'assets/images/trang-tri-slide-01.jpg',
            'assets/images/factory-01.jpg',
            'assets/images/factory-04.jpg',
            'assets/images/trang-tri-slide-02.jpg',
            'assets/images/factory-02.png',
        ];
        $sliderPool = [
            'assets/images/factory-02.png',
            'assets/images/den-gom-01.png',
            'assets/images/factory-03.png',
            'assets/images/factory-04.jpg',
            'assets/images/trang-tri-slide-03.jpg',
        ];
        $materialPool = [
            'assets/images/factory-03.png',
            'assets/images/factory-04.jpg',
            'assets/images/trang-tri-slide-04.jpg',
            'assets/images/gach-co-work-1.jpg',
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

        PageFactory::create($factory);
        $this->seedFromData('page_contact', PageContact::class);
        $this->seedFromData('page_faq', PageFaq::class);
        $this->seedFromData('faqs', Faq::class);
    }
}
