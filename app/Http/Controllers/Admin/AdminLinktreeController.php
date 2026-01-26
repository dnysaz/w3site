<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Linktree;

class AdminLinktreeController extends Controller
{
    public function index()
    {
        $linktrees = Linktree::with('user')->latest()->paginate(10);
        $totalViews = Linktree::sum('views_count');
    
        // Menyiapkan data untuk Grafik (7 Hari Terakhir)
        $chartLabels = [];
        $chartData = [];
    
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            // Format Tanggal untuk Label (Contoh: 24 Jan)
            $chartLabels[] = $date->translatedFormat('d M'); 
            
            // Hitung jumlah linktree yang dibuat pada tanggal tersebut
            $chartData[] = Linktree::whereDate('created_at', $date->toDateString())->count();
        }
    
        return view('admin-dashboard.linktrees.index', compact(
            'linktrees', 
            'totalViews', 
            'chartLabels', 
            'chartData'
        ));
    }

    public function toggle($id)
    {
        $linktree = Linktree::findOrFail($id);
        
        // Balikkan status (jika true jadi false, dst)
        $linktree->is_active = !$linktree->is_active;
        $linktree->save();

        return back()->with('success', 'Status Biolink berhasil diperbarui!');
    }
}