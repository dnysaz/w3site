<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\AiLog;
use App\Models\AiDesign;
use App\Models\Linktree;



class AiController extends Controller
{
    public function generate(Request $request)
    {
        // 1. Sinkronisasi Timeout: Paksa PHP menunggu lebih lama dari API
        // Ini penting agar PHP tidak mati sebelum DeepSeek selesai mengirim data besar
        set_time_limit(300); 
    
        // 2. Validasi Input
        $request->validate([
            'prompt' => 'required|string|min:10|max:1000',
        ]);
    
        // 3. Ambil System Prompt
        $path = database_path('data/system_prompt.json');
        if (!File::exists($path)) {
            return response()->json([
                'success' => false, 
                'message' => 'Konfigurasi AI (JSON) tidak ditemukan.'
            ], 500);
        }
    
        $config = json_decode(File::get($path), true);
        $systemPrompt = $config['landing_page']['content'] ?? 'You are a professional web developer.';
    
        // 4. Konfigurasi API
        $apiKey = env('DEEPSEEK_API_KEY');
        $apiUrl = "https://api.deepseek.com/chat/completions";
    
        try {
            // 5. Eksekusi Request dengan Optimasi Network
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ])
            ->withoutVerifying() // Hindari masalah SSL Handshake di Mac
            ->connectTimeout(15) // Batalkan cepat jika server DeepSeek mati
            ->timeout(360)       // Beri waktu 4 menit untuk proses generate kode berat
            ->post($apiUrl, [
                'model'       => 'deepseek-chat', 
                'messages'    => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $request->prompt],
                ],
                'temperature' => 0.4, // Rendah = Kode lebih stabil & terstruktur
                'max_tokens'  => 8000, // Cukup untuk landing page lengkap
            ]);
    
            // 6. Penanganan Jika Sukses
            if ($response->successful()) {
                $data = $response->json();
                $htmlResult = $data['choices'][0]['message']['content'] ?? '';
    
                if (empty($htmlResult)) {
                    throw new \Exception("DeepSeek memberikan respon kosong.");
                }
                
                // Bersihkan markdown tag (```html ... ```)
                $htmlResult = preg_replace('/^```html\s*|```$/i', '', trim($htmlResult));
    
                // Log penggunaan ke DB
                AiLog::create([
                    'user_id' => auth()->id(),
                    'feature' => 'generate_site',
                    'prompt'  => $request->prompt,
                ]);
    
                return response()->json([
                    'success' => true,
                    'html'    => $htmlResult
                ]);
            }
    
            // 7. Penanganan Error API (Quota habis, Server Busy, dsb)
            $errorResponse = $response->json();
            $errorMessage = $errorResponse['error']['message'] ?? 'Status: ' . $response->status();
            
            Log::error("DeepSeek API Error: " . $errorMessage);
    
            return response()->json([
                'success' => false, 
                'message' => 'AI Provider Error: ' . $errorMessage
            ], $response->status());
    
        // } catch (\Exception $e) {
        //     // 8. Penanganan Error Sistem (Timeout, cURL, dsb)
        //     $msg = $e->getMessage();
            
        //     // Deteksi jika ini adalah masalah timeout murni
        //     if (str_contains($msg, 'timed out')) {
        //         $msg = "Proses terlalu lama. DeepSeek sedang sibuk atau kode terlalu kompleks.";
        //     }
    
        //     Log::critical("System Error Generate: " . $msg);
    
        //     return response()->json([
        //         'success' => false, 
        //         'message' => 'System Error: ' . $msg
        //     ], 500);
        // }
        } catch (\Exception $e) {
            // Balikan error asli agar kita bisa lihat di Inspect Element > Network
            return response()->json([
                'success' => false, 
                'message' => 'DEBUG ERROR: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function saveDesign(Request $request)
    {
        $request->validate([
            'html' => 'required',
            'prompt' => 'required'
        ]);

        $user = auth()->user();
        $randomStr = Str::random(5);
        $fileName = 'w3site-preview-' . Str::slug($user->name) . '-' . $randomStr . '.html';
        $path = 'users-designs/' . $user->id . '/' . $fileName;

        // 1. Simpan file fisik ke storage/app/public/users-designs/
        Storage::disk('public')->put($path, $request->html);

        // 2. Simpan record ke database
        AiDesign::create([
            'user_id' => $user->id,
            'file_name' => $fileName,
            'title' => 'Design ' . strtoupper($randomStr),
            'prompt' => $request->prompt,
            'path' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Desain berhasil disimpan ke My LP Design!'
        ]);
    }


    public function editDesign($fileName)
    {
        $design = AiDesign::where('file_name', $fileName)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();
        
        $content = Storage::disk('public')->get($design->path);
        
        return view('user-dashboard.edit_ai_design', compact('design', 'content'));
    }

    public function updateDesign(Request $request, $fileName)
    {
        $design = AiDesign::where('file_name', $fileName)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();
        
        // Update file fisik
        Storage::disk('public')->put($design->path, $request->html);
        
        return response()->json(['success' => true, 'message' => 'Perubahan berhasil disimpan!']);
    }

    public function viewFile($fileName)
    {
        $design = AiDesign::where('file_name', $fileName)->first();

        // Jika data di DB tidak ada ATAU file fisik sudah dihapus
        if (!$design || !Storage::disk('public')->exists($design->path)) {
            return redirect()->route('my.ai.design')
                ->with('error', 'Maaf, file desain tersebut sudah tidak tersedia atau telah dihapus.');
        }

        // Jika ada, tampilkan filenya
        return response()->file(storage_path('app/public/' . $design->path));
    }

    public function destroy($fileName)
    {
        // Cari data berdasarkan file_name dan pastikan milik user yang login
        $design = AiDesign::where('file_name', $fileName)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        // 1. Hapus file fisik dari storage
        if (Storage::disk('public')->exists($design->path)) {
            Storage::disk('public')->delete($design->path);
        }

        // 2. Hapus record dari database
        $design->delete();

        return back()->with('success', 'Project berhasil dihapus secara permanen.');
    }


    public function aiswot()
    {
        return view('user-dashboard.ai_swot');
    }


    public function aiswot_generate(Request $request)
    {
        $request->validate([
            'businessName' => 'required|string|max:100',
            'businessType' => 'required|string|max:100',
            'description' => 'required|string|min:20',
        ]);

        $prompt = "Lakukan analisis SWOT mendalam untuk bisnis:
        Nama: {$request->businessName} | Jenis: {$request->businessType}
        Pesaing: {$request->competitors} | Deskripsi: {$request->description}

        WAJIB Berikan TEPAT 5 POIN untuk setiap kategori (Strengths, Weaknesses, Opportunities, Threats).
        Setiap poin harus memiliki persentase bobot pengaruh (0-100%) berdasarkan dampaknya terhadap bisnis.

        Berikan respons dalam format JSON murni:
        {
        \"strengths\": [
            {\"text\": \"Pesan poin 1\", \"percentage\": 85},
            {\"text\": \"Pesan poin 2\", \"percentage\": 70},
            ... (sampai 4 poin)
        ],
        \"weaknesses\": [... 4 poin],
        \"opportunities\": [... 4 poin],
        \"threats\": [... 4 poin],
        \"recommendation\": \"Satu kalimat strategi utama.\",
        \"conclusion\": \"Kesimpulan mendalam untuk menaikkan penjualan berdasarkan analisis di atas.\"
        }
        Bahasa: Indonesia Profesional.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('DEEPSEEK_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah konsultan strategi bisnis McKinsey.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $result = json_decode($response->json('choices.0.message.content'), true);
                return response()->json($result);
            }
            return response()->json(['error' => 'API Error'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy_swot_session()
    {
        session()->forget(['swot_result', 'swot_form_data']);
        session()->save();
        return redirect()->route('ai.swot.page')->with('success', 'Analisa berhasil direset.');
    }


    public function ai_seo_header()
    {
        return view("user-dashboard.ai_seo_header");
    }

    public function ai_seo_generate(Request $request)
    {
        $request->validate([
            'businessName' => 'required|string|max:100',
            'description' => 'required|string',
        ]);

        $prompt = "Buatkan Meta SEO Header untuk bisnis berikut:
        Nama: {$request->businessName}
        Jenis: {$request->businessType}
        Deskripsi: {$request->description}
        Image URL: {$request->imageUrl}

        Berikan respons dalam format JSON murni:
        {
            \"title\": \"Judul SEO Max 60 Karakter\",
            \"description\": \"Meta Deskripsi Max 155 Karakter\",
            \"seo_score\": 95,
            \"analysis\": \"Penjelasan strategi SEO singkat (max 2 kalimat).\",
            \"html_code\": \"<title>...</title><meta...> (Lengkap Google, OG FB/WA, Twitter, Schema JSON-LD)\"
        }
        Bahasa: Indonesia Profesional.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('DEEPSEEK_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah pakar SEO Senior.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'response_format' => ['type' => 'json_object'],
            ]);

            if ($response->successful()) {
                // Ambil konten dari Deepseek dan decode
                $content = $response->json('choices.0.message.content');
                $result = json_decode($content, true);

                // Pastikan key sinkron dengan file Blade
                return response()->json([
                    'title'       => $result['title'] ?? $request->businessName,
                    'description' => $result['description'] ?? '',
                    'seo_score'   => (int)($result['seo_score'] ?? 80),
                    'analysis'    => $result['analysis'] ?? 'Analisis otomatis disesuaikan.',
                    'html_code'   => $result['html_code'] ?? ''
                ]);
            }
            
            return response()->json(['error' => 'Gagal menghubungi Deepseek'], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy_seo_session()
    {
        session()->forget(['seo_result', 'seo_form_data']);
        session()->save();

        return redirect()->route('ai.seo.page')->with('success', 'Konfigurasi SEO berhasil direset.');
    }


    public function ai_blog_header()
    {

        $articles = auth()->user()->aiBlogs()->latest()->get();
        return view('user-dashboard.ai_blog_header', compact('articles'));
    }

    public function ai_blog_generate(Request $request)
    {
        $request->validate([
            'blogTitle' => 'required|string|max:255',
            'description' => 'required|string',
            'imageUrl' => 'nullable|string', // Sesuai dengan x-model formData.imageUrl
        ]);
    
        $title = $request->blogTitle;
        $desc = $request->description;
        $headerImage = $request->imageUrl;
    
        // Prompt yang lebih terstruktur untuk kebutuhan database
        $prompt = "Tuliskan artikel blog lengkap, informatif, dan SEO-friendly.
        Topik: '$title'
        Deskripsi/Poin Utama: $desc
        
        Instruksi:
        1. Gunakan gaya bahasa profesional dan mudah dimengerti.
        2. Format HTML dengan H2 dan H3.
        3. Jika ada Image URL berikut: '$headerImage', pasang di paling atas sebagai header menggunakan tag <img src='$headerImage' class='w-full rounded-3xl mb-8 shadow-lg'>.
        4. Buat minimal 500 kata.
        5. Berikan FAQ singkat di akhir.
    
        Kembalikan dalam JSON murni (Strict):
        {
          'full_content': 'HTML string artikel lengkap',
          'seo_score': angka 1-100,
          'hashtags': ['tag1', 'tag2', 'tag3'],
          'meta_description': 'deskripsi SEO singkat'
        }";
    
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('DEEPSEEK_API_KEY'),
                'Content-Type' => 'application/json',
            ])
            ->timeout(240)
            ->connectTimeout(10)
            ->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah AI Blog Writer profesional yang merespon hanya dalam format JSON.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'response_format' => ['type' => 'json_object']
            ]);
    
            if ($response->failed()) {
                $errorMsg = $response->status() == 429 ? 'Server AI sibuk, coba lagi nanti.' : 'AI Provider Error.';
                return response()->json(['error' => $errorMsg], 500);
            }
    
            // Decode konten dari DeepSeek
            $content = json_decode($response->json('choices.0.message.content'), true);
    
            // Tambahkan metadata tambahan jika diperlukan sebelum dikirim ke frontend
            return response()->json($content);
    
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json(['error' => 'Koneksi ke AI terputus (Timeout).'], 504);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store_blog(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'seo_score' => 'required|numeric',
            'image_url' => 'nullable|string',
            'description' => 'nullable|string',
            'hashtags' => 'nullable|array'
        ]);

        // Menggunakan relasi yang baru kita buat di Model User
        $blog = auth()->user()->aiBlogs()->create($validated);

        return response()->json([
            'success' => true,
            'article' => [
                'id' => $blog->id,
                'title' => $blog->title,
                'seo_score' => $blog->seo_score,
                'date' => $blog->created_at->format('d M')
            ]
        ]);
    }

    public function show_blog($id)
    {
        $article = auth()->user()->aiBlogs()->findOrFail($id);

        return response()->json([
            'id'         => $article->id,
            'title'      => $article->title,
            'content'    => $article->content,
            'seo_score'  => $article->seo_score,
            // Pastikan kolom hashtags di database di-cast ke array di Model atau didecode di sini
            'hashtags'   => is_array($article->hashtags) ? $article->hashtags : json_decode($article->hashtags, true) ?? [],
        ]);
    }

    public function delete_blog($id)
    {
        $blog = auth()->user()->aiBlogs()->findOrFail($id);
        $blog->delete();

        return response()->json(['success' => true]);
    }




    public function ai_linktree_generate(Request $request)
    {
        $request->validate([
            'profileName' => 'required|string|max:255',
            'designConcept' => 'required|string',
            'links' => 'required|array',
            'profileImage' => 'nullable|string',
        ]);
    
        $linksData = collect($request->links)->map(fn($l) => [
            'label' => $l['label'],
            'url' => $l['url']
        ]);
    
        // Panduan Desain untuk AI
        $designGuide = "
        PANDUAN VISUAL (WAJIB):
        1. STYLE: Flat Design, Clean, Modern, Light Mode Default.
        2. WARNA: Latar belakang Soft (Slate 50), Kartu Putih bersih, Aksen sesuai konsep '{$request->designConcept}'.
        3. FONT: Gunakan 'Inter' atau 'Plus Jakarta Sans' dari Google Fonts.
        4. GAMBAR PROFIL: 
           - Jika URL profil ada: Tampilkan dalam lingkaran (w-24 h-24) dengan border putih tebal.
           - Jika URL profil KOSONG: Buat div lingkaran dengan gradient warna cerah dan icon Lucu/Estetik dari FontAwesome (misal: fa-user-ninja, fa-cat, fa-robot).
        5. ICON LINK: Setiap tombol link WAJIB memiliki icon FontAwesome yang relevan di sebelah kiri (misal: Instagram -> fa-instagram).
        6. FOOTER: Tambahkan footer kecil di paling bawah: 'w3site bio link | 2026' dengan opacity rendah.
        ";
    
        $prompt = "Buatkan satu halaman HTML Linktree profesional.
        
        DATA:
        - Nama: '{$request->profileName}'
        - Foto URL: '{$request->profileImage}'
        - Links: " . json_encode($linksData) . "
        - Konsep: '{$request->designConcept}'
    
        {$designGuide}
    
        INSTRUKSI TEKNIS:
        1. Pakai Tailwind CDN & FontAwesome CDN.
        2. Mobile-first (max-width container 480px di desktop).
        3. Tambahkan animasi fade-in-up untuk setiap elemen.
        4. Pastikan target='_blank' dan rel='noopener'.
        5. Jangan pakai css tambahan, gunakan tailwind saja.
    
        KEMBALIKAN JSON: { 'full_html': '...', 'design_summary': '...' }";
    
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('DEEPSEEK_API_KEY'),
            ])
            ->timeout(240)
            ->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah UI/UX Designer spesialis Flat Design Modern.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'response_format' => ['type' => 'json_object']
            ]);
    
            return response()->json(json_decode($response->json('choices.0.message.content'), true));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function linktree()
    {
        $user = Auth::user();
        $myLinks = $user->linktrees()->latest()->get();
        
        // Opsional: Anda bisa mengirimkan data limit langsung ke view
        $limits = [0 => 10, 1 => 100, 2 => 1000];
        $currentLimit = $limits[$user->package] ?? 10;
    
        return view('user-dashboard.linktree', compact('myLinks', 'currentLimit'));
    }
    public function store_linktree(Request $request)
    {
        $request->validate([
            'profileName' => 'required|string|max:255',
            'html_content' => 'required',
        ]);
    
        try {
            $user = Auth::user();
    
            // --- START LOGIKA LIMITASI ---
            // 1. Tentukan limit berdasarkan paket user (0: 10, 1: 100, 2: 1000)
            $limits = [0 => 10, 1 => 100, 2 => 1000];
            $maxBiolinks = $limits[$user->package] ?? 10;
    
            // 2. Hitung jumlah biolink yang sudah ada
            $currentCount = $user->linktrees()->count();
    
            // 3. Jika ini pembuatan BARU (bukan update), cek apakah kuota masih ada
            if (!$request->id && $currentCount >= $maxBiolinks) {
                return response()->json([
                    'success' => false,
                    'message' => "Limit tercapai! Paket Anda hanya mendukung maksimal {$maxBiolinks} biolink."
                ], 403); // Return 403 Forbidden agar terbaca di catch frontend
            }
            // --- END LOGIKA LIMITASI ---
    
            $data = [
                'title' => $request->profileName,
                'image_url' => $request->profileImage,
                'links_json' => $request->links,
                'design_concept' => $request->designConcept,
                'html_content' => $request->html_content,
            ];
    
            // Logika Handle Slug
            if (!$request->id) {
                $baseSlug = Str::slug($request->profileName);
                $slug = $baseSlug;
                
                // Cek keunikan slug secara rekursif/looping agar tidak crash
                $count = \App\Models\Linktree::where('slug', $slug)->count();
                if ($count > 0) {
                    $slug = $baseSlug . '-' . Str::lower(Str::random(5));
                }
                $data['slug'] = $slug;
            }
    
            // Simpan ke Database
            $linktree = $user->linktrees()->updateOrCreate(
                ['id' => $request->id], 
                $data
            );
    
            return response()->json([
                'success' => true,
                'message' => 'Design successfully saved!',
                'url' => url('/' . $linktree->slug) 
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show_linktree($id)
    {
        $data = Auth::user()->linktrees()->findOrFail($id);
        return response()->json($data);
    }

    public function delete_linktree($id)
    {
        $data = Auth::user()->linktrees()->findOrFail($id);
        $data->delete();

        return response()->json(['success' => true]);
    }

    public function render_linktree($slug)
    {
        // 1. Cari data berdasarkan slug
        $linktree = Linktree::where('slug', $slug)
                        ->where('is_active', true)
                        ->firstOrFail();

        // 2. Update Statistik (Monitoring)
        $linktree->increment('views_count');
        $linktree->update(['last_accessed_at' => now()]);

        return response($linktree->html_content, 200)
                ->header('Content-Type', 'text/html');
    }


}