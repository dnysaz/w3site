<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="icon" type="image/png" href="{{ asset('favicon.png') }}"><link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Documentation — w3site.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { font-family: 'Geist', 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .docs-section h2 { font-size: 32px; font-weight: 900; letter-spacing: -0.04em; color: #171717; margin-bottom: 16px; line-height: 1; }
        .docs-section h3 { font-size: 20px; font-weight: 800; color: #171717; margin-top: 40px; margin-bottom: 12px; letter-spacing: -0.02em; }
        .docs-section p { font-size: 16px; color: #666; line-height: 1.6; margin-bottom: 20px; font-medium; }
        .code-block { background: #fafafa; border: 1px border-[#eaeaea]; padding: 24px; border-radius: 16px; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size: 14px; margin: 24px 0; color: #444; font-weight: 500; }
        .sidebar-item { font-size: 14px; padding: 10px 16px; border-radius: 12px; color: #888; transition: all 0.2s; display: block; border: 1px border-transparent; font-weight: 600; }
        .sidebar-item.active { background: #fafafa; color: #000; border-color: #eaeaea; }
        .sidebar-item:hover:not(.active) { color: #000; background: #fafafa/50; }
    </style>
</head>
<body class="bg-white text-[#171717] antialiased" x-data="{ currentSection: 'intro', mobileOpen: false }">

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
                    <a href="{{ route('docs.index') }}" class="text-[13px] font-bold text-black border-b border-black">Docs</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-[13px] font-bold px-5 h-9 flex items-center bg-black text-white rounded-full hover:bg-zinc-800 transition-all">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-[13px] font-bold text-[#666] hover:text-black transition-colors">Log In</a>
                    <a href="{{ route('register') }}" class="text-[13px] font-bold px-5 h-9 flex items-center bg-black text-white rounded-full hover:bg-zinc-800 transition-all">Sign Up</a>
                @endauth
                <button @click="mobileOpen = !mobileOpen" class="md:hidden text-black p-1">
                    <i class="fa-solid" :class="mobileOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </nav>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-16 flex flex-col md:flex-row gap-16">
        
        {{-- SIDEBAR --}}
        <aside class="w-full md:w-64 shrink-0" :class="mobileOpen ? 'block' : 'hidden md:block'">
            <div class="sticky top-24 space-y-8">
                <div>
                    <h4 class="text-[11px] font-black text-zinc-300 tracking-[0.2em] mb-4 px-4 uppercase">Guides</h4>
                    <nav class="space-y-1">
                        <a href="#intro" @click="currentSection = 'intro'" :class="currentSection === 'intro' ? 'active' : ''" class="sidebar-item">Introduction</a>
                        <a href="#structure" @click="currentSection = 'structure'" :class="currentSection === 'structure' ? 'active' : ''" class="sidebar-item">Project Structure</a>
                        <a href="#deploy-zip" @click="currentSection = 'deploy-zip'" :class="currentSection === 'deploy-zip' ? 'active' : ''" class="sidebar-item">Deploy via ZIP</a>
                        <a href="#deploy-git" @click="currentSection = 'deploy-git'" :class="currentSection === 'deploy-git' ? 'active' : ''" class="sidebar-item">Deploy via GitHub</a>
                        <a href="#faq" @click="currentSection = 'faq'" :class="currentSection === 'faq' ? 'active' : ''" class="sidebar-item">FAQs</a>
                    </nav>
                </div>
            </div>
        </aside>

        {{-- CONTENT --}}
        <main class="flex-1 min-w-0 docs-section">
            
            <section id="intro" class="mb-24 scroll-mt-24">
                <h2>Introduction</h2>
                <p>w3site.id is a specialized hosting platform designed for **static websites**. Focus on building your digital experience, and let our infrastructure handle the global delivery. We provide free subdomains, automatic SSL, and lightning-fast deployments.</p>
                <div class="p-6 bg-zinc-50 border border-zinc-200 rounded-3xl">
                    <p class="text-[14px] text-zinc-500 font-medium mb-0 leading-relaxed"><i class="fa-solid fa-circle-info mr-2 text-zinc-400"></i>Static sites are built from HTML, CSS, and JS. We do not support server-side environments like PHP, Node.js runtime, or Python.</p>
                </div>
            </section>

            <section id="structure" class="mb-24 scroll-mt-24">
                <h2>Project Structure</h2>
                <p>For a successful deployment, your main entry point must be named <span class="font-mono text-black bg-zinc-100 px-2 py-0.5 rounded-md font-bold">index.html</span> and must reside at the very root of your project directory.</p>
                <div class="code-block shadow-sm">
                    my-website/<br>
                    ├── index.html <span class="text-zinc-300 font-bold ml-2">// Required Entry Point</span><br>
                    ├── styles.css<br>
                    ├── main.js<br>
                    └── assets/<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;└── logo.png
                </div>
            </section>

            <section id="deploy-zip" class="mb-24 scroll-mt-24">
                <h2>Deploy via ZIP</h2>
                <p>The fastest way to take your project live. Perfect for manual testing, portfolios, or completed client hand-overs.</p>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-zinc-100 flex items-center justify-center shrink-0 text-black font-black text-xs">1</div>
                        <p class="text-[15px] text-zinc-600 font-medium">Select all project files (not the parent folder) and compress them into a <span class="font-bold text-black">.zip</span> bundle.</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-zinc-100 flex items-center justify-center shrink-0 text-black font-black text-xs">2</div>
                        <p class="text-[15px] text-zinc-600 font-medium">Open your dashboard, reserve a name, and upload your ZIP file.</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-zinc-100 flex items-center justify-center shrink-0 text-black font-black text-xs">3</div>
                        <p class="text-[15px] text-zinc-600 font-medium">Your site is live instantly at <span class="font-bold text-black underline">your-name.w3site.id</span>.</p>
                    </div>
                </div>
            </section>

            <section id="deploy-git" class="mb-24 scroll-mt-24">
                <h2>Deploy via GitHub</h2>
                <p>Connect any public repository for a more streamlined workflow. You can trigger updates directly from your dashboard whenever you push new changes.</p>
                <div class="p-8 border border-zinc-200 rounded-[32px] bg-zinc-50/50">
                    <h4 class="text-[14px] font-black text-black mb-4">How to obtain Repository URL:</h4>
                    <p class="text-[14px] text-zinc-500 font-medium mb-6">Navigate to your GitHub repository and copy the HTTPS clone URL.</p>
                    <div class="bg-white border border-zinc-200 p-4 rounded-2xl font-mono text-[13px] text-zinc-600 overflow-x-auto shadow-sm">
                        https://github.com/username/project-name.git
                    </div>
                    <p class="mt-8 text-[12px] font-bold text-zinc-400 italic"><i class="fa-solid fa-lock mr-2"></i>Note: We currently only support Public Repositories.</p>
                </div>
            </section>


            <section id="faq" class="mb-32 scroll-mt-24">
                <h2>FAQs</h2>
                <div class="space-y-4 mt-8">
                    <div class="border border-zinc-200 rounded-[24px] overflow-hidden transition-all hover:border-black" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-8 py-5 text-left text-[16px] font-black hover:bg-zinc-50/50 flex justify-between items-center transition-colors">
                            Why do I see a 404 error?
                            <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-cloak class="px-8 py-6 border-t border-zinc-200 text-[15px] text-zinc-500 font-medium leading-relaxed bg-zinc-50/30">
                            This typically occurs if your <span class="font-bold text-black">index.html</span> is not located at the root of your project. Check if your files are nested inside another subfolder within the ZIP bundle.
                        </div>
                    </div>
                    <div class="border border-zinc-200 rounded-[24px] overflow-hidden transition-all hover:border-black" x-data="{ open: false }">
                        <button @click="open = !open" class="w-full px-8 py-5 text-left text-[16px] font-black hover:bg-zinc-50/50 flex justify-between items-center transition-colors">
                            Do you support PHP?
                            <i class="fa-solid fa-chevron-down text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-cloak class="px-8 py-6 border-t border-zinc-200 text-[15px] text-zinc-500 font-medium leading-relaxed bg-zinc-50/30">
                            No. w3site.id is dedicated to static hosting. If your project requires a backend, we recommend integrating with a BaaS provider such as Supabase or Firebase via their JS SDK.
                        </div>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <footer class="py-16 border-t border-[#eaeaea]">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-3">
                <span class="flex items-center justify-center border-2 border-black rounded w-7 h-7 font-black text-[14px] tracking-tighter">w</span>
                <span class="text-[12px] font-bold text-zinc-400">&copy; 2026 w3site.id — Static Hosting Forever</span>
            </div>
            <div class="flex items-center gap-8">
                <a href="#" class="text-[13px] font-bold text-zinc-400 hover:text-black transition-colors">Twitter</a>
                <a href="#" class="text-[13px] font-bold text-zinc-400 hover:text-black transition-colors">GitHub</a>
                <a href="mailto:hello@w3site.id" class="text-[13px] font-bold text-zinc-400 hover:text-black transition-colors">Support</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section');
            let current = '';
            sections.forEach(section => {
                const top = section.offsetTop;
                if (window.pageYOffset >= top - 150) current = section.getAttribute('id');
            });
            // Update UI/Alpine state if needed or simply use CSS scroll-spy
        });
    </script>
</body>
</html>