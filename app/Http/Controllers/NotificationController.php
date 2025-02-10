<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $notification = $user->notifications()->find($id); // Gunakan find() saja, handle jika null

            if (!$notification) {
                return response()->json(['success' => false, 'message' => 'Notifikasi tidak ditemukan.'], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'count' => $user->unreadNotifications()->count()
            ]);
        } catch (Throwable $e) {
            report($e); // Log error untuk debugging

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() || 'Terjadi kesalahan server.'
            ], 500);
        }
    }
}
