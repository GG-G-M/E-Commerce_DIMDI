<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'size',
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

    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price ?? $this->product->current_price;
    }

    public function getHasDiscountAttribute()
    {
        return !is_null($this->sale_price) && $this->sale_price < ($this->price ?? $this->product->price);
    }

    // Check if variant is in stock
    public function getInStockAttribute()
    {
        return $this->stock_quantity > 0;
    }

    // Get available quantity for display
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
}