<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PollController extends Controller
{
    public function index()
    {
        $polls = Poll::paginate(10);
        $users = User::where('role', 'company')
            ->where('status', 'active')
            ->get();
        return view('dashboard.polls.index', compact('polls', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'visibility' => 'required|in:public,private',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('polls', 'public') : null;

        Poll::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'price' => $request->price,
            'status' => $request->status,
            'visibility' => $request->visibility,
        ]);

        return redirect()->route('polls.index')->with('success', 'Poll created successfully.');
    }

    public function update(Request $request, Poll $poll)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'visibility' => 'required|in:public,private',
        ]);

        if ($request->hasFile('image')) {
            if ($poll->image) {
                Storage::disk('public')->delete($poll->image);
            }
            $poll->image = $request->file('image')->store('polls', 'public');
        }

        $poll->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $poll->image,
            'price' => $request->price,
            'status' => $request->status,
            'visibility' => $request->visibility,
        ]);

        return redirect()->route('polls.index')->with('success', 'Poll updated successfully.');
    }

    public function destroy(Poll $poll)
    {
        if ($poll->image) {
            Storage::disk('public')->delete($poll->image);
        }

        $poll->delete();
        return redirect()->route('polls.index')->with('success', 'Poll deleted successfully.');
    }
}
