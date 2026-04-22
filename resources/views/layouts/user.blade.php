<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>{{ config('app.name', 'w3site.id') }} — Dashboard</title>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        * { font-family: 'Geist', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        [x-cloak] { display: none !important; }
        
        {{-- Custom Scrollbar --}}
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #eaeaea; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #d4d4d4; }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TZVXJ1RH2X"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','G-TZVXJ1RH2X');</script>
</head>
<body class="antialiased bg-white text-[#171717]" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        {{-- MOBILE OVERLAY --}}
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 bg-black/20 z-40 md:hidden" @click="sidebarOpen = false" x-cloak></div>

        {{-- SIDEBAR --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#fafafa] border-r border-[#eaeaea] transform transition-transform duration-200 ease-in-out md:translate-x-0 md:sticky md:top-0 md:h-screen md:flex-shrink-0 flex flex-col overflow-y-auto">
            {{-- Sidebar Header --}}
            <div class="h-14 flex items-center px-6 border-b border-[#eaeaea]">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="flex items-center justify-center border-2 border-[#000] rounded-md w-7 h-7 font-bold text-[14px] tracking-tighter">w3</span>
                    <span class="text-[17px] font-bold tracking-tight">w3site.id</span>
                </a>
            </div>

            {{-- User Profile Snippet --}}
            <div class="p-6 border-b border-[#eaeaea]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#eaeaea] border border-[#d4d4d4] flex items-center justify-center text-[14px] font-medium text-[#666]">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-[15px] font-bold text-[#171717] truncate leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[13px] font-semibold text-[#888] truncate mt-0.5">Free Forever</p>
                    </div>
                </div>
            </div>

            {{-- Sidebar Nav --}}
            <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[14px] font-bold transition-colors {{ request()->routeIs('dashboard') ? 'bg-white text-black border border-[#eaeaea]' : 'border border-transparent text-[#666] hover:bg-[#eaeaea]/50 hover:text-black' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i> Overview
                </a>
                <a href="{{ route('mysite') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[14px] font-bold transition-colors {{ request()->routeIs('mysite') || request()->routeIs('deploy.github') ? 'bg-white text-black border border-[#eaeaea]' : 'border border-transparent text-[#666] hover:bg-[#eaeaea]/50 hover:text-black' }}">
                    <i class="fa-solid fa-layer-group w-5 text-center"></i> Projects
                </a>
                <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[14px] font-bold transition-colors {{ request()->routeIs('profile') ? 'bg-white text-black border border-[#eaeaea]' : 'border border-transparent text-[#666] hover:bg-[#eaeaea]/50 hover:text-black' }}">
                    <i class="fa-solid fa-gear w-5 text-center"></i> Settings
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[14px] font-bold transition-colors border border-transparent text-[#666] hover:bg-[#eaeaea]/50 hover:text-black">
                    <i class="fa-solid fa-heart w-5 text-center text-red-500"></i> Support Us
                </a>
            </nav>

            {{-- Sidebar Footer --}}
            <div class="p-3 border-t border-[#eaeaea]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-[14px] font-bold text-[#666] hover:bg-[#eaeaea]/50 hover:text-red-600 transition-colors border border-transparent text-left">
                        <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Log Out
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
            {{-- Mobile Header --}}
            <header class="md:hidden flex items-center justify-between h-14 px-4 border-b border-[#eaeaea] bg-white sticky top-0 z-30">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="flex items-center justify-center border-2 border-[#000] rounded-md w-7 h-7 font-bold text-[13px] tracking-tighter">w3</span>
                </a>
                <button @click="sidebarOpen = true" class="p-2 text-[#666]">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 w-full max-w-[1400px] mx-auto p-4 md:p-10">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>