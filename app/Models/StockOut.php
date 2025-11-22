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

    /**
     * Decrement stock-in remaining quantity after stock-out
     */
    public function deductFromBatches(int $quantity)
    {
        $quantityToDeduct = $quantity;

        $batches = StockIn::where(function ($q) {
                    $q->when($this->product_id, fn($x) => $x->where('product_id', $this->product_id))
                      ->when($this->product_variant_id, fn($x) => $x->where('product_variant_id', $this->product_variant_id));
                })
                ->where('remaining_quantity', '>', 0)
                ->orderBy('created_at', 'asc')
                ->get();

        foreach ($batches as $batch) {
            if ($quantityToDeduct <= 0) break;

            $deducted = min($batch->remaining_quantity, $quantityToDeduct);

            $batch->decrement('remaining_quantity', $deducted);

            $this->stockInBatches()->attach($batch->id, ['deducted_quantity' => $deducted]);

            $quantityToDeduct -= $deducted;
        }

        if ($quantityToDeduct > 0) {
            return false; // Not enough stock
        }

        return true;
    }
}
