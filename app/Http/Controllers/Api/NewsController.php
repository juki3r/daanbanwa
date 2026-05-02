<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\NewsView;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function markViewed($id, Request $request)
    {
        NewsView::updateOrCreate(
            [
                'news_id' => $id,
                'user_id' => $request->user()->id,
            ],
            [
                'viewed_at' => now(),
            ]
        );

        return response()->json(['message' => 'News marked as viewed']);
    }
}
