<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="icon" type="image/png" href="{{ asset('favicon.png') }}"><link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Deployment Guide — w3site.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { font-family: 'Geist', 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white text-[#171717] antialiased">

    <header class="sticky top-0 z-[100] bg-white/70 backdrop-blur-xl border-b border-[#eaeaea]">
        <nav class="max-w-7xl mx-auto px-6 h-14 flex justify-between items-center">
            <div class="flex items-center gap-8">
                <a href="/" class="flex items-center gap-2 group">
                    <span class="flex items-center justify-center border-2 border-black rounded w-6 h-6 font-bold text-[13px] tracking-tighter group-hover:bg-black group-hover:text-white transition-all">w</span>
                    <span class="text-sm font-bold tracking-tight">w3site<span class="text-zinc-400">.id</span></span>
                </a>
                <div class="hidden md:flex items-center gap-6">
                    <a href="/#features" class="text-[13px] text-[#666] hover:text-black transition-colors">Features</a>
                    <a href="{{ route('showcase.index') }}" class="text-[13px] text-[#666] hover:text-black transition-colors">Showcase</a>
                    <a href="{{ route('docs.index') }}" class="text-[13px] text-[#666] hover:text-black transition-colors">Docs</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-[13px] font-bold px-5 h-9 flex items-center bg-black text-white rounded-full hover:bg-zinc-800 transition-all">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-[13px] font-bold text-[#666] hover:text-black transition-colors">Log In</a>
                    <a href="{{ route('register') }}" class="text-[13px] font-bold px-5 h-9 flex items-center bg-black text-white rounded-full hover:bg-zinc-800 transition-all">Sign Up</a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="max-w-[700px] mx-auto px-6 py-24">
        <h1 class="text-[48px] md:text-[64px] font-black tracking-tight mb-6 leading-[1]">Deployment <span class="text-zinc-400">Guide.</span></h1>
        <p class="text-[18px] text-[#666] font-medium leading-relaxed mb-16">Get your static site online in seconds. Choose the method that best fits your workflow.</p>

        <div class="space-y-12">
            {{-- ZIP Card --}}
            <div class="p-10 border border-zinc-200 rounded-[40px] bg-white group hover:border-black transition-all">
                <div class="w-14 h-14 bg-zinc-50 border border-zinc-200 rounded-2xl flex items-center justify-center mb-10 group-hover:bg-black group-hover:text-white transition-all">
                    <i class="fa-solid fa-file-zipper text-[20px]"></i>
                </div>
                <h3 class="text-[24px] font-black mb-4">ZIP Method</h3>
                <p class="text-[16px] text-zinc-500 font-medium mb-10 leading-relaxed">Ideal for pure HTML/CSS projects or static builds from frameworks without complex CI/CD needs.</p>
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <span class="w-2 h-2 rounded-full bg-zinc-300"></span>
                        <p class="text-[14px] font-bold text-zinc-600">Select all project files and compress them.</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="w-2 h-2 rounded-full bg-zinc-300"></span>
                        <p class="text-[14px] font-bold text-zinc-600">Ensure index.html is at the zip root.</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="w-2 h-2 rounded-full bg-zinc-300"></span>
                        <p class="text-[14px] font-bold text-zinc-600">Upload via the w3site dashboard.</p>
                    </div>
                </div>
            </div>

            {{-- GitHub Card --}}
            <div class="p-10 border border-zinc-200 rounded-[40px] bg-white group hover:border-black transition-all">
                <div class="w-14 h-14 bg-zinc-50 border border-zinc-200 rounded-2xl flex items-center justify-center mb-10 group-hover:bg-black group-hover:text-white transition-all">
                    <i class="fa-brands fa-github text-[24px]"></i>
                </div>
                <h3 class="text-[24px] font-black mb-4">GitHub Method</h3>
                <p class="text-[16px] text-zinc-500 font-medium mb-10 leading-relaxed">Automate deployments by connecting your repository. Perfect for teams and iterative projects.</p>
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <span class="w-2 h-2 rounded-full bg-zinc-300"></span>
                        <p class="text-[14px] font-bold text-zinc-600">Make your repository Public.</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="w-2 h-2 rounded-full bg-zinc-300"></span>
                        <p class="text-[14px] font-bold text-zinc-600">Copy the HTTPS clone URL.</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="w-2 h-2 rounded-full bg-zinc-300"></span>
                        <p class="text-[14px] font-bold text-zinc-600">Trigger sync on every major update.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-32 p-12 border border-zinc-200 bg-zinc-50 rounded-[48px] text-center">
            <h4 class="text-[24px] font-black mb-4">Ready to go live?</h4>
            <p class="text-[15px] font-medium text-zinc-400 mb-10">Start your journey today with our zero-config hosting.</p>
            <a href="{{ route('dashboard') }}" class="inline-flex h-14 items-center px-10 bg-black text-white text-[16px] font-black rounded-full hover:bg-zinc-800 transition-all shadow-xl shadow-black/10 transform hover:scale-105 active:scale-95">Open My Dashboard</a>
        </div>
    </main>

    <footer class="py-16 border-t border-zinc-200 text-center">
        <p class="text-[12px] font-bold text-zinc-300">&copy; 2026 w3site.id — Empowering the Web</p>
    </footer>

</body>
</html>