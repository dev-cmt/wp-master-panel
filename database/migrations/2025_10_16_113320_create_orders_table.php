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

            $table->unsignedBigInteger('store_id')->nullable();
            $table->string('invoice_no')->unique()->nullable();
            $table->string('order_date')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('paid', 12, 2)->default(0);
            $table->decimal('due', 12, 2)->default(0);

            $table->string('source')->nullable();

            $table->unsignedBigInteger('courier_id')->nullable();
            $table->unsignedBigInteger('courier_city_id')->nullable();
            $table->unsignedBigInteger('courier_zone_id')->nullable();

            $table->json('shipping')->nullable();
            $table->json('order_data')->nullable(); // raw JSON (WooCommerce or API data)

            $table->tinyInteger('status')->default(0); // e.g., pending, processing, completed, cancelled
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
