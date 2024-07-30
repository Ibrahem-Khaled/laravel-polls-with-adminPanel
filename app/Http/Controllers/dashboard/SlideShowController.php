<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\SlideShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideShowController extends Controller
{

    public function index()
    {
        $slides = SlideShow::paginate(10);
        return view('dashboard.sliders', compact('slides'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = $request->file('image')->store('slides', 'public');

        SlideShow::create([
            'image' => $imagePath,
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Slide created successfully.');
    }

    public function update(Request $request, SlideShow $slideShow)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($slideShow->image);
            $imagePath = $request->file('image')->store('slides', 'public');
        } else {
            $imagePath = $slideShow->image;
        }

        $slideShow->update([
            'image' => $imagePath,
            'title' => $request->title,
            'description' => $request->description,
            'link' => $request->link,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Slide updated successfully.');
    }

    public function destroy(SlideShow $slideShow)
    {
        Storage::disk('public')->delete($slideShow->image);
        $slideShow->delete();

        return redirect()->back()->with('success', 'Slide deleted successfully.');
    }

}
