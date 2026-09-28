<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_archive_record_maps', function (Blueprint $table) {
            $table->id();
            $table->uuid('source_id');
            $table->string('table_name', 100);
            $table->unsignedBigInteger('source_record_id');
            $table->unsignedBigInteger('target_record_id');
            $table->timestamps();
            $table->unique(['source_id', 'table_name', 'source_record_id'], 'content_archive_source_record_unique');
            $table->index(['table_name', 'target_record_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_archive_record_maps');
    }
};
