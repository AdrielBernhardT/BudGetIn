<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Full notifications page.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);

        return view('pages.notifications.index', ['title' => __('nav.notifications')], compact('notifications'));
    }

    /**
     * Lightweight JSON feed used by the bell-icon dropdown.
     */
    public function feed()
    {
        $user = Auth::user();

         return response()->json([
            'notifications' => $user->unreadNotifications()->latest()->limit(8)->get(),
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    public function markAsRead(Request $request, string $id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->to($request->input('redirect', url()->previous()));
    }

    public function markAllAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }
}
