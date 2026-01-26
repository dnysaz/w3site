<x-user-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div x-data="gitManager()" x-cloak class="max-w-2xl mx-auto py-10">
        {{-- Back Button --}}
        <a href="{{ route('mysite') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-blue-600 transition-colors mb-8">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
        </a>

        {{-- Main Card --}}
        <div class="bg-white border border-slate-100 rounded-[2.5rem] p-10 shadow-[0_40px_80px_-20px_rgba(0,0,0,0.03)]">
            {{-- Header --}}
            <div class="text-center mb-12">
                <div class="inline-flex h-16 w-16 bg-slate-900 rounded-[1.5rem] items-center justify-center text-white mb-6 shadow-xl shadow-slate-200">
                    <i class="fa-brands fa-github text-3xl"></i>
                </div>
                <h1 class="text-3xl font-[1000] text-slate-900 tracking-tighter mb-2">Deploy from GitHub</h1>
                <p class="text-slate-400 text-sm font-medium">Hubungkan repository public untuk deployment otomatis.</p>
            </div>

            <div class="space-y-8">
                {{-- Input: Subdomain --}}
                <div class="space-y-3">
                    <div class="flex justify-between items-end px-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pilih Subdomain</label>
                        <span class="text-[10px] font-bold text-blue-600 italic">Quota: {{ $siteCount }}/{{ $limit }}</span>
                    </div>
                    <div class="relative">
                        <select x-model="selectedSubdomain" 
                                class="w-full px-8 py-5 bg-slate-50 border-2 border-transparent rounded-2xl outline-none font-bold text-slate-900 appearance-none focus:border-blue-500 focus:bg-white transition-all text-sm">
                            <option value="">-- Pilih Project --</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->subdomain }}">
                                    {{ $site->subdomain }}.w3site.id
                                </option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-8 top-1/2 -translate-y-1/2 text-slate-300 pointer-events-none"></i>
                    </div>
                </div>

                {{-- Input: Repo URL --}}
                <div class="space-y-3">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Repository URL</label>
                    <div class="flex items-center bg-slate-50 border-2 border-transparent rounded-2xl overflow-hidden focus-within:border-blue-500 focus-within:bg-white transition-all group px-4">
                        <div class="pl-4 text-slate-300 group-focus-within:text-blue-600 transition-colors">
                            <i class="fa-brands fa-github text-xl"></i>
                        </div>
                        <input type="text" x-model="repoUrl" placeholder="https://github.com/username/project" 
                               class="flex-1 px-4 py-5 bg-transparent outline-none font-bold text-slate-900 text-sm placeholder:text-slate-300">
                    </div>
                </div>

                {{-- Alert: Overwrite Warning (Hanya muncul jika repoUrl diisi) --}}
                <div x-show="repoUrl.length > 0 && selectedSubdomain" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     class="p-6 bg-red-50 rounded-[2rem] border-2 border-dashed border-red-100 flex gap-4">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-red-500 animate-pulse"></i>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[11px] text-red-800 font-[1000] uppercase tracking-widest">Perhatian: Overwrite Data</p>
                        <p class="text-[11px] text-red-700 font-medium leading-relaxed italic">
                            Menghubungkan GitHub akan <span class="font-black underline">menghapus permanen</span> file yang ada di subdomain ini dan menggantinya dengan konten dari repository.
                        </p>
                    </div>
                </div>

                {{-- Info Box --}}
                <div class="bg-blue-50/50 rounded-[2rem] p-6 border border-blue-100/50">
                    <div class="flex gap-4">
                        <i class="fa-solid fa-circle-info text-blue-500 mt-1"></i>
                        <p class="text-[11px] text-blue-800 font-medium leading-relaxed italic">
                            Pastikan repository Anda memiliki file <span class="font-bold underline text-slate-900">index.html</span> di root folder agar situs dapat berjalan dengan benar.
                        </p>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button @click="startDeploy()" 
                        :disabled="!selectedSubdomain || !repoUrl || isDeploying"
                        class="w-full py-6 bg-slate-900 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.3em] shadow-2xl shadow-slate-200 hover:bg-blue-600 disabled:bg-slate-50 disabled:text-slate-300 transition-all transform active:scale-[0.98]">
                    <span x-show="!isDeploying">Jalankan Deployment <i class="fa-solid fa-rocket ml-2"></i></span>
                    <span x-show="isDeploying" class="flex items-center justify-center gap-3">
                        <i class="fa-solid fa-spinner animate-spin"></i> 
                        Sinking Files...
                    </span>
                </button>
            </div>
        </div>

        {{-- Small Footer Note --}}
        <p class="text-center mt-10 text-[10px] font-bold text-slate-300 uppercase tracking-widest">
            Powered by GitHub API & w3site Engine
        </p>

        {{-- Toast Notif --}}
        <div x-show="showNotif" x-transition class="fixed bottom-10 inset-x-0 flex justify-center z-[200]">
            <div :class="notifType === 'success' ? 'bg-slate-900' : 'bg-red-600'" 
                 class="px-8 py-4 rounded-full shadow-2xl flex items-center gap-4 text-white border border-white/10">
                <i class="fa-solid" :class="notifType === 'success' ? 'fa-check-circle text-blue-400' : 'fa-circle-xmark'"></i>
                <p class="text-[10px] font-black uppercase tracking-widest" x-text="notifMsg"></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function gitManager() {
            return {
                selectedSubdomain: '{{ $subdomain ?? "" }}',
                repoUrl: '',
                isDeploying: false,
                showNotif: false,
                notifType: 'success',
                notifMsg: '',

                notify(msg, type = 'success') {
                    this.notifMsg = msg;
                    this.notifType = type;
                    this.showNotif = true;
                    setTimeout(() => this.showNotif = false, 4000);
                },

                async startDeploy() {
                    this.isDeploying = true;
                    try {
                        await axios.post('{{ route("deploy.process") }}', {
                            subdomain: this.selectedSubdomain,
                            repository_url: this.repoUrl
                        });
                        this.notify('Deployment berhasil dimulai!', 'success');
                        setTimeout(() => window.location.href = '{{ route("mysite") }}', 1500);
                    } catch (e) {
                        this.isDeploying = false;
                        this.notify(e.response?.data?.message || 'Gagal terhubung ke GitHub.', 'error');
                    }
                }
            }
        }
    </script>
</x-user-layout>