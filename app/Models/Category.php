<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getImageUrlAttribute()
    {
        // If image is already a full URL, return it
        if ($this->image && filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        // If image is stored in public directory
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        
        // Fallback to placeholder
        return 'https://picsum.photos/400/300?random=' . uniqid();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}