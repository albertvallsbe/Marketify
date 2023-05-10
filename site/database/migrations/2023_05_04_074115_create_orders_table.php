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
            $table->timestamps();

            $table->text('products');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('shop_id')->index();

            $table->enum('status', ['pending', 'payed', 'sending', 'completed'])->default('pending');

            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->cascadeOnDelete();

            $table->foreign('shop_id')
                    ->references('id')->on('shops')
                    ->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['shop_id']);
        });
        Schema::dropIfExists('orders');
    }
};
