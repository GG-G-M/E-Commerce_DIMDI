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
        'sizes',
        'is_featured',
        'is_active',
        'is_archived',
        'category_id'
    ];

    protected $casts = [
        'sizes' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'is_archived' => 'boolean',
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

    public function getAvailableSizesAttribute()
    {
        if (!$this->sizes) {
            return ['One Size'];
        }
        
        $sizes = json_decode($this->sizes, true) ?: [];
        return !empty($sizes) ? $sizes : ['One Size'];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('is_archived', false)
                    ->whereHas('category', function($q) {
                        $q->where('is_active', true);
                    });
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%");
        });
    }

    public function scopeFilterByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    public function scopeFilterByStatus($query, $status)
    {
        switch ($status) {
            case 'active':
                return $query->where('is_active', true)
                            ->where('is_archived', false)
                            ->whereHas('category', function($q) {
                                $q->where('is_active', true);
                            });
            case 'inactive':
                return $query->where(function($q) {
                    $q->where('is_active', false)
                      ->orWhereHas('category', function($q2) {
                          $q2->where('is_active', false);
                      });
                })->where('is_archived', false);
            case 'archived':
                return $query->where('is_archived', true);
            case 'featured':
                return $query->where('is_featured', true)
                            ->where('is_archived', false)
                            ->whereHas('category', function($q) {
                                $q->where('is_active', true);
                            });
            case 'all':
                return $query; // Show all products including archived
            default:
                return $query->where('is_archived', false);
        }
    }

    // Check if product is effectively inactive (either product inactive or category inactive)
    public function getIsEffectivelyInactiveAttribute()
    {
        return !$this->is_active || !$this->category->is_active;
    }

    public function archive()
    {
        $this->update(['is_archived' => true]);
    }

    public function unarchive()
    {
        $this->update(['is_archived' => false]);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}