<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>w3site | Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>

<body class="bg-[#f8fafc] text-slate-900 font-sans antialiased overflow-hidden">
    <div x-data="{ 
            sidebarOpen: window.innerWidth > 1024, 
            mobileMenu: false 
         }" 
         @resize.window="if(window.innerWidth > 1024) { mobileMenu = false } else { sidebarOpen = false }"
         class="relative h-screen flex flex-col">
        
        <nav class="bg-white border-b border-slate-200 h-16 flex-none sticky top-0 z-[60] flex items-center px-4 justify-between">
            <div class="flex items-center gap-4">
                <button @click="if(window.innerWidth > 1024) { sidebarOpen = !sidebarOpen } else { mobileMenu = !mobileMenu }" 
                        class="p-2 hover:bg-slate-100 rounded-xl text-slate-500 transition-colors">
                    <i class="fas fa-bars-staggered"></i>
                </button>
                <h1 class="font-black text-xl tracking-tighter text-slate-900">
                    <a href="{{ route("dashboard") }}">w3<span class="text-blue-600">site.</span><span class="text-[10px] text-slate-400 tracking-widest">Admin</span>
                    </a>
                </h1>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3 pl-2">
                    {{-- Icon Profile --}}
                    <div class="w-10 h-10 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <i class="fas fa-user-shield text-sm"></i>
                    </div>
            
                    {{-- Nama User --}}
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none mb-1">Authenticated</span>
                        <span class="text-sm font-black text-slate-900 tracking-tight leading-none">
                            {{ auth()->user()->name }}
                        </span>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex flex-1 overflow-hidden relative">
            
            <div x-show="mobileMenu" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileMenu = false"
                 class="fixed inset-0 bg-slate-900/50 z-[40] lg:hidden" x-cloak>
            </div>

            <aside 
                :class="{
                    'w-64': sidebarOpen || mobileMenu, 
                    'w-20': !sidebarOpen && !mobileMenu,
                    'translate-x-0': mobileMenu,
                    '-translate-x-full': !mobileMenu && window.innerWidth <= 1024,
                    'lg:translate-x-0': true
                }"
                class="fixed lg:relative bg-white border-r border-slate-200 transition-all duration-300 flex flex-col z-[50] h-full overflow-hidden">
                
                <div class="flex-1 py-6 px-3 space-y-2 overflow-y-auto no-scrollbar">
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="w-full flex items-center gap-4 p-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50' }}">
                        <div class="w-10 h-10 flex-none rounded-xl flex items-center justify-center transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-white' }}">
                            <i class="fas fa-chart-pie text-sm"></i>
                        </div>
                        <span x-show="sidebarOpen || mobileMenu" x-transition.opacity class="font-bold text-sm tracking-tight whitespace-nowrap">Ringkasan</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" 
                       class="w-full flex items-center gap-4 p-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50' }}">
                        <div class="w-10 h-10 flex-none rounded-xl flex items-center justify-center transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-white' }}">
                            <i class="fas fa-users text-sm"></i>
                        </div>
                        <span x-show="sidebarOpen || mobileMenu" x-transition.opacity class="font-bold text-sm tracking-tight whitespace-nowrap">Pengguna</span>
                    </a>

                    <a href="{{ route('admin.sites.index') }}" 
                       class="w-full flex items-center gap-4 p-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('admin.sites.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50' }}">
                        <div class="w-10 h-10 flex-none rounded-xl flex items-center justify-center transition-colors {{ request()->routeIs('admin.sites.*') ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-white' }}">
                            <i class="fas fa-globe text-sm"></i>
                        </div>
                        <span x-show="sidebarOpen || mobileMenu" x-transition.opacity class="font-bold text-sm tracking-tight whitespace-nowrap">Websites</span>
                    </a>

                    <a href="{{ route('admin.linktrees.index') }}" 
                       class="w-full flex items-center gap-4 p-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('admin.linktrees.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50' }}">
                        <div class="w-10 h-10 flex-none rounded-xl flex items-center justify-center transition-colors {{ request()->routeIs('admin.linktrees.*') ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-white' }}">
                            <i class="fas fa-share-nodes text-sm"></i>
                        </div>
                        <span x-show="sidebarOpen || mobileMenu" x-transition.opacity class="font-bold text-sm tracking-tight whitespace-nowrap">Bio Links</span>
                    </a>

                    <a href="{{ route('admin.shortlinks.index') }}" 
                       class="w-full flex items-center gap-4 p-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('admin.shortlinks.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50' }}">
                        <div class="w-10 h-10 flex-none rounded-xl flex items-center justify-center transition-colors {{ request()->routeIs('admin.shortlinks.*') ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-white' }}">
                            <i class="fas fa-link text-sm"></i>
                        </div>
                        <span x-show="sidebarOpen || mobileMenu" x-transition.opacity class="font-bold text-sm tracking-tight whitespace-nowrap">Shortlinks</span>
                    </a>

                    <a href="{{ route('admin.transactions.index') }}" 
                       class="w-full flex items-center gap-4 p-3 rounded-2xl transition-all duration-300 group {{ request()->routeIs('admin.transactions.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-slate-50' }}">
                        <div class="w-10 h-10 flex-none rounded-xl flex items-center justify-center transition-colors {{ request()->routeIs('admin.transactions.*') ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-white' }}">
                            <i class="fas fa-credit-card text-sm"></i>
                        </div>
                        <span x-show="sidebarOpen || mobileMenu" x-transition.opacity class="font-bold text-sm tracking-tight whitespace-nowrap">Transaksi</span>
                    </a>

                </div>

                <div class="p-4 border-t border-slate-100 flex-none bg-white">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-4 p-3 text-red-500 hover:bg-red-50 rounded-2xl transition-all group">
                            <div class="w-10 h-10 flex-none bg-red-50 rounded-xl flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-colors">
                                <i class="fas fa-power-off text-sm"></i>
                            </div>
                            <span x-show="sidebarOpen || mobileMenu" class="font-bold text-sm whitespace-nowrap">Keluar</span>
                        </button>
                    </form>
                    <div class="mt-4 text-sm text-gray-600">
                        Admin | w3site 2026
                    </div>
                </div>
            </aside>

            <main class="flex-1 overflow-y-auto p-6 md:p-8 bg-[#f8fafc] scroll-smooth">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateElem = document.getElementById('currentDate');
            const timeElem = document.getElementById('currentTime');
            if(dateElem) dateElem.textContent = now.toLocaleDateString('id-ID', dateOptions);
            if(timeElem) timeElem.textContent = now.toLocaleTimeString('id-ID');
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>

    @stack('scripts')
</body>
</html>