<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Http\Controllers\Admin\StockOutController;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'billing_address',
        'subtotal',
        'shipping_cost',
        'tax_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'cancellation_reason',
        'cancelled_at',
        'delivered_at',
        'notes',
        'status_history',
        'delivery_id',
        'assigned_at',
        'picked_up_at',
        'refund_processed',
        'refund_amount',
        'refund_method',
        'refund_notes',
        'refund_processed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'refund_processed' => 'boolean',
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refund_processed_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->items();
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    // Scopes
    public function scopeReadyForDelivery($query)
    {
        // CHANGED: Removed 'processing', now only 'confirmed' and 'shipped'
        return $query->whereIn('order_status', ['confirmed', 'shipped'])
                    ->whereNull('delivery_id');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('order_status', ['cancelled', 'completed', 'delivered']);
    }

    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('order_status', 'confirmed');
    }

    public function scopeProcessing($query)
    {
        return $query->where('order_status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('order_status', 'shipped');
    }

    public function scopeOutForDelivery($query)
    {
        return $query->where('order_status', 'out_for_delivery');
    }

    public function scopeDelivered($query)
    {
        return $query->where('order_status', 'delivered');
    }

    public function scopeCompleted($query)
    {
        return $query->where('order_status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('order_status', 'cancelled');
    }

    // Status Check Methods
    public function isPending(): bool
    {
        return $this->order_status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->order_status === 'confirmed';
    }

    public function isProcessing(): bool
    {
        return $this->order_status === 'processing';
    }

    public function isShipped(): bool
    {
        return $this->order_status === 'shipped';
    }

    public function isOutForDelivery(): bool
    {
        return $this->order_status === 'out_for_delivery';
    }

    public function isDelivered(): bool
    {
        return $this->order_status === 'delivered';
    }

    public function isCompleted(): bool
    {
        return $this->order_status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->order_status === 'cancelled';
    }

    // Business Logic Methods

    /**
     * Update order status with notes and create history
     */
    public function updateStatus(string $status, ?string $notes = null): self
    {
        $oldStatus = $this->order_status;
        
        $this->update([
            'order_status' => $status,
            'notes' => $notes,
        ]);

        // Create status history record
        if ($this->relationLoaded('statusHistory') || method_exists($this, 'statusHistory')) {
            $this->statusHistory()->create([
                'status' => $status,
                'notes' => $notes ?? "Status changed from {$oldStatus} to {$status}",
            ]);
        }

        // Handle specific status changes
        if ($status === 'delivered') {
            $this->markAsDelivered();
        }

        if ($status === 'out_for_delivery' && $this->delivery_id) {
            $this->update(['picked_up_at' => now()]);
        }

        if ($status === 'cancelled') {
            $this->update(['cancelled_at' => now()]);
            $this->restoreStock();
        }

        // NOTE: physical stock deduction is performed when order is marked 'shipped'.
        // Do not perform stock reduction on 'confirmed' to ensure FIFO is applied only once at 'shipped'.

        return $this;
    }

    /**
     * Assign order to delivery person
     */
    public function assignToDelivery(int $deliveryId): self
    {
        $this->update([
            'delivery_id' => $deliveryId,
            'assigned_at' => now(),
        ]);

        // Auto update status to out_for_delivery if it was shipped
        if ($this->order_status === 'shipped') {
            $this->updateStatus('out_for_delivery', 'Assigned to delivery personnel');
        }

        return $this;
    }

    /**
     * Mark order as picked up by delivery person
     */
    public function markAsPickedUp(): self
    {
        if (!$this->delivery_id) {
            throw new \Exception('Order must be assigned to a delivery person first.');
        }

        $this->update([
            'picked_up_at' => now(),
            'order_status' => 'out_for_delivery',
        ]);

        // Create status history
        if ($this->relationLoaded('statusHistory') || method_exists($this, 'statusHistory')) {
            $this->statusHistory()->create([
                'status' => 'out_for_delivery',
                'notes' => 'Order picked up by delivery personnel',
            ]);
        }

        return $this;
    }

    /**
     * Mark order as delivered
     */
    public function markAsDelivered(): self
    {
        if (!$this->delivery_id) {
            throw new \Exception('Order must be assigned to a delivery person first.');
        }

        $this->update([
            'delivered_at' => now(),
            'order_status' => 'delivered',
        ]);

        // Create status history
        if ($this->relationLoaded('statusHistory') || method_exists($this, 'statusHistory')) {
            $this->statusHistory()->create([
                'status' => 'delivered',
                'notes' => 'Order successfully delivered to customer',
            ]);
        }

        return $this;
    }

    /**
     * Cancel order with reason
     */
    public function cancel(string $reason): self
    {
        $this->update([
            'order_status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
        ]);

        // Create status history
        if ($this->relationLoaded('statusHistory') || method_exists($this, 'statusHistory')) {
            $this->statusHistory()->create([
                'status' => 'cancelled',
                'notes' => $reason,
            ]);
        }

        // Restore stock
        $this->restoreStock();

        return $this;
    }

    /**
     * Reduce stock when order is confirmed
     */
    public function reduceStock(): self
    {
        foreach ($this->items as $item) {
            if ($item->product) {
                // If a variant was selected, deduct from variant stock
                if ($item->selected_size) {
                    $variant = $item->product->variants()
                        ->where('variant_name', $item->selected_size)
                        ->first();

                    if ($variant) {
                        // Deduct from variant stock
                        $newVariantStock = max(0, $variant->stock_quantity - $item->quantity);
                        $variant->update(['stock_quantity' => $newVariantStock]);

                        // Update main product stock as sum of all variants
                        if ($item->product->has_variants) {
                            $totalVariantStock = $item->product->variants()->sum('stock_quantity');
                            $item->product->update(['stock_quantity' => $totalVariantStock]);
                        }
                    }
                } else {
                    // No variant selected, deduct from main product stock
                    $newStock = max(0, $item->product->stock_quantity - $item->quantity);
                    $item->product->update(['stock_quantity' => $newStock]);
                }
            }
        }

        return $this;
    }

    /**
     * Restore stock when order is cancelled
     */
    public function restoreStock(): self
    {
        // Only restore stock if there are StockOut records associated with this order.
        $stockOuts = \App\Models\StockOut::where('reason', 'like', '%Order #' . $this->id . '%')->get();

        if ($stockOuts->isEmpty()) {
            // Nothing to restore (no physical stock-out performed yet)
            return $this;
        }

        foreach ($stockOuts as $stockOut) {
            // Restore remaining_quantity on batches
            foreach ($stockOut->stockInBatches as $batch) {
                $batch->increment('remaining_quantity', $batch->pivot->deducted_quantity);
            }

            // Restore product/variant totals
            if ($stockOut->product_variant_id) {
                $variant = \App\Models\ProductVariant::find($stockOut->product_variant_id);
                if ($variant) {
                    $variant->increment('stock_quantity', $stockOut->quantity);
                    // Sync parent product total
                    if (method_exists($variant->product, 'updateTotalStock')) {
                        $variant->product->updateTotalStock();
                    }
                }
            } else {
                $product = \App\Models\Product::find($stockOut->product_id);
                if ($product) {
                    $product->increment('stock_quantity', $stockOut->quantity);
                }
            }

            // Detach pivot records and delete the StockOut log
            $stockOut->stockInBatches()->detach();
            $stockOut->delete();
        }

        return $this;
    }

    // Validation Methods

    /**
     * Check if order can be assigned to delivery
     */
    public function canAssignToDelivery(): bool
    {
        // CHANGED: Removed 'processing', now only 'confirmed' and 'shipped'
        return in_array($this->order_status, ['confirmed', 'shipped']) 
               && !$this->delivery_id;
    }

    /**
     * Check if order can be marked as delivered
     */
    public function canMarkAsDelivered(): bool
    {
        return in_array($this->order_status, ['out_for_delivery', 'shipped']) 
               && $this->delivery_id;
    }

    /**
     * Check if order can be cancelled by customer
     */
    public function canBeCancelled(): bool
    {
        // CHANGED: Removed 'processing', now only 'pending' and 'confirmed'
        return in_array($this->order_status, ['pending', 'confirmed']);
    }

    /**
     * Check if order can be refunded
     */
    public function canBeRefunded(): bool
    {
        return $this->isCancelled() && !$this->refund_processed;
    }

    // Helper Methods

    public function isAssignedToDelivery(): bool
    {
        return !is_null($this->delivery_id);
    }

    public function isReadyForDelivery(): bool
    {
        return $this->canAssignToDelivery();
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->order_status) {
            'pending' => 'bg-warning',
            'confirmed' => 'bg-info',
            'processing' => 'bg-primary',
            'shipped' => 'bg-secondary',
            'out_for_delivery' => 'bg-purple',
            'delivered' => 'bg-success',
            'completed' => 'bg-dark',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function getCancellationReasonDisplayAttribute(): string
    {
        return $this->cancellation_reason ?? 'No reason provided';
    }

    public function getIsDeliveredAttribute(): bool
    {
        return $this->order_status === 'delivered';
    }

    public function getIsCancelledAttribute(): bool
    {
        return $this->order_status === 'cancelled';
    }

    public function getFormattedTotalAttribute(): string
    {
        return '₱' . number_format($this->total_amount, 2);
    }

    public function getFormattedFinalAmountAttribute(): string
    {
        return '₱' . number_format($this->total_amount, 2);
    }

    /**
     * Calculate order totals
     */
    public function calculateTotals(): self
    {
        $subtotal = $this->items->sum('total_price');
        $taxAmount = $subtotal * 0.10;
        $finalAmount = $subtotal + $taxAmount + ($this->shipping_cost ?? 0);

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $finalAmount,
        ]);

        return $this;
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
            }
        });

        static::created(function ($order) {
            // Create initial status history if the relationship exists
            if (method_exists($order, 'statusHistory')) {
                $order->statusHistory()->create([
                    'status' => $order->order_status,
                    'notes' => 'Order created',
                ]);
            }
        });
    }

    // ========== ADDED METHODS FOR DELIVERY FLOW ==========

    /**
     * Check if order can be picked up by delivery person
     */
    public function canBePickedUp(): bool
    {
        // CHANGED: From 'processing' to 'confirmed'
        return $this->order_status === 'confirmed' && is_null($this->delivery_id);
    }

    /**
     * Check if order can be marked as delivered by delivery person
     */
    public function canBeMarkedAsDelivered(): bool
    {
        return in_array($this->order_status, ['shipped', 'out_for_delivery']) 
               && !is_null($this->delivery_id);
    }

    /**
     * Mark order as picked up by delivery person (for delivery flow)
     */
    public function markAsPickedUpByDelivery($deliveryId): self
    {
        $this->update([
            'delivery_id' => $deliveryId,
            'order_status' => 'shipped',
            'picked_up_at' => now(),
            'assigned_at' => now(),
        ]);

        // Create status history
        if ($this->relationLoaded('statusHistory') || method_exists($this, 'statusHistory')) {
            $this->statusHistory()->create([
                'status' => 'shipped',
                'notes' => 'Order picked up by delivery personnel',
            ]);
        }

        return $this;
    }

    /**
     * Mark order as delivered by delivery person
     */
    public function markAsDeliveredByDelivery(): self
    {
        $this->update([
            'order_status' => 'delivered',
            'delivered_at' => now(),
        ]);

        // Create status history
        if ($this->relationLoaded('statusHistory') || method_exists($this, 'statusHistory')) {
            $this->statusHistory()->create([
                'status' => 'delivered',
                'notes' => 'Order delivered to customer by delivery personnel',
            ]);
        }

        return $this;
    }

    /**
     * Get orders assigned to specific delivery person
     */
    public function scopeAssignedToDelivery($query, $deliveryId)
    {
        return $query->where('delivery_id', $deliveryId);
    }

    /**
     * Get available orders for pickup (no delivery person assigned)
     */
    public function scopeAvailableForPickup($query)
    {
        // CHANGED: From 'processing' to 'confirmed'
        return $query->where('order_status', 'confirmed')
                    ->whereNull('delivery_id');
    }

    /**
     * Get delivered orders by specific delivery person
     */
    public function scopeDeliveredBy($query, $deliveryId)
    {
        return $query->where('delivery_id', $deliveryId)
                    ->where('order_status', 'delivered');
    }

    /**
     * Get active orders for delivery person (shipped/out_for_delivery)
     */
    public function scopeActiveForDelivery($query, $deliveryId)
    {
        return $query->where('delivery_id', $deliveryId)
                    ->whereIn('order_status', ['shipped', 'out_for_delivery']);
    }
    
    // Add this to your Order model
    public function deliveryRecords()
    {
        return $this->hasMany(OrderDelivery::class);
    }

    public function currentDelivery()
    {
        return $this->hasOne(OrderDelivery::class)->latest();
    }
}