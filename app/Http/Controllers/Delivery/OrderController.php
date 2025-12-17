<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Get the deliveries table ID for the current authenticated user
     */
    private function getDeliveriesTableId()
    {
        $deliveryUser = Auth::user();
        if (!$deliveryUser) {
            return null;
        }
        
        $deliveryEntry = DB::table('deliveries')->where('email', $deliveryUser->email)->first();
        return $deliveryEntry ? $deliveryEntry->id : null;
    }

    public function index()
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            $orders = collect([]);
            return view('delivery.orders.index', compact('orders'))
                ->with('error', 'You are not registered in the deliveries system.');
        }
        
        $orders = Order::where(function($query) use ($deliveriesTableId) {
                $query->where('delivery_id', $deliveriesTableId)
                      ->whereIn('order_status', ['shipped', 'out_for_delivery']);
            })
            ->orWhere(function($query) {
                $query->where('order_status', 'confirmed')
                      ->whereNull('delivery_id');
            })
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.index', compact('orders'));
    }

    public function pickup()
    {
        $orders = Order::where('order_status', 'confirmed')
            ->whereNull('delivery_id')
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.pickup', compact('orders'));
    }

    public function delivered()
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            $orders = collect([]);
            return view('delivery.orders.delivered', compact('orders'))
                ->with('error', 'You are not registered in the deliveries system.');
        }
        
        $orders = Order::where('delivery_id', $deliveriesTableId)
            ->where('order_status', 'delivered')
            ->with(['user', 'orderItems.product'])
            ->orderBy('delivered_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.delivered', compact('orders'));
    }

    public function myOrders()
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            $orders = collect([]);
            return view('delivery.orders.my-orders', compact('orders'))
                ->with('error', 'You are not registered in the deliveries system.');
        }
        
        $orders = Order::where('delivery_id', $deliveriesTableId)
            ->whereIn('order_status', ['shipped', 'out_for_delivery'])
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.my-orders', compact('orders'));
    }

    public function show(Order $order)
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            abort(403, 'You are not registered in the deliveries system.');
        }
        
        // Allow viewing if:
        // 1. Order is assigned to current delivery person, OR
        // 2. Order is available for pickup (no delivery person assigned)
        if ($order->delivery_id !== $deliveriesTableId && !is_null($order->delivery_id)) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['user', 'orderItems.product', 'statusHistory']);

        return view('delivery.orders.show', compact('order'));
    }

    public function markAsPickedUp(Order $order, Request $request)
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            Log::error('Delivery staff not found in deliveries table', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email
            ]);
            return redirect()->back()->with('error', 'You are not registered in the deliveries system.');
        }
        
        // Check if order can be picked up using model method
        if (!$order->canBePickedUp()) {
            return redirect()->back()->with('error', 'This order is no longer available for pickup.');
        }

        try {
            DB::transaction(function () use ($order, $deliveriesTableId) {
                // Load order items before processing
                $order->load('items.product.variants');
                
                // Use deliveries table ID
                $order->update([
                    'delivery_id' => $deliveriesTableId, // This is from deliveries table
                    'picked_up_at' => now(),
                    'assigned_at' => now(),
                ]);
                
                $order->updateStatus('shipped', 'Order picked up by delivery personnel');
                
                // AUTO STOCK-OUT WHEN ORDER BECOMES SHIPPED (Same logic as admin)
                // If FIFO stock-out already occurred at confirmation, skip to avoid double deduction
                $existingStockOut = \App\Models\StockOut::where('reason', 'like', '%Order #' . $order->id . '%')->exists();
                if (!$existingStockOut) {
                    foreach ($order->items as $item) {
                        // Resolve variant id from selected_size when available
                        $variantId = null;
                        if (!empty($item->selected_size) && $item->product) {
                            // First try a DB lookup by variant_name
                            $variant = $item->product->variants()->where('variant_name', $item->selected_size)->first();

                            // Fallback: check the loaded collection (accessors like ->size may exist)
                            if (!$variant) {
                                $variant = $item->product->variants->first(function ($v) use ($item) {
                                    return ($v->variant_name === $item->selected_size) || (isset($v->size) && $v->size === $item->selected_size);
                                });
                            }

                            if ($variant) {
                                $variantId = $variant->id;
                            }
                        }

                        app(\App\Http\Controllers\Admin\StockOutController::class)
                            ->autoStockOut(
                                $item->product_id,
                                $variantId,
                                $item->quantity,
                                'Order #' . $order->id . ' shipped'
                            );
                    }
                }
            });

            return redirect()->route('delivery.orders.index')
                ->with('success', 'Order #' . $order->order_number . ' has been assigned to you and marked as shipped!');

        } catch (\Exception $e) {
            Log::error('Failed to mark order as picked up', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to mark order as picked up: ' . $e->getMessage());
        }
    }

    /**
     * Bulk pickup multiple orders at once
     */
    public function bulkPickup(Request $request)
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            return redirect()->back()->with('error', 'You are not registered in the deliveries system.');
        }

        $request->validate([
            'order_ids' => 'required|json',
            'pickup_notes' => 'nullable|string|max:500'
        ]);

        $orderIds = json_decode($request->order_ids);
        $pickupNotes = $request->pickup_notes;
        
        if (!is_array($orderIds) || empty($orderIds)) {
            return redirect()->back()->with('error', 'No orders selected for bulk pickup.');
        }

        $successCount = 0;
        $failedOrders = [];
        $processedOrders = [];

        DB::beginTransaction();
        
        try {
            foreach ($orderIds as $orderId) {
                try {
                    $order = Order::find($orderId);
                    
                    if (!$order) {
                        $failedOrders[] = "Order #{$orderId} (Not found)";
                        continue;
                    }
                    
                    // Check if order can be picked up
                    if (!$order->canBePickedUp()) {
                        $failedOrders[] = "Order #{$order->order_number} (Not available)";
                        continue;
                    }
                    
                    // Load order items before processing
                    $order->load('items.product.variants');
                    
                    // Use deliveries table ID
                    $order->update([
                        'delivery_id' => $deliveriesTableId,
                        'picked_up_at' => now(),
                        'assigned_at' => now(),
                    ]);
                    
                    $statusNote = 'Order picked up by delivery personnel';
                    if ($pickupNotes) {
                        $statusNote .= ' | Notes: ' . $pickupNotes;
                    }
                    
                    $order->updateStatus('shipped', $statusNote);
                    
                    // AUTO STOCK-OUT WHEN ORDER BECOMES SHIPPED
                    $existingStockOut = \App\Models\StockOut::where('reason', 'like', '%Order #' . $order->id . '%')->exists();
                    if (!$existingStockOut) {
                        foreach ($order->items as $item) {
                            // Resolve variant id from selected_size when available
                            $variantId = null;
                            if (!empty($item->selected_size) && $item->product) {
                                $variant = $item->product->variants()->where('variant_name', $item->selected_size)->first();
                                
                                if (!$variant) {
                                    $variant = $item->product->variants->first(function ($v) use ($item) {
                                        return ($v->variant_name === $item->selected_size) || (isset($v->size) && $v->size === $item->selected_size);
                                    });
                                }
                                
                                if ($variant) {
                                    $variantId = $variant->id;
                                }
                            }
                            
                            app(\App\Http\Controllers\Admin\StockOutController::class)
                                ->autoStockOut(
                                    $item->product_id,
                                    $variantId,
                                    $item->quantity,
                                    'Order #' . $order->id . ' shipped'
                                );
                        }
                    }
                    
                    $successCount++;
                    $processedOrders[] = $order->order_number;
                    
                } catch (\Exception $e) {
                    $failedOrders[] = "Order #{$orderId} (Error: " . $e->getMessage() . ")";
                    Log::error('Error in bulk pickup for order ' . $orderId, [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
            
            DB::commit();
            
            if ($successCount > 0) {
                $message = "Successfully picked up {$successCount} order(s): #" . implode(', #', $processedOrders);
                
                if (!empty($failedOrders)) {
                    $message .= "\nFailed to pick up " . count($failedOrders) . " order(s): " . implode(', ', $failedOrders);
                }
                
                return redirect()->route('delivery.orders.pickup')->with('success', $message);
            } else {
                return redirect()->route('delivery.orders.pickup')->with('error', 'Failed to pick up any orders. ' . implode(', ', $failedOrders));
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk pickup transaction failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('delivery.orders.pickup')->with('error', 'Bulk pickup failed: ' . $e->getMessage());
        }
    }
    
    public function testDeliverRoute(Order $order)
    {
        return response()->json([
            'success' => true,
            'message' => 'Test route working',
            'order_id' => $order->id,
            'order_number' => $order->order_number
        ]);
    }

    public function markAsDelivered(Order $order, Request $request)
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            return response()->json([
                'success' => false, 
                'message' => 'You are not registered in the deliveries system.'
            ], 403);
        }
        
        // Verify the order belongs to the current delivery driver
        if ($order->delivery_id !== $deliveriesTableId) {
            return response()->json([
                'success' => false, 
                'message' => 'Unauthorized action. This order is not assigned to you.'
            ], 403);
        }

        // Check if order can be marked as delivered using model method
        if (!$order->canBeMarkedAsDelivered()) {
            return response()->json([
                'success' => false, 
                'message' => 'This order cannot be marked as delivered. Current status: ' . $order->order_status
            ], 400);
        }

        try {
            // Validate request
            $request->validate([
                'delivery_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
                'delivery_notes' => 'nullable|string|max:500'
            ]);

            $updateData = [
                'delivered_at' => now(),
            ];

            // Handle file upload
            if ($request->hasFile('delivery_photo')) {
                $photo = $request->file('delivery_photo');
                
                // Validate image
                if (!$photo->isValid()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid image file uploaded.'
                    ], 400);
                }

                // Generate unique filename
                $filename = 'delivery_proof_' . $order->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
                
                // Store the file
                $path = $photo->storeAs('delivery_proofs', $filename, 'public');
                
                if ($path) {
                    $updateData['delivery_proof_photo'] = $path;
                } else {
                    Log::error('Failed to store delivery proof photo', [
                        'order_id' => $order->id,
                        'filename' => $filename
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to save delivery proof photo.'
                    ], 500);
                }
            }

            // Add delivery notes if provided
            if ($request->filled('delivery_notes')) {
                $updateData['delivery_notes'] = $request->delivery_notes;
            }

            // Update the order
            $order->update($updateData);

            // Get delivery staff name for status notes
            $deliveryStaffName = 'Delivery Personnel';
            if (Auth::check()) {
                $deliveryStaffName = Auth::user()->name ?? 'Delivery Personnel';
            }

            // Update status with notes
            $statusNotes = "Order delivered by {$deliveryStaffName}";
            if (!empty($updateData['delivery_notes'])) {
                $statusNotes .= ' | Notes: ' . $updateData['delivery_notes'];
            }
            if (!empty($updateData['delivery_proof_photo'])) {
                $statusNotes .= ' | Proof photo uploaded';
            }

            $order->updateStatus('delivered', $statusNotes);

            Log::info('Order marked as delivered successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'delivery_id' => $deliveriesTableId,
                'has_photo' => !empty($updateData['delivery_proof_photo']),
                'has_notes' => !empty($updateData['delivery_notes'])
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'Order #' . $order->order_number . ' has been marked as delivered successfully!',
                'order_id' => $order->id
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for order delivery', [
                'order_id' => $order->id,
                'errors' => $e->errors()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', array_values($e->errors())[0] ?? ['Unknown error'])
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Failed to mark order as delivered', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Failed to mark order as delivered: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAsOutForDelivery(Order $order, Request $request)
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            return redirect()->back()->with('error', 'You are not registered in the deliveries system.');
        }
        
        if ($order->delivery_id !== $deliveriesTableId) {
            return redirect()->back()->with('error', 'Unauthorized action. This order is not assigned to you.');
        }

        if ($order->order_status !== 'shipped') {
            return redirect()->back()->with('error', 'Order must be in shipped status to mark as out for delivery.');
        }

        try {
            $order->updateStatus('out_for_delivery', 'Order is out for delivery');

            return redirect()->back()->with('success', 'Order marked as out for delivery successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    public function updateDeliveryNotes(Order $order, Request $request)
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            return response()->json(['error' => 'You are not registered in the deliveries system.'], 403);
        }
        
        if ($order->delivery_id !== $deliveriesTableId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'delivery_notes' => 'nullable|string|max:500'
        ]);

        try {
            $order->update([
                'notes' => $request->delivery_notes
            ]);

            return response()->json(['success' => 'Delivery notes updated successfully']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update notes'], 500);
        }
    }

    public function claimOrder(Order $order, Request $request)
    {
        // Alias for markAsPickedUp
        return $this->markAsPickedUp($order, $request);
    }

    public function getOrderStats()
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        
        if (!$deliveriesTableId) {
            return response()->json([
                'total_assigned' => 0,
                'available_for_pickup' => 0,
                'delivered_today' => 0,
                'total_delivered' => 0
            ]);
        }
        
        $stats = [
            'total_assigned' => Order::where('delivery_id', $deliveriesTableId)
                ->whereIn('order_status', ['shipped', 'out_for_delivery'])
                ->count(),
                
            'available_for_pickup' => Order::where('order_status', 'confirmed')
                ->whereNull('delivery_id')
                ->count(),
                
            'delivered_today' => Order::where('delivery_id', $deliveriesTableId)
                ->where('order_status', 'delivered')
                ->whereDate('delivered_at', today())
                ->count(),
                
            'total_delivered' => Order::where('delivery_id', $deliveriesTableId)
                ->where('order_status', 'delivered')
                ->count(),
        ];

        return response()->json($stats);
    }

    public function searchOrders(Request $request)
    {
        $deliveriesTableId = $this->getDeliveriesTableId();
        $search = $request->get('search');
        
        if (!$deliveriesTableId) {
            $orders = collect([]);
            return view('delivery.orders.index', compact('orders'))
                ->with('error', 'You are not registered in the deliveries system.');
        }
        
        $orders = Order::where(function($query) use ($deliveriesTableId, $search) {
                $query->where('delivery_id', $deliveriesTableId)
                      ->whereIn('order_status', ['shipped', 'out_for_delivery'])
                      ->where(function($q) use ($search) {
                          $q->where('order_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%")
                            ->orWhere('customer_phone', 'like', "%{$search}%");
                      });
            })
            ->orWhere(function($query) use ($search) {
                $query->where('order_status', 'confirmed')
                      ->whereNull('delivery_id')
                      ->where(function($q) use ($search) {
                          $q->where('order_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%")
                            ->orWhere('customer_phone', 'like', "%{$search}%");
                      });
            })
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('delivery.orders.index', compact('orders'));
    }
}