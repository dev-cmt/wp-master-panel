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
        Schema::create('wp_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wp_order_id')->constrained('wp_orders')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->nullable(); // wc product id
            $table->string('product_name')->nullable();
            $table->string('sku')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wp_order_items');
    }
};
