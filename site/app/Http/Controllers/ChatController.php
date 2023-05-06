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
    public function updateMessageRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();
    }
    
    public function messageSend(Request $request, $id)
    {
    
        $validatedData = $request->validate([
            'messagetext' => 'required|string',
        ]);

        
        $message = Message::create([
            'chat_id' => $id,
            'sender_id' => auth()->id(),
            'content' => $validatedData['messagetext']
        ]);
        return redirect()->route('chat.index');
    }
}