<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContact;
use Illuminate\Http\Request;

class EmergencyContactController extends Controller
{

    //Api fetch to mobile app
    public function index()
    {
        return response()->json([
            'contacts' => EmergencyContact::where('is_active', 1)
                ->orderBy('priority', 'asc')
                ->get()
        ]);
    }

    public function index_web(Request $request)
    {
        $query = EmergencyContact::where('is_active', 1)
            ->orderBy('priority', 'asc');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('number', 'like', "%{$request->search}%");
            });
        }

        $emergency = $query->paginate(7);

        return view('admin.emergency.index', compact('emergency'));
    }

    public function fetch(Request $request)
    {
        $query = EmergencyContact::where('is_active', 1)
            ->orderBy('priority', 'asc');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('number', 'like', "%{$request->search}%");
            });
        }

        $emergency = $query->paginate(7);

        return response()->json([
            'html' => view('admin.emergency.partials.rows', compact('emergency'))->render(),
            'pagination' => (string) $emergency->links(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
        ]);

        EmergencyContact::create([
            'name' => $request->name,
            'number' => $request->phone_number,
            'is_active' => 1,
        ]);

        return back()->with('success', 'Contact added successfully');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
        ]);

        $contact = EmergencyContact::findOrFail($id);

        $contact->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
        ]);

        return back()->with('success', 'Contact updated successfully');
    }
    // DELETE
    public function destroy($id)
    {
        EmergencyContact::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact item deleted successfully.'
        ]);
    }
}
