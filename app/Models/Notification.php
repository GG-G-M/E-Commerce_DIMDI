<?php

// Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// REMOVED: use Illuminate\Notifications\Notifiable; // Remove this trait

class Notification extends Model
{
    // REMOVED: , Notifiable // Remove trait usage
    use HasFactory; 

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'read_at',
    ];

    public function user()
    {
        // FIX: Complete the relationship
        return $this->belongsTo(User::class);
    }
}