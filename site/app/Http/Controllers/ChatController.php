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

class ChatController extends Controller
{    
    public function index(Request $request)
    {
        try {  
            if(auth()->user()){
                $chats = Chat::getByUserID();
                $categories = Category::all();
                session(['notificationCount' => Notification::unreadCountForCurrentUser()]);
                return view('chat.index', [
                    'categories' => $categories,
                    'options_order' => HeaderVariables::$order_array,
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
        return redirect()->route('chat.index');
    }

    public function confirmSeller(Request $request, $id)
    {
        $action = $request->input('actionValue');
        $chat = Chat::findOrFail($id);
        $fieldsToUpdate = [];
        $messageContent = '';
        
        switch ($action) {
            case 'confirmPayment':
                $fieldsToUpdate = ['status' => 'payed'];
                $messageContent = 'Payment has been accepted. Seller must send your order in the next 72 hours.';
                break;
            case 'shipmentSend':
                $fieldsToUpdate = ['status' => 'sending'];
                $messageContent = 'Your order has been sent. You will receive it within 5 working days.';
                break;
            case 'shipmentDone':
                $fieldsToUpdate = ['status' => 'completed'];
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
        
        Notification::updateOrCreate(
            ['chat_id' => $chat->id, 'user_id' => $chat->customer_id],
            ['read' => false]
        );  
        
        return redirect()->route('chat.index');
    }
    







    
}