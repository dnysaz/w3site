<?php

namespace App\Http\Controllers;

use App\Models\Shortlink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShortlinkController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $links = Shortlink::where('user_id', $user->id)->latest()->get();
        $count = $links->count();
    
        $packageLimits = [0 => 10, 1 => 100, 2 => 1000];
        $limit = $packageLimits[$user->package] ?? 10;
        
        $percent = min(100, ($count / $limit) * 100);
        $isFull = $count >= $limit;
        $limitText = ($user->package >= 2) ? '1000' : (string)$limit;
    
        return view('user-dashboard.shortlink', compact(
            'links', 
            'count', 
            'limit', 
            'percent', 
            'isFull', 
            'limitText',
            'user'
        ))->with('pkg', $user->package);
    }

    public function store(Request $request)
    {
        $request->validate([
            'destination_url' => 'required|url'
        ]);

        $user = Auth::user();
        
        // 1. Ambil Limit dari Mapping yang sama
        $packageLimits = [0 => 10, 1 => 100, 2 => 1000];
        $limit = $packageLimits[$user->package] ?? 10;
        
        // 2. Proteksi Database (Keamanan Sisi Server)
        $count = Shortlink::where('user_id', $user->id)->count();
        
        if ($count >= $limit) {
            return back()->with('error', "Limit {$limit} Shortlink tercapai. Silakan upgrade paket Anda!");
        }

        Shortlink::create([
            'user_id' => $user->id,
            'destination_url' => $request->destination_url
        ]);

        return back()->with('success', 'Shortlink berhasil dibuat!');
    }

    public function destroy($id)
    {
        $link = Shortlink::where('user_id', Auth::id())->findOrFail($id);
        $link->delete();

        return back()->with('success', 'Shortlink berhasil dihapus!');
    }
}