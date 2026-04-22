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

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="w3site.id — Free Hosting, Free Deploy, Free Subdomain">
    <meta property="og:description" content="Host your site for free. Get professional free hosting, free deploy, and a free subdomain in seconds.">
    <meta property="og:image" content="{{ asset('assets/og-image.png') }}">

    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="w3site.id — Free Hosting, Free Deploy, Free Subdomain">
    <meta property="twitter:description" content="Host your portfolio or project for free. Instant free deploy, free hosting, and free subdomain with w3site.id.">
    <meta property="twitter:image" content="{{ asset('assets/og-image.png') }}">

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "w3site.id",
      "operatingSystem": "All",
      "applicationCategory": "DeveloperApplication",
      "description": "Free forever static hosting platform. Deploy your portfolio or project in seconds with zero config.",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "USD"
      }
    }
    </script>
    
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
        
        /* Smooth Marquee */
        @keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .animate-marquee { display: flex; width: max-content; animation: marquee 60s linear infinite; }
        .animate-marquee:hover { animation-play-state: paused; }

        /* Subtle Glow */
        .glow {
            position: absolute; width: 600px; height: 300px;
            background: radial-gradient(circle, rgba(0,0,0,0.03) 0%, rgba(255,255,255,0) 70%);
            z-index: -1; top: 0; left: 50%; transform: translateX(-50%);
        }

        .bento-card {
            transition: all 0.2s ease-in-out;
            background: #fff;
            border: 1px solid #eaeaea;
        }
        .bento-card:hover {
            border-color: #000;
        }
    </style>
