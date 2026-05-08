<?php

namespace App\Http\Controllers;

use App\Models\Concern;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConcernController extends Controller
{
    // public function index()
    // {
    //     $concerns = Concern::all();
    //     return view('admin.concern.index', compact('concerns'));
    // }
    public function index(Request $request)
    {
        $query = Concern::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('location', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $concerns = $query->latest()->paginate(8);

        return view('admin.concern.index', compact('concerns'));
    }

    public function fetch(Request $request)
    {
        $query = Concern::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('location', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        $concerns = $query->latest()->paginate(8);


        return response()->json([
            'html' => view('admin.concern.partials.rows', compact('concerns'))->render(),
            'pagination' => (string) $concerns->links(),
        ]);
    }








    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'status' => 'required|in:received,under_review,in_progress,resolved,rejected'
        ]);

        $concern = Concern::findOrFail($id);

        $concern->update([
            'status' => $request->status
        ]);



        // Get user from request (IMPORTANT FIX)
        $user = User::find($concern->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found for this concern'
            ]);
        }

        if (!$user->fcm_token) {
            return response()->json([
                'success' => false,
                'message' => 'FCM token not found for user'
            ]);
        }

        $title = "Concern Update !";
        $body  = "Your concern is " . $request->status;

        (new \App\Services\FirebaseService)->sendNotification(
            $user->fcm_token,
            $title,
            $body,
            [
                'screen' => 'Concerns',
                'concerns_id' => (string) $concern->id,
            ]
        );

        //  SEND SMS
        try {
            Http::withHeaders([
                'X-API-KEY' => env('SMS_API_KEY')
            ])->post('https://carlesppo.com/api/send-sms-api', [
                'phone_number' => $user->phone,
                'message' => "[Daan Banwa ALERT]\n$title\n$body"
            ]);
        } catch (\Exception $e) {
            \Log::error('SMS failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'status' => $request->status,
            'message' => 'Concern status updated successfully'
        ]);
    }

    //Delete Concern
    public function destroy($id)
    {
        $request = Concern::findOrFail($id);
        $request->delete();

        return response()->json([
            'success' => true,
            'message' => 'Concern deleted successfully'
        ]);
    }
}
