<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id', 
        'product_id',
        'quantity',
        'selected_size'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get total price for this cart item
     */
    public function getTotalPriceAttribute()
    {
        if ($this->product) {
            // For products with variants, get the price for the selected size
            if ($this->product->has_variants) {
                $variant = $this->product->variants->first(function($v) {
                    return ($v->size === $this->selected_size) || ($v->variant_name === $this->selected_size);
                });
                
                if ($variant) {
                    return $variant->current_price * $this->quantity;
                }
            }
            
            // For products without variants, use the product's current price
            return $this->product->current_price * $this->quantity;
        }
        
        return 0;
    }

    /**
     * Check if the selected size is still available
     */
    public function getIsSizeAvailableAttribute()
    {
        if (!$this->product) {
            return false;
        }

        return $this->product->isSizeInStock($this->selected_size);
    }

    /**
     * Get available stock for the selected size
     */
    public function getAvailableStockAttribute()
    {
        if (!$this->product) {
            return 0;
        }

        return $this->product->getStockForSize($this->selected_size);
    }

    /**
     * Check if cart item can be updated (has enough stock)
     */
    public function canUpdateQuantity($newQuantity)
    {
        if (!$this->product) {
            return false;
        }

        $availableStock = $this->getAvailableStockAttribute();
        return $availableStock >= $newQuantity;
    }
}