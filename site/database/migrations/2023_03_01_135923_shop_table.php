<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('shopname')->unique();
            $table->string('username')->unique();
            $table->text('logo')->default('images/logos/default-logo.png');
            $table->text('header_color')->default('#84FF9B');
            $table->text('background_color')->default('#D9FFE0');
            $table->string('nif')->unique();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('url')->unique();
            $table->string('order')->default('name_asc');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('shops');
    }
};
