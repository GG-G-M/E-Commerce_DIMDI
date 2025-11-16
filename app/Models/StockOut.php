<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    protected $fillable = [
        'product_id',
        'product_variant_id',
        'quantity',
        'reason',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * MANY-TO-MANY (each stock-out may consume multiple stock-in batches)
     */
    public function stockInBatches()
    {
        return $this->belongsToMany(StockIn::class, 'stock_in_stock_out')
                    ->withPivot('deducted_quantity')
                    ->withTimestamps();
    }
}
