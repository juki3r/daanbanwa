<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Request as BarangayRequest;

class RequestController extends Controller
{
    public function index()
    {
        $requests = BarangayRequest::all();

        return view('admin.requests.index', compact('requests'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $req = BarangayRequest::findOrFail($id);

        $req->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status updated successfully');
    }

    public function destroy($id)
    {
        BarangayRequest::findOrFail($id)->delete();

        return back()->with('success', 'Request deleted successfully');
    }
}
