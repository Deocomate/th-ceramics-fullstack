<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trang_chu', function (Blueprint $table) {
            $table->boolean('is_content_protection_enabled')->default(false)->after('is_ecommerce_enabled');
            $table->boolean('is_devtools_guard_enabled')->default(false)->after('is_content_protection_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('trang_chu', function (Blueprint $table) {
            $table->dropColumn(['is_content_protection_enabled', 'is_devtools_guard_enabled']);
        });
    }
};
