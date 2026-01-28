<x-admin-layout>
    <div class="w-full">
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">System Logs (Direct)</h1>
                <p class="text-sm text-slate-500">Membaca langsung dari storage/logs/laravel.log.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <form action="{{ route('admin.logs.clear') }}" method="POST" onsubmit="return confirm('Hapus semua catatan log?')">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 border border-red-100 rounded-xl font-bold text-sm hover:bg-red-100 transition-all shadow-sm">
                        <i class="fas fa-trash-alt text-xs"></i>
                        Bersihkan Log
                    </button>
                </form>
                <a href="{{ url()->current() }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-sm">
                    <i class="fas fa-sync-alt text-xs"></i>
                    Refresh
                </a>
            </div>
        </div>

        <div class="bg-slate-900 rounded-[2rem] border border-slate-800 shadow-2xl overflow-hidden">
            <div class="p-6 h-[70vh] overflow-y-auto font-mono text-md leading-relaxed custom-scrollbar">
                @if($logData)
                    <pre class="text-emerald-400 selection:bg-emerald-500/30">{{ $logData }}</pre>
                @else
                    <div class="h-full flex flex-col items-center justify-center text-slate-500">
                        <i class="fas fa-ghost text-4xl mb-4 opacity-20"></i>
                        <p>File log kosong atau belum ada aktivitas.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</x-admin-layout>