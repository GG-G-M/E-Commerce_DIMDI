<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRecentFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeOldestFirst($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    public function scopeBetweenUsers($query, $user1, $user2, $productId = null)
    {
        $query = $query->where(function($q) use ($user1, $user2) {
            $q->where('sender_id', $user1)
              ->where('receiver_id', $user2);
        })->orWhere(function($q) use ($user1, $user2) {
            $q->where('sender_id', $user2)
              ->where('receiver_id', $user1);
        });

        if ($productId) {
            $query->where('product_id', $productId);
        }

        return $query;
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    // Timestamp Methods
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getFormattedTimeAttribute()
    {
        if ($this->created_at->isToday()) {
            return $this->created_at->format('H:i');
        } elseif ($this->created_at->isYesterday()) {
            return 'Yesterday ' . $this->created_at->format('H:i');
        } elseif ($this->created_at->isCurrentWeek()) {
            return $this->created_at->format('D H:i');
        } else {
            return $this->created_at->format('M j, H:i');
        }
    }

    public function getDateHeaderAttribute()
    {
        if ($this->created_at->isToday()) {
            return 'Today';
        } elseif ($this->created_at->isYesterday()) {
            return 'Yesterday';
        } else {
            return $this->created_at->format('F j, Y');
        }
    }

    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }
    }

    // Check if message is recent (within 5 minutes)
    public function getIsRecentAttribute()
    {
        return $this->created_at->gt(now()->subMinutes(5));
    }
}