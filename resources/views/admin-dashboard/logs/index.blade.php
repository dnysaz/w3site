<x-admin-layout>
    <div class="w-full">
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">System Logs</h1>
                <p class="text-sm text-slate-500">Pantau kesehatan server dan error kodingan secara real-time.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('log-viewer.index') }}" target="_blank" 
                   class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-sm">
                    <i class="fas fa-external-link-alt text-xs"></i>
                    Buka Fullscreen
                </a>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
            <div class="p-1 h-[75vh]">
                {{-- Iframe yang memanggil route bawaan package --}}
                <iframe 
                    src="{{ route('log-viewer.index') }}" 
                    class="w-full h-full rounded-2xl border-none"
                    allow="fullscreen"
                ></iframe>
            </div>
        </div>

        <div class="mt-4 flex items-center gap-2 text-slate-400 px-4">
            <i class="fas fa-info-circle text-xs"></i>
            <span class="text-xs italic">Data logs diperbarui secara otomatis sesuai isi file storage/logs.</span>
        </div>
    </div>
</x-admin-layout>