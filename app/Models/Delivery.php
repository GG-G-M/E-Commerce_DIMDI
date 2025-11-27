<?php
// app/Models/Delivery.php

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

    // UPDATE THIS RELATIONSHIP
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ADD: Scope for active delivery personnel
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ADD: Get delivered orders count
    public function getDeliveredOrdersCountAttribute()
    {
        return $this->orders()->where('order_status', 'delivered')->count();
    }

    // ADD: Get pending delivery orders count
    public function getPendingDeliveryOrdersCountAttribute()
    {
        return $this->orders()->whereIn('order_status', ['out_for_delivery', 'confirmed'])->count();
    }
}