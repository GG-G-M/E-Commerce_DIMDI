<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        $conversations = $admin->getConversations();
        
        return view('admin.messages.index', compact('conversations'));
    }

    public function show($productId, $userId)
    {
        $admin = Auth::user();
        $customer = User::findOrFail($userId);
        $product = Product::findOrFail($productId); // NOW THIS WILL WORK
        
        $conversations = $admin->getConversations();
        
        $messages = Message::betweenUsers($admin->id, $userId, $productId)
            ->with(['sender', 'receiver', 'product'])
            ->latest()
            ->paginate(50);
            
        // Mark messages as read
        Message::betweenUsers($admin->id, $userId, $productId)
            ->where('receiver_id', $admin->id)
            ->unread()
            ->update(['is_read' => true, 'read_at' => now()]);
        
        return view('admin.messages.conversation', compact('messages', 'customer', 'product', 'conversations'));
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
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender'),
                'formatted_time' => $message->formatted_time
            ]);
        }

        return back()->with('success', 'Message sent successfully!');
    }
}