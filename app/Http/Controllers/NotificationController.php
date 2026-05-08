<?php

namespace App\Http\Controllers;

use App\Models\Blotter;
use App\Models\Concern;
use App\Models\Request as BarangayRequest;

class NotificationController extends Controller
{

    public function counts()
    {
        return response()->json([
            'certCount' => BarangayRequest::where('admin_read', false)->count(),
            'concernCount' => Concern::where('admin_read', false)->count(),
            'blotterCount' => Blotter::where('admin_read', false)->count(),
        ]);
    }
}
