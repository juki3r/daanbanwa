<?php

namespace App\Http\Controllers;

use App\Models\Official;
use Illuminate\Http\Request;

class OfficialController extends Controller
{
    public function index(Request $request)
    {
        $query = Official::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('position', 'like', "%{$request->search}%")
                    ->orWhere('assignment', 'like', "%{$request->search}%");
            });
        }

        $officials = $query
            ->orderByRaw("
        FIELD(position,
            'Punong Barangay',
            'Barangay Kagawad',
            'Barangay Secretary',
            'Barangay Treasurer',
            'SK Chairman',
            'SK Kagawad',
            'SK Secretary',
            'SK Treasurer',
            'Chief Tanod'
        )
    ")
            ->paginate(7);

        return view('admin.officials.index', compact('officials'));
    }

    public function fetch(Request $request)
    {
        $query = Official::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('position', 'like', "%{$request->search}%")
                    ->orWhere('assignment', 'like', "%{$request->search}%");
            });
        }

        $officials = $query
            ->orderByRaw("
        FIELD(position,
            'Punong Barangay',
            'Barangay Kagawad',
            'Barangay Secretary',
            'Barangay Treasurer',
            'SK Chairman',
            'SK Kagawad',
            'SK Secretary',
            'SK Treasurer',
            'Chief Tanod'
        )
    ")
            ->paginate(7);

        return response()->json([
            'html' => view('admin.officials.partials.rows', compact('officials'))->render(),
            'pagination' => (string) $officials->links(),
        ]);
    }

    // STORE (optional admin side)
    public function store(Request $request)
    {
        $request->validate([
            'ordinance_no' => 'required|unique:ordinances,ordinance_no',
            'title' => 'required|unique:ordinances,title',
            'description' => 'required',
        ], [
            'ordinance_no.required' => 'The ordinance number is required.',
            'ordinance_no.unique' => 'This ordinance number already exists.',

            'title.required' => 'The title is required.',
            'title.unique' => 'This title already exists.',

            'description.required' => 'The description is required.',
        ]);

        $ordinance = Ordinance::create($request->all());

        // get all user tokens
        // $tokens = \App\Models\User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
        $tokens = User::whereNotNull('fcm_token')
            ->where('granted', true)
            ->pluck('fcm_token')
            ->toArray();

        // send notification to all
        foreach ($tokens as $token) {
            (new \App\Services\FirebaseService)->sendNotification(
                $token,
                $ordinance->ordinance_no . ': ' . $ordinance->title,
                \Illuminate\Support\Str::limit($ordinance->description, 160),
                [
                    'screen' => 'Ordinance',
                    'ordinance_id' => (string) $ordinance->id,
                ]
            );
        }

        //  SEND SMS
        // $users = \App\Models\User::whereNotNull('phone')
        //     ->where('role', '!=', 'admin')
        //     ->get();

        $users = User::whereNotNull('phone')
            ->where('role', '!=', 'admin')
            ->where('granted', true)
            ->get();

        foreach ($users as $user) {
            try {
                Http::withHeaders([
                    'X-API-KEY' => env('SMS_API_KEY')
                ])->post('https://carlesppo.com/api/send-sms-api', [
                    'phone_number' => $user->phone,
                    'message' => \Illuminate\Support\Str::limit(
                        "[Daan Banwa ALERT]\n{$ordinance->title}\n\nOpen your DaanBanwa app for details.",
                        140
                    )
                ]);
            } catch (\Exception $e) {
                \Log::error('SMS failed for ' . $user->phone . ': ' . $e->getMessage());
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Ordinance added successfully.');
    }

    // DELETE
    public function destroy($id)
    {
        Official::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'News item deleted successfully.'
        ]);
    }
}
