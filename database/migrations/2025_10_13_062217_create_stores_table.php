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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Store name
            $table->string('prefix'); // prefix name
            $table->string('base_url')->nullable(); // Base URL of the store
            $table->string('api_key')->nullable(); // WooCommerce API key
            $table->string('api_secret')->nullable(); // WooCommerce API secret
            $table->string('ep_order_store')->nullable(); // Endpoint to create order
            $table->string('ep_order_update')->nullable(); // Endpoint to update order
            $table->string('ep_order_status')->nullable(); // Endpoint to check order status
            $table->string('ep_order_delete')->nullable(); // Endpoint to delete order
            $table->boolean('status')->nullable()->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
