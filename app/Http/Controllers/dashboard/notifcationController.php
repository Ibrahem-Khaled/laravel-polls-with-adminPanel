<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifcation as Notification;

class notifcationController extends Controller
{
    public function index()
    {
        $notifications = Notification::all();
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

        Notification::create([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('notifications.index')->with('success', 'Notification created successfully.');
    }

    public function update(Request $request, Notification $notification)
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

        return redirect()->route('notifications.index')->with('success', 'Notification updated successfully.');
    }

    public function destroy(Notification $notification)
    {
        if ($notification->image) {
            Storage::disk('public')->delete($notification->image);
        }

        $notification->delete();
        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully.');
    }
}
