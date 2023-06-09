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
            $table->id();
            $table->timestamps();

            $table->text('email')->unique()->default('patata@exampler.com');
            $table->text('name')->unique();
            $table->text('password')->unique()->default('12345678');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->enum('role' , ['seller','customer','administrator'])->default('customer');
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
