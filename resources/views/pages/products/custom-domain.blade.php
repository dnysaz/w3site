<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Domain - w3site.id</title>
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
            <h1 class="text-[15rem] md:text-[30rem] font-[1000] text-slate-50 tracking-tighter leading-none italic uppercase">DOMAIN</h1>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            {{-- Header --}}
            <div class="max-w-3xl mb-24">
                <p class="text-[10px] font-black text-blue-600 tracking-[0.5em] mb-6 uppercase">Branding & Identitas</p>
                <h2 class="text-6xl md:text-8xl font-[1000] text-slate-900 tracking-tighter italic leading-[0.9]">
                    Custom <span class="text-blue-600">Domain.</span>
                </h2>
                <p class="mt-8 text-lg text-slate-500 font-medium leading-relaxed italic">
                    Nama adalah brand. Bangun kredibilitas bisnismu atau portofoliomu dengan alamat web yang profesional dan mudah diingat.
                </p>
            </div>

            {{-- Info Section --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-32 italic">
                <div class="p-10 bg-slate-50 rounded-[3rem] border border-slate-100">
                    <h4 class="font-black text-xl mb-4 italic">SSL Otomatis</h4>
                    <p class="text-slate-500 text-sm">Setiap domain yang kamu hubungkan otomatis mendapatkan enkripsi HTTPS gratis untuk keamanan data pengunjung.</p>
                </div>
                <div class="p-10 bg-slate-50 rounded-[3rem] border border-slate-100">
                    <h4 class="font-black text-xl mb-4 italic">Tanpa Ribet</h4>
                    <p class="text-slate-500 text-sm">Integrasi DNS yang simpel. Kami bantu arahkan langkah demi langkah sampai situsmu aktif dengan nama domain baru.</p>
                </div>
                <div class="p-10 bg-blue-600 rounded-[3rem] text-white">
                    <h4 class="font-black text-xl mb-4 italic text-slate-900">Terpercaya</h4>
                    <p class="text-blue-50 text-sm font-medium">Penggunaan domain .com atau .id meningkatkan kepercayaan klien hingga 80% dibandingkan domain gratisan.</p>
                </div>
            </div>

            {{-- HOSTINGER PARTNER SECTION --}}
            <section class="mb-20">
                <div class="relative bg-slate-900 rounded-[3rem] p-10 md:p-16 overflow-hidden shadow-2xl shadow-indigo-900/40 group">
                    
                    {{-- Animasi Latar Belakang --}}
                    <div class="absolute -right-20 -top-20 w-[30rem] h-[30rem] bg-indigo-600/20 rounded-full blur-[120px] group-hover:bg-indigo-600/30 transition-colors duration-1000"></div>
                    <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-blue-600/10 rounded-full blur-[100px]"></div>
            
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10 text-left">
                        <div class="lg:col-span-7">
                            <div class="inline-flex items-center gap-3 px-4 py-2 bg-indigo-500/10 border border-indigo-500/20 rounded-full mb-8">
                                <span class="flex h-2 w-2 rounded-full bg-indigo-500 animate-ping"></span>
                                <span class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.3em]">Official Hosting Partner</span>
                            </div>
                            
                            <h2 class="text-4xl md:text-6xl font-[1000] text-white leading-[1.1] mb-8 tracking-tighter italic">
                                Butuh Domain <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-blue-400">.com</span> & <br>
                                Hosting Premium?
                            </h2>
                            
                            <p class="text-slate-400 text-lg leading-relaxed mb-10 font-medium max-w-2xl italic">
                                Jika Anda butuh performa lebih tinggi, <span class="text-white font-bold">Dedicated Domain</span>, dan penyimpanan besar, kami merekomendasikan **Hostinger**. Dapatkan diskon khusus untuk komunitas w3site.id.
                            </p>
            
                            <div class="flex flex-wrap gap-5">
                                <a href="https://hostinger.co.id?REFERRALCODE=w3site" target="_blank" class="px-10 py-5 bg-white text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-400 hover:text-white transition-all shadow-xl active:scale-95 flex items-center gap-3 italic">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    Klaim Diskon 20% Anda
                                </a>
                                <div class="flex flex-col justify-center">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">Mulai Dari</span>
                                    <span class="text-xl font-black text-white leading-none">Rp 24.900<span class="text-xs text-slate-500 font-bold italic ml-1">/bln</span></span>
                                </div>
                            </div>
                        </div>
            
                        <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                            {{-- Feature Cards --}}
                            <div class="p-6 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-md group-hover:border-indigo-500/30 transition-colors">
                                <div class="w-10 h-10 bg-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400 mb-4 text-sm">
                                    <i class="fa-solid fa-globe"></i>
                                </div>
                                <h4 class="text-white font-black text-[10px] uppercase tracking-widest mb-2 italic leading-none">Free Domain</h4>
                                <p class="text-slate-500 text-[11px] font-medium leading-snug italic">Dapatkan domain .com/.net gratis untuk paket tertentu.</p>
                            </div>
            
                            <div class="p-6 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-md group-hover:border-blue-500/30 transition-colors">
                                <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center text-blue-400 mb-4 text-sm">
                                    <i class="fa-solid fa-bolt-lightning"></i>
                                </div>
                                <h4 class="text-white font-black text-[10px] uppercase tracking-widest mb-2 italic leading-none">LiteSpeed Engine</h4>
                                <p class="text-slate-500 text-[11px] font-medium leading-snug italic">Kecepatan website maksimal dengan teknologi caching terbaru.</p>
                            </div>
            
                            <div class="p-6 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-md group-hover:border-emerald-500/30 transition-colors">
                                <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400 mb-4 text-sm">
                                    <i class="fa-solid fa-shield-virus"></i>
                                </div>
                                <h4 class="text-white font-black text-[10px] uppercase tracking-widest mb-2 italic leading-none">Weekly Backup</h4>
                                <p class="text-slate-500 text-[11px] font-medium leading-snug italic">Data Anda aman dengan sistem backup otomatis mingguan.</p>
                            </div>
            
                            <div class="p-6 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-md group-hover:border-amber-500/30 transition-colors">
                                <div class="w-10 h-10 bg-amber-500/20 rounded-xl flex items-center justify-center text-amber-400 mb-4 text-sm">
                                    <i class="fa-solid fa-headset"></i>
                                </div>
                                <h4 class="text-white font-black text-[10px] uppercase tracking-widest mb-2 italic leading-none">24/7 CS Lokal</h4>
                                <p class="text-slate-500 text-[11px] font-medium leading-snug italic">Bantuan teknis dalam bahasa Indonesia kapan saja.</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Trust Badge --}}
                    <div class="mt-16 pt-8 border-t border-white/5 flex flex-wrap justify-between items-center gap-6 relative z-10">
                        <div class="flex items-center gap-6 opacity-40">
                            <span class="text-white font-black italic tracking-tighter text-xl uppercase">HOSTINGER</span>
                            <span class="h-4 w-[1px] bg-white/20"></span>
                            <span class="text-white/60 text-[10px] font-bold uppercase tracking-[0.2em]">Referral Partner</span>
                        </div>
                        <p class="text-[9px] text-slate-500 font-bold tracking-widest italic max-w-xs md:text-right text-left">
                            * Komisi membantu w3site.id tetap gratis untuk pelajar & UMKM Indonesia.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-100 py-12 text-center relative z-10">
        <p class="text-[10px] font-black text-slate-400 tracking-widest uppercase">&copy; 2026 w3site.id — Partnering with the best</p>
    </footer>

</body>
</html>