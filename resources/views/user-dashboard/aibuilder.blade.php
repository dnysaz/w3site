<x-user-layout>
    <div x-data="{ 
        promptText: '', 
        isGenerating: false,
        isSaving: false,
        showPreview: false,
        activeTab: 'preview',
        generatedHtml: '',
        progress: 0,
        progressText: 'Menghubungkan ke DeepSeek...',
    
        modal: { show: false, type: 'success', title: '', message: '' },
    
        openModal(type, title, message) {
            this.modal.type = type; this.modal.title = title; this.modal.message = message; this.modal.show = true;
        },
        
        generateSite() {
            if(this.promptText.length < 10) {
                this.openModal('info', 'Prompt Pendek', 'Berikan deskripsi minimal 10 karakter.');
                return;
            }
    
            this.isGenerating = true; 
            this.showPreview = true;
            this.progress = 0;
            this.progressText = 'Menghubungkan ke DeepSeek...';
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
    
            let interval = setInterval(() => {
                if(this.progress < 88) {
                    this.progress += Math.floor(Math.random() * 3) + 1;
                    if(this.progress > 20) this.progressText = 'Menganalisis Vibe & Layout...';
                    if(this.progress > 45) this.progressText = 'Menyusun Struktur Tailwind...';
                    if(this.progress > 75) this.progressText = 'Merender Elemen Visual...';
                }
            }, 400);
    
            fetch('{{ route('ai.generate') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ prompt: this.promptText })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    this.progress = 100;
                    this.progressText = 'Selesai! Menyempurnakan tampilan...';
                    this.generatedHtml = data.html;
                    setTimeout(() => {
                        const iframe = document.getElementById('previewFrame');
                        iframe.srcdoc = data.html;
                        clearInterval(interval);
                    }, 500);
                } else {
                    this.isGenerating = false;
                    clearInterval(interval);
                    this.openModal('error', 'Gagal', data.message);
                }
            })
            .catch((err) => { 
                this.isGenerating = false;
                clearInterval(interval);
                
                // Menampilkan error detail di Console Log (F12 -> Console)
                console.error('AI Generation Error Detail:', err);

                // Opsional: Menampilkan pesan error spesifik di modal agar user tahu penyebabnya
                this.openModal('error', 'Network Error', 'Gagal memproses AI: ' + err.message); 
            })
            .finally(() => { 
                setTimeout(() => { this.isGenerating = false; }, 1500);
            });
        },
    
        saveToCloud() {
            if(!this.generatedHtml || this.isSaving) return;
            this.isSaving = true;
            fetch('{{ route('ai.save') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ html: this.generatedHtml, prompt: this.promptText })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    this.openModal('success', 'Tersimpan!', 'Mengarahkan ke desain Anda...');
                    setTimeout(() => { window.location.href = '{{ route('ai.index') }}'; }, 1500);
                } else {
                    this.openModal('error', 'Gagal', data.message);
                }
            })
            .finally(() => { this.isSaving = false; });
        },
    
        openFullscreen() {
            const iframe = document.getElementById('previewFrame');
            if (iframe.requestFullscreen) iframe.requestFullscreen();
            else if (iframe.webkitRequestFullscreen) iframe.webkitRequestFullscreen();
        }
    }" class="relative min-h-screen flex flex-col bg-[#fcfcfd] pb-40">

        {{-- 1. HEADER --}}
        <header x-show="!isGenerating" x-collapse x-transition.duration.800ms class="max-w-7xl mx-auto pt-24 pb-16 px-6 text-center relative overflow-hidden">
            {{-- Dekorasi Glow Halus di Background --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10 pointer-events-none">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[500px] h-[300px] bg-blue-50/50 blur-[120px] rounded-full"></div>
            </div>
        
            {{-- Badge AI Modern --}}
            <div class="inline-flex items-center gap-3 px-5 py-2.5 bg-white border border-slate-100 rounded-full mb-10 shadow-xl shadow-blue-500/5 group hover:border-blue-200 transition-colors duration-500">
                <div class="relative">
                    <span class="flex h-2.5 w-2.5 rounded-full bg-blue-600 animate-ping absolute opacity-75"></span>
                    <span class="relative flex h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                </div>
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.25em]">
                    AI Builder <span class="text-slate-300 mx-1">|</span> <span class="text-blue-600 group-hover:text-blue-700">DeepSeek V3 Intelligence</span>
                </span>
            </div>
        
            {{-- Main Headline --}}
            <h1 class="text-4xl md:text-6xl font-[1000] text-slate-950 tracking-[-0.05em] mb-8 leading-[0.85] md:leading-[0.85]">
                Buat Landing Page <br> 
                <span class="bg-clip-text text-transparent bg-gradient-to-b from-blue-600 to-blue-800 italic pr-3">Mewah</span> 
                Secara <span class="text-slate-900">Instan.</span>
            </h1>
        
            {{-- Sub-headline / Info --}}
            <div class="max-w-2xl mx-auto space-y-4">
                <p class="text-slate-500 font-bold text-md md:text-xl leading-relaxed">
                    Hanya dengan satu baris deskripsi, AI kami akan merancang kode HTML & Tailwind CSS yang <span class="text-slate-900">siap pakai, responsif, dan elegan.</span>
                </p>
                
                {{-- Feature Mini Tags --}}
                <div class="flex flex-wrap justify-center gap-3 pt-4">
                    <span class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 text-slate-400 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-100">
                        <i class="fa-solid fa-bolt text-amber-500"></i> Vibe Coding
                    </span>
                    <span class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 text-slate-400 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-100">
                        <i class="fa-solid fa-mobile-screen text-blue-500"></i> Mobile Ready
                    </span>
                    <span class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 text-slate-400 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-100">
                        <i class="fa-solid fa-code text-emerald-500"></i> Tailwind CSS
                    </span>
                </div>
            </div>
        </header>

        {{-- 2. AREA PREVIEW & RESULT --}}
        <div id="resultArea" class="flex-1 max-w-7xl mx-auto w-full px-4 mt-10">
            <div x-show="showPreview || isGenerating" x-transition:enter.duration.1000ms class="w-full">
                
                <div class="flex items-center justify-between mb-6 px-4">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
                        <span x-show="isGenerating" class="flex items-center gap-2">
                            <i class="fa-solid fa-circle-notch animate-spin text-blue-600"></i> AI Processing
                        </span>
                        <span x-show="!isGenerating">Live Preview Engine</span>
                    </h3>
                </div>
        
                {{-- Container Utama --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-[0_40px_100px_rgba(0,0,0,0.03)] overflow-hidden min-h-[600px] relative">
                    
                    {{-- Modern AI Progress Overlay --}}
                    <div x-show="isGenerating" 
                         x-transition:enter.opacity.duration.500ms 
                         x-transition:leave.opacity.duration.500ms
                         class="absolute inset-0 z-20 bg-slate-900/95 backdrop-blur-xl flex flex-col items-center justify-center p-10">
                        
                        {{-- Glowing Orbit Animation --}}
                        <div class="relative w-32 h-32 mb-12">
                            <div class="absolute inset-0 rounded-full border-2 border-blue-500/20"></div>
                            <div class="absolute inset-0 rounded-full border-t-2 border-blue-500 animate-spin"></div>
                            <div class="absolute inset-4 rounded-full border-2 border-purple-500/20"></div>
                            <div class="absolute inset-4 rounded-full border-b-2 border-purple-500 animate-spin-slow"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fa-solid fa-wand-magic-sparkles text-3xl text-white animate-pulse"></i>
                            </div>
                        </div>
        
                        {{-- Progress Text --}}
                        <div class="text-center space-y-4 w-full max-w-md">
                            <div class="flex justify-between items-end mb-2">
                                <span x-text="progressText" class="text-blue-400 text-[10px] font-black uppercase tracking-[0.2em]"></span>
                                <span class="text-white font-black text-2xl tracking-tighter" x-text="progress + '%'"></span>
                            </div>
                            
                            {{-- Progress Bar --}}
                            <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden p-[2px]">
                                <div class="h-full bg-gradient-to-r from-blue-600 to-indigo-500 rounded-full transition-all duration-500 shadow-[0_0_15px_rgba(37,99,235,0.5)]"
                                     :style="`width: ${progress}%` text-align: right">
                                </div>
                            </div>
        
                            <p class="text-slate-500 text-[9px] font-bold uppercase tracking-widest pt-4">
                                DeepSeek V3 is thinking. This usually takes 2-3 minutes depending on your internet connections.
                            </p>
                        </div>
                    </div>
                    
                    {{-- Iframe Output --}}
                    <div class="w-full h-full bg-slate-50 transition-all duration-1000" :class="isGenerating ? 'blur-xl scale-95' : 'blur-0 scale-100'">
                        <iframe id="previewFrame" class="w-full h-[750px] border-none shadow-inner"></iframe>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            .animate-spin-slow { animation: spin 3s linear infinite; }
        </style>

        {{-- 3. INPUT FORM & STATS --}}
        <div class="fixed bottom-0 left-0 md:left-64 right-0 z-[100] p-6 md:p-10 pointer-events-none">
            <div class="max-w-4xl mx-auto w-full pointer-events-auto">
                
                {{-- AI Usage Stats --}}
                <div class="flex items-end justify-between mb-4 px-6">
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Credits</span>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl font-black text-slate-900 leading-none">{{ $aiStats['used'] }}</span>
                            <span class="text-slate-300 font-bold">/</span>
                            <span class="text-sm font-bold text-slate-500">{{ $aiStats['limit'] }}</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-end gap-2 w-32 md:w-48">
                        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-blue-600 transition-all duration-1000" style="width: {{ $aiStats['percentage'] }}%"></div>
                        </div>
                        <span class="text-[9px] font-black uppercase text-slate-400 tracking-tighter">
                            {{ $aiStats['remaining'] }} Sisa Penggunaan
                        </span>
                    </div>
                </div>

                {{-- Input Bar --}}
                <div class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] p-2 md:p-3 shadow-[0_20px_70px_rgba(0,0,0,0.15)] border border-slate-200 transition-all focus-within:border-blue-400">
                    <div class="flex flex-col md:flex-row items-stretch md:items-end gap-3 px-2">
                        <div class="flex-1 pt-1">
                            <textarea 
                                x-model="promptText"
                                {{-- Logika: Enter = Submit, Shift + Enter = New Line --}}
                                @keydown.enter="if (!$event.shiftKey) { 
                                    $event.preventDefault(); 
                                    if(!isGenerating && promptText.length >= 10) generateSite() 
                                }"
                                {{-- Auto Resize Height (Elastic Fix) --}}
                                @input="$el.style.height = '48px'; $el.style.height = ($el.scrollHeight) + 'px'; if(!promptText) $el.style.height = '48px'"
                                placeholder="Website kedai kopi mewah dengan bento grid..."
                                class="w-full min-h-[48px] max-h-[250px] py-3 px-4 bg-transparent outline-none font-bold text-slate-800 placeholder:text-slate-300 resize-none overflow-y-auto leading-tight transition-[height] duration-100"
                                style="height: 48px;"
                            ></textarea>
                        </div>
                        
                        {{-- Container Tombol: Mobile Friendly --}}
                        <div class="flex items-center gap-2 pb-1">
                            <button @click="generateSite()" 
                                :disabled="promptText.length < 10 || isGenerating"
                                class="flex-1 md:flex-none bg-slate-900 text-white w-full md:w-12 h-12 rounded-xl md:rounded-2xl flex items-center justify-center transition-all hover:bg-black active:scale-95 disabled:opacity-20 shadow-lg relative min-w-[48px]">
                                
                                <div class="flex items-center gap-2 md:block">
                                    <i x-show="!isGenerating" class="fa-solid fa-wand-magic-sparkles text-sm"></i>
                                    <span class="md:hidden font-black text-[10px] uppercase tracking-widest" x-show="!isGenerating">Generate</span>
                                </div>
                                
                                <div x-show="isGenerating" x-cloak>
                                    <i class="fa-solid fa-circle-notch animate-spin text-sm"></i>
                                </div>
                            </button>
                
                            <button @click="saveToCloud()" 
                                :disabled="!generatedHtml || isGenerating || isSaving"
                                class="flex-[2] md:flex-none h-12 px-5 md:px-6 rounded-xl md:rounded-2xl font-black text-[10px] md:text-[11px] uppercase tracking-widest transition-all flex items-center justify-center gap-2
                                        disabled:bg-slate-100 disabled:text-slate-300 bg-blue-600 text-white hover:bg-blue-700 shadow-xl shadow-blue-200 active:scale-95">
                                <i x-show="!isSaving" class="fa-solid fa-cloud-arrow-up"></i>
                                <i x-show="isSaving" x-cloak class="fa-solid fa-circle-notch animate-spin"></i>
                                <span x-text="isSaving ? 'Saving...' : 'Save Design'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL DINAMIS --}}
        <template x-teleport="body">
            <div x-show="modal.show" 
                 class="fixed inset-0 z-[999] flex items-center justify-center p-4 backdrop-blur-md bg-slate-900/40" 
                 x-cloak>
                <div @click.away="modal.show = false" 
                     x-show="modal.show"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="bg-white w-full max-w-[400px] rounded-[3rem] shadow-2xl p-10 text-center border border-white/20 relative overflow-hidden">
                    
                    {{-- Icon Section --}}
                    <div :class="{
                            'bg-blue-50 text-blue-600': modal.type === 'success' || modal.type === 'info',
                            'bg-red-50 text-red-500': modal.type === 'error'
                        }" 
                        class="w-20 h-20 rounded-[2rem] flex items-center justify-center text-3xl mx-auto mb-6">
                        
                        {{-- Icon Succes & Info sekarang Biru --}}
                        <i x-show="modal.type === 'success'" class="fa-solid fa-circle-check"></i>
                        <i x-show="modal.type === 'info'" class="fa-solid fa-circle-info"></i>
                        
                        {{-- Icon Error tetap Merah --}}
                        <i x-show="modal.type === 'error'" class="fa-solid fa-triangle-exclamation"></i>
                    </div>
        
                    <h3 class="text-2xl font-[1000] text-slate-900 tracking-tighter mb-2" x-text="modal.title"></h3>
                    <p class="text-slate-500 font-medium text-sm mb-8 px-4" x-text="modal.message"></p>
        
                    {{-- Button Section --}}
                    <button @click="modal.show = false" 
                            :class="{
                                'bg-blue-600 shadow-blue-100': modal.type === 'success' || modal.type === 'info',
                                'bg-red-600 shadow-red-100': modal.type === 'error'
                            }"
                            class="w-full py-4 rounded-2xl text-white font-black text-[11px] uppercase tracking-[0.2em] shadow-xl active:scale-95 transition-all">
                        Lanjutkan
                    </button>
                </div>
            </div>
        </template>
    </div>
</x-user-layout>