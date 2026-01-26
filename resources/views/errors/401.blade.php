<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 - Akses Tidak Sah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white antialiased">
    <div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
        <div class="absolute inset-0 flex items-center justify-center select-none pointer-events-none">
            <h1 class="text-[25rem] md:text-[35rem] font-[1000] text-slate-100 leading-none">401</h1>
        </div>
        <div class="relative z-10 text-center">
            <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.4em] mb-4">Error Detected</p>
            <h2 class="text-5xl md:text-7xl font-[1000] text-slate-900 tracking-tighter italic mb-8">
                Akses <span class="text-blue-600">Tidak Sah!</span>
            </h2>
            <a href="{{ route('home') }}" class="inline-flex items-center px-10 py-4 bg-slate-900 text-white text-xs font-black uppercase italic tracking-[0.2em] rounded-2xl hover:bg-blue-600 hover:shadow-2xl hover:shadow-blue-500/20 transition-all duration-300">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali Ke Awal
            </a>
            <div class="mt-12 opacity-30 text-[9px] font-bold text-slate-400 uppercase tracking-widest">w3site.id</div>
        </div>
    </div>
</body>
</html>