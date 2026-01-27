<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminChatController extends Controller
{
    public function chat_admin()
    {
        // Opsional: Cek apakah yang akses benar-benar admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view("admin-dashboard.chat");
    }
}
