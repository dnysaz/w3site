<x-user-layout>
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

    <div x-data="designManager()">
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
                </div>
                <div class="absolute -right-2 -bottom-4 text-slate-50 text-7xl rotate-12 pointer-events-none group-hover:text-blue-50/50">
                    <i class="fa-solid fa-folder-closed"></i>
                </div>
            </div>
        
            <div class="bg-white p-7 rounded-[2rem] border border-slate-100 shadow-sm transition-all hover:shadow-md relative overflow-hidden">
                @php $stats = auth()->user()->getAiStats(); @endphp
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mb-4">AI Credits Usage</p>
                <div class="flex items-end gap-1.5 mb-8">
                    <span class="text-4xl font-[1000] text-slate-900 tracking-tighter leading-none">{{ $stats['used'] }}</span>
                    <span class="text-slate-400 font-black text-xs uppercase mb-1">/ {{ $stats['limit'] }}</span>
                </div>
                <div class="w-full h-1.5 bg-slate-50 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 transition-all duration-1000" style="width: {{ $stats['percentage'] }}%"></div>
                </div>
            </div>
        </div>

        {{-- Grid Design List --}}
        <div class="space-y-6 pb-20">
            <h3 class="font-black text-slate-900 text-lg tracking-tight px-2">Project Tersimpan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($designs as $design)
                    <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden group hover:border-blue-200 transition-all duration-500">
                        <div class="relative h-48 bg-slate-50 overflow-hidden flex items-center justify-center">
                            <iframe srcdoc="{{ Storage::disk('public')->get($design->path) }}" 
                                    class="w-[1200px] h-[900px] border-none pointer-events-none scale-[0.30] origin-center">
                            </iframe>
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 backdrop-blur-[2px]">
                                <a href="{{ route('ai.view', $design->file_name) }}" target="_blank" 
                                class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-900 hover:bg-blue-600 hover:text-white transition-all shadow-lg">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        </div>

                        <div class="p-6">
                            <h4 class="text-sm font-black text-slate-900 leading-tight mb-1 truncate">{{ $design->title }}</h4>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-4 italic">{{ $design->created_at->format('d M Y • H:i') }}</p>
                            
                            <div class="grid grid-cols-4 gap-2 pt-4 border-t border-slate-50">
                                <a href="{{ route('ai.edit', $design->file_name) }}" class="flex items-center justify-center py-3 bg-blue-50 text-blue-600 rounded-xl text-[9px] font-black uppercase hover:bg-blue-600 hover:text-white transition-all"><i class="fa-solid fa-pen-to-square"></i></a>
                                
                                <button type="button" @click="openDeployPicker('{{ $design->id }}', '{{ addslashes($design->title) }}', '{{ $design->path }}')" 
                                    class="flex items-center justify-center py-3 bg-emerald-50 text-emerald-600 rounded-xl text-[9px] font-black uppercase hover:bg-emerald-600 hover:text-white transition-all">
                                    <i class="fa-solid fa-rocket"></i>
                                </button>

                                <a href="{{ asset('storage/' . $design->path) }}" download class="flex items-center justify-center py-3 bg-slate-50 text-slate-600 rounded-xl text-[9px] font-black uppercase hover:bg-slate-900 hover:text-white transition-all">
                                    <i class="fa-solid fa-download"></i>
                                </a>

                                <button type="button" @click="confirmDelete('{{ $design->file_name }}', '{{ addslashes($design->title) }}')" 
                                    class="flex items-center justify-center py-3 bg-red-50 text-red-500 rounded-xl text-[9px] font-black uppercase hover:bg-red-500 hover:text-white transition-all">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center bg-white rounded-[2.5rem] border-2 border-dashed border-slate-100">
                        <i class="fa-solid fa-cloud-slash text-slate-200 text-4xl mb-4"></i>
                        <p class="text-slate-400 font-black uppercase text-xs tracking-widest">Belum ada desain tersimpan</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- MODALS --}}
        <template x-teleport="body">
            <div>
                {{-- MODAL: PILIH SITUS --}}
                <div x-show="deployModal.show" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 backdrop-blur-md bg-slate-950/60" x-cloak x-transition>
                    <div @click.away="if(!isProcessing) deployModal.show = false" class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl py-12 px-10 relative overflow-hidden">
                        
                        {{-- Dekorasi Latar Belakang --}}
                        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-50"></div>

                        <div class="flex justify-between items-start mb-8 relative z-10">
                            <div>
                                <h3 class="text-3xl font-[1000] text-slate-900 tracking-tighter">Pasang ke Situs</h3>
                                <p class="text-slate-500 text-sm font-medium mt-1">Pilih destinasi untuk <span class="text-blue-600 font-bold" x-text="deployModal.designTitle"></span></p>
                            </div>
                            <button @click="deployModal.show = false" class="text-slate-300 hover:text-red-500 transition-colors">
                                <i class="fa-solid fa-circle-xmark text-2xl"></i>
                            </button>
                        </div>

                        {{-- Banner Peringatan (Keterangan Tambahan) --}}
                        <div class="mb-8 p-5 bg-amber-50 border border-amber-100 rounded-[1.5rem] flex gap-4 items-start relative z-10">
                            <div class="w-10 h-10 bg-amber-500 text-white rounded-xl flex-none flex items-center justify-center shadow-lg shadow-amber-200">
                                <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-[11px] font-black uppercase tracking-widest text-amber-700 mb-1">Penting untuk Diketahui</h4>
                                <p class="text-[11px] text-amber-600 leading-relaxed font-medium italic">
                                    Memilih situs yang sudah memiliki konten akan **menghapus dan mengganti** tampilan lama secara permanen. Pastikan Anda memilih subdomain yang tepat.
                                </p>
                            </div>
                        </div>

                        {{-- Daftar Situs --}}
                        <div class="space-y-3 max-h-[380px] overflow-y-auto pr-3 custom-scrollbar relative z-10">
                            @forelse($sites as $site)
                                <button @click="askDeployConfirmation('{{ $site->subdomain }}')" 
                                    class="w-full flex items-center justify-between p-5 bg-white border-2 border-slate-100 rounded-[1.5rem] hover:border-blue-500 hover:bg-blue-50/30 transition-all duration-300 group shadow-sm hover:shadow-md relative overflow-hidden">
                                    
                                    <div class="text-left flex items-center gap-4 relative z-10">
                                        <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 shadow-inner">
                                            <i class="fa-solid fa-globe text-lg"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-black text-slate-900 text-base group-hover:text-blue-600 transition-colors">{{ $site->subdomain }}.w3site.id</p>
                                                {{-- Badge Status --}}
                                                <span class="px-2 py-0.5 bg-slate-100 text-slate-400 text-[8px] font-black uppercase rounded-md tracking-tighter">Ready</span>
                                            </div>
                                            <p class="text-[10px] text-slate-400 font-medium tracking-tight">Klik untuk menimpa konten situs ini</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3 relative z-10">
                                        <i class="fa-solid fa-chevron-right text-slate-200 group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
                                    </div>

                                    {{-- Efek Hover Background --}}
                                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </button>
                            @empty
                                <div class="text-center py-16 bg-slate-50 rounded-[2rem] border-2 border-dashed border-slate-200">
                                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                                        <i class="fa-solid fa-link-slash text-slate-200 text-2xl"></i>
                                    </div>
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Belum Ada Situs Aktif</p>
                                    <a href="{{ route('sites.index') }}" class="text-blue-600 text-[10px] font-bold underline mt-2 inline-block">Buat situs pertama Anda</a>
                                </div>
                            @endforelse
                        </div>

                        {{-- Loading State --}}
                        <div x-show="isProcessing" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="mt-8 flex items-center justify-center gap-4 p-6 bg-slate-900 text-white rounded-[1.5rem] shadow-2xl shadow-blue-200/50">
                            <div class="relative">
                                <i class="fa-solid fa-circle-notch animate-spin text-blue-400 text-xl"></i>
                                <i class="fa-solid fa-rocket text-[8px] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white"></i>
                            </div>
                            <div class="text-left">
                                <span class="text-[11px] font-black uppercase tracking-[0.2em] block">Sistem Sedang Bekerja</span>
                                <span class="text-[9px] text-slate-400 font-medium">Mentransfer file ke server w3site...</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL: KONFIRMASI DEPLOY --}}
                <div x-show="confirmDeployModal.show" class="fixed inset-0 z-[10000] flex items-center justify-center p-4 backdrop-blur-md bg-slate-950/40" x-cloak>
                    <div class="bg-white w-full max-w-sm rounded-[2.5rem] p-8 text-center shadow-2xl">
                        <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-5 text-2xl"><i class="fa-solid fa-circle-question"></i></div>
                        <h3 class="font-black text-lg text-slate-900 uppercase tracking-tight">Pasang Sekarang?</h3>
                        <p class="text-xs text-slate-500 mt-3 px-2">Anda akan memasang landing page ke <span class="font-bold text-slate-900" x-text="confirmDeployModal.subdomain + '.w3site.id'"></span>.</p>
                        <div class="mt-8 flex flex-col gap-3">
                            <button @click="processDeploy()" class="w-full py-4 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest">Ya, Deploy Sekarang</button>
                            <button @click="confirmDeployModal.show = false" class="w-full py-4 bg-slate-100 text-slate-400 rounded-2xl text-[10px] font-black uppercase">Batal</button>
                        </div>
                    </div>
                </div>

                {{-- MODAL: HAPUS --}}
                <div x-show="deleteModal.show" class="fixed inset-0 z-[10000] flex items-center justify-center p-4 backdrop-blur-md bg-slate-950/60" x-cloak>
                    <div class="bg-white w-full max-w-[400px] rounded-[2.5rem] shadow-2xl p-10 text-center">
                        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-[2rem] flex items-center justify-center text-3xl mx-auto mb-6"><i class="fa-solid fa-trash-can"></i></div>
                        <h3 class="text-2xl font-[1000] text-slate-900 tracking-tighter mb-2">Hapus Project?</h3>
                        <p class="text-slate-500 font-medium text-sm mb-8" x-text="'Hapus ' + deleteModal.title + '?'"></p>
                        <div class="flex gap-3">
                            <button @click="deleteModal.show = false" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase">Batal</button>
                            <form :action="'{{ url('/ai/design') }}/' + deleteModal.fileName" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-2xl font-black text-[10px] uppercase">Ya, Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- MODAL: NOTIFIKASI --}}
                <div x-show="notifModal.show" class="fixed inset-0 z-[20000] flex items-center justify-center p-4 backdrop-blur-sm bg-slate-950/50" x-cloak>
                    <div class="bg-white w-full max-w-[320px] rounded-[2.5rem] p-8 text-center shadow-2xl">
                        <div :class="notifModal.type === 'success' ? 'bg-emerald-50 text-emerald-500' : 'bg-red-50 text-red-500'" class="w-20 h-20 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-3xl">
                            <i :class="notifModal.type === 'success' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-xmark'"></i>
                        </div>
                        <h3 class="font-[1000] text-slate-900 text-xl tracking-tighter uppercase" x-text="notifModal.title"></h3>
                        <p class="text-[11px] text-slate-500 mt-3" x-text="notifModal.msg"></p>
                        <button @click="notifModal.show = false" class="mt-8 w-full py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase">Tutup</button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        // Definisikan secara global agar Alpine bisa menemukannya kapan saja
        function designManager() {
            return {
                deleteModal: { show: false, fileName: '', title: '' },
                deployModal: { show: false, designId: null, designTitle: '', designPath: '' },
                confirmDeployModal: { show: false, subdomain: '' },
                notifModal: { show: false, type: 'success', title: '', msg: '' },
                isProcessing: false,
    
                confirmDelete(fileName, title) {
                    console.log('Delete Triggered:', fileName); // Untuk Debugging
                    this.deleteModal.fileName = fileName;
                    this.deleteModal.title = title;
                    this.deleteModal.show = true;
                },
    
                openDeployPicker(id, title, path) {
                    console.log('Deploy Triggered:', id); // Untuk Debugging
                    this.deployModal.designId = id;
                    this.deployModal.designTitle = title;
                    this.deployModal.designPath = path;
                    this.deployModal.show = true;
                },
    
                askDeployConfirmation(subdomain) {
                    this.confirmDeployModal.subdomain = subdomain;
                    this.confirmDeployModal.show = true;
                },
    
                notify(title, msg, type = 'success') {
                    this.notifModal.title = title;
                    this.notifModal.msg = msg;
                    this.notifModal.type = type;
                    this.notifModal.show = true;
                },
    
                async processDeploy() {
                    if(!this.confirmDeployModal.subdomain || !this.deployModal.designPath) return;
    
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
                                'Accept': 'application/json'
                            }
                        });
                        
                        this.isProcessing = false;
                        this.deployModal.show = false;
                        this.notify('Berhasil!', 'Landing Page berhasil dipasang.', 'success');
                        
                        setTimeout(() => window.location.href = '/mysite', 2000);
    
                    } catch (error) {
                        this.isProcessing = false;
                        const msg = error.response?.data?.message || 'Gagal menghubungi server.';
                        this.notify('Gagal Deploy', msg, 'error');
                    }
                }
            }
        }
    </script>
</x-user-layout>