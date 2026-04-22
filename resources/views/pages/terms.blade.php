<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="icon" type="image/png" href="{{ asset('favicon.png') }}"><link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Terms — w3site.id</title>
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
        <h1 class="text-[48px] md:text-[64px] font-black tracking-tight mb-6 leading-[1]">Terms of <span class="text-zinc-400">Service.</span></h1>
        <p class="text-[18px] text-[#666] font-medium leading-relaxed mb-16">We want to keep this ecosystem safe and professional for everyone. Please read our rules of service below.</p>

        <div class="space-y-16">
            <section>
                <h3 class="text-[20px] font-black mb-4">1. Service Usage</h3>
                <p class="text-[16px] text-[#666] leading-relaxed">
                    By using w3site.id, you agree to use our services only for lawful purposes. You must not use the platform for any activities that violate local or international laws.
                </p>
            </section>

            <section>
                <h3 class="text-[20px] font-black mb-4">2. User Content</h3>
                <p class="text-[16px] text-[#666] leading-relaxed mb-6">
                    You are solely responsible for any content (text, images, code) you upload. We strictly prohibit content related to:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[13px] font-bold text-[#666]">
                    <div class="p-5 bg-[#fafafa] border border-[#eaeaea] rounded-2xl flex items-center gap-3">
                         <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500"><i class="fa-solid fa-ban text-[12px]"></i></div>
                         Gambling & Scams
                    </div>
                    <div class="p-5 bg-[#fafafa] border border-[#eaeaea] rounded-2xl flex items-center gap-3">
                         <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500"><i class="fa-solid fa-copy text-[12px]"></i></div>
                         Copyright Infringement
                    </div>
                    <div class="p-5 bg-[#fafafa] border border-[#eaeaea] rounded-2xl flex items-center gap-3">
                         <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500"><i class="fa-solid fa-bullhorn text-[12px]"></i></div>
                         Hate Speech & Harassment
                    </div>
                    <div class="p-5 bg-[#fafafa] border border-[#eaeaea] rounded-2xl flex items-center gap-3">
                         <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-500"><i class="fa-solid fa-user-secret text-[12px]"></i></div>
                         Adult Content
                    </div>
                </div>
            </section>

            <section>
                <h3 class="text-[20px] font-black mb-4">3. Limitation of Liability</h3>
                <p class="text-[16px] text-[#666] leading-relaxed">
                    Our service is provided "as is". w3site.id is not responsible for any direct or indirect damages resulting from the use of our services or unexpected technical interruptions.
                </p>
            </section>

            <section>
                <h3 class="text-[20px] font-black mb-4">4. Account Termination</h3>
                <p class="text-[16px] text-[#666] leading-relaxed">
                    We reserve the right to suspend or delete your account/site if you violate these terms, without prior notice.
                </p>
            </section>
        </div>

        <div class="mt-32 p-10 border border-[#eaeaea] bg-zinc-50 rounded-[32px] text-center">
            <h3 class="text-[20px] font-black mb-2">Have Questions?</h3>
            <p class="text-[14px] font-medium text-[#666] mb-8">If any part of these rules is unclear, feel free to reach out to our support team.</p>
            <a href="mailto:support@w3site.id" class="inline-flex h-12 items-center px-8 bg-black text-white text-[14px] font-bold rounded-full hover:bg-zinc-800 transition-all shadow-lg shadow-black/10">Contact Support</a>
        </div>
    </main>

    <footer class="py-16 border-t border-[#eaeaea] text-center">
        <p class="text-[12px] font-bold text-[#999]">&copy; 2026 w3site.id — Updated January 2026</p>
    </footer>

</body>
</html>