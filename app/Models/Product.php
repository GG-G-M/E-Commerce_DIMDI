<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'stock_quantity',
        'sku',
        'image',
        'gallery',
        'is_featured',
        'is_active',
        'category_id'
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getHasDiscountAttribute()
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->has_discount) {
            return 0;
        }
        
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function getImageUrlAttribute()
    {
        // If image is already a full URL (from seeder), return it
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

    public function getGalleryUrlsAttribute()
    {
        if (!$this->gallery) {
            return [$this->image_url];
        }
        
        $gallery = json_decode($this->gallery, true) ?: [];
        $urls = [];
        
        foreach ($gallery as $image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                $urls[] = $image;
            } elseif (file_exists(public_path($image))) {
                $urls[] = asset($image);
            }
        }
        
        return !empty($urls) ? $urls : [$this->image_url];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}