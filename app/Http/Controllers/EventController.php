<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return view('admin.calendar.index');
    }

    public function fetchEvents()
    {
        $events = Event::all();

        return response()->json($events);
    }

    public function store(Request $request)
    {
        Event::create($request->all());

        return response()->json(['status' => 'success']);
    }
}
