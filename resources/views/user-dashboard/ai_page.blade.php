<x-user-layout>
    {{-- Header --}}
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
        <div>
            <h2 class="text-[10px] font-black text-blue-600 uppercase tracking-[0.3em] mb-2">Intelligence Center</h2>
            <h1 class="text-3xl font-[1000] text-slate-900 tracking-tight leading-none">
                AI Magic <span class="text-slate-400">Tools</span>
            </h1>
            <p class="text-slate-500 text-sm font-medium mt-2">Pilih alat AI untuk mempercepat pekerjaan Anda.</p>
        </div>
    </header>

    {{-- Grid Menu Utama AI --}}
    {{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        
        <a href="{{ Route::has('aibuilder') ? route('aibuilder') : '#' }}" class="group relative bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-500 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-slate-200 group-hover:bg-blue-600 group-hover:rotate-6 transition-all duration-500">
                    <i class="fa-solid fa-wand-magic-sparkles text-xl"></i>
                </div>
                <h3 class="text-xl font-[900] text-slate-900 mb-2 tracking-tight">Landing Page Builder</h3>
                <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Buat website mewah satu halaman hanya dengan deskripsi teks singkat.</p>
                <div class="flex items-center text-[10px] font-black text-blue-600 uppercase tracking-widest">
                    Mulai Generate <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('my.ai.design') }}" class="group relative bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-slate-500/10 hover:-translate-y-1 transition-all duration-500 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-slate-50 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-150 duration-700"></div>
            <div class="relative z-10">
                <div class="relative w-14 h-14 mb-6">
                    <div class="w-14 h-14 bg-white text-slate-900 border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm group-hover:border-blue-600 group-hover:text-blue-600 transition-all duration-500">
                        <i class="fa-solid fa-folder-open text-xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 bg-blue-600 text-white text-[10px] font-black px-2 py-1 rounded-lg shadow-lg border-2 border-white transform transition-transform group-hover:scale-110">
                        {{ $aiDesignCount }}
                    </div>
                </div>
                <h3 class="text-xl font-[900] text-slate-900 mb-2 tracking-tight">My AI Designs</h3>
                <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Lihat dan kelola koleksi landing page yang sudah pernah Anda buat.</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-900 transition-colors">
                        Buka Koleksi <i class="fa-solid fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </div>
        </a>

        <a href="{{ route("ai.swot.page") }}" class="group relative bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-500">
            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                <i class="fa-solid fa-chart-pie text-xl"></i>
            </div>
            <h3 class="text-xl font-[900] text-slate-900 mb-2 tracking-tight">AI S.W.O.T Analysis</h3>
            <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Analisis kekuatan, kelemahan, peluang, dan ancaman bisnis Anda secara instan.</p>
            <div class="flex items-center text-[10px] font-black text-indigo-600 uppercase tracking-widest">
                Analisis Sekarang <i class="fa-solid fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <a href="{{ route("ai.seo.header") }}" class="group relative bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-emerald-500/10 hover:-translate-y-1 transition-all duration-500">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                <i class="fa-solid fa-magnifying-glass-chart text-xl"></i>
            </div>
            <h3 class="text-xl font-[900] text-slate-900 mb-2 tracking-tight">SEO Header Gen</h3>
            <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Buat Meta Title & Description yang menjual dan ramah mesin pencari (Google).</p>
            <div class="flex items-center text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                Optimasi SEO <i class="fa-solid fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <a href="#" class="group relative bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-orange-500/10 hover:-translate-y-1 transition-all duration-500">
            <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all duration-500">
                <i class="fa-solid fa-newspaper text-xl"></i>
            </div>
            <h3 class="text-xl font-[900] text-slate-900 mb-2 tracking-tight">AI Blog Post</h3>
            <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Tulis artikel blog berkualitas tinggi dan informatif hanya dengan satu topik.</p>
            <div class="flex items-center text-[10px] font-black text-orange-600 uppercase tracking-widest">
                Tulis Artikel <i class="fa-solid fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <a href="#" class="group relative bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-purple-500/10 hover:-translate-y-1 transition-all duration-500">
            <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-all duration-500">
                <i class="fa-solid fa-bullhorn text-xl"></i>
            </div>
            <h3 class="text-xl font-[900] text-slate-900 mb-2 tracking-tight">AI CTA Section</h3>
            <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Buat kalimat ajakan (Call to Action) yang persuasif untuk konversi tinggi.</p>
            <div class="flex items-center text-[10px] font-black text-purple-600 uppercase tracking-widest">
                Buat Kalimat <i class="fa-solid fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

    </div> --}}

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        
        {{-- Tool: Landing Page Builder (FREE) --}}
        <a href="{{ Route::has('aibuilder') ? route('aibuilder') : '#' }}" class="group relative bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-1 transition-all duration-500 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-150 duration-700"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-slate-200 group-hover:bg-blue-600 group-hover:rotate-6 transition-all duration-500">
                    <i class="fa-solid fa-wand-magic-sparkles text-xl"></i>
                </div>
                <h3 class="text-xl font-[900] text-slate-900 mb-2 tracking-tight">Landing Page Builder</h3>
                <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Buat website mewah satu halaman hanya dengan deskripsi teks singkat.</p>
                <div class="flex items-center text-[10px] font-black text-blue-600 uppercase tracking-widest">
                    Mulai Generate <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i>
                </div>
            </div>
        </a>
    
        {{-- Tool: My AI Designs (FREE) --}}
        <a href="{{ route('my.ai.design') }}" class="group relative bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-slate-500/10 hover:-translate-y-1 transition-all duration-500 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-slate-50 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-150 duration-700"></div>
            <div class="relative z-10">
                <div class="relative w-14 h-14 mb-6">
                    <div class="w-14 h-14 bg-white text-slate-900 border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm group-hover:border-blue-600 group-hover:text-blue-600 transition-all duration-500">
                        <i class="fa-solid fa-folder-open text-xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 bg-blue-600 text-white text-[10px] font-black px-2 py-1 rounded-lg shadow-lg border-2 border-white transform transition-transform group-hover:scale-110">
                        {{ $aiDesignCount }}
                    </div>
                </div>
                <h3 class="text-xl font-[900] text-slate-900 mb-2 tracking-tight">My AI Designs</h3>
                <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Lihat dan kelola koleksi landing page yang sudah pernah Anda buat.</p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-900 transition-colors">
                        Buka Koleksi <i class="fa-solid fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </div>
        </a>
    
        {{-- Tool: AI S.W.O.T Analysis (PRO) --}}
        <a href="{{ route("ai.swot.page") }}" class="group relative bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-500 overflow-hidden">
            @if(!Auth::user()->is_pro)
                <div class="absolute top-0 right-0">
                    <div class="bg-amber-400 text-white text-[9px] font-[1000] px-10 py-1 shadow-sm transform rotate-45 translate-x-10 translate-y-4 uppercase tracking-[0.2em]">PRO</div>
                </div>
            @endif
            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                <i class="fa-solid fa-chart-pie text-xl"></i>
            </div>
            <h3 class="text-xl font-[900] text-slate-900 mb-2 tracking-tight">S.W.O.T Analysis</h3>
            <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Analisis kekuatan, kelemahan, peluang, dan ancaman bisnis Anda secara instan.</p>
            <div class="flex items-center text-[10px] font-black text-indigo-600 uppercase tracking-widest">
                Analisis Sekarang <i class="fa-solid fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
    
        {{-- Tool: AI SEO Header Generator (PRO) --}}
        <a href="{{ route("ai.seo.header") }}" class="group relative bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-emerald-500/10 hover:-translate-y-1 transition-all duration-500 overflow-hidden">
            @if(!Auth::user()->is_pro)
                <div class="absolute top-0 right-0">
                    <div class="bg-amber-400 text-white text-[9px] font-[1000] px-10 py-1 shadow-sm transform rotate-45 translate-x-10 translate-y-4 uppercase tracking-[0.2em]">PRO</div>
                </div>
            @endif
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                <i class="fa-solid fa-magnifying-glass-chart text-xl"></i>
            </div>
            <h3 class="text-xl font-[900] text-slate-900 mb-2 tracking-tight">SEO Header Gen</h3>
            <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Buat Meta Title & Description yang menjual dan ramah mesin pencari (Google).</p>
            <div class="flex items-center text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                Optimasi SEO <i class="fa-solid fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
    
        {{-- Tool: AI Blog Post Generator (PRO) --}}
        <a href="{{ route("ai.blog.header") }}" class="group relative bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-orange-500/10 hover:-translate-y-1 transition-all duration-500 overflow-hidden">
            @if(!Auth::user()->is_pro)
                <div class="absolute top-0 right-0">
                    <div class="bg-amber-400 text-white text-[9px] font-[1000] px-10 py-1 shadow-sm transform rotate-45 translate-x-10 translate-y-4 uppercase tracking-[0.2em]">PRO</div>
                </div>
            @endif
            <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-600 group-hover:text-white transition-all duration-500">
                <i class="fa-solid fa-newspaper text-xl"></i>
            </div>
            <h3 class="text-xl font-[900] text-slate-900 mb-2 tracking-tight">AI Blog Post</h3>
            <p class="text-slate-500 text-xs font-medium leading-relaxed mb-6">Tulis artikel blog berkualitas tinggi dan informatif hanya dengan satu topik.</p>
            <div class="flex items-center text-[10px] font-black text-orange-600 uppercase tracking-widest">
                Tulis Artikel <i class="fa-solid fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
    </div>

    {{-- Info Section: Model Status --}}
    <div class="bg-slate-950 rounded-[3rem] p-8 md:p-14 relative overflow-hidden shadow-2xl shadow-blue-900/20 border border-white/5">
        {{-- Decorative AI Glow --}}
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-600/20 blur-[120px] rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-600/10 blur-[100px] rounded-full -ml-10 -mb-10"></div>
        
        {{-- Mesh Grid Background --}}
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px;"></div>
    
        <div class="relative z-10 flex flex-col lg:flex-row items-center gap-12">
            <div class="flex-1 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                    <span class="text-[9px] font-black text-blue-400 uppercase tracking-widest">Enterprise Intelligence</span>
                </div>
                
                <h3 class="text-white text-3xl md:text-5xl font-[1000] tracking-tighter mb-6 leading-[0.9]">
                    Powered by <span class="text-blue-500">DeepSeek V3.2</span> <br> 
                    <span class="text-slate-500">& Tailwind Engine</span>
                </h3>
                
                <p class="text-slate-400 text-sm md:text-base leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Sistem kami menggunakan model bahasa terbaru yang dioptimalkan khusus untuk menghasilkan kode HTML bersih, modern, dan sangat cepat (load speed optimal).
                </p>
    
                {{-- Info Paket Pro --}}
                <div class="mt-8 p-4 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 inline-flex flex-col md:flex-row items-center gap-4">
                    <p class="text-slate-300 text-xs font-medium">
                        <i class="fa-solid fa-circle-check text-emerald-500 mr-2"></i> 
                        Dapatkan output hingga <span class="text-white font-bold">15,000+ tokens</span> di paket <span class="text-blue-400 font-black">PRO</span>
                    </p>
                    <a href="{{ route('pricing') }}" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-blue-600/20">
                        Upgrade Sekarang
                    </a>
                </div>
            </div>
    
            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 gap-4 w-full lg:w-auto">
                <div class="group bg-white/5 backdrop-blur-xl border border-white/10 p-8 rounded-[2rem] text-center hover:bg-white/10 transition-all duration-500">
                    <div class="text-blue-500 mb-3 group-hover:scale-110 transition-transform duration-500">
                        <i class="fa-solid fa-microchip text-2xl"></i>
                    </div>
                    <p class="text-white font-black text-3xl leading-none">5000+</p>
                    <p class="text-slate-500 text-[8px] font-black uppercase tracking-[0.2em] mt-3">Tokens / Page</p>
                </div>
                
                <div class="group bg-gradient-to-br from-blue-600/20 to-transparent backdrop-blur-xl border border-blue-500/20 p-8 rounded-[2rem] text-center hover:border-blue-500/50 transition-all duration-500">
                    <div class="text-blue-400 mb-3">
                        <i class="fa-solid fa-bolt-lightning text-2xl animate-pulse"></i>
                    </div>
                    <p class="text-white font-black text-3xl leading-none">PRO</p>
                    <p class="text-blue-400 text-[8px] font-black uppercase tracking-[0.2em] mt-3">Max Performance</p>
                </div>
            </div>
        </div>
    </div>
</x-user-layout>