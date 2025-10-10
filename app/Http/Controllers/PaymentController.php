<?php
namespace App\Http\Controllers;

use App\Models\Order;
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
                            'amount' => $request->amount,
                            'payment_method_allowed' => ['card', 'gcash', 'grab_pay'],
                            'payment_method_options' => [
                                'card' => [
                                    'request_three_d_secure' => 'automatic'
                                ]
                            ],
                            'currency' => $request->currency ?? 'PHP',
                            'capture_type' => 'automatic'
                        ]
                    ]
                ]);

            $responseData = $response->json();

            if ($response->failed()) {
                Log::error('PayMongo Intent Creation Failed:', $responseData);
                return response()->json([
                    'error' => 'Failed to create payment intent',
                    'details' => $responseData
                ], 500);
            }

            // Return the intent data directly
            return response()->json([
                'intent' => $responseData['data']
            ]);

        } catch (\Exception $e) {
            Log::error('PayMongo Intent Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Payment service unavailable',
                'message' => $e->getMessage()
            ], 503);
        }
    }

    public function createSourceForOrder(Order $order)
{
    $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
        ->post('https://api.paymongo.com/v1/sources', [
            'data' => [
                'attributes' => [
                    'type' => $order->payment_method, // 'gcash' or 'grab_pay'
                    'amount' => $order->total_amount * 100, // Convert to cents
                    'currency' => 'PHP',
                    'redirect' => [
                        'success' => route('payment.success', ['order' => $order->id]),
                        'failed' => route('payment.failed', ['order' => $order->id])
                    ]
                ]
            ]
        ]);

    $responseData = $response->json();

    if ($response->failed()) {
        throw new \Exception('Failed to create payment source: ' . json_encode($responseData));
    }

    $order->update([
        'payment_method_id' => $responseData['data']['id']
    ]);

    return $responseData;
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

            // Clear cart here if needed
            \App\Models\Cart::where('user_id', auth()->id())->delete();

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
    $orderId = $request->query('order');
    if ($orderId) {
        $order = Order::find($orderId);
        if ($order) {
            $order->update([
                'payment_status' => 'failed',
                'order_status' => 'cancelled'
            ]);
        }
    }

    return view('payment.failed', [
        'message' => 'Payment failed. Please try again.'
    ]);
}
}