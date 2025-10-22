<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_email',
        'customer_name',
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
        'notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'delivered_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function canBeCancelled()
    {
        return in_array($this->order_status, ['pending', 'confirmed']);
    }

    // Update status and create history entry
    public function updateStatus($newStatus, $notes = null)
    {
        $oldStatus = $this->order_status;
        
        // Only proceed if status is actually changing
        if ($oldStatus === $newStatus) {
            return;
        }
        
        // Update the order status
        $this->update(['order_status' => $newStatus]);
        
        // Create status history entry
        OrderStatusHistory::create([
            'order_id' => $this->id,
            'status' => $newStatus,
            'notes' => $notes,
            'created_at' => now()
        ]);

        // Handle specific status timestamps
        if ($newStatus === 'cancelled') {
            $this->update([
                'cancellation_reason' => $notes,
                'cancelled_at' => now()
            ]);
            
            // Restore product stock when cancelled
            $this->restoreStock();
        } elseif ($newStatus === 'delivered') {
            $this->update(['delivered_at' => now()]);
        }
    }

    // Restore stock for cancelled orders
    private function restoreStock()
    {
        foreach ($this->items as $item) {
            $product = $item->product;
            
            if ($product) {
                // Check if product has variants
                if ($product->has_variants && $item->selected_size) {
                    // Find the specific variant that was ordered
                    $variant = $product->variants->first(function($v) use ($item) {
                        return ($v->size === $item->selected_size) || ($v->variant_name === $item->selected_size);
                    });
                    
                    if ($variant) {
                        // Restore variant stock
                        $variant->increment('stock_quantity', $item->quantity);
                    } else {
                        // Fallback: restore main product stock if variant not found
                        $product->increment('stock_quantity', $item->quantity);
                    }
                } else {
                    // For products without variants, restore main product stock
                    $product->increment('stock_quantity', $item->quantity);
                }
            }
        }
    }

    // Mark as delivered
    public function markAsDelivered()
    {
        $this->updateStatus('delivered', 'Order delivered to customer');
    }

    // Get current status with formatted date
    public function getCurrentStatusAttribute()
    {
        $latestHistory = $this->statusHistory()->first();
        return [
            'status' => $this->order_status,
            'timestamp' => $latestHistory ? $latestHistory->created_at : $this->created_at,
            'notes' => $latestHistory ? $latestHistory->notes : 'Order created',
            'formatted_date' => ($latestHistory ? $latestHistory->created_at : $this->created_at)->format('M j, Y g:i A'),
            'status_label' => ucfirst($this->order_status)
        ];
    }

    // Get formatted status history for display
    public function getFormattedStatusHistoryAttribute()
    {
        $history = $this->statusHistory;
        
        // If no history exists, create initial entry from order creation
        if ($history->isEmpty()) {
            return collect([[
                'status' => 'pending',
                'timestamp' => $this->created_at,
                'notes' => 'Order created',
                'formatted_date' => $this->created_at->format('M j, Y g:i A'),
                'status_label' => 'Pending'
            ]]);
        }
        
        return $history->map(function($entry) {
            return [
                'status' => $entry->status,
                'timestamp' => $entry->created_at,
                'notes' => $entry->notes,
                'formatted_date' => $entry->created_at->format('M j, Y g:i A'),
                'status_label' => ucfirst($entry->status)
            ];
        });
    }

    // Check if order is delivered
    public function getIsDeliveredAttribute()
    {
        return $this->order_status === 'delivered';
    }

    // Check if order is cancelled
    public function getIsCancelledAttribute()
    {
        return $this->order_status === 'cancelled';
    }

    // Get cancellation reason with fallback
    public function getCancellationReasonDisplayAttribute()
    {
        if ($this->is_cancelled) {
            return $this->cancellation_reason ?: 'No reason provided';
        }
        return null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $date = date('Ymd');
                $random = strtoupper(bin2hex(random_bytes(4)));
                $order->order_number = "ORD-{$date}-{$random}";
            }
        });

        static::created(function ($order) {
            // Create initial status history entry
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'notes' => 'Order created',
                'created_at' => $order->created_at
            ]);
        });
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->items->sum('total_price');
        $this->tax_amount = $this->subtotal * 0.10;
        $this->shipping_cost = $this->subtotal > 100 ? 0 : 10;
        $this->total_amount = $this->subtotal + $this->tax_amount + $this->shipping_cost;
        $this->save();
    }

    public function reduceStock()
{
    foreach ($this->items as $item) {
        $product = $item->product;
        
        if ($product) {
            // Check if product has variants
            if ($product->has_variants && $item->selected_size) {
                // Find the specific variant that was ordered
                $variant = $product->variants->first(function($v) use ($item) {
                    return ($v->size === $item->selected_size) || ($v->variant_name === $item->selected_size);
                });
                
                if ($variant) {
                    // Reduce variant stock
                    $variant->decrement('stock_quantity', $item->quantity);
                } else {
                    // Fallback: reduce main product stock if variant not found
                    $product->decrement('stock_quantity', $item->quantity);
                }
            } else {
                // For products without variants, reduce main product stock
                $product->decrement('stock_quantity', $item->quantity);
            }
        }
    }
}
}