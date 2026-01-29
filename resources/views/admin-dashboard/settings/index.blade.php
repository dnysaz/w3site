<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">
            System <span class="text-blue-600">Settings</span>
        </h2>
        <p class="text-slate-500 text-sm font-medium">Pusat kendali infrastruktur, log, dan pemeliharaan w3site.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        
        <a href="{{ route('admin.terminal.index') }}" class="group relative bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-500 overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-slate-50 rounded-full group-hover:scale-150 transition-transform duration-700 opacity-50"></div>
            
            <div class="relative z-10">
                <div class="w-16 h-16 bg-slate-900 rounded-2xl flex items-center justify-center text-emerald-400 shadow-lg shadow-slate-200 group-hover:rotate-6 transition-transform">
                    <i class="fas fa-terminal text-2xl"></i>
                </div>
                <div class="mt-6">
                    <h3 class="font-black text-slate-900 text-lg uppercase tracking-tight">Terminal VPS</h3>
                    <p class="text-slate-500 text-xs font-medium mt-2 leading-relaxed">Akses konsol server secara langsung untuk eksekusi perintah artisan atau shell.</p>
                </div>
                <div class="mt-6 flex items-center text-blue-600 font-black text-[10px] uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Buka Terminal <i class="fa-solid fa-arrow-right ml-2"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.logs.index') }}" class="group relative bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-rose-900/5 transition-all duration-500 overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-rose-50 rounded-full group-hover:scale-150 transition-transform duration-700 opacity-50"></div>
            
            <div class="relative z-10">
                <div class="w-16 h-16 bg-rose-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-200 group-hover:rotate-6 transition-transform">
                    <i class="fas fa-bug text-2xl"></i>
                </div>
                <div class="mt-6">
                    <h3 class="font-black text-slate-900 text-lg uppercase tracking-tight">System Logs</h3>
                    <p class="text-slate-500 text-xs font-medium mt-2 leading-relaxed">Pantau error log dan riwayat aktivitas sistem untuk menjaga kestabilan aplikasi.</p>
                </div>
                <div class="mt-6 flex items-center text-rose-600 font-black text-[10px] uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Cek Log <i class="fa-solid fa-arrow-right ml-2"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.env.index') }}" class="group relative bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-900/5 transition-all duration-500 overflow-hidden h-full flex flex-col">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-700 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex-none flex items-center justify-center text-white shadow-lg shadow-indigo-200 group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-gears text-2xl"></i>
                </div>
        
                <div class="mt-6 flex-1">
                    <h3 class="font-black text-slate-900 text-lg uppercase tracking-tight">Env Editor</h3>
                    <p class="text-slate-500 text-xs font-medium mt-2 leading-relaxed">
                        Kelola konfigurasi utama aplikasi, API key, dan database langsung melalui editor file .env.
                    </p>
                </div>
        
                <div class="mt-6 flex items-center text-indigo-600 font-black text-[10px] uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Buka Editor <i class="fa-solid fa-arrow-right ml-2"></i>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.db.backup') }}" class="group relative bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-500 overflow-hidden h-full flex flex-col">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-700 opacity-50"></div>
            
            <div class="relative z-10 flex flex-col h-full">
                <div class="w-16 h-16 bg-emerald-600 rounded-2xl flex-none flex items-center justify-center text-white shadow-lg shadow-emerald-200 group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-database text-2xl"></i>
                </div>
        
                <div class="mt-6 flex-1">
                    <h3 class="font-black text-slate-900 text-lg uppercase tracking-tight">Database Backup</h3>
                    <p class="text-slate-500 text-xs font-medium mt-2 leading-relaxed">
                        Ekspor seluruh data PostgreSQL ke file .sql secara instan untuk cadangan keamanan data w3site.id.
                    </p>
                </div>
        
                <div class="mt-6 flex items-center text-emerald-600 font-black text-[10px] uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Download SQL <i class="fa-solid fa-download ml-2"></i>
                </div>
            </div>
        </a>

        <div x-data="maintenanceHandler()">
            <div class="group relative bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-amber-900/5 transition-all duration-500 overflow-hidden h-full flex flex-col">
                
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-150 transition-transform duration-700 opacity-50"></div>
                
                <div class="relative z-10 flex flex-col h-full">
                    <div :class="isMaintenance ? 'bg-amber-500 shadow-amber-200' : 'bg-slate-800 shadow-slate-200'" 
                         class="w-16 h-16 rounded-2xl flex-none flex items-center justify-center text-white shadow-lg transition-all duration-500 group-hover:rotate-6">
                        <i class="fas fa-tools text-2xl" :class="isLoading ? 'animate-spin' : ''"></i>
                    </div>
        
                    <div class="mt-6 flex-1">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <h3 class="font-black text-slate-900 text-lg uppercase tracking-tight">Maintenance Mode</h3>
                                <p class="text-slate-500 text-xs font-medium mt-2 leading-relaxed">
                                    Matikan akses dashboard user secara instan untuk keperluan perbaikan atau pembaruan sistem.
                                </p>
                            </div>
                            
                            <button type="button"
                                    @click="showConfirm = true" 
                                    :disabled="isLoading"
                                    :class="isMaintenance ? 'bg-amber-500' : 'bg-slate-200'"
                                    class="relative inline-flex h-7 w-12 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 ease-in-out outline-none shrink-0">
                                <span :class="isMaintenance ? 'translate-x-5' : 'translate-x-0'"
                                      class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-md transition duration-300 ease-in-out">
                                </span>
                            </button>
                        </div>
                    </div>
        
                    <div class="mt-6 flex items-center gap-2">
                        <template x-if="isMaintenance">
                            <div class="flex items-center gap-1.5 px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-black rounded-lg uppercase tracking-tighter">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Status: Sistem Terkunci
                            </div>
                        </template>
                        <template x-if="!isMaintenance">
                            <div class="flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-400 text-[10px] font-black rounded-lg uppercase tracking-tighter">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                Status: Normal
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        
            <template x-teleport="body">
                <div x-show="showConfirm" 
                     x-transition.opacity
                     class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
                    
                    <div @click.away="showConfirm = false" 
                         class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl">
                        <div class="p-8 text-center">
                            <div :class="isMaintenance ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600'" 
                                 class="w-20 h-20 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-6">
                                <i :class="isMaintenance ? 'fa-solid fa-power-off' : 'fa-solid fa-triangle-exclamation'"></i>
                            </div>
                            
                            <h4 class="text-xl font-black text-slate-900 mb-2" x-text="isMaintenance ? 'Matikan Maintenance?' : 'Aktifkan Maintenance?'"></h4>
                            <p class="text-sm text-slate-500 font-medium mb-8 leading-relaxed">
                                Konfirmasi perubahan akses dashboard w3site.id.
                            </p>
                            
                            <div class="flex gap-3">
                                <button @click="showConfirm = false" class="flex-1 py-4 bg-slate-100 text-slate-500 font-black rounded-2xl text-xs uppercase tracking-widest">Batal</button>
                                <button @click="confirmToggle()" 
                                        :disabled="isLoading"
                                        :class="isMaintenance ? 'bg-blue-600' : 'bg-amber-500'"
                                        class="flex-1 py-4 text-white font-black rounded-2xl shadow-lg transition-all text-xs uppercase tracking-widest flex items-center justify-center gap-2">
                                    <span x-show="!isLoading">Ya, Eksekusi</span>
                                    <i x-show="isLoading" class="fas fa-spinner animate-spin"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>


    </div>

    <span class="block h-[1px] w-full bg-blue-600/10 my-12 shadow-[0_0_8px_rgba(37,99,235,0.05)]"></span>
    <script>
        function maintenanceHandler() {
            return {
                // State awal diambil dari server-side cache
                isMaintenance: {{ cache('dashboard_maintenance', false) ? 'true' : 'false' }},
                isLoading: false,
                showConfirm: false,
    
                async confirmToggle() {
                    if (this.isLoading) return;
    
                    this.isLoading = true;
                    const newState = !this.isMaintenance;
    
                    try {
                        const response = await fetch('{{ route("admin.maintenance.toggle") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ status: newState })
                        });
    
                        const data = await response.json();
    
                        if (response.ok && data.success) {
                            // Di sini keajaibannya, isMaintenance berubah -> tombol bergeser otomatis
                            this.isMaintenance = newState;
                            this.showConfirm = false;
                        } else {
                            throw new Error(data.message || 'Server Error');
                        }
                    } catch (error) {
                        console.error('Gagal update maintenance:', error);
                        alert('Gagal menghubungi server. Pastikan rute dan koneksi tersedia.');
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
</x-admin-layout>