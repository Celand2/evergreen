<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        return view('client.notifications.index', compact('notifications'));
    }

    public function markRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        return back()->with('success', 'Notification marked as read.');
    }
}