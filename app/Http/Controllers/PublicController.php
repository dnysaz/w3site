<?php

namespace App\Http\Controllers;

use App\Models\Shortlink;
use App\Models\Linktree;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function handleSlug($slug)
    {
        // 1. Cek Shortlink
        $shortlink = Shortlink::where('slug', $slug)->first();
        
        if ($shortlink) {
            if (!$shortlink->is_active) {
                abort(404, 'Tautan ini tidak ditemukan.');
            }

            $shortlink->increment('clicks');
            return redirect()->away($shortlink->destination_url);
        }

        // 2. Cek Linktree
        $linktree = Linktree::where('slug', $slug)->first();
        
        if ($linktree) {
            if (!$linktree->is_active) {
                abort(404, 'Halaman biolink ini sedang tidak aktif.');
            }

            $linktree->increment('views_count');
            $linktree->update(['last_accessed_at' => now()]);
            
            return response($linktree->html_content)->header('Content-Type', 'text/html');
        }

        // 3. Jika tidak ditemukan dua-duanya
        abort(404);
    }
}