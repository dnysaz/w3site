<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function chat_user()
    {
        // Mengambil data user yang sedang login agar bisa dipassing ke View jika perlu
        $user = Auth::user();
        
        // Pastikan hanya user yang sudah login bisa akses (Middleware biasanya sudah handle ini)
        return view("user-dashboard.chat", [
            'user' => $user
        ]);
    }

    public function chat_admin()
    {
        // Opsional: Cek apakah yang akses benar-benar admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view("admin-dashboard.chat");
    }
}