<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\userPushToken;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use App\Models\Notifcation as NotificationModal;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class notifcationController extends Controller
{
    public function index()
    {
        $notifications = NotificationModal::all();
        return view('dashboard.notifications', compact('notifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:read,unread',
        ]);

        $imagePath = $request->hasFile('image') ? $request->file('image')->store('notifications', 'public') : null;

        $notification = NotificationModal::create([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        $expoPushTokens = UserPushToken::pluck('expo_push_token')->toArray();

        // إرسال الإشعارات على مجموعات صغيرة
        foreach (array_chunk($expoPushTokens, 100) as $tokensChunk) {
            Notification::send(null, new UserNotification($request->title, $request->body, $tokensChunk));
        }

        return redirect()->route('notifications.index')->with('success', 'Notification created and sent successfully.');
    }

    public function update(Request $request, NotificationModal $notification)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:read,unread',
        ]);

        if ($request->hasFile('image')) {
            if ($notification->image) {
                Storage::disk('public')->delete($notification->image);
            }
            $imagePath = $request->file('image')->store('notifications', 'public');
        } else {
            $imagePath = $notification->image;
        }

        $notification->update([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        $expoPushTokens = userPushToken::pluck('expo_push_token')->toArray();

        foreach (array_chunk($expoPushTokens, 100) as $tokensChunk) {
            Notification::send(null, new UserNotification($request->title, $request->body, $tokensChunk));
        }

        return redirect()->route('notifications.index')->with('success', 'Notification updated and sent successfully.');
    }

    public function destroy(NotificationModal $notification)
    {
        if ($notification->image) {
            Storage::disk('public')->delete($notification->image);
        }

        $notification->delete();
        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully.');
    }
}
