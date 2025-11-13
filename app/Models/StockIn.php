<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockIn extends Model
{
    protected $fillable = [
        'product_id',
        'product_variant_id',
        'warehouse_id',
        'quantity',
        'reason',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Update stock quantity after creating a stock-in
     */
    protected static function booted()
    {
        static::created(function ($stockIn) {
            if ($stockIn->product_id) {
                $product = $stockIn->product;
                $product->increment('stock_quantity', $stockIn->quantity);
            } elseif ($stockIn->product_variant_id) {
                $variant = $stockIn->variant;
                $variant->increment('stock_quantity', $stockIn->quantity);

                // Optionally, update parent product total stock
                $variant->product->updateTotalStock();
            }
        });
    }
}
