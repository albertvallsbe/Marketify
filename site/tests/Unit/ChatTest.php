<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Chat;
use App\Models\Category;
use App\Models\Notification;
use App\Models\User;

class ChatTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_redirects_to_login_when_user_not_logged_in()
    {
        $response = $this->get(route('chat.index'));

        $response->assertRedirect(route('login.index'));
    }

    public function test_index_returns_view_with_chats_when_user_logged_in()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('chat.index'));

        $response->assertViewIs('chat.index');
        $response->assertViewHas('chats');
        $response->assertViewHas('categories', Category::all());
    }

    public function test_show_redirects_to_login_when_user_not_logged_in()
    {
        $chat = Chat::factory()->create();

        $response = $this->get(route('chat.show', ['id' => $chat->id]));

        $response->assertRedirect(route('login.index'));
    }

    public function test_show_returns_view_with_chat_when_user_logged_in()
    {
        $user = User::factory()->create();
        $chat = Chat::factory()->create();

        $response = $this->actingAs($user)->get(route('chat.show', ['id' => $chat->id]));

        $response->assertStatus(200);
        $response->assertViewIs('chat.show');
        $response->assertViewHas('categories', Category::all());
    }

    public function test_confirm_seller_updates_order_status_and_creates_message_and_notification()
    {
        $user = User::factory()->create();
        $chat = Chat::factory()->create();
        $order = $chat->order;
        $orderId = $order->id;
        $actionValue = 'confirmPayment';

        $response = $this->actingAs($user)->post(route('chat.confirmSeller', ['chatId' => $chat->id, 'orderId' => $orderId]), [
            'actionValue' => $actionValue,
        ]);

        $response->assertRedirect(route('chat.show', ['id' => $chat->id]));
        $this->assertEquals('paid', $order->fresh()->status);
        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'automatic' => true,
            'content' => 'Payment has been accepted. Seller must send your order in the next 72 hours.',
        ]);
        $this->assertDatabaseHas('notifications', [
            'chat_id' => $chat->id,
            'user_id' => $chat->customer_id,
            'read' => false,
        ]);
    }
}
