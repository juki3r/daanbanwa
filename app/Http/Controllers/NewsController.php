<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsView;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('content', 'like', "%{$request->search}%");
            });
        }

        $news = $query->latest()->paginate(7);

        return view('admin.news.index', compact('news'));
    }

    public function fetch(Request $request)
    {
        $query = News::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('content', 'like', "%{$request->search}%");
            });
        }

        $news = $query->latest()->paginate(7);

        return response()->json([
            'html' => view('admin.news.partials.rows', compact('news'))->render(),
            'pagination' => (string) $news->links(),
        ]);
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


        $tokens = \App\Models\User::whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();

        foreach ($tokens as $token) {
            (new \App\Services\FirebaseService)->sendNotification(
                $token,
                $news->ucwords(strtolower($news->title)),
                \Illuminate\Support\Str::limit($news->content, 160),
                [
                    'screen' => 'News',
                    'news_id' => (string) $news->id,
                ]
            );
        }

        //  SEND SMS
        $users = \App\Models\User::whereNotNull('phone')
            ->where('role', '!=', 'admin')
            ->get();

        foreach ($users as $user) {
            try {
                Http::withHeaders([
                    'X-API-KEY' => env('SMS_API_KEY')
                ])->post('https://carlesppo.com/api/send-sms-api', [
                    'phone_number' => $user->phone,
                    'message' => \Illuminate\Support\Str::limit(
                        "[Daan Banwa ALERT]\n{$news->ucwords(strtolower($news->title))}\n\nOpen your DaanBanwa app for details.",
                        140
                    )
                ]);
            } catch (\Exception $e) {
                \Log::error('SMS failed for ' . $user->phone . ': ' . $e->getMessage());
            }
        }

        // return redirect()->route('news.index')->with('success', 'News created and notification sent.');
        return redirect()
            ->route('news.index')
            ->with('success', 'News created successfully.');
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

        return response()->json([
            'success' => true,
            'message' => 'News item deleted successfully.'
        ]);
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

    //Mark news views from api mobile app
    public function markViewed(Request $request, $id)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }

            $news = News::findOrFail($id);

            NewsView::updateOrCreate(
                [
                    'news_id' => $news->id,
                    'user_id' => $user->id,
                ],
                [
                    'viewed_at' => now(),
                ]
            );

            return response()->json([
                'message' => 'News marked as viewed'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('markViewed failed', [
                'error' => $e->getMessage(),
                'news_id' => $id,
                'user_id' => optional($request->user())->id,
            ]);

            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //Send all news that is not view by user via api.
    // public function getNews(Request $request)
    // {
    //     $userId = $request->user()->id;

    //     $news = News::whereDoesntHave('views', function ($query) use ($userId) {
    //         $query->where('user_id', $userId);
    //     })
    //         ->latest()
    //         ->get();

    //     return response()->json([
    //         'news' => $news
    //     ]);
    // }
    public function unreadNews(Request $request)
    {
        $userId = $request->user()->id;

        $news = News::whereDoesntHave('views', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->latest()
            ->get();

        return response()->json([
            'notifications' => $news,
            'unread_count' => $news->count()
        ]);
    }
}
