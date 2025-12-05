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
        'supplier_id',       // added
        'stock_checker_id',  // added
        'quantity',
        'remaining_quantity',
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

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function checker(): BelongsTo
    {
        return $this->belongsTo(StockChecker::class, 'stock_checker_id');
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
     * Auto-set remaining_quantity on creation
     */
    protected static function booted()
    {
        static::creating(function ($stockIn) {
            $stockIn->remaining_quantity = $stockIn->quantity;
        });

        static::created(function ($stockIn) {
            // Increment product or variant total stock
            if ($stockIn->product_id) {
                $stockIn->product->increment('stock_quantity', $stockIn->quantity);
            }

            if ($stockIn->product_variant_id) {
                $stockIn->variant->increment('stock_quantity', $stockIn->quantity);
                // Optional: update parent product total stock
                if (method_exists($stockIn->variant->product, 'updateTotalStock')) {
                    $stockIn->variant->product->updateTotalStock();
                }
            }
        });
    }
}
