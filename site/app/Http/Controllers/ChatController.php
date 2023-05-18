<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Order;
use App\Models\Message;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Classes\HeaderVariables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ChatController extends Controller {
    /**
     * Vista principal del chat
     */
    public function index(Request $request) {
        try {
            if(auth()->user()) {
                $chats = Chat::getByUserID();
                $categories = Category::all();
                session(['notificationCount' => Notification::unreadCountForCurrentUser()]);
                Log::channel('marketify')->info('chat.index view loaded');
                return view('chat.index', [
                    'categories' => $categories,
                    'options_order' => HeaderVariables::$order_array,
                    'chats' => $chats,
                    'selectedChat' => null
                ]);
            } else {
                Log::channel('marketify')->info('User not logged in cart.index, redirect to login instead');
                return redirect()->route('login.index');
            }
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred showing chat view: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }

    /**
     * Vista selectiva del chat
     */
    public function show(Request $request, $id) {
        try {
            if(auth()->user()) {
                $chat = Chat::findOrFail($id);
                $chats = Chat::getByUserID();
                $categories = Category::all();
                session(['notificationCount' => Notification::unreadCountForCurrentUser()]);
                Log::channel('marketify')->info('chat.show view loaded');
                return view('chat.show', [
                    'categories' => $categories,
                    'options_order' => HeaderVariables::$order_array,
                    'chats' => $chats,
                    'selectedChat' => $chat
                ]);
            } else{
                Log::channel('marketify')->info('User not logged in cart.show, redirect to login instead');
                return redirect()->route('login.index');
            }
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred showing chat show: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }

    /**
     * Marcar notificación como leída
     */
    public function updateMessageRead($chatId) {
        try {
            $notification = Notification::showbyChatID($chatId)->latest()->first();
            $notification->markAsRead($chatId);
            Log::channel('marketify')->info('Updated read status in chat #$chatId');
        } catch (\Exception $e) {
            Log::channel('marketify')->error("An error occurred updating read status in chat #$chatId: ".$e->getMessage());
        }
    }

    /**
     * Envíar mensaje y notificación al otro usuario
     */
    public function messageSend(Request $request, $id) {
        try {
            $validatedData = $request->validate([
                'messagetext' => 'required|string',
            ]);
            $chat = Chat::findOrFail($id);
            $message = Message::create([
                'chat_id' => $chat->id,
                'sender_id' => auth()->id(),
                'content' => $validatedData['messagetext']
            ]);
            $notification = Notification::where('chat_id', $chat->id)->first();
            if ($notification) {
                $notification->read = false;
                $notification->save();
            } else {
                $notifications = Notification::where('chat_id', $chat->id)->get();
                if ($notifications->isNotEmpty()) {
                    $notifications->each(function ($notification) {
                        $notification->read = false;
                        $notification->save();
                    });
                } else {
                    $chatId = $chat->id;
                    $userId = $chat->seller_id == auth()->id() ? $chat->customer_id : $chat->seller_id;

                    Notification::updateOrCreate(
                        ['chat_id' => $chatId, 'user_id' => $userId],
                        ['read' => false]
                    );
                }
            }
            Log::channel('marketify')->info("Created message and notification for chat #$chatId");
            return redirect()->route('chat.show', ['id' => $chat->id]);
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred creating message: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }

    /**
     * Actualizar estado del pedido
     */
    public function confirmSeller(Request $request, $chatId, $orderId) {
        try {
            $action = $request->input('actionValue');
            $chat = Chat::findOrFail($chatId);
            $order = $chat->order;
            $messageContent = '';

            /**
             * Según el botón presionado en la vista, elige un caso u otro
             */
            switch ($action) {
                case 'confirmPayment':
                    $order->status = 'paid';
                    $messageContent = 'Payment has been accepted. Seller must send your order in the next 72 hours.';
                    break;
                case 'shipmentSend':
                    $order->status = 'sending';
                    $messageContent = 'Your order has been sent. You will receive it within 5 working days.';
                    break;
                case 'shipmentDone':
                    $order->status = 'completed';
                    $messageContent = 'Customer has received the order!';
                    break;
                case 'confirmFail':
                    $order->status = 'failed';
                    $messageContent = 'The seller has declared the order as incorrect. Something went wrong with the order!';

                    $orderItems = $order->orderItems;
                    foreach ($orderItems as $orderItem) {
                        $orderItem->product->status = 'active';
                        $orderItem->product->save();
                    }
                    break;
            }
            $order->save();
            $message = Message::create([
                'chat_id' => $chat->id,
                'sender_id' => auth()->id(),
                'automatic' => true,
                'content' => $messageContent
            ]);
            Notification::updateOrCreate(
                ['chat_id' => $chat->id, 'user_id' => $chat->customer_id],
                ['read' => false]
            );
            Log::channel('marketify')->info("Order status for chat #$chatId has updated");
            return redirect()->route('chat.show', ['id' => $chat->id]);
        } catch (\Exception $e) {
            Log::channel('marketify')->error('An error occurred updating order status: '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred.');
        }
    }
}
