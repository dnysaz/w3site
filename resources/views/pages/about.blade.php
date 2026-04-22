<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="icon" type="image/png" href="{{ asset('favicon.png') }}"><link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>About Us — w3site.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
        <p class="text-[12px] font-bold text-zinc-400 tracking-[0.2em] mb-4">Our Mission</p>
        <h1 class="text-[48px] md:text-[64px] font-black tracking-tight mb-10 leading-[1]">Empowering creators with <span class="text-zinc-400">limitless possibilities.</span></h1>
        
        <div class="prose prose-zinc prose-sm">
            <p class="text-[18px] text-[#666] font-medium leading-relaxed mb-10">
                In a digital-first world, having an online presence is no longer a luxury—it's a necessity. w3site.id was founded to ensure that technical barriers and costs never stand in the way of your dreams.
            </p>

            <h3 class="text-[20px] font-black mt-16 mb-6">Space to Grow</h3>
            <p class="text-[16px] text-[#666] leading-relaxed mb-8">
                Many developers, students, and small business owners have great ideas but are often hindered by complex hosting environments or high monthly fees. We break those barriers by providing a platform where you can go live in seconds, for free.
            </p>

            <div class="grid grid-cols-2 gap-8 my-16 py-10 border-y border-[#eaeaea]">
                <div>
                    <span class="block text-[32px] font-black leading-none mb-1">Free</span>
                    <span class="text-[11px] font-bold text-zinc-400 tracking-widest">For Learning</span>
                </div>
                <div>
                    <span class="block text-[32px] font-black leading-none mb-1">Open</span>
                    <span class="text-[11px] font-bold text-zinc-400 tracking-widest">For Everyone</span>
                </div>
            </div>

            <h3 class="text-[20px] font-black mt-16 mb-6">Modern Infrastructure</h3>
            <p class="text-[16px] text-[#666] leading-relaxed mb-16">
                We provide a robust, secure, and high-performance environment for your static projects. Whether it's a personal portfolio or a business landing page, your content is safe and accessible globally.
            </p>
        </div>

        <div class="mt-32 pt-16 border-t border-[#eaeaea] grid grid-cols-1 md:grid-cols-3 gap-12">
            <div>
                <h4 class="text-[17px] font-black mb-3">For Freelancers</h4>
                <p class="text-[14px] text-[#666] leading-relaxed font-medium">Showcase your portfolio professionally without worrying about monthly costs.</p>
            </div>
            <div>
                <h4 class="text-[17px] font-black mb-3">For Students</h4>
                <p class="text-[14px] text-[#666] leading-relaxed font-medium">Perfect for learning web development and hosting your school projects.</p>
            </div>
            <div>
                <h4 class="text-[17px] font-black mb-3">For Small Biz</h4>
                <p class="text-[14px] text-[#666] leading-relaxed font-medium">Go digital today. Focus on your business while we handle your web presence.</p>
            </div>
        </div>
    </main>

    <footer class="py-16 border-t border-[#eaeaea] text-center">
        <p class="text-[12px] font-bold text-[#999]">&copy; 2026 w3site.id — Empowering the Web</p>
    </footer>

</body>
</html>