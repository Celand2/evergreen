<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        if ($validated['user_id']) {
            // Send to specific user
            Notification::create([
                'user_id' => $validated['user_id'],
                'title' => $validated['title'],
                'body' => $validated['body'],
            ]);
        } else {
            // Send to all users
            $users = User::all();
            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => $validated['title'],
                    'body' => $validated['body'],
                ]);
            }
        }

        return back()->with('success', 'Notification sent successfully.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('success', 'Notification deleted successfully.');
    }
}