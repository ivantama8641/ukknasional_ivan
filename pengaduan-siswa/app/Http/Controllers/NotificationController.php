<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(15);
        
        // Mark as read after viewing index?
        // auth()->user()->unreadNotifications()->update(['is_read' => true]);
        
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) abort(403);
        
        $notification->update(['is_read' => true]);
        
        if ($notification->complaint_id) {
            $role = auth()->user()->role;
            return redirect()->route("$role.complaints.show", $notification->complaint_id);
        }
        
        return redirect()->back();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
