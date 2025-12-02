<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Delivery extends Authenticatable
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $guard = 'delivery';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'vehicle_type',
        'vehicle_number',
        'license_number',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // KEEP: Direct orders relationship (if you still want it)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ADD: Relationship with OrderDeliveries (delivery tracking)
    public function orderDeliveries()
    {
        return $this->hasMany(OrderDelivery::class, 'delivery_personnel_id');
    }

    // ADD: Get active delivery assignments
    public function activeDeliveries()
    {
        return $this->orderDeliveries()->whereIn('status', ['assigned', 'picked_up', 'in_transit']);
    }

    // ADD: Get completed deliveries
    public function completedDeliveries()
    {
        return $this->orderDeliveries()->where('status', 'delivered');
    }

    // Scope for active delivery personnel
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ADD: Get delivered orders count through order_deliveries
    public function getDeliveredOrdersCountAttribute()
    {
        return $this->orderDeliveries()->where('status', 'delivered')->count();
    }

    // ADD: Get pending delivery orders count through order_deliveries
    public function getPendingDeliveryOrdersCountAttribute()
    {
        return $this->orderDeliveries()->whereIn('status', ['assigned', 'picked_up', 'in_transit'])->count();
    }

    // ADD: Get success rate
    public function getSuccessRateAttribute()
    {
        $total = $this->orderDeliveries()->count();
        $delivered = $this->orderDeliveries()->where('status', 'delivered')->count();
        
        return $total > 0 ? round(($delivered / $total) * 100, 2) : 0;
    }
}