<?php

namespace App\Http\Controllers;

use App\Classes\Order;
use App\Models\Message;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{    
    public function index(Request $request)
    {
        try {  
            if(auth()->user()){
                $chats = Chat::getByUserID();
                // $chats = Chat::with('messages')->getByUserID();
                $categories = Category::all();
                session(['notificationCount' => Notification::unreadCountForCurrentUser()]);
                return view('chat.index', [
                    'categories' => $categories,
                    'options_order' => Order::$order_array,
                    'chats' => $chats
                ]);
            } else{
                return redirect()->route('login.index');
            }
            } catch (\Exception $e) {
                Log::channel('marketify')->error('An error occurred while loading the home view: '.$e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while loading the home view.');
            }
    } 
    public function updateMessageRead($chatId)
    { 
        $notification = Notification::showbyChatID($chatId)->latest()->first();
        $notification->markAsRead($chatId);
    }
    
    public function messageSend(Request $request, $id)
    {
    
        $validatedData = $request->validate([
            'messagetext' => 'required|string',
        ]);

        
        $chat = Chat::findOrFail($id);
        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => auth()->id(),
            'content' => $validatedData['messagetext']
        ]);
        if ($chat->seller_id == auth()->id()) {
            $notification = Notification::create([
                'user_id' => $chat->customer_id,
                'chat_id' => $chat->id,
                'read' => false
            ]);
        }else{
            $notification = Notification::create([
                'user_id' => $chat->seller_id,
                'chat_id' => $chat->id,
                'read' => false
            ]);
        }
        LOG::INFO($chat);
        LOG::INFO($notification);
        return redirect()->route('chat.index');
    }

    public function confirmSeller(Request $request, $id){
        $action = $request->input('actionValue');
        LOG::INFO($action);
        $chat = Chat::findOrFail($id);
        LOG::INFO($id);
        $fieldsToUpdate = [];
        $messageContent = '';
        
        switch ($action) {
            case 'confirmPayment':
                $fieldsToUpdate = ['paymentDone' => true];
                $messageContent = 'Payment has been accepted. Seller must send your order in next 72h.';
                break;
            case 'shipmentSend':
                $fieldsToUpdate = ['shipmentSend' => true];
                $messageContent = 'Your order has been sent. You will receive it within 5 working days.';
                break;
            case 'shipmentDone':
                $fieldsToUpdate = ['shipmentDone' => true];
                $messageContent = 'Customer has received the order!';
                break;
        }
        
        if (!empty($fieldsToUpdate)) {
            $chat->update($fieldsToUpdate);
            $message = Message::create([
                'chat_id' => $chat->id,
                'sender_id' => auth()->id(),
                'automatic' => true,
                'content' => $messageContent
            ]);
        }
        $notification = Notification::create([
            'user_id' => $chat->customer_id,
            'chat_id' => $chat->id,
            'read' => false
        ]);
        LOG::INFO($chat);
        return redirect()->route('chat.index');
    }
    
}