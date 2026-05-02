<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;

class AppStatusController extends Controller
{
    public function index()
    {
        // get first row (we only use 1 row as global config)
        $settings = AppSetting::first();

        // if empty, create default row
        if (!$settings) {
            $settings = AppSetting::create([
                'force_logout' => false,
            ]);
        }

        return response()->json([
            'force_logout' => (int) $settings->force_logout,
        ]);
    }
}
