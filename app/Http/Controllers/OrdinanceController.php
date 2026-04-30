<?php

namespace App\Http\Controllers;

use App\Models\Ordinance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrdinanceController extends Controller
{
    // GET all ordinances
    public function index()
    {
        $query = request('search');

        $ordinances = Ordinance::where('title', 'like', "%{$query}%")
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('ordinance_no', 'like', "%{$query}%")
                        ->orWhere('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                });
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.ordinances.index', compact('ordinances', 'query'));
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
                $ordinance->ordinance_no,
                \Illuminate\Support\Str::limit($ordinance->title, 160)
            );
        }

        //  SEND SMS
        $users = \App\Models\User::whereNotNull('phone')->get();

        foreach ($users as $user) {
            try {
                Http::withHeaders([
                    'X-API-KEY' => env('SMS_API_KEY')
                ])->post('https://carlesppo.com/api/send-sms-api', [
                    'phone_number' => $user->phone,
                    'message' => \Illuminate\Support\Str::limit(
                        "[Daan Banwa ALERT]\n{$ordinance->title}\n{$ordinance->title}",
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
