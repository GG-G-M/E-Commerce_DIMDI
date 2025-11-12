<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $conversations = $user->getConversations();
        
        return view('messages.index', compact('conversations'));
    }

   public function show($productId, $userId)
{
    $user = Auth::user();
    $otherUser = User::findOrFail($userId);
    $product = Product::findOrFail($productId);
    
    // Get conversations for the sidebar
    $conversations = $user->getConversations();
    
    // Get messages between users for this product, ordered by latest first
    $messages = Message::betweenUsers($user->id, $userId, $productId)
        ->with(['sender', 'receiver'])
        ->latest()
        ->paginate(50);
        
    // Mark received messages as read
    Message::betweenUsers($user->id, $userId, $productId)
        ->where('receiver_id', $user->id)
        ->unread()
        ->update(['is_read' => true, 'read_at' => now()]);
    
    return view('messages.conversation', compact('messages', 'otherUser', 'product', 'conversations'));
}

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'product_id' => $request->product_id,
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'created_at' => now(), // Explicitly set timestamp
            'updated_at' => now()
        ]);

        // Load relationships for response
        $message->load(['sender', 'receiver']);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'formatted_time' => $message->formatted_time,
                'time_ago' => $message->time_ago
            ]);
        }

        return back()->with('success', 'Message sent successfully!');
    }

    public function getMessages($productId, $userId)
    {
        $user = Auth::user();
        
        $messages = Message::betweenUsers($user->id, $userId, $productId)
            ->with(['sender', 'receiver'])
            ->oldestFirst() // Get oldest first for proper display
            ->paginate(50);

        return response()->json([
            'messages' => $messages,
            'formatted_messages' => $messages->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'sender_id' => $msg->sender_id,
                    'is_own' => $msg->sender_id == Auth::id(),
                    'formatted_time' => $msg->formatted_time,
                    'time_ago' => $msg->time_ago,
                    'date_header' => $msg->date_header,
                    'sender_name' => $msg->sender->name
                ];
            })
        ]);
    }

    public function markAsRead(Message $message)
    {
        if ($message->receiver_id == Auth::id()) {
            $message->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->receivedMessages()->unread()->count();
        
        return response()->json(['unread_count' => $count]);
    }
}