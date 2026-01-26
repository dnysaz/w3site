<x-user-layout>
    <div x-data="seoStudio()" class="relative">

        {{-- 1. HEADER --}}
        <header class="mb-12 text-center" x-show="!showResult && !isGenerating" x-transition x-cloak>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-full mb-4 border border-blue-100">
                <i class="fa-solid fa-magnifying-glass-chart text-[10px]"></i>
                <span class="text-[9px] font-black uppercase tracking-[0.2em]">AI SEO Generator v.2026</span>
            </div>
            <h1 class="text-5xl font-[1000] text-slate-900 tracking-tighter leading-none mb-4">SEO <span class="text-blue-600">Header</span></h1>
            <p class="text-slate-400 font-medium max-w-lg mx-auto text-sm">Hasilkan Meta Tags dan JSON-LD yang meningkatkan CTR dan peringkat pencarian.</p>
        </header>

        <div class="max-w-6xl mx-auto px-6">
            {{-- 2. FORM INPUT --}}
            <template x-if="!showResult && !isGenerating">
                <div class="bg-white p-10 md:p-14 rounded-[2.5rem] border border-slate-100 relative overflow-hidden text-left shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Nama Bisnis / Brand</label>
                                <input x-model="formData.businessName" type="text" placeholder="Misal: Komputer Kita" 
                                    class="w-full px-7 py-5 bg-slate-50 border-2 border-transparent rounded-[1.5rem] focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-slate-800 text-lg">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Jenis Usaha</label>
                                <input x-model="formData.businessType" type="text" placeholder="IT Consultant & Service" 
                                    class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-slate-800">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">OG Image URL (Opsional)</label>
                                <input x-model="formData.imageUrl" type="text" placeholder="https://domain.com/seo-image.jpg" 
                                    class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-slate-800">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Deskripsi / Visi Bisnis</label>
                            <textarea x-model="formData.description" class="w-full h-full min-h-[220px] px-7 py-6 bg-slate-50 border-2 border-transparent rounded-[2rem] focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-slate-800 resize-none leading-relaxed" 
                                placeholder="Tuliskan keunggulan bisnis Anda di sini..."></textarea>
                        </div>
                    </div>
                    <div class="mt-10 pt-8 border-t border-slate-50 flex justify-end">
                        <button @click="generateSEO()" class="px-16 py-6 bg-blue-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-blue-100 hover:bg-slate-900 transition-all active:scale-95">
                            Generate SEO Header
                        </button>
                    </div>
                </div>
            </template>

            {{-- 3. LOADER --}}
            <div x-show="isGenerating" class="py-24 text-center" x-cloak>
                <div class="w-20 h-20 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin mx-auto mb-8"></div>
                <h2 class="text-2xl font-[1000] text-slate-900 uppercase tracking-widest animate-pulse">Optimizing Metadata...</h2>
            </div>

            {{-- 4. HASIL ANALISIS --}}
            <div x-show="showResult" x-transition class="space-y-8 text-left" x-cloak>
                <div class="mb-12 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div class="border-l-8 border-blue-600 pl-8">
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.5em] mb-2">Optimized For</p>
                        <h2 class="text-4xl font-[1000] text-slate-900 uppercase tracking-tighter" x-text="formData.businessName"></h2>
                    </div>
                    <div class="flex gap-4 w-full md:w-auto">
                        <button @click="showResetModal = true" class="flex-1 md:flex-none px-6 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-50 hover:text-red-600 transition-all">Reset</button>
                        <button @click="copyToClipboard()" 
                            :class="isCopied ? 'bg-slate-900 ring-4 ring-blue-500/20' : 'bg-blue-600 shadow-xl shadow-blue-100'"
                            class="flex-1 md:flex-none px-8 py-4 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all duration-300">
                            <span x-show="!isCopied">Copy All Code</span>
                            <span x-show="isCopied" class="flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check"></i> COPIED!
                            </span>
                        </button>
                    </div>
                </div>

                {{-- Metrics Row --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col items-center justify-center">
                        <div class="relative w-32 h-32 flex items-center justify-center mb-4">
                            <svg class="w-full h-full -rotate-90">
                                <circle cx="64" cy="64" r="58" stroke="#f1f5f9" stroke-width="10" fill="transparent" />
                                <circle cx="64" cy="64" r="58" stroke="#2563eb" stroke-width="10" fill="transparent" 
                                    stroke-dasharray="364.4" 
                                    :stroke-dashoffset="364.4 - (seoResult.seo_score / 100) * 364.4" 
                                    stroke-linecap="round" class="transition-all duration-1000" />
                            </svg>
                            <span class="absolute text-3xl font-[1000] text-slate-900" x-text="seoResult.seo_score + '%'"></span>
                        </div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">SEO Optimization Score</p>
                    </div>

                    <div class="md:col-span-2 bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl">
                        <i class="fa-solid fa-wand-magic-sparkles absolute top-4 right-8 text-white/5 text-6xl"></i>
                        <h4 class="text-blue-400 text-[10px] font-black uppercase tracking-widest mb-3">AI Strategic Analysis</h4>
                        <p class="text-slate-300 font-medium leading-relaxed text-sm md:text-base" x-text="seoResult.analysis"></p>
                    </div>
                </div>

                {{-- Google Preview --}}
                <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm text-left">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 border border-slate-100">
                            <i class="fa-brands fa-google"></i>
                        </div>
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Google Search Preview</span>
                    </div>
                    <div class="max-w-2xl overflow-hidden">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-7 h-7 bg-slate-200 rounded-full flex items-center justify-center text-[10px] font-bold text-slate-600" x-text="formData.businessName.charAt(0).toUpperCase()"></div>
                            <div class="overflow-hidden">
                                <div class="text-[13px] text-slate-900 font-medium leading-tight truncate" x-text="formData.businessName"></div>
                                <div class="text-[11px] text-slate-500 leading-tight truncate" x-text="'https://' + formData.businessName.toLowerCase().replace(/\s+/g, '') + '.com'"></div>
                            </div>
                        </div>
                        <h3 class="text-[20px] text-[#1a0dab] font-medium mb-1 hover:underline cursor-pointer line-clamp-1" x-text="seoResult.title"></h3>
                        <p class="text-[14px] text-[#4d5156] leading-relaxed line-clamp-2" x-text="seoResult.description"></p>
                    </div>
                </div>

                {{-- Code Box --}}
                <div class="bg-[#1e1e1e] rounded-[3rem] overflow-hidden shadow-2xl border border-white/5">
                    <div class="bg-[#252526] px-8 py-4 flex justify-between items-center border-b border-white/5">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-[#ff5f56]"></div>
                            <div class="w-3 h-3 rounded-full bg-[#ffbd2e]"></div>
                            <div class="w-3 h-3 rounded-full bg-[#27c93f]"></div>
                        </div>
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">head_section.html</span>
                    </div>
                    <div class="p-8 md:p-12 overflow-x-auto bg-[#1e1e1e]">
                        <pre class="vscode-theme font-mono text-xs md:text-sm leading-relaxed"><code x-text="seoResult.html_code"></code></pre>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL PESAN --}}
        <template x-teleport="body">
            <div x-show="messageModal.show" class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md" x-cloak x-transition>
                <div @click.away="messageModal.show = false" class="bg-white w-full max-w-md rounded-[3rem] p-10 text-center shadow-2xl">
                    <div :class="{
                        'bg-red-50 text-red-500': messageModal.type === 'error',
                        'bg-amber-50 text-amber-500': messageModal.type === 'warning',
                        'bg-blue-50 text-blue-500': messageModal.type === 'info'
                    }" class="w-20 h-20 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-6">
                        <i x-show="messageModal.type === 'error'" class="fa-solid fa-circle-xmark"></i>
                        <i x-show="messageModal.type === 'warning'" class="fa-solid fa-triangle-exclamation"></i>
                        <i x-show="messageModal.type === 'info'" class="fa-solid fa-circle-info"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2" x-text="messageModal.title"></h3>
                    <p class="text-slate-500 mb-8 font-medium text-sm" x-text="messageModal.message"></p>
                    <button @click="messageModal.show = false" 
                        :class="messageModal.type === 'error' ? 'bg-red-600' : (messageModal.type === 'warning' ? 'bg-amber-500' : 'bg-blue-600')"
                        class="w-full py-4 text-white rounded-2xl font-black text-[10px] uppercase shadow-xl transition-all">
                        Saya Mengerti
                    </button>
                </div>
            </div>
        </template>

        {{-- MODAL RESET --}}
        <template x-teleport="body">
            <div x-show="showResetModal" class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md" x-cloak x-transition>
                <div @click.away="showResetModal = false" class="bg-white w-full max-w-md rounded-[3rem] p-10 text-center shadow-2xl">
                    <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-6">
                        <i class="fa-solid fa-rotate-left"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Hapus Sesi Ini?</h3>
                    <p class="text-slate-500 mb-8 font-medium text-sm">Pastikan Anda sudah mengcopy hasil sebelum menghapus sesi ini.</p>
                    <div class="flex gap-4">
                        <button @click="showResetModal = false" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase">Batal</button>
                        <button @click="hancurkanSesi()" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-black text-[10px] uppercase shadow-xl transition-all hover:bg-red-700">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- SCRIPTS --}}
    <script>
        function seoStudio() {
            return {
                isGenerating: false,
                showResult: false,
                showResetModal: false,
                isCopied: false,
                messageModal: { show: false, title: '', message: '', type: 'info' },
                formData: { businessName: '', imageUrl: '', businessType: '', description: '' },
                seoResult: { title: '', description: '', seo_score: 0, analysis: '', html_code: '' },

                showAlert(title, message, type = 'info') {
                    this.messageModal = { show: true, title, message, type };
                },

                generateSEO() {
                    if(!this.formData.businessName || !this.formData.description) {
                        this.showAlert('Data Minim', 'Nama usaha dan deskripsi wajib diisi.', 'warning');
                        return;
                    }
                    this.isGenerating = true;
                    this.showResult = false;
                    
                    fetch('{{ route('ai.seo.generate') }}', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.formData)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.error) throw new Error(data.error);
                        this.seoResult = data;
                        this.isGenerating = false;
                        this.showResult = true;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    })
                    .catch(err => {
                        this.isGenerating = false;
                        this.showAlert('System Error', err.message || 'Gagal terhubung ke AI.', 'error');
                    });
                },

                copyToClipboard() {
                    const code = this.seoResult.html_code;
                    if (!code) return;

                    const textArea = document.createElement('textarea');
                    textArea.value = code;
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        this.isCopied = true;
                        setTimeout(() => { this.isCopied = false; }, 2000);
                    } catch (err) {
                        this.showAlert('Copy Error', 'Gagal menyalin kode.', 'error');
                    }
                    document.body.removeChild(textArea);
                },

                hancurkanSesi() {
                    fetch('{{ route('ai.seo.destroy') }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    }).finally(() => window.location.reload());
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .font-mono { font-family: 'Fira Code', 'Courier New', monospace; }
        .vscode-theme { color: #9cdcfe; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1e1e1e; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</x-user-layout>