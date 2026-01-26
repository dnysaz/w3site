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
            <h1 class="text-[15rem] md:text-[30rem] font-[1000] text-slate-50 tracking-tighter leading-none italic uppercase">STATIC</h1>
        </div>
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="max-w-3xl mb-24">
                <p class="text-[10px] font-black text-blue-600 tracking-[0.5em] mb-6">Layanan Utama</p>
                <h2 class="text-6xl md:text-8xl font-[1000] text-slate-900 tracking-tighter italic leading-[0.9]">Situs <span class="text-blue-600">Statis.</span></h2>
                <p class="mt-8 text-lg text-slate-500 font-medium leading-relaxed italic">Tanpa database, tanpa loading lama. Cukup upload file HTML/CSS/JS kamu dan biarkan infrastruktur global kami yang bekerja.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-32 italic">
                <div class="bg-slate-50 rounded-[3rem] p-10 border border-slate-100 hover:bg-slate-900 hover:text-white transition-all duration-500 group">
                    <h3 class="text-2xl font-black mb-6">Kecepatan Maksimal</h3>
                    <p class="text-slate-500 group-hover:text-slate-400 font-medium">Situs statis didistribusikan melalui CDN global, memastikan pengunjung dari mana saja dapat mengakses web kamu secepat kilat.</p>
                </div>
                <div class="bg-blue-600 rounded-[3rem] p-10 text-white shadow-2xl shadow-blue-500/20">
                    <h3 class="text-2xl font-black mb-6">Gratis & Selamanya</h3>
                    <p class="text-blue-50 font-medium">Khusus untuk pelajar dan freelancer yang baru memulai, kami sediakan slot hosting statis gratis untuk mendukung portofolio kamu.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>