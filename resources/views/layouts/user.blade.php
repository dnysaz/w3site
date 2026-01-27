<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'w3site.id') }} — Dashboard</title>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .hero-gradient { background: radial-gradient(circle at 10% 20%, rgba(56, 189, 248, 0.05) 0%, rgba(255, 255, 255, 0) 50%); }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup.min.js"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TZVXJ1RH2X"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-TZVXJ1RH2X');
    </script>
</head>
<body class="antialiased hero-gradient">
    <div class="min-h-screen bg-[#f8fafc] flex flex-col md:flex-row">
        
        <div class="md:hidden bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-50">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold italic text-sm">w3</div>
                <span class="text-lg font-extrabold tracking-tight text-slate-900">w3site</span>
            </div>
            <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')" class="p-2 bg-slate-50 rounded-lg text-slate-600">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
        </div>
    
        <aside id="sidebar" class="w-64 bg-white border-r border-slate-200 flex flex-col fixed h-full z-[60] transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center text-nowrap">
                <a href="{{ route('home') }}">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold italic text-sm">w3</div>
                        <span class="text-lg font-extrabold tracking-tight text-slate-900">w3site<span class="text-blue-600">.id</span></span>
                    </div>
                </a>
                <button onclick="document.getElementById('sidebar').classList.add('-translate-x-full')" class="md:hidden text-slate-400">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
    
            <nav class="flex-1 p-6 space-y-8">
                {{-- Section: Main Menu --}}
                <div>
                    <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Utama</p>
                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-4 px-4 py-3 rounded-xl font-bold text-sm transition-all
                            {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-house w-5 text-center transition-transform group-hover:scale-110"></i>
                            <span>Dashboard</span>
                        </a>
                
                        <a href="{{ route('mysite') }}" class="group flex items-center gap-4 px-4 py-3 rounded-xl font-bold text-sm transition-all
                            {{ request()->routeIs('mysite') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-globe w-5 text-center transition-transform group-hover:scale-110"></i>
                            <span>My w3sites</span>
                        </a>
                
                        <a href="{{ route('shortlink') }}" class="group flex items-center gap-4 px-4 py-3 rounded-xl font-bold text-sm transition-all
                            {{ request()->routeIs('shortlink') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-link w-5 text-center transition-transform group-hover:scale-110"></i>
                            <span>Shortlink</span>
                        </a>
                        
                        <a href="{{ route('linktree') }}" class="group flex items-center gap-4 px-4 py-3 rounded-xl font-bold text-sm transition-all
                            {{ request()->routeIs('linktree') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-list-ul w-5 text-center transition-transform group-hover:scale-110"></i>
                            <span>Bio linktree</span>
                        </a>
                
                        {{-- Link Transactions Baru --}}
                        <a href="{{ route('billing.history') }}" class="group flex items-center gap-4 px-4 py-3 rounded-xl font-bold text-sm transition-all
                            {{ request()->routeIs('billing.history') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-receipt w-5 text-center transition-transform group-hover:scale-110"></i>
                            <span>Transactions</span>
                        </a>
                    </div>
                </div>
            
                {{-- Section: AI Magic Hub --}}
                <div>
                    <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Intelligence</p>
                    <div class="space-y-3">
                        {{-- HUB UTAMA AI --}}
                        <a href="{{ route('ai.index') }}" 
                            class="group flex items-center gap-4 px-4 py-4 rounded-2xl font-black transition-all duration-300
                            {{ request()->routeIs('ai.*') 
                                ? 'bg-slate-900 text-white shadow-xl shadow-slate-200' 
                                : 'bg-white text-slate-600 border border-slate-100 hover:border-blue-600 hover:text-blue-600 hover:shadow-xl hover:shadow-blue-500/10 active:scale-95' 
                            }}">
                            
                            <div class="flex items-center justify-center w-10 h-10 rounded-xl transition-all duration-300
                                {{ request()->routeIs('ai.*') ? 'bg-blue-600 text-white' : 'bg-slate-50 text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600' }}">
                                <i class="fa-solid fa-wand-magic-sparkles text-base animate-pulse"></i>
                            </div>
            
                            <div class="flex flex-col text-left">
                                <span class="tracking-tight uppercase text-[11px] font-[1000] leading-none">AI Magic Hub</span>
                                <span class="text-[8px] font-bold opacity-40 uppercase tracking-widest mt-1">Smart Solutions</span>
                            </div>
            
                            <i class="fa-solid fa-chevron-right ml-auto text-[10px] opacity-20 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                        </a>
                    </div>
                </div>
            
                {{-- Section: Account --}}
                <div class="pt-4 border-t border-slate-50">
                    <a href="{{ route('profile') }}" class="group flex items-center gap-4 px-4 py-3 rounded-xl font-bold text-sm transition-all
                        {{ request()->routeIs('profile') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-user w-5 text-center transition-transform group-hover:scale-110"></i>
                        <span>Profile Setting</span>
                    </a>
                </div>
            </nav>
    
            <div class="p-6 border-t border-slate-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-4 px-4 py-3 w-full text-red-500 hover:bg-red-50 rounded-xl font-bold transition-all text-sm group">
                        <i class="fa-solid fa-right-from-bracket text-lg w-5 text-center transition-transform group-hover:-translate-x-1"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>
    
        <main class="flex-1 ml-0 md:ml-64 p-6 md:p-10 w-full overflow-x-hidden">
            {{ $slot }}
        </main>
    </div>
</body>
</html>