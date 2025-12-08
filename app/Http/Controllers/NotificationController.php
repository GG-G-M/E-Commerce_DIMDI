<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }
    
    // NEW: Handle receipt viewing through notifications
    public function viewReceipt($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $orderId = $notification->data['order_id'] ?? null;
        
        if (!$orderId) {
            return redirect()->route('notifications.index')
                ->with('error', 'Order information not found in notification.');
        }
        
        $order = Order::findOrFail($orderId);
        
        // Authorization check
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Mark notification as read
        $notification->markAsRead();
        
        // Render receipt view (allows download/preview using the notification id)
        return view('receipt.view', compact('order', 'notification'));
    }
    
    public function downloadReceipt($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $orderId = $notification->data['order_id'] ?? null;
        
        if (!$orderId) {
            return redirect()->route('notifications.index')
                ->with('error', 'Order information not found in notification.');
        }
        
        $order = Order::findOrFail($orderId);
        
        // Authorization check
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Mark notification as read
        $notification->markAsRead();
        
        // Generate PDF
        $order->load(['items.product', 'user']);
        $pdf = Pdf::loadView('receipt.pdf', compact('order'));
        
        return $pdf->download("receipt-{$order->order_number}.pdf");
    }
    
    public function previewReceipt($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $orderId = $notification->data['order_id'] ?? null;
        
        if (!$orderId) {
            return redirect()->route('notifications.index')
                ->with('error', 'Order information not found in notification.');
        }
        
        $order = Order::findOrFail($orderId);
        
        // Authorization check
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Mark notification as read
        $notification->markAsRead();
        
        // Generate PDF
        $order->load(['items.product', 'user']);
        $pdf = Pdf::loadView('receipt.pdf', compact('order'));
        
        return $pdf->stream("receipt-{$order->order_number}.pdf");
    }
    
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        
        return back()->with('success', 'All notifications marked as read');
    }
    
    // Add these additional methods:
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return back()->with('success', 'Notification deleted.');
    }
    
    public function clearAll()
    {
        Auth::user()->notifications()->delete();
        
        return back()->with('success', 'All notifications cleared.');
    }
    
    public function list()
    {
        $notifications = Auth::user()->notifications()->latest()->get();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unreadNotifications()->count()
        ]);
    }
    
    public function checkNew()
    {
        $count = Auth::user()->unreadNotifications()->count();
        
        return response()->json([
            'has_new' => $count > 0,
            'count' => $count
        ]);
    }
    
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        
        return response()->json(['count' => $count]);
    }
}