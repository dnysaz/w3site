<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situs Statis - w3site.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-white antialiased">
    <nav class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white font-black italic text-sm">w3</div>
                <span class="text-xl font-black tracking-tighter text-slate-900">w3site<span class="text-blue-600">.id</span></span>
            </a>
            <a href="{{ route('home') }}" class="text-xs font-black tracking-widest text-slate-400 hover:text-blue-600 transition-colors italic">Kembali</a>
        </div>
    </nav>

    <main class="relative pt-40 pb-24 overflow-hidden">
        <div class="absolute top-20 left-1/2 -translate-x-1/2 select-none pointer-events-none z-0">
            <h1 class="text-[15rem] md:text-[30rem] font-[1000] text-slate-50 tracking-tighter leading-none italic uppercase">MAGIC</h1>
        </div>
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="max-w-3xl mb-24">
                <p class="text-[10px] font-black text-blue-600 tracking-[0.5em] mb-6">Era Baru</p>
                <h2 class="text-6xl md:text-8xl font-[1000] text-slate-900 tracking-tighter italic leading-[0.9]">AI <span class="text-blue-600">Builder.</span></h2>
                <p class="mt-8 text-lg text-slate-500 font-medium leading-relaxed italic">Bikin website semudah chatting. Deskripsikan bisnismu, biar AI kami yang menyusun desain dan kontennya secara instan.</p>
            </div>
            <div class="bg-slate-900 rounded-[3rem] p-10 md:p-16 text-white italic relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-600/20 rounded-full blur-3xl group-hover:bg-blue-600/40 transition-all duration-700"></div>
                <h3 class="text-3xl font-black mb-6 text-blue-500 leading-tight">Mendukung Kreativitas Tanpa Batas.</h3>
                <p class="text-slate-400 text-lg leading-relaxed max-w-2xl">Kami percaya bahwa keterbatasan teknis tidak boleh membunuh ide besar. AI Builder hadir sebagai asisten pribadi bagi setiap anak muda dan UMKM Indonesia.</p>
            </div>
        </div>
    </main>
</body>
</html>