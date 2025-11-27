<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    protected $table = 'order_status_history';
    
    protected $fillable = [
        'order_id',
        'status',
        'notes',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public $timestamps = false;

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}