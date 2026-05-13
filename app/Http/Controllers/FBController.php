<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\FacebookService;


class FBController extends Controller
{
    // function postToFacebook(Request $request)
    // {
    //     // $message = $request->input('message');
    //     $message = "Hello, this is a test post from server to Facebook!";

    //     $fb = new FacebookService();
    //     $response = $fb->postToPage($message);

    //     return response()->json($response);
    // }

    public function postToFacebook(Request $request, FacebookService $fb)
    {
        // $message =
        //     "📢 {$news->title}\n\n" .
        //     "{$news->content}\n\n" .
        //     "📅 " . now()->format('F d, Y h:i A') . "\n" .
        //     "🏢 Barangay Announcement";

        $message =
            "📢 Demo Post\n\n" .
            "This is a demo post for barangay announcement.\n\n" .
            "📅 " . now()->format('F d, Y h:i A') . "\n" .
            "🏢 Barangay Announcement";

        $response = $fb->postToPage($message);

        return response()->json($response);
    }
}
