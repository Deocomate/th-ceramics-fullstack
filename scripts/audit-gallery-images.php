<?php

declare(strict_types=1);

/**
 * Audit gallery image counts after seeding.
 *
 * Usage: php artisan db:seed && php scripts/audit-gallery-images.php
 */

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\DuAn;
use App\Models\TrangChu;
use Database\Seeders\Support\SeederDataContract;

$failures = [];

$home = TrangChu::first();
if ($home) {
    foreach (['banner', 'showroom_images'] as $field) {
        $count = count($home->{$field} ?? []);
        if ($count < SeederDataContract::MIN_GALLERY_IMAGES || $count > SeederDataContract::MAX_GALLERY_IMAGES) {
            $failures[] = "trang_chu.{$field}: {$count} images";
        }
    }
}

foreach (DuAn::all() as $project) {
    $count = count($project->images ?? []);
    if ($count < SeederDataContract::MIN_GALLERY_IMAGES || $count > SeederDataContract::MAX_GALLERY_IMAGES) {
        $failures[] = "du_an.{$project->du_an_id}.images: {$count} images";
    }
}

if ($failures === []) {
    echo "OK: all audited galleries pass 5–10 rule\n";
    exit(0);
}

echo "FAILURES:\n";
foreach ($failures as $failure) {
    echo " - {$failure}\n";
}
exit(1);
