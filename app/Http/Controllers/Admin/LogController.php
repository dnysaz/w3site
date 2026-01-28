<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Menampilkan halaman pembungkus (wrapper) untuk Log Viewer
     */
    public function index()
    {
        // Kita hanya mengembalikan view blade biasa
        return view('admin-dashboard.logs.index');
    }
}