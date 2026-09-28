<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_public_id_sequences', function (Blueprint $table) {
            $table->string('type_key', 50)->primary();
            $table->unsignedBigInteger('next_product_id')->default(1);
            $table->unsignedBigInteger('next_variant_id')->default(1);
        });

        Schema::create('product_public_ids', function (Blueprint $table) {
            $table->id();
            $table->string('type_key', 50);
            $table->unsignedBigInteger('public_id');
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unique(['type_key', 'public_id']);
            $table->unique('product_id');
        });

        Schema::create('variant_public_ids', function (Blueprint $table) {
            $table->id();
            $table->string('type_key', 50);
            $table->unsignedBigInteger('public_id');
            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->unique(['type_key', 'public_id']);
            $table->unique('product_variant_id');
        });

        // Existing mixed-schema installations retain their public URLs after
        // this additive migration. A fresh canonical schema starts empty.
        if (Schema::hasTable('product_legacy_ids')) {
            foreach (DB::table('product_legacy_ids')->join('products', 'products.id', '=', 'product_legacy_ids.product_id')
                ->select('products.type_key', 'product_legacy_ids.source_id', 'product_legacy_ids.product_id')->get() as $row) {
                DB::table('product_public_ids')->insertOrIgnore([
                    'type_key' => $row->type_key,
                    'public_id' => $row->source_id,
                    'product_id' => $row->product_id,
                ]);
            }
        }
        if (Schema::hasTable('variant_legacy_ids')) {
            foreach (DB::table('variant_legacy_ids')
                ->join('product_variants', 'product_variants.id', '=', 'variant_legacy_ids.product_variant_id')
                ->join('products', 'products.id', '=', 'product_variants.product_id')
                ->select('products.type_key', 'variant_legacy_ids.source_id', 'variant_legacy_ids.product_variant_id')->get() as $row) {
                DB::table('variant_public_ids')->insertOrIgnore([
                    'type_key' => $row->type_key,
                    'public_id' => $row->source_id,
                    'product_variant_id' => $row->product_variant_id,
                ]);
            }
        }

        foreach (DB::table('product_public_ids')->select('type_key')->distinct()->pluck('type_key') as $type) {
            DB::table('catalog_public_id_sequences')->insertOrIgnore([
                'type_key' => $type,
                'next_product_id' => (int) DB::table('product_public_ids')->where('type_key', $type)->max('public_id') + 1,
                'next_variant_id' => (int) DB::table('variant_public_ids')->where('type_key', $type)->max('public_id') + 1,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_public_ids');
        Schema::dropIfExists('product_public_ids');
        Schema::dropIfExists('catalog_public_id_sequences');
    }
};
