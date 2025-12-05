<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}