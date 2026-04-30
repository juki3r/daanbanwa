<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = \App\Models\News::latest()->get();
        return view('admin.news.index', compact('news'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048', // Optional image upload
        ]);
        \App\Models\News::create($validatedData);
        return redirect()->route('admin.news.index')->with('success', 'News item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $news = \App\Models\News::findOrFail($id);
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048', // Optional image upload
        ]);
        $news->update($validatedData);
        return redirect()->route('admin.news.index')->with('success', 'News item updated successfully.');
    }

    public function destroy($id)
    {
        $news = \App\Models\News::findOrFail($id);
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'News item deleted successfully.');
    }
}
