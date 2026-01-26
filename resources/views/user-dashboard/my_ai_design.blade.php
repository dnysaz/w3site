<x-user-layout>
    {{-- Inisialisasi Component dengan Nama 'designManager' --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <div x-data="designManager">
        {{-- Header --}}
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                    <span class="text-blue-600">AI Landing Page</span> Koleksi Saya
                </h1>
                <p class="text-slate-500 text-sm font-medium">Lihat dan kelola semua hasil racikan AI Landing Page Anda.</p>
            </div>
            <a href="{{ route('aibuilder') }}" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] hover:bg-blue-600 transition-all shadow-xl shadow-slate-200 flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Buat Baru
            </a>
        </header>

        {{-- Stats Row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white p-7 rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden group transition-all hover:shadow-md">
                <div class="relative z-10">
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mb-4">Total Koleksi</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-4xl font-[1000] text-slate-900 tracking-tighter">{{ $designs->count() }}</span>
                        <span class="text-slate-300 text-[10px] font-black uppercase tracking-widest">Designs</span>
                    </div>
                    <p class="text-[9px] text-blue-500 font-black uppercase tracking-tighter mt-4 flex items-center gap-1">
                        <i class="fa-solid fa-cloud-check text-[8px]"></i> Synchronized to Cloud
                    </p>
                </div>
                <div class="absolute -right-2 -bottom-4 text-slate-50 text-7xl rotate-12 pointer-events-none transition-colors group-hover:text-blue-50/50">
                    <i class="fa-solid fa-folder-closed"></i>
                </div>
            </div>
        
            <div class="bg-white p-7 rounded-[2rem] border border-slate-100 shadow-sm transition-all hover:shadow-md flex flex-col justify-between relative overflow-hidden">
                @php $stats = auth()->user()->getAiStats(); @endphp
                <div class="relative z-10">
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mb-4">AI Credits Usage</p>
                    <div class="flex items-end gap-1.5">
                        <span class="text-4xl font-[1000] text-slate-900 tracking-tighter leading-none">{{ $stats['used'] }}</span>
                        <span class="text-slate-400 font-black text-xs uppercase mb-1">/ {{ $stats['limit'] }}</span>
                    </div>
                </div>
                <div class="mt-8 relative z-10">
                    <div class="w-full h-1.5 bg-slate-50 rounded-full overflow-hidden p-[1px]">
                        <div class="h-full bg-blue-600 rounded-full transition-all duration-1000" style="width: {{ $stats['percentage'] }}%"></div>
                    </div>
                    <div class="flex justify-between mt-3">
                        <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest italic">Consumption Rate</span>
                        <span class="text-[9px] text-slate-900 font-black uppercase tracking-widest">{{ round($stats['percentage']) }}%</span>
                    </div>
                </div>
                <div class="absolute -right-2 -bottom-4 text-slate-50 text-7xl rotate-12 pointer-events-none">
                    <i class="fa-solid fa-bolt"></i>
                </div>
            </div>
        </div>

        {{-- Grid Design List --}}
        <div class="space-y-6 pb-20">
            <h3 class="font-black text-slate-900 text-lg tracking-tight px-2">Project Tersimpan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($designs as $design)
                    <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden group hover:border-blue-200 hover:shadow-xl transition-all duration-500">
                        <div class="relative h-48 bg-slate-50 overflow-hidden border-b border-slate-50 flex items-center justify-center">
                            <div class="w-full h-full flex items-center justify-center">
                                <iframe srcdoc="{{ Storage::disk('public')->get($design->path) }}" 
                                        class="w-[1200px] h-[900px] border-none pointer-events-none scale-[0.30] origin-center">
                                </iframe>
                            </div>
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 backdrop-blur-[2px] z-10">
                                <a href="{{ route('ai.view', $design->file_name) }}" target="_blank" 
                                class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-900 hover:bg-blue-600 hover:text-white transition-all shadow-lg">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        </div>

                        <div class="p-6">
                            <h4 class="text-sm font-black text-slate-900 leading-tight mb-1 group-hover:text-blue-600 transition-colors truncate">
                                {{ $design->title }}
                            </h4>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest italic mb-4">
                                {{ $design->created_at->format('d M Y • H:i') }}
                            </p>
                            
                            <div class="mb-6">
                                <p class="text-slate-500 text-[11px] font-medium italic line-clamp-2 leading-relaxed">"{{ $design->prompt }}"</p>
                            </div>

                            <div class="grid grid-cols-4 gap-2 pt-4 border-t border-slate-50">
                                <a href="{{ route('ai.edit', $design->file_name) }}" class="flex items-center justify-center py-3 bg-blue-50 text-blue-600 rounded-xl text-[9px] font-black uppercase hover:bg-blue-600 hover:text-white transition-all"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                <button type="button" @click="openDeployPicker('{{ $design->id }}', '{{ addslashes($design->title) }}', '{{ $design->path }}')" class="flex items-center justify-center py-3 bg-emerald-50 text-emerald-600 rounded-xl text-[9px] font-black uppercase hover:bg-emerald-600 hover:text-white transition-all"><i class="fa-solid fa-rocket"></i> Deploy</button>
                                <a href="{{ asset('storage/' . $design->path) }}" download="{{ $design->file_name }}" class="flex items-center justify-center py-3 bg-slate-50 text-slate-600 rounded-xl text-[9px] font-black uppercase hover:bg-slate-900 hover:text-white transition-all"><i class="fa-solid fa-download"></i> Download</a>
                                <button type="button" @click="confirmDelete('{{ $design->file_name }}', '{{ addslashes($design->title) }}')" class="flex items-center justify-center py-3 bg-red-50 text-red-500 rounded-xl text-[9px] font-black uppercase hover:bg-red-500 hover:text-white transition-all"><i class="fa-solid fa-trash-can"></i> Delete</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center bg-white rounded-[2.5rem] border-2 border-dashed border-slate-100 flex flex-col items-center">
                        <i class="fa-solid fa-cloud-slash text-slate-200 text-4xl mb-4"></i>
                        <p class="text-slate-400 font-black uppercase text-xs tracking-widest">Belum ada desain tersimpan</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- AREA MODALS (TELEPORTED) --}}
        <template x-teleport="body">
            <div>
                {{-- MODAL 1: PILIH SUBDOMAIN --}}
                <div x-show="deployModal.show" class="fixed inset-0 z-[1000] flex items-center justify-center p-4 backdrop-blur-md bg-slate-950/60" x-cloak>
                    <div @click.away="if(!isProcessing) deployModal.show = false" class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl py-12 px-10 relative">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-2xl font-[1000] text-slate-900 tracking-tighter">Pilih Situs</h3>
                            <button @click="deployModal.show = false" class="text-slate-300 hover:text-red-500 transition-colors">
                                <i class="fa-solid fa-circle-xmark text-2xl"></i>
                            </button>
                        </div>
                        <div class="space-y-4 max-h-[450px] overflow-y-auto pr-3 custom-scrollbar">
                            @forelse($sites as $site)
                                <button @click="askDeployConfirmation('{{ $site->subdomain }}')" 
                                    class="w-full flex items-center justify-between p-6 bg-white border-2 border-slate-100 rounded-[1.5rem] hover:border-blue-500 hover:bg-blue-50/30 transition-all duration-300 group shadow-sm hover:shadow-md">
                                    <div class="text-left flex items-center gap-4">
                                        <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                            <i class="fa-solid fa-globe text-lg"></i>
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-900 text-base group-hover:text-blue-600">{{ $site->subdomain }}.w3site.id</p>
                                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $site->status }}</span>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-arrow-right-long text-slate-200 group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
                                </button>
                            @empty
                                <div class="text-center py-20 bg-slate-50 rounded-[2rem] border-2 border-dashed border-slate-200">
                                    <i class="fa-solid fa-link-slash text-slate-200 text-4xl mb-4"></i>
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Belum ada Situs aktif</p>
                                </div>
                            @endforelse
                        </div>
                        <div x-show="isProcessing" class="mt-8 flex items-center justify-center gap-3 p-5 bg-slate-900 text-white rounded-2xl shadow-xl shadow-slate-200">
                            <i class="fa-solid fa-spinner animate-spin text-blue-400"></i>
                            <span class="text-[11px] font-black uppercase tracking-widest">Sedang Memproses Deploy...</span>
                        </div>
                    </div>
                </div>

                {{-- MODAL 2: KONFIRMASI DEPLOY --}}
                <div x-show="confirmDeployModal.show" class="fixed inset-0 z-[1100] flex items-center justify-center p-4 backdrop-blur-md bg-slate-950/40" x-cloak>
                    <div class="bg-white w-full max-w-sm rounded-[2.5rem] p-8 text-center shadow-2xl">
                        <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-5 text-2xl"><i class="fa-solid fa-circle-question"></i></div>
                        <h3 class="font-black text-lg text-slate-900 uppercase tracking-tight">Pasang Sekarang?</h3>
                        <p class="text-xs text-slate-500 mt-3 px-2">Anda akan memasang landing page ke <span class="font-bold text-slate-900" x-text="confirmDeployModal.subdomain + '.w3site.id'"></span>. File lama akan digantikan.</p>
                        <div class="mt-8 flex flex-col gap-3">
                            <button @click="processDeploy()" class="w-full py-4 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-100">Ya, Deploy Sekarang</button>
                            <button @click="confirmDeployModal.show = false" class="w-full py-4 bg-slate-100 text-slate-400 rounded-2xl text-[10px] font-black uppercase">Batal</button>
                        </div>
                    </div>
                </div>

                {{-- MODAL 3: NOTIFIKASI --}}
                <div x-show="notifModal.show" class="fixed inset-0 z-[2000] flex items-center justify-center p-4 backdrop-blur-sm bg-slate-950/50" x-cloak>
                    <div class="bg-white w-full max-w-[320px] rounded-[2.5rem] p-8 text-center shadow-2xl">
                        <div :class="notifModal.type === 'success' ? 'bg-emerald-50 text-emerald-500' : 'bg-red-50 text-red-500'" class="w-20 h-20 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-3xl">
                            <i :class="notifModal.type === 'success' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-xmark'"></i>
                        </div>
                        <h3 class="font-[1000] text-slate-900 text-xl tracking-tighter uppercase" x-text="notifModal.title"></h3>
                        <p class="text-[11px] text-slate-500 mt-3" x-text="notifModal.msg"></p>
                        <button @click="notifModal.show = false" class="mt-8 w-full py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase">Tutup</button>
                    </div>
                </div>

                {{-- MODAL 4: HAPUS PROJECT --}}
                <div x-show="deleteModal.show" class="fixed inset-0 z-[999] flex items-center justify-center p-4 backdrop-blur-md bg-slate-950/60" x-cloak>
                    <div class="bg-white w-full max-w-[400px] rounded-[2.5rem] shadow-2xl p-10 text-center relative overflow-hidden">
                        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-[2rem] flex items-center justify-center text-3xl mx-auto mb-6"><i class="fa-solid fa-trash-can animate-bounce"></i></div>
                        <h3 class="text-2xl font-[1000] text-slate-900 tracking-tighter mb-2">Hapus Project?</h3>
                        <p class="text-slate-500 font-medium text-sm mb-8 px-4 text-center">Menghapus <span class="font-bold text-slate-900" x-text="deleteModal.title"></span> tidak dapat dibatalkan.</p>
                        <div class="flex gap-3">
                            <button @click="deleteModal.show = false" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase">Batal</button>
                            <form :action="'{{ url('/ai/design') }}/' + deleteModal.fileName" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-2xl font-black text-[10px] uppercase shadow-xl shadow-red-200">Ya, Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- LOGIKA JAVASCRIPT --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('designManager', () => ({
                // --- State Modal Hapus ---
                deleteModal: {
                    show: false,
                    fileName: '',
                    title: ''
                },
            
                // --- State Modal Deploy Picker ---
                deployModal: {
                    show: false,
                    designId: null,
                    designTitle: '',
                    designPath: ''
                },
            
                // --- State Konfirmasi Akhir Deploy ---
                confirmDeployModal: {
                    show: false,
                    subdomain: ''
                },
            
                // --- State Notifikasi Global ---
                notifModal: {
                    show: false,
                    type: 'success',
                    title: '',
                    msg: ''
                },
            
                isProcessing: false,
            
                // --- Handler: Hapus Project ---
                confirmDelete(fileName, title) {
                    this.deleteModal.fileName = fileName;
                    this.deleteModal.title = title;
                    this.deleteModal.show = true;
                },
            
                // --- Handler: Buka Pilih Subdomain ---
                openDeployPicker(id, title, path) {
                    this.deployModal.designId = id;
                    this.deployModal.designTitle = title;
                    this.deployModal.designPath = path;
                    this.deployModal.show = true;
                },
            
                // --- Handler: Klik Subdomain dari List ---
                askDeployConfirmation(subdomain) {
                    this.confirmDeployModal.subdomain = subdomain;
                    this.confirmDeployModal.show = true;
                },
            
                // --- Handler: Tampilkan Pesan ---
                notify(title, msg, type = 'success') {
                    this.notifModal.title = title;
                    this.notifModal.msg = msg;
                    this.notifModal.type = type;
                    this.notifModal.show = true;
                },
            
                // --- FUNGSI EKSEKUSI DEPLOY ---
                async processDeploy() {
                    if (typeof axios === 'undefined') {
                        this.notify('System Error', 'Library Axios belum dimuat.', 'error');
                        return;
                    }
            
                    if(!this.confirmDeployModal.subdomain || !this.deployModal.designPath) {
                        this.notify('Data Tidak Lengkap', 'Silakan pilih subdomain kembali.', 'error');
                        return;
                    }
            
                    const selectedSubdomain = this.confirmDeployModal.subdomain;
                    const designPath = this.deployModal.designPath;
                    
                    this.confirmDeployModal.show = false;
                    this.isProcessing = true;
                    
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        
                        const response = await axios.post('{{ route("sites.deployAi") }}', {
                            subdomain: selectedSubdomain,
                            design_path: designPath
                        }, {
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        this.deployModal.show = false;
                        this.isProcessing = false;
                        this.notify('Berhasil!', response.data.message || 'Landing Page berhasil dideploy.', 'success');
                        
                        setTimeout(() => {
                            window.location.href = '/mysite';
                        }, 2000);
            
                    } catch (error) {
                        this.isProcessing = false;
                        let errorMessage = 'Terjadi kesalahan sistem.';
                        
                        if (error.response) {
                            errorMessage = error.response.data.message || 'Server menolak permintaan.';
                        } else if (error.request) {
                            errorMessage = 'Koneksi ke server terputus.';
                        }
            
                        this.notify('Gagal Deploy', errorMessage, 'error');
                    }
                }
            }));
        });
    </script>
    @endpush
</x-user-layout>