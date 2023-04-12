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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->text('email')->unique();
            $table->text('name')->unique();
            $table->text('password')->unique();
            $table->timestamp('expires_at');
            $table->timestamp('email_verified_at')->nullable();
            // $table->rememberToken();
            // $table->string('api_token')->unique();
            $table->timestamps();
            $table->enum('role' , ['seller','shopper','administrator'])->default('shopper');
            $table->text('avatar')->default('images/profiles/default-avatar.jpg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
