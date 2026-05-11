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
        return Event::all()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date,
                'color' => $event->color,
                'description' => $event->description,
            ];
        });
    }

    public function store(Request $request)
    {
        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'color' => $request->color,
        ]);

        return response()->json(['status' => 'created']);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'color' => $request->color,
        ]);

        return response()->json(['status' => 'updated']);
    }

    public function destroy($id)
    {
        Event::destroy($id);

        return response()->json(['status' => 'deleted']);
    }
}
