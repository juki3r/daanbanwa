<?php

namespace App\Http\Controllers;

use App\Models\Request as BarangayRequest;

class NotificationController extends Controller
{
    // Show admin notifications page
    public function index()
    {
        $unreadRequests = BarangayRequest::where('admin_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.notification.index', compact('unreadRequests'));
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
