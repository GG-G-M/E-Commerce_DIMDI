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
        'street_address',
        'barangay',
        'city',
        'province',
        'region',
        'country',
        'vehicle_type',
        'vehicle_number',
        'license_number',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Role constants
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_DELIVERY = 'delivery';
    const ROLE_CUSTOMER = 'customer';

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

    // Relationships
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    // Role checking methods
    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isDelivery(): bool
    {
        return $this->role === self::ROLE_DELIVERY;
    }

    public function isCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER || empty($this->role);
    }

    // Check if user can create accounts
    public function canCreateRole($role)
    {
        if ($this->isSuperAdmin()) {
            return in_array($role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN, self::ROLE_DELIVERY, self::ROLE_CUSTOMER]);
        }
        
        if ($this->isAdmin()) {
            return in_array($role, [self::ROLE_DELIVERY, self::ROLE_CUSTOMER]);
        }
        
        return false;
    }

    // Get role display name
    public function getRoleNameAttribute()
    {
        $roles = [
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_DELIVERY => 'Delivery Staff',
            self::ROLE_CUSTOMER => 'Customer',
        ];
        
        return $roles[$this->role] ?? 'Customer';
    }
}
