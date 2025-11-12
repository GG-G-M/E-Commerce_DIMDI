<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Add accessor for full name
    public function getNameAttribute()
    {
        $names = [$this->first_name];
        
        if ($this->middle_name) {
            $names[] = $this->middle_name;
        }
        
        $names[] = $this->last_name;
        
        return implode(' ', $names);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function conversations()
    {
        return Message::where('sender_id', $this->id)
            ->orWhere('receiver_id', $this->id)
            ->with(['sender', 'receiver', 'product'])
            ->get()
            ->groupBy(function($message) {
                $otherUserId = $message->sender_id == $this->id 
                    ? $message->receiver_id 
                    : $message->sender_id;
                return $otherUserId . '_' . $message->product_id;
            });
    }

    /**
     * Get unread messages count
     */
    public function getUnreadMessagesCount()
    {
        return $this->receivedMessages()->where('is_read', false)->count();
    }

    /**
     * Get admin user
     */
    public static function getAdmin()
    {
        return static::where('role', 'admin')->first();
    }

    /**
     * ADD THIS METHOD - Enhanced conversations with unread count and proper structure
     */
    public function getConversations()
{
    $messages = Message::where('sender_id', $this->id)
        ->orWhere('receiver_id', $this->id)
        ->with(['sender', 'receiver', 'product'])
        ->latest()
        ->get();

    // Check if there are any messages
    if ($messages->isEmpty()) {
        return collect(); // Return empty collection
    }

    $conversations = $messages->groupBy(function($message) {
        $otherUserId = $message->sender_id == $this->id 
            ? $message->receiver_id 
            : $message->sender_id;
        return $otherUserId . '_' . $message->product_id;
    })->map(function($messages) {
        $latestMessage = $messages->first();
        
        // Add null checks for relationships
        if (!$latestMessage) {
            return null;
        }

        $unreadCount = $messages->where('receiver_id', $this->id)
                              ->where('is_read', false)
                              ->count();
        
        return (object) [
            'other_user' => $latestMessage->sender_id == $this->id 
                ? ($latestMessage->receiver ?? null)
                : ($latestMessage->sender ?? null),
            'product' => $latestMessage->product ?? null,
            'latest_message' => $latestMessage,
            'unread_count' => $unreadCount,
            'messages' => $messages->sortBy('created_at'),
            'last_activity' => $latestMessage->created_at
        ];
    })->filter(function($conversation) {
        // Filter out any null conversations and conversations with missing data
        return $conversation !== null && 
               $conversation->other_user !== null && 
               $conversation->product !== null;
    })->sortByDesc('last_activity');

    return $conversations;
}
}