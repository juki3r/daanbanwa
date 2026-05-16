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
