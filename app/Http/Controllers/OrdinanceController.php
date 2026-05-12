<?php

namespace App\Http\Controllers;

use App\Models\Ordinance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrdinanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Ordinance::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
                    ->orWhere('ordinance_no', 'like', "%{$request->search}%");
            });
        }

        $ordinances = $query->latest()->paginate(7);

        return view('admin.ordinances.index', compact('ordinances'));
    }

    public function fetch(Request $request)
    {
        $query = Ordinance::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
                    ->orWhere('ordinance_no', 'like', "%{$request->search}%");
            });
        }

        $ordinances = $query->latest()->paginate(7);

        return response()->json([
            'html' => view('admin.ordinances.partials.rows', compact('ordinances'))->render(),
            'pagination' => (string) $ordinances->links(),
        ]);
    }

    // STORE (optional admin side)
    public function store(Request $request)
    {
        $request->validate([
            'ordinance_no' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        $ordinance = Ordinance::create($request->all());

        // get all user tokens
        $tokens = \App\Models\User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

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

    // SHOW single
    public function show($id)
    {
        return Ordinance::findOrFail($id);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $ordinance = Ordinance::findOrFail($id);
        $ordinance->update($request->all());

        return redirect()
            ->back()
            ->with('success', 'Ordinance updated successfully.');
    }

    // DELETE
    public function destroy($id)
    {
        Ordinance::findOrFail($id)->delete();

        return redirect()
            ->back()
            ->with('success', 'Ordinance deleted successfully.');
    }
}
