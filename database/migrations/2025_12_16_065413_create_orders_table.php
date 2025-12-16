<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('shop_id')->constrained('shops');
            $table->enum('status',['created','paid','shipped','delivered','cancelled','refunded'])->default('created');
            $table->decimal('total_amount',12,2);
            $table->decimal('shipping_fee',10,2)->default(0);
            $table->string('payment_provider')->nullable();
            $table->string('payment_reference')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
