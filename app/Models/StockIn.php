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
     * MANY-TO-MANY (FIFO batches consumed by stock-out)
     */
    public function stockOutLogs()
    {
        return $this->belongsToMany(StockOut::class, 'stock_in_stock_out')
                    ->withPivot('deducted_quantity')
                    ->withTimestamps();
    }

    /**
     * Auto-increment stock on creation
     */
    protected static function booted()
    {
        static::created(function ($stockIn) {
            if ($stockIn->product_id) {
                $product = $stockIn->product;
                $product->increment('stock_quantity', $stockIn->quantity);
            }

            if ($stockIn->product_variant_id) {
                $variant = $stockIn->variant;
                $variant->increment('stock_quantity', $stockIn->quantity);

                // Update parent product stock if needed
                $variant->product->updateTotalStock();
            }
        });
    }
}
