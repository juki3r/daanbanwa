<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsView;
use Illuminate\Http\Request;

class NewsController extends Controller
{
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
}
