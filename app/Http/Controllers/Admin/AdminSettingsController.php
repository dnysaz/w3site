<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminSettingsController extends Controller
{
    public function index()
    {
        return view("admin-dashboard.settings.index");
    }

    public function toggleMaintenance(Request $request)
    {
        // Simpan status ke dalam Cache selamanya (atau sampai diubah lagi)
        Cache::forever('dashboard_maintenance', $request->status);

        return response()->json(['success' => true, 'message' => 'Status diupdate']);
    }
}
