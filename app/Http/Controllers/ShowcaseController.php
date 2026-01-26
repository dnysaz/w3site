<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class ShowcaseController extends Controller
{
    /**
     * Menampilkan galeri showcase dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        // Ambil query pencarian dari input 'q'
        $search = $request->input('q');

        $sites = Site::query()
            // Pastikan hanya menampilkan situs yang sudah dipublikasikan
            ->where('status', 'active') 
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('subdomain', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->with('user') // Eager load user untuk menampilkan nama kreator
            ->latest()      // Urutkan dari yang terbaru
            ->paginate(9)   // Batasi 9 per halaman agar grid rapi 3x3
            ->withQueryString();

        return view('pages.showcase', compact('sites', 'search'));
    }
}