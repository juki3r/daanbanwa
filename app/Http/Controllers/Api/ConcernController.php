<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Concern;
use Illuminate\Http\Request;

class ConcernController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'location' => 'required',
            'description' => 'required',
        ]);

        $concern = Concern::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'submitted',
            'progress' => 10,
        ]);

        return response()->json([
            'message' => 'Concern submitted successfully',
            'concern' => $concern
        ]);
    }

    public function myConcerns(Request $request)
    {
        return response()->json([
            'concerns' => Concern::where('user_id', $request->user()->id)
                ->latest()
                ->get()
        ]);
    }

    private function getProgress($status)
    {
        return match ($status) {
            'submitted' => 10,
            'received' => 25,
            'under_review' => 50,
            'in_progress' => 80,
            'resolved' => 100,
            'rejected' => 0,
            default => 10,
        };
    }

    public function updateStatus(Request $request, $id)
    {
        $concern = Concern::findOrFail($id);

        $concern->status = $request->status;
        $concern->progress = $this->getProgress($request->status);
        $concern->admin_reply = $request->admin_reply;

        $concern->save();

        return response()->json([
            'message' => 'Concern updated',
            'concern' => $concern
        ]);
    }
}
