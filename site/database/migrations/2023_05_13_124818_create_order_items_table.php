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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('shop_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->enum('status', ['pending', 'payed', 'sending', 'completed'])->default('pending');

            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->cascadeOnDelete();
            $table->foreign('shop_id')
                ->references('id')->on('shops')
                ->cascadeOnDelete();
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
