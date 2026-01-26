<x-user-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    {{-- SEO & Meta --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div x-data="siteManager()" x-cloak>
        {{-- Header Section --}}
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">My <span class="text-blue-500">w3site</span></h1>
                <p class="text-slate-500 text-sm font-medium">Paket: <span class="text-slate-900 font-bold">{{ $currentPkg['name'] }}</span></p>
            </div>
        
            @if(!$isFull)
            <button @click="showAddNameModal = true" class="px-6 py-3 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                <i class="fa-solid fa-plus mr-1"></i> Buat Situs Baru
            </button>
            @endif
        </header>
        
        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-10 text-slate-900">
            <div class="bg-white p-6 rounded-[1.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Total Situs</p>
                <span class="text-3xl font-black">{{ $siteCount }} <span class="text-slate-300 text-lg">/ {{ $limit }}</span></span>
                <div class="w-full h-1.5 bg-slate-100 rounded-full mt-4 overflow-hidden">
                    <div class="h-full bg-blue-500 transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            <div class="bg-white p-6 hidden rounded-[1.5rem] border border-slate-100 shadow-sm">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Sisa Situs</p>
                <span class="text-3xl font-black text-blue-600">{{ max(0, $remaining) }}</span>
            </div>
            <div class="bg-white p-6 rounded-[1.5rem] border border-slate-100 shadow-sm">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Masa Aktif</p>
                <span class="text-lg font-black">{{ $expiredDate }}</span>
            </div>
        </div>

        {{-- Grid Site Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($sites as $site)
                <div class="group relative bg-white rounded-[2rem] p-8 transition-all duration-500 hover:shadow-[0_40px_80px_-20px_rgba(37,99,235,0.1)] border border-slate-100 flex flex-col justify-between min-h-[340px]">
                    
                    {{-- Hover Aksen: Garis Biru Tipis di Atas --}}
                    <div class="absolute top-0 left-10 right-10 h-1 bg-blue-600 rounded-b-full scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>

                    <div>
                        {{-- Header: Brand & Status --}}
                        <div class="flex justify-between items-center mb-10">
                            <div class="h-10 w-10 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-slate-200 group-hover:bg-blue-600 group-hover:shadow-blue-200 transition-all duration-500">
                                <i class="fa-solid fa-terminal text-xs"></i>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $site->status == 'active' ? 'bg-blue-400' : 'bg-slate-300' }} opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 {{ $site->status == 'active' ? 'bg-blue-600' : 'bg-slate-400' }}"></span>
                                </span>
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] {{ $site->status == 'active' ? 'text-blue-600' : 'text-slate-400' }}">
                                    {{ $site->status }}
                                </span>
                            </div>
                        </div>

                        {{-- Domain Display --}}
                        <div class="space-y-1">
                            <h3 class="text-2xl font-black text-slate-900 tracking-tight leading-none group-hover:text-blue-600 transition-colors">
                                {{ $site->subdomain }}<span class="text-slate-400 text-sm font-medium tracking-wide">.w3site.id</span>
                            </h3>
                        </div>

                        <div class="mt-8 flex flex-col gap-3">
                            @if($site->repository_url)
                                {{-- TAMPILAN JIKA SUDAH TERHUBUNG KE GITHUB --}}
                                <div class="space-y-3">
                                    {{-- Tombol Utama: Sync --}}
                                    <button @click="syncGitHub({{ $site->id }})" 
                                        class="w-full flex items-center justify-center gap-3 py-3.5 px-5 bg-slate-900 text-white rounded-xl text-[10px] font-[1000] uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-blue-900/10">                                        <i class="fa-solid fa-rotate group-hover:rotate-180 transition-transform duration-500"></i>
                                        Sync dari GitHub
                                    </button>

                                    {{-- Divider Visual --}}
                                    <div class="flex items-center gap-3 px-2 py-1">
                                        <div class="h-[1px] flex-1 bg-slate-100"></div>
                                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-tighter">Atau</span>
                                        <div class="h-[1px] flex-1 bg-slate-100"></div>
                                    </div>
                        
                                    {{-- Tombol Link Repo (Opsional: Agar user bisa cek repo mereka) --}}
                                    <a href="{{ $site->repository_url }}" target="_blank"
                                        class="flex items-center justify-center gap-3 py-3.5 px-5 bg-white border-2 border-slate-100 text-slate-900 rounded-xl text-[10px] font-[1000] uppercase tracking-widest hover:border-blue-600 hover:text-blue-600 transition-all">
                                        <i class="fa-brands fa-github"></i>
                                        Lihat Repository
                                    </a>
                                </div>
                            @else
                                {{-- TAMPILAN DEFAULT (BELUM TERHUBUNG GITHUB) --}}
                                {{-- Opsi 1: Upload ZIP --}}
                                <button @click="subdomain = '{{ $site->subdomain }}'; showUploadModal = true" 
                                        class="flex items-center justify-center gap-3 py-3.5 px-5 bg-slate-900 text-white rounded-xl text-[10px] font-[1000] uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-blue-900/10">
                                    <i class="fa-solid fa-file-zipper"></i>
                                    Upload ZIP File
                                </button>
                        
                                {{-- Divider Visual --}}
                                <div class="flex items-center gap-3 px-2 py-1">
                                    <div class="h-[1px] flex-1 bg-slate-100"></div>
                                    <span class="text-[8px] font-black text-slate-300 uppercase tracking-tighter">Atau</span>
                                    <div class="h-[1px] flex-1 bg-slate-100"></div>
                                </div>
                        
                                {{-- Opsi 2: Deploy GitHub --}}
                                <a href="{{ route('deploy.github', ['subdomain' => $site->subdomain]) }}"
                                   class="flex items-center justify-center gap-3 py-3.5 px-5 bg-white border-2 border-slate-100 text-slate-900 rounded-xl text-[10px] font-[1000] uppercase tracking-widest hover:border-blue-600 hover:text-blue-600 transition-all">
                                    <i class="fa-brands fa-github"></i>
                                    Deploy via GitHub
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="flex items-center gap-6 pt-6 mt-8 border-t border-slate-100/50">
                        @if($site->status == 'active')
                            <button @click="openUpdateModal('{{ $site->subdomain }}')" 
                                    class="group flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.15em] text-slate-800 hover:text-blue-600 transition-all">
                                <i class="fa-solid fa-arrow-up-from-bracket text-[9px] opacity-40 group-hover:opacity-100 group-hover:-translate-y-0.5 transition-all"></i>
                                Update via .Zip
                            </button>
                        @endif

                        <button @click="openDeleteModal({{ $site->id }}, '{{ $site->subdomain }}')" 
                                class="group flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.15em] text-gray-500 hover:text-red-500 transition-all">
                            <i class="fa-solid fa-trash-can text-[9px] opacity-90 group-hover:opacity-100 transition-all"></i>
                            Delete
                        </button>
                        
                        <div class="ml-auto opacity-0 group-hover:opacity-100 transition-all duration-500">
                            <i class="fa-solid fa-ellipsis text-slate-200"></i>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 flex flex-col items-center justify-center bg-slate-50/50 rounded-[3rem] border border-slate-100">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-sm mb-4">
                        <i class="fa-solid fa-layer-group text-slate-200 text-xl"></i>
                    </div>
                    <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em]">No Active Sites</p>
                </div>
            @endforelse
        </div>

        {{-- MODALS SECTION --}}
        
        <div x-show="showNotif" class="fixed inset-0 z-[200] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showNotif = false"></div>
            <div class="relative bg-white w-full max-w-xs rounded-[2rem] p-8 text-center shadow-2xl">
                <div :class="notifType === 'success' ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500'" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid text-2xl" :class="notifType === 'success' ? 'fa-check' : 'fa-xmark'"></i>
                </div>
                <h3 class="font-black text-slate-900 text-lg" x-text="notifTitle"></h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed" x-text="notifMsg"></p>
                <button @click="showNotif = false" class="mt-6 w-full py-3 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest">Tutup</button>
            </div>
        </div>

        <div x-show="showAddNameModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="if(!isDeploying) showAddNameModal = false"></div>
            <div class="relative bg-white w-full max-w-xl rounded-[2.5rem] p-10 shadow-2xl">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-black text-2xl text-slate-900 tracking-tight">Pesan Nama Situs 🏷️</h3>
                    <button x-show="!isDeploying" @click="showAddNameModal = false" class="text-slate-400 hover:text-slate-900">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Nama Subdomain</label>
                        <div class="flex items-center mt-2 bg-slate-50 border-2 border-slate-100 rounded-2xl overflow-hidden focus-within:border-blue-500 transition-all">
                            <input type="text" x-model="subdomain" @input="checkSubdomain()" placeholder="nama-proyek-anda" class="flex-1 px-6 py-5 bg-transparent outline-none font-bold text-slate-900">
                            <span class="px-6 py-5 bg-slate-100 text-slate-500 font-black text-sm">.w3site.id</span>
                        </div>
                        <p x-show="subdomain && !isAvailable" class="mt-2 text-red-500 text-[10px] font-black uppercase ml-1">Nama tidak tersedia</p>
                    </div>
                    <div class="p-5 bg-amber-50 rounded-[1.5rem] border border-amber-100/50 flex gap-4 text-left">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-1"></i>
                        <p class="text-[13px] text-amber-800 font-medium">Nama yang sudah didaftarkan <span class="font-black underline">tidak dapat diubah</span>.</p>
                    </div>
                    <button @click="reserveName()" :disabled="!subdomain || !isAvailable || isDeploying" class="w-full py-5 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] disabled:bg-slate-200 shadow-xl shadow-blue-500/20 active:scale-[0.98]">
                        <span x-show="!isDeploying">Daftarkan Sekarang</span>
                        <span x-show="isDeploying"><i class="fa-solid fa-spinner animate-spin"></i> Memproses...</span>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="showUploadModal || showUpdateZipModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="if(!isDeploying) closeModal()"></div>
            <div class="relative bg-white w-full max-w-xl rounded-[2.5rem] p-8 shadow-2xl text-center">
                <h3 class="font-black text-xl text-slate-900 mb-6" x-text="(showUpdateZipModal ? 'Update Situs: ' : 'Deploy ke ') + subdomain + '.w3site.id'"></h3>
                
                <div x-show="showUpdateZipModal" class="p-4 bg-amber-50 rounded-2xl border border-amber-100 mb-6 flex gap-3 text-left">
                    <i class="fa-solid fa-circle-exclamation text-amber-500 mt-1"></i>
                    <p class="text-[11px] text-amber-800 font-medium leading-relaxed">ZIP baru akan <span class="font-bold">mengganti</span> seluruh file lama.</p>
                </div>

                <div class="border-2 border-dashed border-slate-200 rounded-[2rem] p-8 hover:bg-blue-50 transition-all relative">
                    <input type="file" @change="handleFile($event)" class="absolute inset-0 opacity-0 cursor-pointer" accept=".zip">
                    <i class="fa-solid fa-file-zipper text-4xl text-blue-400 mb-4"></i>
                    <p class="text-xs font-black text-slate-600" x-text="fileName ? fileName : 'Pilih file .ZIP'"></p>
                </div>

                <div x-show="isDeploying" class="mt-8">
                    <div class="w-full h-2 bg-slate-100 rounded-full mt-2 overflow-hidden">
                        <div class="h-full bg-blue-600 transition-all duration-300" :style="'width:' + uploadProgress + '%'"></div>
                    </div>
                </div>

                <button @click="showUpdateZipModal ? startUpdateZip() : startUpload()" :disabled="!selectedFile || isDeploying" class="w-full mt-8 py-4 bg-slate-900 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-blue-600 disabled:bg-slate-300 transition-all">
                    <span x-show="!isDeploying">Konfirmasi & Jalankan <i class="fa-solid fa-rocket ml-1"></i></span>
                    <span x-show="isDeploying"><i class="fa-solid fa-spinner animate-spin"></i> Proses...</span>
                </button>
            </div>
        </div>

        <div x-show="showDeleteModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="fixed inset-0 z-[120] flex items-center justify-center p-4">
            
            {{-- Overlay --}}
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md" @click="if(!isDeploying) showDeleteModal = false"></div>
            
            {{-- Modal Content --}}
            <div class="relative bg-white w-full max-w-sm rounded-[2.5rem] p-10 text-center shadow-2xl border border-red-50">
                {{-- Icon --}}
                <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                    <i class="fa-solid fa-triangle-exclamation text-3xl"></i>
                </div>

                {{-- Title & Warning --}}
                <h3 class="font-black text-xl text-slate-900 uppercase tracking-tight">Konfirmasi Hapus</h3>
                
                <div class="mt-4 p-4 bg-red-50 rounded-2xl border border-red-100">
                    <p class="text-[11px] text-red-700 leading-relaxed">
                        Situs <span class="font-black underline" x-text="siteToDeleteSubdomain"></span> akan dihapus beserta seluruh filenya. 
                        <span class="block mt-1 font-black uppercase text-[9px] tracking-widest text-red-600">⚠️ Tindakan ini tidak dapat dibatalkan!</span>
                    </p>
                </div>

                {{-- Actions --}}
                <div class="mt-8 flex flex-col gap-3">
                    <button @click="confirmDelete()" 
                            :disabled="isDeploying" 
                            class="w-full py-4 bg-red-600 hover:bg-red-700 text-white rounded-2xl text-[10px] font-[1000] uppercase tracking-widest transition-all shadow-lg shadow-red-200 disabled:opacity-50">
                        <span x-show="!isDeploying">Saya Mengerti, Hapus Permanen!</span>
                        <span x-show="isDeploying" class="flex items-center justify-center gap-2">
                            <i class="fa-solid fa-spinner animate-spin"></i> Menghapus...
                        </span>
                    </button>
                    
                    <button @click="showDeleteModal = false" 
                            :disabled="isDeploying"
                            class="w-full py-4 bg-white text-slate-400 border border-slate-100 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>

        <div x-show="showSyncModal" x-cloak class="fixed inset-0 z-[120] flex items-center justify-center p-4">
            {{-- Overlay --}}
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="if(!isDeploying) showSyncModal = false"></div>
            
            {{-- Card Modal --}}
            <div class="relative bg-white w-full max-w-sm rounded-[2.5rem] p-8 text-center shadow-2xl transition-all">
                {{-- Ikon Sync --}}
                <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-5">
                    <i class="fa-solid fa-rotate text-2xl" :class="isDeploying ? 'animate-spin' : ''"></i>
                </div>
        
                <h3 class="font-black text-lg text-slate-900 tracking-tight">Sync Repository?</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Menarik source code terbaru dari GitHub untuk situs <span class="font-bold text-blue-600" x-text="siteToSyncSubdomain"></span>.
                </p>
        
                {{-- Progress Loading (Opsional) --}}
                <div x-show="isDeploying" class="mt-6">
                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full animate-[progress_2s_ease-in-out_infinite] w-1/2"></div>
                    </div>
                    <p class="text-[9px] font-bold text-blue-600 uppercase mt-2 tracking-widest animate-pulse">Sinking Files...</p>
                </div>
        
                {{-- Tombol Aksi --}}
                <div class="mt-8 flex flex-col gap-3" x-show="!isDeploying">
                    <button @click="confirmSync()" 
                            class="w-full py-4 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all">
                        Ya, Update Sekarang!
                    </button>
                    <button @click="showSyncModal = false" 
                            class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em]">
                        Nanti Saja
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Set Default Axios Header
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function siteManager() {
            return {
                subdomain: '',
                isAvailable: true,
                isDeploying: false,
                
                showAddNameModal: false,
                showUploadModal: false,
                showDeleteModal: false,
                showUpdateZipModal: false,

                showSyncModal: false, 
                siteToSyncId: null,  
                siteToSyncSubdomain: '', 
                
                showNotif: false,
                notifType: 'success',
                notifTitle: '',
                notifMsg: '',

                selectedFile: null,
                fileName: '',
                uploadProgress: 0,
                
                siteToDeleteId: null,
                siteToDeleteSubdomain: '',

                closeModal() {
                    this.showUploadModal = false;
                    this.showUpdateZipModal = false;
                    this.selectedFile = null;
                    this.fileName = '';
                },

                notify(title, msg, type = 'success') {
                    this.notifTitle = title;
                    this.notifMsg = msg;
                    this.notifType = type;
                    this.showNotif = true;
                },

                handleFile(e) {
                    const file = e.target.files[0];
                    if(file) { 
                        this.fileName = file.name; 
                        this.selectedFile = file; 
                    }
                },

                checkSubdomain() {
                    this.subdomain = this.subdomain.toLowerCase().replace(/[^a-z0-9-]/g, '');
                    const reserved = ['admin', 'google', 'web', 'dashboard', 'login'];
                    this.isAvailable = !reserved.includes(this.subdomain) && this.subdomain.length > 2;
                },

                // Membuka modal konfirmasi
                syncGitHub(id, sub) {
                    this.siteToSyncId = id;
                    this.siteToSyncSubdomain = sub;
                    this.showSyncModal = true;
                },

                // Eksekusi proses sync sesungguhnya
                async confirmSync() {
                    this.isDeploying = true;
                    try {
                        const res = await axios.post(`/deploy/github/sync/${this.siteToSyncId}`);
                        this.showSyncModal = false;
                        this.notify('Berhasil!', res.data.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } catch (e) {
                        this.isDeploying = false;
                        this.notify('Gagal!', e.response?.data?.message || 'Gagal sinkronisasi.', 'error');
                    }
                },

                async reserveName() {
                    if (!this.subdomain || !this.isAvailable) return;
                    this.isDeploying = true;
                    try {
                        await axios.post('/sites/store-name', { subdomain: this.subdomain });
                        this.showAddNameModal = false;
                        this.notify('Berhasil!', 'Nama situs berhasil dipesan.', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } catch (error) {
                        this.isDeploying = false;
                        this.notify('Gagal!', error.response?.data?.message || 'Gagal mendaftarkan nama.', 'error');
                    }
                },

                async startUpload() {
                    if (!this.selectedFile) return;
                    await this.uploadLogic('/sites/upload-file', 'Deploy Sukses!', 'Website Anda telah online.');
                },

                async startUpdateZip() {
                    if (!this.selectedFile) return;
                    // Menggunakan endpoint yang sama untuk overwrite
                    await this.uploadLogic('/sites/upload-file', 'Update Berhasil!', 'File website Anda telah diperbarui.');
                },

                async uploadLogic(url, successTitle, successMsg) {
                    this.isDeploying = true;
                    let formData = new FormData();
                    formData.append('file', this.selectedFile);
                    formData.append('subdomain', this.subdomain);

                    try {
                        await axios.post(url, formData, {
                            onUploadProgress: (p) => {
                                this.uploadProgress = Math.round((p.loaded * 100) / p.total);
                            }
                        });
                        this.notify(successTitle, successMsg, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } catch (error) {
                        this.isDeploying = false;
                        this.notify('Gagal!', error.response?.data?.message || 'Terjadi kesalahan sistem.', 'error');
                    }
                },

                openDeleteModal(id, sub) {
                    this.siteToDeleteId = id;
                    this.siteToDeleteSubdomain = sub;
                    this.showDeleteModal = true;
                },

                openUpdateModal(sub) {
                    this.subdomain = sub;
                    this.fileName = '';
                    this.selectedFile = null;
                    this.uploadProgress = 0;
                    this.showUpdateZipModal = true;
                },

                async confirmDelete() {
                    this.isDeploying = true;
                    try {
                        await axios.delete(`/delete-site/${this.siteToDeleteId}`);
                        this.showDeleteModal = false;
                        this.notify('Dihapus!', 'Situs berhasil dihapus.', 'success');
                        setTimeout(() => location.reload(), 1200);
                    } catch (error) {
                        this.isDeploying = false;
                        this.notify('Gagal!', 'Situs tidak dapat dihapus.', 'error');
                    }
                }
            }
        }
    </script>

</x-user-layout>