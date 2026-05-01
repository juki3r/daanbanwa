<?php

namespace App\Http\Controllers;

use App\Models\Request;

class RequestController extends Controller
{
    public function index()
    {
        $requests = Request::with('user')
            ->latest()
            ->get();

        return view('admin.requests.index', compact('requests'));
    }

    public function update(Request $request, $id)
    {
        $req = Request::findOrFail($id);

        $req->update([
            'full_name' => $request->full_name,
            'age' => $request->age,
            'gender' => $request->gender,
            'address' => $request->address,
            'document_type' => $request->document_type,
            'purpose' => $request->purpose,
        ]);

        return back()->with('success', 'Request updated successfully');
    }

    public function destroy($id)
    {
        $req = Request::findOrFail($id);
        $req->delete();

        return back()->with('success', 'Request deleted successfully');
    }
}
