<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloud Database - w3site.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-white antialiased">
    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white font-black italic text-sm">w3</div>
                <span class="text-xl font-black tracking-tighter text-slate-900">w3site<span class="text-blue-600">.id</span></span>
            </a>
            <a href="{{ route('home') }}" class="text-xs font-black tracking-widest text-slate-400 hover:text-blue-600 transition-colors italic uppercase">Kembali</a>
        </div>
    </nav>

    <main class="relative pt-40 pb-24 overflow-hidden">
        {{-- Siluet Background --}}
        <div class="absolute top-20 left-1/2 -translate-x-1/2 select-none pointer-events-none z-0">
            <h1 class="text-[15rem] md:text-[30rem] font-[1000] text-slate-50 tracking-tighter leading-none italic uppercase">DATA</h1>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            {{-- Header --}}
            <div class="max-w-3xl mb-24">
                <p class="text-[10px] font-black text-blue-600 tracking-[0.5em] mb-6 uppercase italic">Infrastruktur & Penyimpanan</p>
                <h2 class="text-6xl md:text-8xl font-[1000] text-slate-900 tracking-tighter italic leading-[0.9]">
                    Cloud <span class="text-blue-600">Database.</span>
                </h2>
                <p class="mt-8 text-lg text-slate-500 font-medium leading-relaxed italic">
                    Simpan dan akses data aplikasimu secara real-time dengan infrastruktur yang aman dan terukur. Kami mendukung berbagai metode penyimpanan modern.
                </p>
            </div>

            {{-- Main Highlight --}}
            <div class="bg-slate-900 rounded-[3rem] p-10 md:p-14 text-white italic flex flex-col md:flex-row gap-12 items-center mb-12 relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl"></div>
                <div class="flex-1 relative z-10">
                    <h3 class="text-3xl font-black mb-6 text-blue-500">Integrasi Sempurna.</h3>
                    <p class="text-slate-400 text-lg leading-relaxed">
                        Khusus untuk UMKM dan freelancer yang membutuhkan sistem dinamis, database kami dirancang agar sangat mudah diakses melalui API untuk kebutuhan situs interaktifmu.
                    </p>
                </div>
                <div class="px-8 py-6 bg-slate-800 rounded-2xl border border-slate-700 font-mono text-xs text-blue-400 shadow-2xl relative z-10">
                    <div class="flex gap-2 mb-2">
                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                        <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                    </div>
                    <span>{ "status": "online", "sync": true }</span>
                </div>
            </div>

            {{-- Database Options --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-24">
                {{-- SQLite Option --}}
                <div class="p-10 bg-slate-50 rounded-[3rem] border border-slate-100 group hover:border-blue-600/30 transition-all">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-file-code text-xl"></i>
                    </div>
                    <h4 class="font-black text-2xl mb-4 italic text-slate-900">SQLite</h4>
                    <p class="text-slate-500 text-sm leading-relaxed italic">
                        Untuk aplikasi yang lebih simpel, kamu bisa menggunakan database berbasis file seperti **SQLite**. Praktis, tanpa konfigurasi server tambahan, dan sangat kencang untuk website statis yang dinamis.
                    </p>
                </div>

                {{-- Cloud Provider Option --}}
                <div class="p-10 bg-slate-50 rounded-[3rem] border border-slate-100 group hover:border-emerald-600/30 transition-all">
                    <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i class="fa-solid fa-cloud text-xl"></i>
                    </div>
                    <h4 class="font-black text-2xl mb-4 italic text-slate-900">Supabase & Firebase</h4>
                    <p class="text-slate-500 text-sm leading-relaxed italic">
                        Butuh fitur real-time atau autentikasi? w3site.id sangat kompatibel dengan layanan cloud eksternal seperti **Supabase** atau **Firebase**. Hubungkan API mereka dan buat aplikasi skala besar.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-100 py-12 text-center">
        <p class="text-[10px] font-black text-slate-400 tracking-widest uppercase">&copy; 2026 w3site.id — Flexible Data Solutions</p>
    </footer>
</body>
</html>