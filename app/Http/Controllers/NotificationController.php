<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        
        // Mark all as read when viewing all notifications
        Auth::user()->unreadNotifications->markAsRead();
        
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Auth::user()->notifications()->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    public function clearAll()
    {
        Auth::user()->notifications()->delete();
        return response()->json(['success' => true]);
    }

    public function list()
    {
        $notifications = Auth::user()->notifications()->take(5)->get();
        
        return view('partials.notification-list', compact('notifications'))->render();
    }

    public function checkNew()
    {
        $user = Auth::user();
        $latestNotification = $user->notifications()->first();
        $newCount = $user->unreadNotifications()->count();
        
        if ($latestNotification) {
            $data = $latestNotification->data;
            $data['time_ago'] = $latestNotification->created_at->diffForHumans();
            
            return response()->json([
                'new_count' => $newCount,
                'latest' => $data
            ]);
        }
        
        return response()->json(['new_count' => $newCount]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }

    /**
     * View receipt for an order notification
     */
    public function viewReceipt($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        
        if (!$notification) {
            abort(404);
        }
        
        // Mark notification as read
        $notification->markAsRead();
        
        // Get order from notification data
        $orderId = $notification->data['order_id'] ?? null;
        
        if (!$orderId) {
            abort(404);
        }
        
        $order = Order::with(['items.product', 'user'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        return view('receipt.view', compact('order', 'notification'));
    }

    /**
     * Download receipt PDF
     */
    public function downloadReceipt($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        
        if (!$notification) {
            abort(404);
        }
        
        $orderId = $notification->data['order_id'] ?? null;
        
        if (!$orderId) {
            abort(404);
        }
        
        $order = Order::with(['items.product', 'user'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        // Generate PDF
        $pdf = Pdf::loadView('receipt.pdf', compact('order'));
        
        // Mark notification as read
        $notification->markAsRead();
        
        // Download PDF
        return $pdf->download("receipt-{$order->order_number}.pdf");
    }

    /**
     * Preview receipt PDF in browser
     */
    public function previewReceipt($notificationId)
    {
        $notification = Auth::user()->notifications()->where('id', $notificationId)->first();
        
        if (!$notification) {
            abort(404);
        }
        
        $orderId = $notification->data['order_id'] ?? null;
        
        if (!$orderId) {
            abort(404);
        }
        
        $order = Order::with(['items.product', 'user'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        // Generate PDF
        $pdf = Pdf::loadView('receipt.pdf', compact('order'));
        
        // Mark notification as read
        $notification->markAsRead();
        
        // Preview in browser
        return $pdf->stream("receipt-{$order->order_number}.pdf");
    }
}