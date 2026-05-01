<?php

namespace App\Http\Controllers;

use App\Models\Request;

class RequestController extends Controller
{
    public function index()
    {
        $requests = Request::all();

        return view('admin.requests.index', compact('requests'));
    }

    public function updateStatus(Request $request, $id)
    {
        $req = Request::findOrFail($id);

        $req->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status updated successfully');
    }

    public function destroy($id)
    {
        Request::findOrFail($id)->delete();

        return back()->with('success', 'Request deleted successfully');
    }
}
