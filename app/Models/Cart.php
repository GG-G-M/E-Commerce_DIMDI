<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'product_id',
        'selected_size',
        'quantity'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->product->getVariantBySize($this->selected_size);
    }

    public function getTotalPriceAttribute()
    {
        $variant = $this->variant();
        if ($variant && $variant->current_price) {
            return $variant->current_price * $this->quantity;
        }
        return $this->product->current_price * $this->quantity;
    }

    // Check if cart item is still available
    public function getIsAvailableAttribute()
    {
        $variant = $this->variant();
        if (!$variant) {
            return false;
        }
        return $variant->stock_quantity >= $this->quantity;
    }
}