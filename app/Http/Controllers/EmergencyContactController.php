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

    // STORE (optional admin side)
    public function store_official(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'assignment' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        Official::create([
            'name' => $request->name,
            'position' => $request->position,
            'assignment' => $request->assignment,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Official created successfully!');
    }

    public function update_official(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'assignment' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $official = Official::findOrFail($id);

        $official->update([
            'name' => $request->name,
            'position' => $request->position,
            'assignment' => $request->assignment,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Official updated successfully!');
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
