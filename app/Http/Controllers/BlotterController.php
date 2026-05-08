<?php

namespace App\Http\Controllers;

use App\Models\Blotter;
use App\Models\Concern;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BlotterController extends Controller
{
    public function index(Request $request)
    {
        $query = Blotter::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%")
                    ->orWhere('document_type', 'like', "%{$request->search}%")
                    ->orWhere('address', 'like', "%{$request->search}%");
            });
        }

        $blotters = $query->latest()->paginate(7);

        return view('admin.blotter.index', compact('blotters'));
    }

    public function fetch(Request $request)
    {
        $query = Blotter::with('user');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('case_number', 'like', "%{$request->search}%")
                    ->orWhere('complainant_name', 'like', "%{$request->search}%")
                    ->orWhere('statement', 'like', "%{$request->search}%");
            });
        }

        $blotters = $query->latest()->paginate(7);

        return response()->json([
            'html' => view('admin.blotter.partials.rows', compact('blotters'))->render(),
            'pagination' => (string) $blotters->links(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'complainant_name' => 'required|string|max:255',
            'statement' => 'required|string',
        ]);

        // Case number generator
        $countToday = Blotter::whereDate('created_at', today())->count() + 1;

        $caseNumber = 'BLT-' . now()->format('Ymd') . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);

        $blotter = Blotter::create([
            'user_id' => $request->user()->id,
            'complainant_name' => $request->complainant_name,
            'statement' => $request->statement,
            'status' => 'pending',
            'case_number' => $caseNumber,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Blotter created successfully.',
            'data' => $blotter
        ]);
    }

    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $blotter = Blotter::findOrFail($id);

        $blotter->update([
            'status' => strtolower($request->status),
        ]);



        // Get user from request (IMPORTANT FIX)
        // $user = User::find($blotter->user_id);

        // if (!$user) {
        //     return back()->with('error', 'User not found');
        // }

        // if (!$user->fcm_token) {
        //     return back()->with('error', 'User has no FCM token registered.');
        // }

        // $title = "Blotter Update !";
        // $body  = "Your blotter is " . $request->status;

        // (new \App\Services\FirebaseService)->sendNotification(
        //     $user->fcm_token,
        //     $title,
        //     $body,
        //     [
        //         'screen' => 'Requests',
        //         'blotter_id' => (string) $blotter->id,
        //     ]
        // );

        // //  SEND SMS
        // try {
        //     Http::withHeaders([
        //         'X-API-KEY' => env('SMS_API_KEY')
        //     ])->post('https://carlesppo.com/api/send-sms-api', [
        //         'phone_number' => $user->phone,
        //         'message' => "[Daan Banwa ALERT]\n$title\n$body"
        //     ]);
        // } catch (\Exception $e) {
        //     \Log::error('SMS failed: ' . $e->getMessage());
        // }

        return response()->json([
            'success' => true,
            'status' => $blotter->status,
            'message' => 'Status updated successfully'
        ]);
    }

    public function destroy($id)
    {
        Blotter::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blotter deleted successfully'
        ]);
    }
}