</head>
<body class="antialiased selection:bg-black selection:text-white" x-data="{ mobileMenu: false }">

    {{-- NAVIGATION --}}
    <header class="sticky top-0 z-[100] bg-white/70 backdrop-blur-xl border-b border-[#eaeaea]">
        <nav class="max-w-7xl mx-auto px-6 h-14 flex justify-between items-center">
            <div class="flex items-center gap-8">
                <a href="/" class="flex items-center gap-2 group">
                    <span class="flex items-center justify-center border-2 border-black rounded w-6 h-6 font-bold text-[13px] tracking-tighter group-hover:bg-black group-hover:text-white transition-all">w</span>
                    <span class="text-sm font-bold tracking-tight">w3site<span class="text-zinc-400">.id</span></span>
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
        
        {{-- Mobile Overlay --}}
        <div x-show="mobileMenu" x-cloak class="md:hidden border-t border-[#eaeaea] bg-white px-6 py-6 space-y-4">
            <a href="#features" @click="mobileMenu = false" class="block text-lg font-bold">Features</a>
            <a href="{{ route('showcase.index') }}" class="block text-lg font-bold">Showcase</a>
            <a href="{{ route('docs.index') }}" class="block text-lg font-bold">Docs</a>
            <hr class="border-[#eaeaea]">
            @auth
                <a href="{{ route('dashboard') }}" class="block text-lg font-bold">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="block text-lg font-bold text-[#666]">Log in</a>
                <a href="{{ route('register') }}" class="block text-lg font-bold">Sign up</a>
            @endauth
        </div>
    </header>

    {{-- HERO --}}
    <main class="relative overflow-hidden pt-20 md:pt-32 pb-40">
        <div class="glow"></div>
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="inline-flex items-center h-7 px-3 rounded-full bg-zinc-100 text-[11px] font-bold text-zinc-600 uppercase tracking-widest gap-2 mb-8 animate-fade-in">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                Free Forever Static Hosting
            </div>
            
            <h1 class="text-[56px] md:text-[110px] geist-tight font-[900] tracking-[-0.06em] mb-8">
                Your site live <br>
                <span class="text-zinc-300">in seconds.</span>
            </h1>

            <p class="text-[17px] md:text-[21px] geist-sub text-zinc-500 max-w-2xl mx-auto mb-12 leading-relaxed">
                Deploy fast, secure static sites with a free <span class="text-black font-semibold">.w3site.id</span> subdomain. 
                Zero config, zero cost, zero friction.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mb-24">
                <a href="{{ route('register') }}" class="w-full sm:w-80 h-14 bg-black text-white rounded-xl text-[15px] font-bold hover:bg-zinc-800 transition-all flex items-center justify-center gap-3 group">
                    Start Deploying for Free
                    <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="#features" class="w-full sm:w-48 h-14 border border-[#eaeaea] rounded-xl text-[15px] font-bold hover:border-black transition-all flex items-center justify-center">
                    Learn More
                </a>
            </div>

            {{-- Terminal Visual --}}
            <div class="max-w-4xl mx-auto relative group">
                <div class="absolute inset-x-0 bottom-0 top-0 bg-gradient-to-t from-white via-transparent to-transparent z-10 pointer-events-none"></div>
                <div class="bg-[#000] rounded-2xl p-1 overflow-hidden">
                    <div class="flex items-center gap-1.5 px-6 py-4 border-b border-zinc-800">
                        <div class="w-3 h-3 rounded-full bg-zinc-800"></div>
                        <div class="w-3 h-3 rounded-full bg-zinc-800"></div>
                        <div class="w-3 h-3 rounded-full bg-zinc-800"></div>
                        <div class="flex-1 text-center text-[11px] font-bold text-zinc-600 tracking-widest uppercase">w3site CLI</div>
                    </div>
                    <div class="p-8 md:p-12 text-[14px] md:text-[16px] font-mono leading-relaxed text-left">
                        <div class="flex gap-4">
                            <span class="text-zinc-700">1</span>
                            <p class="text-zinc-300"><span class="text-blue-400">deploying</span> your-awesome-site.zip</p>
                        </div>
                        <div class="flex gap-4 mt-2">
                            <span class="text-zinc-700">2</span>
                            <p class="text-zinc-300"><span class="text-green-400">found</span> index.html <span class="text-zinc-600">(root)</span></p>
                        </div>
                        <div class="flex gap-4 mt-2">
                            <span class="text-zinc-700">3</span>
                            <p class="text-zinc-300"><span class="text-yellow-400">pushing</span> to w3site-server-idn</p>
                        </div>
                        <div class="flex gap-4 mt-2">
                            <span class="text-zinc-700">4</span>
                            <p class="text-green-400">Success! <span class="text-zinc-400">Deployment complete in 0.8s</span></p>
                        </div>
                        <div class="flex gap-4 mt-8">
                            <span class="text-zinc-700">></span>
                            <p class="text-white font-bold underline underline-offset-4 decoration-zinc-700">https://your-site.w3site.id</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- FEATURES BENTO --}}
    <section id="features" class="py-32 border-t border-[#eaeaea]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-24">
                <h2 class="text-4xl md:text-[64px] geist-tight font-black mb-6">Designed for simplicity.</h2>
                <p class="text-[17px] text-zinc-500 max-w-lg mx-auto leading-relaxed">No complex configurations. Just upload and you're live on the web.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                {{-- Large Feature --}}
                <div class="md:col-span-8 bento-card p-10 rounded-[30px] flex flex-col justify-between min-h-[400px]">
                    <div>
                        <div class="w-12 h-12 bg-black rounded-xl flex items-center justify-center text-white mb-6">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <h3 class="text-[32px] font-bold tracking-tight mb-4 leading-none">Instant Deployments</h3>
                        <p class="text-zinc-500 text-[16px] leading-relaxed max-w-md">
                            Drag and drop your ZIP file or connect a GitHub repository. We handle extraction, SSL, and DNS propagation automatically.
                        </p>
                    </div>
                </div>

                {{-- Side Feature --}}
                <div class="md:col-span-4 bento-card p-10 rounded-[30px] flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 border border-[#eaeaea] rounded-xl flex items-center justify-center text-black mb-6">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h3 class="text-[24px] font-bold tracking-tight mb-4 leading-none">Automatic SSL</h3>
                        <p class="text-zinc-500 text-[14px] leading-relaxed">
                            HTTPS is non-negotiable. Every site on w3site.id is secured with free SSL certificates from Let's Encrypt.
                        </p>
                    </div>
                </div>

                {{-- Row 2 --}}
                <div class="md:col-span-4 bento-card p-10 rounded-[30px]">
                    <div class="w-12 h-12 border border-[#eaeaea] rounded-xl flex items-center justify-center text-black mb-6">
                        <i class="fa-brands fa-github"></i>
                    </div>
                    <h3 class="text-[20px] font-bold mb-3">GitHub Auth</h3>
                    <p class="text-zinc-500 text-[14px]">Connect your account and deploy directly from your repositories.</p>
                </div>

                <div class="md:col-span-4 bento-card p-10 rounded-[30px]">
                    <div class="w-12 h-12 border border-[#eaeaea] rounded-xl flex items-center justify-center text-black mb-6">
                        <i class="fa-solid fa-cloud"></i>
                    </div>
                    <h3 class="text-[20px] font-bold mb-3">Free Subdomain</h3>
                    <p class="text-zinc-500 text-[14px]">Claim your custom name on the .w3site.id domain for free forever.</p>
                </div>

                <div class="md:col-span-4 bento-card p-10 rounded-[30px]">
                    <div class="w-12 h-12 border border-[#eaeaea] rounded-xl flex items-center justify-center text-black mb-6">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <h3 class="text-[20px] font-bold mb-3">API Ready</h3>
                    <p class="text-zinc-500 text-[14px]">Your static site can connect to any database or dynamic API with ease.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-32 bg-[#fafafa] border-y border-[#eaeaea]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16 px-2">
                <div class="max-w-2xl">
                    <h2 class="text-[40px] md:text-[64px] geist-tight font-black leading-[0.9] mb-4">Built with <br><span class="text-zinc-400">w3site.id</span></h2>
                    <p class="text-[17px] text-zinc-500 font-medium">Explore some of the latest projects created by our community members.</p>
                </div>
                <a href="{{ route('showcase.index') }}" class="inline-flex h-11 items-center px-6 bg-white border border-[#eaeaea] rounded-full text-[14px] font-bold hover:border-black transition-all flex items-center gap-2 group">
                    Browse all showcase <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($latestSites->take(6) as $site)
                <div class="group bg-white border border-[#eaeaea] rounded-[24px] overflow-hidden hover:border-black transition-all flex flex-col p-6 h-full">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl border border-[#eaeaea] bg-zinc-50 flex items-center justify-center text-[12px] font-black text-zinc-400 group-hover:bg-black group-hover:text-white transition-colors">
                            {{ substr($site->user->name ?? '?', 0, 2) }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[15px] font-bold text-black leading-none">{{ $site->subdomain }}</span>
                            <span class="text-[12px] text-zinc-400 mt-1">.w3site.id</span>
                        </div>
                    </div>

                    <p class="text-[14px] text-zinc-500 leading-relaxed mb-8 flex-1 italic">
                        {{ $site->description ?: 'a beautiful static site deployed in seconds with zero configuration.' }}
                    </p>

                    <div class="flex items-center justify-between pt-6 border-t border-zinc-50">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-zinc-300 tracking-wider">Created by</span>
                            <span class="text-[13px] font-bold text-black">{{ $site->user->name }}</span>
                        </div>
                        <a href="https://{{ $site->subdomain }}.w3site.id" target="_blank" class="w-9 h-9 border border-[#eaeaea] rounded-full flex items-center justify-center hover:bg-black hover:text-white transition-colors">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="py-40">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-[48px] md:text-[80px] geist-tight font-black mb-12">Start your story here.</h2>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-64 h-14 bg-black text-white rounded-xl text-[15px] font-bold hover:bg-zinc-800 transition-all flex items-center justify-center">
                    Get Started — Free Forever
                </a>
                <a href="{{ route('docs.index') }}" class="w-full sm:w-48 h-14 border border-[#eaeaea] rounded-xl text-[15px] font-bold hover:border-black transition-all flex items-center justify-center">
                    View Documentation
                </a>
            </div>
            <p class="mt-8 text-[14px] text-zinc-400">No credit card required. Deploy in seconds.</p>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="border-t border-[#eaeaea] py-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-12 mb-20">
                <div class="col-span-2">
                    <a href="/" class="flex items-center gap-2 mb-6 group">
                        <span class="flex items-center justify-center border-2 border-black rounded w-6 h-6 font-bold text-[13px] tracking-tighter group-hover:bg-black group-hover:text-white transition-all">w</span>
                        <span class="text-sm font-bold tracking-tight">w3site.id</span>
                    </a>
                    <p class="text-zinc-500 text-[14px] leading-relaxed max-w-xs">
                        Empowering developers and students with free, fast, and simple static site hosting. 
                        Zero config, maximum performance.
                    </p>
                </div>
                <div>
                    <h4 class="text-[12px] font-bold tracking-widest text-[#999] mb-6">Support</h4>
                    <ul class="space-y-4 text-[13px] font-medium">
                        <li><a href="{{ route('docs.index') }}" class="text-zinc-500 hover:text-black transition-colors">Documentation</a></li>
                        <li><a href="{{ route('docs.deployment') }}" class="text-zinc-500 hover:text-black transition-colors">Guides</a></li>
                        <li><a href="{{ route('showcase.index') }}" class="text-zinc-500 hover:text-black transition-colors">Showcase</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[12px] font-bold tracking-widest text-[#999] mb-6">Legal</h4>
                    <ul class="space-y-4 text-[13px] font-medium">
                        <li><a href="{{ route('about') }}" class="text-zinc-500 hover:text-black transition-colors">About</a></li>
                        <li><a href="{{ route('terms') }}" class="text-zinc-500 hover:text-black transition-colors">Terms</a></li>
                        <li><a href="{{ route('terms') }}" class="text-zinc-500 hover:text-black transition-colors">Privacy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-[#eaeaea] flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-6 text-[13px] text-zinc-400">
                    <span>&copy; 2026 w3site.id</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="#" class="text-zinc-400 hover:text-black transition-colors"><i class="fa-brands fa-github text-lg"></i></a>
                    <a href="#" class="text-zinc-400 hover:text-black transition-colors"><i class="fa-brands fa-x-twitter text-lg"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>