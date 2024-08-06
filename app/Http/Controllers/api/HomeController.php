<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Notifcation;
use App\Models\SlideShow;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function sliders()
    {
        $slides = SlideShow::where('status', 'active')->get();
        if ($slides->count() > 0) {
            return response()->json($slides, 200);
        }
        return response()->json(['error' => 'No slides found.'], 404);
    }

    public function notifications()
    {
        $notifications = Notifcation::all();
        if ($notifications->count() > 0) {
            return response()->json($notifications, 200);
        }
        return response()->json(['error' => 'No notifications found.'], 404);
    }

}
