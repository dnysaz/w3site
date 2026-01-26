<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Showcase — w3site.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700;800&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #fcfcfc;
        }
        .font-space { font-family: 'Space Grotesk', sans-serif; }
        
        .project-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .image-shutter {
            transition: all 0.6s ease;
            filter: grayscale(100%);
        }

        .project-card:hover .image-shutter {
            filter: grayscale(0%);
            transform: scale(1.05);
        }

        .btn-visit {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="antialiased">

    <header class="sticky top-0 z-50 bg-[#f8fafc]/80 backdrop-blur-md border-b border-slate-200/50">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold italic text-lg shadow-lg shadow-blue-200 group-hover:scale-105 transition-transform">
                    w3
                </div>
                <span class="text-xl font-extrabold tracking-tighter text-slate-900">
                    w3site<span class="text-blue-600">.id</span>
                </span>
            </a>
    
            <div class="flex items-center gap-8">
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 pl-2 pr-5 py-2 bg-slate-50 border border-slate-100 rounded-2xl hover:bg-white hover:border-blue-100 hover:shadow-lg hover:shadow-blue-50/50 transition-all duration-300">
                            <div class="w-9 h-9 bg-slate-900 text-white rounded-xl flex items-center justify-center text-xs font-black uppercase tracking-tighter group-hover:bg-blue-600 transition-colors">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="flex flex-col items-start">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none mb-1">Dashboard</span>
                                <span class="text-sm font-bold text-slate-900 leading-none">{{ auth()->user()->name }}</span>
                            </div>
                            <i class="fa-solid fa-chevron-right text-[10px] text-slate-300 ml-2 group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-blue-600 shadow-xl shadow-slate-200 hover:shadow-blue-100 transition-all active:scale-95">Daftar Gratis</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-20">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-3 px-4 py-2 bg-white border border-slate-200 rounded-2xl mb-8 shadow-sm group hover:border-blue-300 transition-colors cursor-default">
                    <span class="flex h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Showcase <span class="text-blue-600">Karya Digital</span></span>
                </div>
                
                <h1 class="text-5xl md:text-[4rem] font-[900] leading-[0.95] mb-8 tracking-[-0.04em] text-slate-900">
                    Inspirasi Karya <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Anak <span class="italic pr-4">Bangsa!</span></span>
                </h1>
                
                <p class="text-lg md:text-xl text-slate-500 max-w-xl leading-relaxed mb-10 font-medium">
                    Jelajahi kumpulan <span class="text-slate-900 font-bold italic">Situs Statis & Landing Page</span> terbaik yang dibangun oleh komunitas. Dari tugas sekolah hingga profil bisnis, semua ada di sini.
                </p>
                
                <form action="{{ route('showcase.index') }}" method="GET" class="bg-white p-3 rounded-[2.5rem] shadow-[0_20px_50px_rgba(8,112,184,0.12)] border border-slate-100 flex flex-col md:flex-row gap-3 max-w-2xl relative z-10 hover:border-blue-200 transition-all">
                    <div class="flex-1 flex items-center px-6 py-4 bg-slate-50 rounded-[1.8rem] group focus-within:bg-white focus-within:ring-2 focus-within:ring-blue-100 transition-all">
                        <i class="fa-solid fa-magnifying-glass text-slate-300 mr-3 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama projek atau kreator..." 
                            class="bg-transparent w-full outline-none font-black text-slate-800 placeholder:font-bold placeholder:text-slate-300 text-lg tracking-tight">
                    </div>
                    <button type="submit" class="bg-slate-900 text-white px-10 py-5 rounded-[1.8rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-slate-200 hover:bg-blue-600 hover:shadow-blue-100 transition-all active:scale-95 flex items-center justify-center gap-2">
                        Cari Karya <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </form>
                
                <div class="mt-12 flex flex-wrap gap-8">
                    <div class="flex items-center gap-3 group">
                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 text-xs shadow-sm border border-blue-100 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-fire-flame-curved"></i>
                        </div>
                        <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Paling Populer</span>
                    </div>
                    <div class="flex items-center gap-3 group">
                        <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 text-xs shadow-sm border border-emerald-100 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Terbaru</span>
                    </div>
                    <div class="flex items-center gap-3 group">
                        <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 text-xs shadow-sm border border-amber-100 group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Pilihan Editor</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 relative hidden lg:block">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-blue-100 rounded-full blur-3xl opacity-60"></div>
                <div class="relative bg-white rounded-3xl border border-gray-100 shadow-xl p-6 overflow-hidden">
                    <div class="flex items-center gap-1.5 mb-6">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        <div class="ml-4 flex-1 h-6 bg-gray-50 rounded-full flex items-center px-3">
                            <span class="text-[12px] text-gray-400">https://<span class="text-blue-600 font-bold">gallery</span>.w3site.id</span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="w-2/3 h-8 bg-gray-100 rounded-lg"></div>
                        <div class="w-full h-32 bg-slate-900 rounded-2xl flex flex-col items-center justify-center border-2 border-white/10 text-white font-bold tracking-widest text-xl relative overflow-hidden group">
                            <div class="absolute inset-0 bg-blue-600/20 opacity-50"></div>
                            <span class="relative z-10 text-xs uppercase tracking-[0.3em] mb-2 text-blue-400">Live Exhibition</span>
                            <span class="relative z-10">USER GALLERY</span>
                        </div>
                        <div class="space-y-2">
                            <div class="w-full h-3 bg-gray-100 rounded"></div>
                            <div class="w-full h-3 bg-gray-100 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-36">
            @forelse($sites as $site)
                <div class="project-card rounded-[2rem] flex flex-col overflow-hidden bg-white group border border-slate-100">
                    <div class="relative aspect-[16/10] m-3 rounded-[1rem] overflow-hidden bg-slate-100 group">
                        <div class="absolute inset-0 w-full h-full">
                            <div class="w-[300%] h-[300%] origin-top-left scale-[0.3333] pointer-events-none transition-all duration-700 group-hover:scale-[0.35]">
                                <iframe 
                                    src="https://{{ $site->subdomain }}.w3site.id" 
                                    class="w-full h-full border-none"
                                    loading="lazy"
                                    frameborder="0"
                                    scrolling="no">
                                </iframe>
                            </div>
                        </div>
                    
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-60 group-hover:opacity-0 transition-opacity duration-500"></div>
                        
                        <a href="https://{{ $site->subdomain }}.w3site.id" target="_blank" class="absolute inset-0 z-10"></a>
                    
                        <div class="absolute top-4 left-4 z-20 flex gap-2">
                            <span class="px-3 py-1 bg-black/80 backdrop-blur-md text-white text-[9px] font-black tracking-widest rounded-full shadow-lg">
                                <i class="fa-solid fa-earth-asia text-[8px] mr-1 text-blue-400"></i> by w3site.id
                            </span>
                        </div>
                    
                        <div class="absolute inset-0 bg-blue-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 pointer-events-none"></div>
                    </div>
                    <div class="px-8 pb-8 pt-4 flex flex-col flex-1">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-space text-2xl tracking-tighter text-slate-900 group-hover:text-blue-600 transition-colors">{{ $site->subdomain }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[11px] font-bold text-slate-400 italic">{{ $site->subdomain }}.w3site.id</span>
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-slate-500 text-xs leading-relaxed mb-8 flex-1 font-medium">
                            {{ Str::limit($site->description, 120, '...') }}
                        </p>
            
                        <div class="flex items-center justify-between pt-6 border-t border-slate-50 mt-auto">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 overflow-hidden shadow-sm">
                                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $site->user->name }}" alt="avatar">
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Kreator</span>
                                    <span class="text-[11px] font-bold text-slate-700">{{ $site->user->name }}</span>
                                </div>
                            </div>
                            <a href="https://{{ $site->subdomain }}.w3site.id" target="_blank" class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-200 transition-all duration-300">
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-50 rounded-full mb-6">
                        <i class="fa-solid fa-magnifying-glass text-slate-200 text-3xl"></i>
                    </div>
                    <h3 class="font-space text-2xl text-slate-400 uppercase">Projek tidak ditemukan</h3>
                    <p class="text-slate-400 text-sm mt-2">Coba kata kunci lain atau telusuri semua karya.</p>
                </div>
            @endforelse

            <a href="{{ route('dashboard') }}" class="border-2 border-dashed border-slate-200 rounded-[2rem] p-8 flex flex-col items-center justify-center text-center group hover:bg-white hover:border-blue-400 hover:shadow-2xl hover:shadow-blue-500/10 transition-all cursor-pointer bg-slate-50/50">
                <div class="w-20 h-20 rounded-[2rem] bg-white shadow-sm flex items-center justify-center text-slate-300 mb-6 group-hover:text-blue-600 group-hover:rotate-12 transition-all">
                    <i class="fa-solid fa-plus-circle text-4xl"></i>
                </div>
                <h3 class="font-space text-xl uppercase text-slate-400 group-hover:text-slate-900">Karyamu Disini?</h3>
                <p class="text-[11px] font-bold text-slate-300 tracking-widest uppercase mt-2">Mulai bangun sekarang</p>
                <div class="mt-8 px-6 py-3 bg-white border border-slate-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-all">
                    Buat Situs Gratis
                </div>
            </a>
        </div>

        <div class="mt-20">
            {{ $sites->links() }}
        </div>
    </main>

    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="relative bg-slate-900 rounded-[3rem] p-10 md:p-16 overflow-hidden shadow-2xl shadow-indigo-900/40 group">
            
            {{-- Animasi Latar Belakang --}}
            <div class="absolute -right-20 -top-20 w-[30rem] h-[30rem] bg-indigo-600/20 rounded-full blur-[120px] group-hover:bg-indigo-600/30 transition-colors duration-1000"></div>
            <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-blue-600/10 rounded-full blur-[100px]"></div>
    
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-3 px-4 py-2 bg-indigo-500/10 border border-indigo-500/20 rounded-full mb-8">
                        <span class="flex h-2 w-2 rounded-full bg-indigo-500 animate-ping"></span>
                        <span class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.3em]">Official Hosting Partner</span>
                    </div>
                    
                    <h2 class="text-4xl md:text-6xl font-[1000] text-white leading-[1.1] mb-8 tracking-tighter">
                        Butuh Domain <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-blue-400">.com</span> & <br>
                        Hosting Premium?
                    </h2>
                    
                    <p class="text-slate-400 text-lg leading-relaxed mb-10 font-medium max-w-2xl">
                        Jika Anda butuh performa lebih tinggi, <span class="text-white font-bold">Dedicated Domain</span>, dan penyimpanan besar, kami merekomendasikan **Hostinger**. Dapatkan diskon khusus untuk komunitas w3site.id.
                    </p>
    
                    <div class="flex flex-wrap gap-5">
                        <a href="https://hostinger.co.id?REFERRALCODE=w3site" target="_blank" class="px-10 py-5 bg-white text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-400 hover:text-white transition-all shadow-xl active:scale-95 flex items-center gap-3">
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
                        <div class="w-10 h-10 bg-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400 mb-4">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                        <h4 class="text-white font-black text-[10px] uppercase tracking-widest mb-2 italic">Free Domain</h4>
                        <p class="text-slate-500 text-[11px] font-medium leading-snug">Dapatkan domain .com/.net gratis untuk paket tertentu.</p>
                    </div>
    
                    <div class="p-6 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-md group-hover:border-blue-500/30 transition-colors">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center text-blue-400 mb-4">
                            <i class="fa-solid fa-bolt-lightning"></i>
                        </div>
                        <h4 class="text-white font-black text-[10px] uppercase tracking-widest mb-2 italic">LiteSpeed Engine</h4>
                        <p class="text-slate-500 text-[11px] font-medium leading-snug">Kecepatan website maksimal dengan teknologi caching terbaru.</p>
                    </div>
    
                    <div class="p-6 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-md group-hover:border-emerald-500/30 transition-colors">
                        <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400 mb-4">
                            <i class="fa-solid fa-shield-virus"></i>
                        </div>
                        <h4 class="text-white font-black text-[10px] uppercase tracking-widest mb-2 italic">Weekly Backup</h4>
                        <p class="text-slate-500 text-[11px] font-medium leading-snug">Data Anda aman dengan sistem backup otomatis mingguan.</p>
                    </div>
    
                    <div class="p-6 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-md group-hover:border-amber-500/30 transition-colors">
                        <div class="w-10 h-10 bg-amber-500/20 rounded-xl flex items-center justify-center text-amber-400 mb-4">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h4 class="text-white font-black text-[10px] uppercase tracking-widest mb-2 italic">24/7 CS Lokal</h4>
                        <p class="text-slate-500 text-[11px] font-medium leading-snug">Bantuan teknis dalam bahasa Indonesia kapan saja.</p>
                    </div>
                </div>
            </div>
            
            {{-- Trust Badge --}}
            <div class="mt-16 pt-8 border-t border-white/5 flex flex-wrap justify-between items-center gap-6 relative z-10">
                <div class="flex items-center gap-6 opacity-40">
                    <span class="text-white font-black italic tracking-tighter text-xl">HOSTINGER</span>
                    <span class="h-4 w-[1px] bg-white/20"></span>
                    <span class="text-white/60 text-[10px] font-bold uppercase tracking-[0.2em]">Referral Partner</span>
                </div>
                <p class="text-[9px] text-slate-500 font-bold tracking-widest italic max-w-xs text-right">
                    * Komisi membantu w3site.id tetap gratis untuk pelajar & UMKM Indonesia.
                </p>
            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-slate-100 pt-24 pb-12 overflow-hidden relative">
        <div class="absolute mb-36 bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 select-none pointer-events-none">
            <h2 class="text-[15rem] md:text-[25rem] font-[1000] text-slate-100 tracking-tighter leading-none opacity-90">
                w3site
            </h2>
        </div>
    
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-16 mb-24">
                
                <div class="md:col-span-5">
                    <a href="{{ route("home") }}" class="flex items-center gap-3 mb-8 group">
                        <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white font-black italic text-lg shadow-xl group-hover:bg-blue-600 transition-colors">
                            w3
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-slate-900">
                            w3site<span class="text-blue-600">.id</span>
                        </span>
                    </a>
                    <p class="text-slate-500 text-lg leading-relaxed max-w-sm font-medium mb-8">
                        Solusi tercepat untuk <span class="text-slate-900 font-bold underline decoration-blue-200">Deploy Static Site & AI </span> Generated Landing Page di Indonesia.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-white hover:text-blue-600 hover:shadow-xl hover:shadow-blue-500/10 transition-all">
                            <i class="fa-brands fa-x-twitter text-lg"></i>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-white hover:text-pink-600 hover:shadow-xl hover:shadow-pink-500/10 transition-all">
                            <i class="fa-brands fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-white hover:text-indigo-600 hover:shadow-xl hover:shadow-indigo-500/10 transition-all">
                            <i class="fa-brands fa-discord text-lg"></i>
                        </a>
                    </div>
                </div>
    
                <div class="md:col-span-7 grid grid-cols-2 md:grid-cols-3 gap-8">
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-8">Produk</h4>
                        <ul class="space-y-4 text-sm font-bold">
                            <li>
                                <a href="{{ route('product.static') }}" class="group text-slate-600 hover:text-blue-600 transition-colors flex items-center gap-2">
                                    Situs Statis 
                                    <i class="fa-solid fa-arrow-right text-[10px] opacity-0 group-hover:opacity-100 transition-all -translate-x-2 group-hover:translate-x-0"></i>
                                </a>
                            </li>
                            <li><a href="{{ route('product.ai') }}" class="text-slate-600 hover:text-blue-600 transition-colors">AI Site Builder</a></li>
                            <li><a href="{{ route('product.domain') }}" class="text-slate-600 hover:text-blue-600 transition-colors">Custom Domain</a></li>
                            <li><a href="{{ route('product.database') }}" class="text-slate-600 hover:text-blue-600 transition-colors">Cloud Database</a></li>
                        </ul>
                    </div>
    
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-8">Bantuan</h4>
                        <ul class="space-y-4 text-sm font-bold">
                            <li><a href="{{ route('docs.index') }}" class="text-slate-600 hover:text-blue-600 transition-colors">Dokumentasi</a></li>
                            <li><a href="{{ route('docs.deployment') }}" class="text-slate-600 hover:text-blue-600 transition-colors">Deployment Guide</a></li>
                            <li><a href="{{ route('about') }}" class="text-slate-600 hover:text-blue-600 transition-colors">About w3site</a></li>
                            <li><a href="{{ route('terms') }}" class="text-slate-600 hover:text-blue-600 transition-colors">Terms & Conditions</a></li>
                        </ul>
                    </div>
    
                    <div class="col-span-2 md:col-span-1">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-8">Newsletter</h4>
                        <div class="relative group">
                            <input type="email" placeholder="Email kamu..." class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-xs font-bold outline-none focus:border-blue-500 focus:bg-white transition-all">
                            <button class="absolute right-2 top-1.5 bg-slate-900 text-white p-1.5 rounded-lg hover:bg-blue-600 transition-colors">
                                <i class="fa-solid fa-paper-plane text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="pt-12 border-t border-slate-100/50 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">&copy; 2026 w3site.id</span>
                    <span class="h-1 w-1 bg-slate-300 rounded-full"></span>
                    <a href="{{ route('terms') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-900">Privacy Policy</a>
                    <span class="h-1 w-1 bg-slate-300 rounded-full"></span>
                    <a href="{{ route('terms') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-900">Usage Terms</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>