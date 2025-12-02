<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'delivery_personnel_id',
        'status',
        'assigned_at',
        'picked_up_at',
        'delivered_at',
        'delivery_notes',
        'customer_signature'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Relationship with Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relationship with Delivery Personnel (Delivery model)
    public function deliveryPersonnel()
    {
        return $this->belongsTo(Delivery::class, 'delivery_personnel_id');
    }

    // Status scopes
    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['assigned', 'picked_up', 'in_transit']);
    }
}