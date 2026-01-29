<x-admin-layout>
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                    <i class="fa-solid fa-gears text-sm"></i>
                </div>
                <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">
                    Environment <span class="text-blue-600">Editor</span>
                </h2>
            </div>
            <p class="text-slate-500 text-sm font-medium">Konfigurasi variabel sistem <span class="text-slate-900 font-bold">w3site.id</span> secara real-time.</p>
        </div>
    </div>
    <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden p-2">
        <form action="{{ route('admin.env.update') }}" method="POST">
            @csrf
            
            <div class="bg-slate-900 rounded-t-[1.5rem] px-6 py-3 flex items-center justify-between border-b border-white/10">
                <div class="flex items-center gap-2">
                    <span class="ml-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Configuration File — .env</span>
                </div>
                <div class="text-[10px] text-emerald-400/50 font-mono">UTF-8 // Laravel Environment</div>
            </div>

            <div class="relative group">
                <textarea 
                    name="content" 
                    rows="20" 
                    class="w-full p-8 font-mono text-sm bg-slate-900 text-emerald-400 outline-none resize-none leading-relaxed border-none focus:ring-0 selection:bg-blue-500/30"
                    spellcheck="false"
                >{{ $content }}</textarea>
                
                <div class="absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-slate-900/50 to-transparent pointer-events-none"></div>
            </div>

            <div class="p-6 bg-slate-50 rounded-b-[2rem] flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <button type="submit" class="px-10 py-4 bg-blue-600 text-white font-black rounded-2xl shadow-xl shadow-blue-200 text-xs uppercase tracking-widest hover:bg-blue-700 hover:-translate-y-1 transition-all active:translate-y-0">
                        Simpan & Update Konfigurasi
                    </button>
                    
                    <div class="hidden lg:flex items-center gap-2 text-slate-400">
                        <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                        <span class="text-[10px] font-bold uppercase tracking-tight">Auto-backup aktif</span>
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-[10px] text-slate-400 font-medium">Terakhir diakses: <span class="text-slate-600 font-bold">{{ date('d M Y, H:i') }}</span></p>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>