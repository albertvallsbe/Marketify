<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatTables extends Migration
{
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedBigInteger('seller_id')->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->timestamps();
            
            $table->foreign('seller_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('chat_id')->unsigned();
            $table->unsignedBigInteger('sender_id')->index();
            $table->text('content');
            $table->timestamps();

            $table->foreign('chat_id')->references('id')->on('chats')->cascadeOnDelete();
            $table->foreign('sender_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->integer('chat_id')->unsigned();
            $table->boolean('read')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('chat_id')->references('id')->on('chats')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['chat_id']);
        });
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['chat_id']);
            $table->dropForeign(['sender_id']);
        });
        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropForeign(['customer_id']);
        });

        Schema::dropIfExists('notifications');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('chats');
    }
}
