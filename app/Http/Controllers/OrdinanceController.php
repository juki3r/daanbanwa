<?php

namespace App\Http\Controllers;

use App\Models\Ordinance;
use Illuminate\Http\Request;

class OrdinanceController extends Controller
{
    // GET all ordinances
    public function index()
    {
        return response()->json([
            'ordinances' => Ordinance::latest()->get()
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

        return response()->json([
            'message' => 'Ordinance created',
            'ordinance' => $ordinance
        ]);
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

        return response()->json([
            'message' => 'Updated successfully',
            'ordinance' => $ordinance
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        Ordinance::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
