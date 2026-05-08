<?php

namespace App\Http\Controllers;

use App\Models\Request as BarangayRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RequestController extends Controller
{
    // public function index()
    // {
    //     $requests = BarangayRequest::orderBy('created_at', 'desc')->paginate(6);

    //     return view('admin.requests.index', compact('requests'));
    // }

    public function index(Request $request)
    {
        $query = BarangayRequest::with('user');


        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%")
                    ->orWhere('document_type', 'like', "%{$request->search}%")
                    ->orWhere('address', 'like', "%{$request->search}%");
            });
        }

        $requests = $query->latest()->paginate(7);

        return view('admin.requests.index', compact('requests'));
    }

    public function fetch(Request $request)
    {
        $query = BarangayRequest::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%")
                    ->orWhere('document_type', 'like', "%{$request->search}%")
                    ->orWhere('address', 'like', "%{$request->search}%")
                    ->orWhere('purpose', 'like', "%{$request->search}%");
            });
        }

        $requests = $query->latest()->paginate(7);

        return response()->json([
            'html' => view('admin.requests.partials.rows', compact('requests'))->render(),
            'pagination' => (string) $requests->links(),
        ]);
    }

    //Admin store data
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1',
            'gender' => 'required|string',
            'address' => 'required|string|max:255',
            'document_type' => 'required|string',
            'purpose' => 'required|string',
            'company_name' => 'nullable|string|max:255',
            'business_nature' => 'nullable|string|max:255',
        ]);

        BarangayRequest::create([
            'user_id' => $request->user_id,
            'full_name' => $request->full_name,
            'age' => $request->age,
            'gender' => $request->gender,
            'address' => $request->address,
            'document_type' => $request->document_type,
            'purpose' => $request->purpose,
            'company_name' => $request->company_name,
            'business_nature' => $request->business_nature,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Request created successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        // Get request record
        $req = BarangayRequest::findOrFail($id);

        // Update status
        $req->update([
            'status' => $request->status,
            'admin_read' => true
        ]);

        // Get user from request (IMPORTANT FIX)
        $user = User::find($req->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'status' => $req->status,
                'message' => 'User not found'
            ]);
        }

        if (!$user->fcm_token) {
            return response()->json([
                'success' => false,
                'status' => $req->status,
                'message' => 'FCM not found for user'
            ]);
        }

        $title = "Certification Request Update";
        $body  = "Your request has been " . $request->status;

        (new \App\Services\FirebaseService)->sendNotification(
            $user->fcm_token,
            $title,
            $body,
            [
                'screen' => 'Requests',
                'requests_id' => (string) $req->id,
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

        // return back()->with('success', 'Status updated successfully');
        return response()->json([
            'success' => true,
            'status' => $req->status,
            'message' => 'Status updated successfully'
        ]);
    }


    //Delete request
    public function destroy($id)
    {
        $request = BarangayRequest::findOrFail($id);
        $request->delete();

        return response()->json([
            'success' => true,
            'message' => 'Request deleted successfully'
        ]);
    }
}
