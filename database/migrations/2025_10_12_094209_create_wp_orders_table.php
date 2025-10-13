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
        Schema::create('wp_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wp_order_id')->nullable(); // WooCommerce order id
            $table->string('invoice_no')->nullable()->unique();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('total', 12, 2)->default(0);
            $table->json('billing')->nullable();
            $table->json('shipping')->nullable();
            $table->json('order_data')->nullable(); // full raw JSON
            $table->string('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wp_orders');
    }
};
