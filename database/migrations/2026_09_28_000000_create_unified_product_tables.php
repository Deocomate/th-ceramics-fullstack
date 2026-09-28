<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('type_key', 50)->index();
            $table->string('category_type', 30)->nullable()->index();
            $table->string('legacy_type', 30)->nullable();
            $table->unsignedBigInteger('legacy_id')->nullable();
            $table->string('name');
            $table->string('color', 100)->nullable();
            $table->json('des')->nullable();
            $table->string('size')->nullable();
            $table->string('size_image')->nullable();
            $table->json('size_des')->nullable();
            $table->string('video', 500)->nullable();
            $table->string('dinh_muc', 50)->nullable();
            $table->string('weight', 50)->nullable();
            $table->unsignedInteger('priority')->default(0);
            $table->boolean('is_delete')->default(false)->index();
            $table->timestamps();
            $table->index(['type_key', 'category_type', 'is_delete', 'priority']);
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('sku', 50)->nullable()->unique();
            $table->unsignedBigInteger('price')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
            $table->index(['product_id', 'is_delete']);
        });

        Schema::create('product_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('kind', 30);
            $table->string('path', 1000);
            $table->unsignedInteger('sort_order');
            $table->boolean('is_cover')->default(false);
            $table->timestamps();
            $table->unique(['product_id', 'sort_order']);
        });

        Schema::create('product_display_options', function (Blueprint $table) {
            $table->id();
            $table->string('type_key', 50)->index();
            $table->unsignedBigInteger('legacy_id')->nullable();
            $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['type_key', 'legacy_id']);
        });

        Schema::create('product_legacy_ids', function (Blueprint $table) {
            $table->id();
            $table->string('source_table', 70);
            $table->unsignedBigInteger('source_id');
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unique(['source_table', 'source_id']);
            $table->unique(['product_id', 'source_table']);
        });

        Schema::create('variant_legacy_ids', function (Blueprint $table) {
            $table->id();
            $table->string('source_table', 70);
            $table->unsignedBigInteger('source_id');
            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->unique(['source_table', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_legacy_ids');
        Schema::dropIfExists('product_legacy_ids');
        Schema::dropIfExists('product_display_options');
        Schema::dropIfExists('product_media');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
    }
};
