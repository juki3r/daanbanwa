<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;

class NewsController extends Controller
{
    public function index()
    {
        $news = \App\Models\News::latest()->get();
        return view('admin.news.index', compact('news'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // save image
        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('news', 'public');
        }

        // save news
        $news = \App\Models\News::create($validatedData);

        // get all user tokens
        $tokens = \App\Models\User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

        // send notification to all
        foreach ($tokens as $token) {
            (new \App\Services\FirebaseService)->sendNotification(
                $token,
                $news->title,
                \Illuminate\Support\Str::limit($news->content, 160)
            );
        }

        return redirect()->route('news.index')->with('success', 'News created and notification sent.');
    }

    public function update(Request $request, $id)
    {
        $news = \App\Models\News::findOrFail($id);
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048', // Optional image upload
        ]);
        $news->update($validatedData);
        return redirect()->route('news.index')->with('success', 'News item updated successfully.');
    }

    public function destroy($id)
    {
        $news = \App\Models\News::findOrFail($id);
        $news->delete();
        return redirect()->route('news.index')->with('success', 'News item deleted successfully.');
    }

    public function sendToAll()
    {
        try {
            $title = request('title');
            $body  = request('body');

            // 1️⃣ Save once (audit trail)
            $log = Notification::create([
                'title' => $title,
                'body'  => $body,
            ]);

            // 2️⃣ Get all users with FCM token
            $users = User::whereNotNull('fcm_token')->get();

            if ($users->isEmpty()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'No users with registered FCM tokens found.'
                ], 400);
            }

            $firebase = new \App\Services\FirebaseService;

            // 3️⃣ Send notification to all users
            foreach ($users as $user) {
                $firebase->sendNotification(
                    $user->fcm_token,
                    $title,
                    $body
                );
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Notification sent to all users successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
