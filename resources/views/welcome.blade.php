<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>w3site.id — Free Hosting, Free Deploy, Free Subdomain for Static Sites</title>
    <meta name="description" content="Get professional free hosting, free deploy, and a free subdomain with w3site.id. The fastest way to host your static projects, portfolios, and landing pages with zero cost.">
    <meta name="keywords" content="free hosting, free deploy, free subdomain, static site hosting, free static hosting, deploy website, fast hosting, zero config hosting">
    <meta name="author" content="w3site.id">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- SEO Metadata --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="w3site.id — Free Hosting, Free Deploy, Free Subdomain">
    <meta property="og:description" content="Host your site for free. Instant free deploy and subdomain.">
    <meta property="og:image" content="{{ asset('assets/og-image.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { font-family: 'Geist', sans-serif; background-color: #fff; color: #000; }
        .geist-tight { letter-spacing: -0.05em; line-height: 0.9; }
        .geist-sub { letter-spacing: -0.02em; }
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .animate-marquee { display: flex; width: max-content; animation: marquee 60s linear infinite; }
        .animate-marquee:hover { animation-play-state: paused; }
        .glow {
            position: absolute; width: 600px; height: 300px;
            background: radial-gradient(circle, rgba(0,0,0,0.03) 0%, rgba(255,255,255,0) 70%);
            z-index: -1; top: 0; left: 50%; transform: translateX(-50%);
        }
        .bento-card { transition: all 0.2s ease-in-out; background: #fff; border: 1px solid #eaeaea; }
        .bento-card:hover { border-color: #000; }
    </style>
</head>
<body class="antialiased selection:bg-black selection:text-white" x-data="{ mobileMenu: false }">

    <header class="sticky top-0 z-[100] bg-white/70 backdrop-blur-xl border-b border-[#eaeaea]">
        <nav class="max-w-7xl mx-auto px-6 h-14 flex justify-between items-center">
            <div class="flex items-center gap-8">
                <a href="/" class="flex items-center gap-2 group">
                    <span class="flex items-center justify-center border-2 border-black rounded w-6 h-6 font-bold text-[13px] tracking-tighter group-hover:bg-black group-hover:text-white transition-all">w</span>
                    <span class="text-sm font-bold tracking-tight">w3site.id</span>
                </a>
                <div class="hidden md:flex items-center gap-6">
                    <a href="#features" class="text-[13px] text-[#666] hover:text-black transition-colors">Features</a>
                    <a href="{{ route('showcase.index') }}" class="text-[13px] text-[#666] hover:text-black transition-colors">Showcase</a>
                    <a href="{{ route('docs.index') }}" class="text-[13px] text-[#666] hover:text-black transition-colors">Docs</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="hidden md:inline-flex text-[13px] font-medium h-8 items-center px-4 rounded-full border border-black hover:bg-black hover:text-white transition-all">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hidden md:inline-flex text-[13px] text-[#666] hover:text-black transition-colors font-medium">Log in</a>
                    <a href="{{ route('register') }}" class="hidden md:inline-flex text-[13px] font-medium h-8 items-center px-4 rounded-full bg-black text-white hover:bg-zinc-800 transition-all">Sign up</a>
                @endauth
                <button @click="mobileMenu = !mobileMenu" class="md:hidden text-black h-8 w-8 flex items-center justify-center">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
            </div>
        </nav>
        
        {{-- Mobile Nav --}}
        <div x-show="mobileMenu" x-cloak class="md:hidden border-t border-[#eaeaea] bg-white px-6 py-6 space-y-4">
            <a href="#features" @click="mobileMenu = false" class="block text-lg font-bold">Features</a>
            <a href="{{ route('showcase.index') }}" @click="mobileMenu = false" class="block text-lg font-bold">Showcase</a>
            <a href="{{ route('docs.index') }}" @click="mobileMenu = false" class="block text-lg font-bold">Docs</a>
            <hr class="border-[#eaeaea]">
            @auth
                <a href="{{ route('dashboard') }}" class="block text-lg font-bold">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="block text-lg font-bold">Log in</a>
                <a href="{{ route('register') }}" class="block text-lg font-bold">Sign up</a>
            @endauth
        </div>
    </header>

    <main class="relative overflow-hidden pt-20 md:pt-32 pb-40 text-center px-6">
        <div class="glow"></div>
        <div class="inline-flex items-center h-7 px-3 rounded-full bg-zinc-100 text-[11px] font-bold text-zinc-600 uppercase tracking-widest gap-2 mb-8">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
            Free Forever Static Hosting
        </div>
        
        <h1 class="text-[56px] md:text-[110px] geist-tight font-[900] tracking-[-0.06em] mb-8">
            Your site live <br>
            <span class="text-zinc-300">in seconds.</span>
        </h1>

        <p class="text-[17px] md:text-[21px] geist-sub text-zinc-500 max-w-2xl mx-auto mb-12">
            Host your professional portfolio or project for free with a <span class="text-black font-semibold">.w3site.id</span> subdomain.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('register') }}" class="w-full sm:w-80 h-14 bg-black text-white rounded-xl text-[15px] font-bold hover:bg-zinc-800 transition-all flex items-center justify-center gap-3 group">
                Start Deploying for Free
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </a>
            <a href="#features" class="w-full sm:w-48 h-14 border border-[#eaeaea] rounded-xl text-[15px] font-bold hover:border-black transition-all flex items-center justify-center">
                Learn More
            </a>
        </div>
    </main>

    <section id="features" class="py-32 border-t border-[#eaeaea]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bento-card p-10 rounded-[30px]">
                    <div class="w-12 h-12 bg-black rounded-xl flex items-center justify-center text-white mb-6"><i class="fa-solid fa-bolt"></i></div>
                    <h3 class="text-[24px] font-bold mb-4 tracking-tight">Fast Deploy</h3>
                    <p class="text-zinc-500 text-[16px]">ZIP upload or GitHub sync. Your page is live globally in milliseconds.</p>
                </div>
                <div class="bento-card p-10 rounded-[30px]">
                    <div class="w-12 h-12 border border-[#eaeaea] rounded-xl flex items-center justify-center mb-6"><i class="fa-solid fa-shield"></i></div>
                    <h3 class="text-[24px] font-bold mb-4 tracking-tight">Free SSL</h3>
                    <p class="text-zinc-500 text-[16px]">Every site is secured with Let's Encrypt SSL certificates automatically.</p>
                </div>
                <div class="bento-card p-10 rounded-[30px]">
                    <div class="w-12 h-12 border border-[#eaeaea] rounded-xl flex items-center justify-center mb-6"><i class="fa-solid fa-at"></i></div>
                    <h3 class="text-[24px] font-bold mb-4 tracking-tight">Free Subdomain</h3>
                    <p class="text-zinc-500 text-[16px]">Get your custom branding on w3site.id without paying a single cent.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-32 bg-[#fafafa] border-y border-[#eaeaea]">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-[40px] md:text-[60px] geist-tight font-black mb-16">Built with w3site.id</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($latestSites->take(6) as $site)
                <div class="bg-white border border-[#eaeaea] rounded-[24px] p-6 flex flex-col h-full hover:border-black transition-all">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-zinc-100 flex items-center justify-center font-black">
                            {{ substr($site->user->name ?? '?', 0, 1) }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[15px] font-bold">{{ $site->subdomain }}</span>
                            <span class="text-[11px] text-zinc-400">.w3site.id</span>
                        </div>
                    </div>
                    <p class="text-[14px] text-zinc-500 mb-8 flex-1">{{ $site->description ?: 'Static site project.' }}</p>
                    <div class="flex items-center justify-between pt-6 border-t border-zinc-100">
                        <span class="text-[13px] font-semibold">{{ $site->user->name }}</span>
                        <a href="https://{{ $site->subdomain }}.w3site.id" target="_blank" class="w-8 h-8 border border-[#eaeaea] rounded-full flex items-center justify-center hover:bg-black hover:text-white transition-all">
                            <i class="fa-solid fa-arrow-up-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <footer class="py-20 border-t border-[#eaeaea]">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-2">
                <a href="/" class="flex items-center gap-2 mb-6">
                    <span class="flex items-center justify-center border-2 border-black rounded w-6 h-6 font-bold text-[13px] tracking-tighter">w</span>
                    <span class="text-sm font-bold">w3site.id</span>
                </a>
                <p class="text-zinc-500 text-[14px]">Free hosting for static projects.</p>
            </div>
            <div>
                <h4 class="text-[12px] font-bold text-[#999] mb-6 uppercase">Legal</h4>
                <ul class="text-[13px] space-y-4">
                    <li><a href="{{ route('about') }}" class="text-zinc-500 hover:text-black">About</a></li>
                    <li><a href="{{ route('terms') }}" class="text-zinc-500 hover:text-black">Terms</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-[12px] font-bold text-[#999] mb-6 uppercase">Support</h4>
                <ul class="text-[13px] space-y-4">
                    <li><a href="{{ route('docs.index') }}" class="text-zinc-500 hover:text-black">Docs</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>
