<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const PRODUCT_TYPE = 'phu_kien_ngoi_ct';

    private const PRODUCT_ALIASES = [
        'ngoi_bo_noc' => 'bo_noc',
        'ngoi_bo_noc_ct' => 'bo_noc',
        'bo_noc' => 'bo_noc',
        'bo_noc_chu_van' => 'chu_van',
        'bo_noc_chu_van_ct' => 'chu_van',
        'chu_van' => 'chu_van',
    ];

    public function up(): void
    {
        $this->createUnifiedTables();

        $productMap = $this->copyProducts();
        $variantMap = $this->copyVariants($productMap);

        $this->updateOrderItems($productMap, $variantMap);
        $this->updateCoupons();

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('phan_loai_bo_noc_chu_van_ct');
        Schema::dropIfExists('bo_noc_chu_van_ct');
        Schema::dropIfExists('phan_loai_ngoi_bo_noc_ct');
        Schema::dropIfExists('ngoi_bo_noc_ct');
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        $this->recreateLegacyTables();

        $productMap = $this->copyProductsBack();
        $variantMap = $this->copyVariantsBack($productMap);

        $this->restoreOrderItems($productMap, $variantMap);
        $this->restoreCoupons();

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('phan_loai_phu_kien_ngoi_ct');
        Schema::dropIfExists('phu_kien_ngoi_ct');
        Schema::enableForeignKeyConstraints();
    }

    private function createUnifiedTables(): void
    {
        if (! Schema::hasTable('phu_kien_ngoi_ct')) {
            Schema::create('phu_kien_ngoi_ct', function (Blueprint $table) {
                $table->id('phu_kien_ngoi_ct_id');
                $table->string('name');
                $table->string('category_type', 20)->index();
                $table->string('legacy_type', 20)->nullable()->index();
                $table->unsignedBigInteger('legacy_id')->nullable()->index();
                $table->string('color', 100)->default('Tự chọn');
                $table->json('images')->nullable();
                $table->json('des')->nullable();
                $table->string('size')->nullable();
                $table->string('size_image')->nullable();
                $table->json('size_des')->nullable();
                $table->boolean('is_delete')->default(0);
                $table->timestamps();
                $table->unique(['legacy_type', 'legacy_id']);
            });
        }

        if (! Schema::hasTable('phan_loai_phu_kien_ngoi_ct')) {
            Schema::create('phan_loai_phu_kien_ngoi_ct', function (Blueprint $table) {
                $table->id('phan_loai_phu_kien_ngoi_ct_id');
                $table->string('name');
                $table->string('code', 50)->unique();
                $table->integer('price');
                $table->foreignId('phu_kien_ngoi_ct_id')
                    ->constrained('phu_kien_ngoi_ct', 'phu_kien_ngoi_ct_id')
                    ->cascadeOnDelete();
                $table->string('legacy_type', 20)->nullable()->index();
                $table->unsignedBigInteger('legacy_id')->nullable()->index();
                $table->boolean('is_delete')->default(0);
                $table->timestamps();
                $table->unique(['legacy_type', 'legacy_id']);
            });
        }
    }

    private function copyProducts(): array
    {
        $map = [];

        foreach ([
            'bo_noc' => ['table' => 'ngoi_bo_noc_ct', 'pk' => 'ngoi_bo_noc_ct_id'],
            'chu_van' => ['table' => 'bo_noc_chu_van_ct', 'pk' => 'bo_noc_chu_van_ct_id'],
        ] as $legacyType => $config) {
            if (! Schema::hasTable($config['table'])) {
                continue;
            }

            foreach (DB::table($config['table'])->orderBy($config['pk'])->get() as $row) {
                $existing = DB::table('phu_kien_ngoi_ct')
                    ->where('legacy_type', $legacyType)
                    ->where('legacy_id', $row->{$config['pk']})
                    ->value('phu_kien_ngoi_ct_id');

                $newId = $existing ?: DB::table('phu_kien_ngoi_ct')->insertGetId([
                    'name' => $row->name,
                    'category_type' => $legacyType,
                    'legacy_type' => $legacyType,
                    'legacy_id' => $row->{$config['pk']},
                    'color' => $row->color ?? 'Tự chọn',
                    'images' => $row->images ?? null,
                    'des' => $row->des ?? null,
                    'size' => $row->size ?? null,
                    'size_image' => $row->size_image ?? null,
                    'size_des' => $row->size_des ?? null,
                    'is_delete' => $row->is_delete ?? 0,
                    'created_at' => $row->created_at ?? now(),
                    'updated_at' => $row->updated_at ?? now(),
                ]);

                $map[$legacyType][(int) $row->{$config['pk']}] = (int) $newId;
            }
        }

        return $map;
    }

    private function copyVariants(array $productMap): array
    {
        $map = [];

        foreach ([
            'bo_noc' => [
                'table' => 'phan_loai_ngoi_bo_noc_ct',
                'pk' => 'phan_loai_ngoi_bo_noc_ct_id',
                'fk' => 'ngoi_bo_noc_ct_id',
            ],
            'chu_van' => [
                'table' => 'phan_loai_bo_noc_chu_van_ct',
                'pk' => 'phan_loai_bo_noc_chu_van_ct_id',
                'fk' => 'bo_noc_chu_van_ct_id',
            ],
        ] as $legacyType => $config) {
            if (! Schema::hasTable($config['table'])) {
                continue;
            }

            foreach (DB::table($config['table'])->orderBy($config['pk'])->get() as $row) {
                $newProductId = $productMap[$legacyType][(int) $row->{$config['fk']}] ?? null;
                if (! $newProductId) {
                    continue;
                }

                $existing = DB::table('phan_loai_phu_kien_ngoi_ct')
                    ->where('legacy_type', $legacyType)
                    ->where('legacy_id', $row->{$config['pk']})
                    ->value('phan_loai_phu_kien_ngoi_ct_id');

                $newId = $existing ?: DB::table('phan_loai_phu_kien_ngoi_ct')->insertGetId([
                    'name' => $row->name,
                    'code' => $row->code,
                    'price' => $row->price,
                    'phu_kien_ngoi_ct_id' => $newProductId,
                    'legacy_type' => $legacyType,
                    'legacy_id' => $row->{$config['pk']},
                    'is_delete' => $row->is_delete ?? 0,
                    'created_at' => $row->created_at ?? now(),
                    'updated_at' => $row->updated_at ?? now(),
                ]);

                $map[$legacyType][(int) $row->{$config['pk']}] = (int) $newId;
            }
        }

        return $map;
    }

    private function updateOrderItems(array $productMap, array $variantMap): void
    {
        if (! Schema::hasTable('order_items')) {
            return;
        }

        foreach (self::PRODUCT_ALIASES as $alias => $legacyType) {
            foreach (DB::table('order_items')->where('product_type', $alias)->get() as $item) {
                $newProductId = $productMap[$legacyType][(int) $item->product_id] ?? null;
                if (! $newProductId) {
                    continue;
                }

                DB::table('order_items')->where('id', $item->id)->update([
                    'product_type' => self::PRODUCT_TYPE,
                    'product_id' => $newProductId,
                    'variant_id' => $item->variant_id ? ($variantMap[$legacyType][(int) $item->variant_id] ?? $item->variant_id) : null,
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function updateCoupons(): void
    {
        if (! Schema::hasTable('coupons')) {
            return;
        }

        foreach (DB::table('coupons')->whereNotNull('applicable_product_types')->get() as $coupon) {
            $types = json_decode($coupon->applicable_product_types, true);
            if (! is_array($types)) {
                continue;
            }

            $types = collect($types)
                ->map(fn ($type) => array_key_exists($type, self::PRODUCT_ALIASES) || $type === 'phu_kien_ngoi' ? self::PRODUCT_TYPE : $type)
                ->unique()
                ->values()
                ->all();

            DB::table('coupons')->where('id', $coupon->id)->update([
                'applicable_product_types' => json_encode($types),
                'updated_at' => now(),
            ]);
        }
    }

    private function recreateLegacyTables(): void
    {
        if (! Schema::hasTable('ngoi_bo_noc_ct')) {
            Schema::create('ngoi_bo_noc_ct', function (Blueprint $table) {
                $table->id('ngoi_bo_noc_ct_id');
                $table->string('name');
                $table->string('color', 100)->default('Tự chọn');
                $table->json('images')->nullable();
                $table->json('des')->nullable();
                $table->string('size')->nullable();
                $table->string('size_image')->nullable();
                $table->json('size_des')->nullable();
                $table->boolean('is_delete')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('bo_noc_chu_van_ct')) {
            Schema::create('bo_noc_chu_van_ct', function (Blueprint $table) {
                $table->id('bo_noc_chu_van_ct_id');
                $table->string('name');
                $table->string('color', 100)->default('Tự chọn');
                $table->json('images')->nullable();
                $table->json('des')->nullable();
                $table->string('size')->nullable();
                $table->string('size_image')->nullable();
                $table->json('size_des')->nullable();
                $table->boolean('is_delete')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('phan_loai_ngoi_bo_noc_ct')) {
            Schema::create('phan_loai_ngoi_bo_noc_ct', function (Blueprint $table) {
                $table->id('phan_loai_ngoi_bo_noc_ct_id');
                $table->string('name')->unique();
                $table->string('code', 50)->unique();
                $table->integer('price');
                $table->foreignId('ngoi_bo_noc_ct_id')->constrained('ngoi_bo_noc_ct', 'ngoi_bo_noc_ct_id')->cascadeOnDelete();
                $table->boolean('is_delete')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('phan_loai_bo_noc_chu_van_ct')) {
            Schema::create('phan_loai_bo_noc_chu_van_ct', function (Blueprint $table) {
                $table->id('phan_loai_bo_noc_chu_van_ct_id');
                $table->string('name')->unique();
                $table->string('code', 50)->unique();
                $table->integer('price');
                $table->foreignId('bo_noc_chu_van_ct_id')->constrained('bo_noc_chu_van_ct', 'bo_noc_chu_van_ct_id')->cascadeOnDelete();
                $table->boolean('is_delete')->default(0);
                $table->timestamps();
            });
        }
    }

    private function copyProductsBack(): array
    {
        $map = [];

        if (! Schema::hasTable('phu_kien_ngoi_ct')) {
            return $map;
        }

        foreach (DB::table('phu_kien_ngoi_ct')->orderBy('phu_kien_ngoi_ct_id')->get() as $row) {
            $legacyType = $row->category_type === 'chu_van' ? 'chu_van' : 'bo_noc';
            $table = $legacyType === 'chu_van' ? 'bo_noc_chu_van_ct' : 'ngoi_bo_noc_ct';
            $pk = $legacyType === 'chu_van' ? 'bo_noc_chu_van_ct_id' : 'ngoi_bo_noc_ct_id';
            $legacyId = $row->legacy_id ?: $row->phu_kien_ngoi_ct_id;

            $legacyProductId = $productMap[$legacyType][(int) $row->phu_kien_ngoi_ct_id] ?? null;
            if (! $legacyProductId) {
                continue;
            }

            DB::table($table)->insert([
                $pk => $legacyId,
                'name' => $row->name,
                'color' => $row->color ?? 'Tự chọn',
                'images' => $row->images ?? null,
                'des' => $row->des ?? null,
                'size' => $row->size ?? null,
                'size_image' => $row->size_image ?? null,
                'size_des' => $row->size_des ?? null,
                'is_delete' => $row->is_delete ?? 0,
                'created_at' => $row->created_at ?? now(),
                'updated_at' => $row->updated_at ?? now(),
            ]);

            $map[$legacyType][(int) $row->phu_kien_ngoi_ct_id] = (int) $legacyId;
        }

        return $map;
    }

    private function copyVariantsBack(array $productMap): array
    {
        $map = [];

        if (! Schema::hasTable('phan_loai_phu_kien_ngoi_ct')) {
            return $map;
        }

        foreach (DB::table('phan_loai_phu_kien_ngoi_ct')->orderBy('phan_loai_phu_kien_ngoi_ct_id')->get() as $row) {
            $product = DB::table('phu_kien_ngoi_ct')->where('phu_kien_ngoi_ct_id', $row->phu_kien_ngoi_ct_id)->first();
            if (! $product) {
                continue;
            }

            $legacyType = $product->category_type === 'chu_van' ? 'chu_van' : 'bo_noc';
            $table = $legacyType === 'chu_van' ? 'phan_loai_bo_noc_chu_van_ct' : 'phan_loai_ngoi_bo_noc_ct';
            $pk = $legacyType === 'chu_van' ? 'phan_loai_bo_noc_chu_van_ct_id' : 'phan_loai_ngoi_bo_noc_ct_id';
            $fk = $legacyType === 'chu_van' ? 'bo_noc_chu_van_ct_id' : 'ngoi_bo_noc_ct_id';
            $legacyId = $row->legacy_id ?: $row->phan_loai_phu_kien_ngoi_ct_id;

            DB::table($table)->insert([
                $pk => $legacyId,
                'name' => $row->name,
                'code' => $row->code,
                'price' => $row->price,
                $fk => $legacyProductId,
                'is_delete' => $row->is_delete ?? 0,
                'created_at' => $row->created_at ?? now(),
                'updated_at' => $row->updated_at ?? now(),
            ]);

            $map[$legacyType][(int) $row->phan_loai_phu_kien_ngoi_ct_id] = (int) $legacyId;
        }

        return $map;
    }

    private function restoreOrderItems(array $productMap, array $variantMap): void
    {
        if (! Schema::hasTable('order_items')) {
            return;
        }

        foreach (DB::table('order_items')->where('product_type', self::PRODUCT_TYPE)->get() as $item) {
            $product = DB::table('phu_kien_ngoi_ct')->where('phu_kien_ngoi_ct_id', $item->product_id)->first();
            if (! $product) {
                continue;
            }

            $legacyType = $product->category_type === 'chu_van' ? 'chu_van' : 'bo_noc';
            $legacyProductId = $productMap[$legacyType][(int) $item->product_id] ?? null;
            if (! $legacyProductId) {
                continue;
            }

            DB::table('order_items')->where('id', $item->id)->update([
                'product_type' => $legacyType === 'chu_van' ? 'bo_noc_chu_van_ct' : 'ngoi_bo_noc_ct',
                'product_id' => $legacyProductId,
                'variant_id' => $item->variant_id ? ($variantMap[$legacyType][(int) $item->variant_id] ?? $item->variant_id) : null,
                'updated_at' => now(),
            ]);
        }
    }

    private function restoreCoupons(): void
    {
        if (! Schema::hasTable('coupons')) {
            return;
        }

        foreach (DB::table('coupons')->whereNotNull('applicable_product_types')->get() as $coupon) {
            $types = json_decode($coupon->applicable_product_types, true);
            if (! is_array($types)) {
                continue;
            }

            $types = collect($types)
                ->map(fn ($type) => $type === self::PRODUCT_TYPE ? 'ngoi_bo_noc_ct' : $type)
                ->unique()
                ->values()
                ->all();

            DB::table('coupons')->where('id', $coupon->id)->update([
                'applicable_product_types' => json_encode($types),
                'updated_at' => now(),
            ]);
        }
    }
};
