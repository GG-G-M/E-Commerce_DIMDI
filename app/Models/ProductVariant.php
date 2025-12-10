<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'variant_name',
        'variant_description',
        'image',
        'sku',
        'stock_quantity',
        'price',
        'sale_price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            
            if (file_exists(public_path($this->image))) {
                return asset($this->image);
            }
        }
        
        // Fallback to product image
        return $this->product->image_url;
    }

    public function getCurrentPriceAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->price ? $this->sale_price : $this->price;
    }

    public function getHasDiscountAttribute()
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->price;
    }

        public function getDiscountPercentageAttribute()
    {
        if (!$this->has_discount || $this->price <= 0) {
            return 0;
        }
        
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

        public function getDisplayNameAttribute()
    {
        return $this->size ?? $this->variant_name ?? 'Standard';
    }

    public function getInStockAttribute()
    {
        return $this->stock_quantity > 0;
    }

    public function getAvailableQuantityAttribute()
    {
        if ($this->stock_quantity > 10) {
            return 'In Stock';
        } elseif ($this->stock_quantity > 0) {
            return "Only {$this->stock_quantity} left";
        } else {
            return 'Out of Stock';
        }
    }

    /**
     * Keep parent product's stock_quantity in sync with variants.
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function (ProductVariant $variant) {
            if ($variant->product) {
                $variant->product->updateTotalStock();
            }
        });

        static::deleted(function (ProductVariant $variant) {
            if ($variant->product) {
                $variant->product->updateTotalStock();
            }
        });
    }
}