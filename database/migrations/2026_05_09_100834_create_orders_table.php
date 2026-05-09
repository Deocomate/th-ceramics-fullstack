<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('order_code')->unique();
            $table->string('customer_name');
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->text('address');
            $table->text('note')->nullable();
            $table->bigInteger('subtotal');
            $table->bigInteger('shipping_fee')->default(0);
            $table->bigInteger('discount')->default(0);
            $table->bigInteger('total_amount');
            $table->enum('payment_method', ['cod', 'banking'])->default('cod');
            $table->enum('status', ['pending_payment', 'processing', 'shipping', 'completed', 'canceled', 'returned'])->default('processing');
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
