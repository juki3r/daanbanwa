<?php

namespace App\Http\Controllers;

use App\Models\Blotter;
use App\Models\Concern;
use App\Models\Request as BarangayRequest;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationController extends Controller
{
    // Show admin notifications page
    public function index()
    {
        // REQUESTS
        $requests = BarangayRequest::with('user')
            ->where('admin_read', false)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'request',
                    'title' => $item->document_type,
                    'subtitle' => $item->purpose,
                    'user' => $item->user?->first_name . ' ' . $item->user?->last_name,
                    'created_at' => $item->created_at,
                    'raw' => $item,
                ];
            });

        // CONCERNS
        $concerns = Concern::with('user')
            ->where('admin_read', false)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'concern',
                    'title' => $item->title,
                    'subtitle' => $item->description,
                    'user' => $item->user?->first_name,
                    'created_at' => $item->created_at,
                    'raw' => $item,
                ];
            });

        // BLOTTERS
        $blotters = Blotter::with('user')
            ->where('admin_read', false)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'blotter',
                    'title' => $item->case_number ?? 'Blotter Report',
                    'subtitle' => $item->statement,
                    'user' => $item->user?->full_name,
                    'created_at' => $item->created_at,
                    'raw' => $item,
                ];
            });

        // MERGE + SORT
        $notifications = $requests
            ->merge($concerns)
            ->merge($blotters)
            ->sortByDesc('created_at')
            ->values();

        // MANUAL PAGINATION
        $perPage = 10;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $items = $notifications->slice(($page - 1) * $perPage, $perPage)->values();

        $notifications = new LengthAwarePaginator(
            $items,
            $notifications->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('admin.notification.index', compact('notifications'));
    }

    // Mark single as read then redirect back
    public function markAsRead($id)
    {
        $request = BarangayRequest::findOrFail($id);

        $request->update([
            'admin_read' => true,
        ]);

        return redirect()->back()->with('success', 'Marked as read');
    }

    // Mark all as read then redirect back
    public function markAllAsRead()
    {
        BarangayRequest::where('admin_read', false)
            ->update(['admin_read' => true]);

        return redirect()->back()->with('success', 'All notifications marked as read');
    }
}
