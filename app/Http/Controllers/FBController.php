<?php

namespace App\Http\Controllers;


use FacebookService;
use Illuminate\Http\Request;


class FBController extends Controller
{
    function postToFacebook(Request $request)
    {
        // $message = $request->input('message');
        $message = "Hello, this is a test post from server to Facebook!";

        $fb = new FacebookService();
        $response = $fb->postToPage($message);

        return response()->json($response);
    }
}
