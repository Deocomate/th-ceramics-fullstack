<?php

namespace Tests\Unit\Architecture\Fixtures\Domains\Catalog\Application;

use App\Domains\Catalog\Http\Admin\ProductController;

class ApplicationViolatingHttpImport
{
    public function handle(ProductController $controller): void {}
}
