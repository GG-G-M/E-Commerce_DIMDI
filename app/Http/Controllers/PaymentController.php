<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{
    public function createIntent(Request $request)
    {
        try {
            $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
                ->post('https://api.paymongo.com/v1/payment_intents', [
                    'data' => [
                        'attributes' => [
                            'amount' => $request->amount * 100, // Convert to cents
                            'payment_method_allowed' => ['card', 'gcash', 'grab_pay'],
                            'payment_method_options' => [
                                'card' => [
                                    'request_three_d_secure' => 'automatic'
                                ]
                            ],
                            'currency' => 'PHP',
                            'capture_type' => 'automatic'
                        ]
                    ]
                ]);

            if ($response->failed()) {
                Log::error('PayMongo Intent Failed:', $response->json());
                return response()->json(['error' => 'Payment service error'], 500);
            }

            return response()->json([
                'client_key' => $response->json()['data']['attributes']['client_key'],
                'intent_id' => $response->json()['data']['id']
            ]);

        } catch (\Exception $e) {
            Log::error('PayMongo Error: ' . $e->getMessage());
            return response()->json(['error' => 'Payment service unavailable'], 503);
        }
    }

    // NEW METHOD: Handle GCash/GrabPay redirects
    public function createRedirectPayment(Request $request)
    {
        try {
            Log::info('Starting redirect payment', $request->all());
            
            $validated = $request->validate([
                'amount' => 'required|numeric',
                'payment_method' => 'required|in:gcash,grab_pay',
                'order_id' => 'required|exists:orders,id'
            ]);

            $order = Order::findOrFail($validated['order_id']);
            
            // Create source in PayMongo
            $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
                ->post('https://api.paymongo.com/v1/sources', [
                    'data' => [
                        'attributes' => [
                            'type' => $validated['payment_method'],
                            'amount' => $validated['amount'] * 100, // Convert to cents
                            'currency' => 'PHP',
                            'redirect' => [
                                'success' => route('payment.success'),
                                'failed' => route('payment.failed')
                            ]
                        ]
                    ]
                ]);

            $responseData = $response->json();
            Log::info('PayMongo Source Response:', $responseData);

            if ($response->failed()) {
                throw new \Exception('PayMongo API error: ' . json_encode($responseData));
            }

            // Update order with source ID
            $order->update([
                'payment_method_id' => $responseData['data']['id'],
                'payment_status' => 'pending'
            ]);

            // Return the redirect URL
            return response()->json([
                'success' => true,
                'redirect_url' => $responseData['data']['attributes']['redirect']['checkout_url']
            ]);

        } catch (\Exception $e) {
            Log::error('Redirect Payment Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order') ?? session('last_order_id');
        
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'confirmed'
                ]);

                // Clear cart
                Cart::where('user_id', auth()->id())->delete();
                session()->forget('last_order_id');

                return redirect()->route('orders.show', $order)
                    ->with('success', 'Payment completed successfully! Your order is now confirmed.');
            }
        }

        return redirect()->route('orders.index')
            ->with('info', 'Payment completed successfully!');
    }

    public function failed(Request $request)
    {
        $orderId = $request->query('order') ?? session('last_order_id');
        
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update([
                    'payment_status' => 'failed',
                    'order_status' => 'cancelled'
                ]);
            }
        }

        return view('payment.failed')
            ->with('error', 'Payment failed. Please try again.');
    }
}