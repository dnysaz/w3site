<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Showcase — w3site.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { font-family: 'Geist', 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-[#171717] antialiased">

    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-[#eaeaea]">
        <nav class="max-w-[1100px] mx-auto px-6 h-16 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <span class="flex items-center justify-center border-2 border-[#000] rounded-md w-7 h-7 font-bold text-[13px] tracking-tighter">w3</span>
                <span class="text-sm font-semibold tracking-tight">w3site.id</span>
            </a>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-[13px] text-[#666] hover:text-[#000] transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-[13px] text-[#666] hover:text-[#000] transition-colors">Log In</a>
                    <a href="{{ route('register') }}" class="text-[13px] bg-[#171717] text-white px-3 py-1.5 rounded-md hover:bg-[#383838] transition-colors">Sign Up</a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="max-w-[1240px] mx-auto px-6 py-20">
        <div class="max-w-2xl mb-24">
            <p class="text-[12px] font-bold text-zinc-400 tracking-[0.2em] mb-4">Showcase</p>
            <h1 class="text-[48px] md:text-[72px] font-black tracking-tight mb-8 leading-[1]">Built by the <span class="text-zinc-400">community.</span></h1>
            <p class="text-[18px] md:text-[21px] text-[#666] font-medium leading-relaxed">
                Explore a curated collection of static websites and landing pages deployed on w3site.id. From personal portfolios to business projects.
            </p>
        </div>    
            <form action="{{ route('showcase.index') }}" method="GET" class="flex gap-2 mb-16">
                <div class="flex-1 relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-[12px]"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search projects or creators..." 
                        class="w-full pl-9 pr-4 py-2 bg-white border border-[#eaeaea] rounded-md text-[13px] outline-none focus:border-zinc-900 transition-colors">
                </div>
                <button type="submit" class="px-4 py-2 bg-[#171717] text-white rounded-md text-[13px] font-medium hover:bg-[#383838] transition-colors">
                    Search
                </button>
            </form>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($sites as $site)
                <div class="group h-full flex flex-col bg-white border border-[#eaeaea] rounded-[24px] overflow-hidden hover:border-black transition-all">
                    <div class="aspect-[16/10] bg-zinc-50 relative overflow-hidden border-b border-[#eaeaea]">
                        <iframe 
                            src="https://{{ $site->subdomain }}.w3site.id" 
                            class="w-[400%] h-[400%] origin-top-left scale-[0.25] border-none pointer-events-none grayscale group-hover:grayscale-0 transition-all duration-500"
                            loading="lazy"
                            frameborder="0"
                            scrolling="no">
                        </iframe>
                        <a href="https://{{ $site->subdomain }}.w3site.id" target="_blank" class="absolute inset-0 z-10"></a>
                    </div>
                    <div class="p-8 flex flex-col flex-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[18px] font-bold truncate tracking-tight">{{ $site->subdomain }}<span class="text-zinc-400">.w3site.id</span></h3>
                            <a href="https://{{ $site->subdomain }}.w3site.id" target="_blank" class="w-8 h-8 rounded-full border border-[#eaeaea] flex items-center justify-center text-zinc-400 hover:text-black hover:border-black transition-all">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
                            </a>
                        </div>
                        <p class="text-[14px] text-[#666] font-medium leading-relaxed mb-8 flex-1 italic">
                            {{ $site->description ?: 'A beautiful static site deployed in seconds with zero configuration.' }}
                        </p>
                        <div class="flex items-center gap-2 pt-6 border-t border-[#f5f5f5]">
                            <div class="w-8 h-8 rounded-xl border border-[#eaeaea] bg-zinc-50 flex items-center justify-center text-[10px] font-black text-zinc-400">
                                {{ substr($site->user->name ?? '?', 0, 2) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-zinc-300 tracking-wider">Creator</span>
                                <span class="text-[13px] font-bold text-black leading-none">{{ $site->user->name }}</span>
                            </div>
                            <span class="ml-auto text-[11px] font-bold text-zinc-400">{{ $site->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 text-center border border-dashed border-[#eaeaea] rounded-[24px]">
                    <p class="text-[15px] font-bold text-zinc-400">No projects found for "{{ request('q') }}"</p>
                </div>
            @endforelse

            @auth
            <a href="{{ route('dashboard') }}" class="h-full border-2 border-dashed border-[#eaeaea] rounded-[24px] p-10 flex flex-col items-center justify-center text-center group hover:border-black transition-all">
                <div class="w-12 h-12 rounded-full border-2 border-dashed border-[#eaeaea] flex items-center justify-center text-zinc-300 mb-6 group-hover:text-black group-hover:border-black transition-all">
                    <i class="fa-solid fa-plus text-lg"></i>
                </div>
                <h3 class="text-[16px] font-bold">Add your site?</h3>
                <p class="text-[12px] font-bold text-zinc-400 mt-1 tracking-widest">Deploy today</p>
            </a>
            @endauth
        </div>

        <div class="mt-12">
            {{ $sites->links() }}
        </div>
    </main>

    <section class="border-t border-[#eaeaea] py-20">
        <div class="max-w-[1100px] mx-auto px-6">
            <div class="bg-zinc-900 rounded-xl p-8 md:p-12 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-[28px] font-bold tracking-tight mb-4">Dedicated hosting for professionals.</h2>
                    <p class="text-zinc-400 text-[15px] mb-8 max-w-xl">
                        Need Custom Domains, more storage, or dedicated support? Our partners at Hostinger offer premium solutions for growing businesses.
                    </p>
                    <a href="https://hostinger.co.id?REFERRALCODE=w3site" target="_blank" class="inline-flex h-10 items-center px-6 bg-white text-zinc-900 rounded-md text-[13px] font-medium hover:bg-zinc-200 transition-colors">
                        Get 20% Discount
                    </a>
                </div>
                <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 blur-[100px] -mr-32 -mt-32"></div>
            </div>
        </div>
    </section>

    <footer class="py-12 border-t border-[#eaeaea] text-center">
        <p class="text-[12px] text-[#999]">&copy; 2026 w3site.id — Empowering the Web</p>
    </footer>

</body>
</html>