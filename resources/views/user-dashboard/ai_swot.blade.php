<x-user-layout>
    {{-- Library html2pdf --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <div x-data="{ 
        isGenerating: false,
        showResult: false,
        showResetModal: false,
        // State untuk Modal Notifikasi (Pengganti Alert)
        notification: { show: false, title: '', message: '', type: 'info' },
        
        progress: 0,
        progressInterval: null,
        formData: { businessName: '', businessType: '', competitors: '', description: '' },
        swotResult: null,

        showAlert(title, message, type = 'info') {
            this.notification = { show: true, title, message, type };
        },

        generateSwot() {
            if(!this.formData.businessName || !this.formData.description) {
                this.showAlert('Data Tidak Lengkap', 'Mohon isi nama usaha dan deskripsi singkat untuk memulai analisis.', 'warning');
                return;
            }
            
            this.isGenerating = true;
            this.showResult = false; 
            this.swotResult = null;  
            this.progress = 0;

            this.progressInterval = setInterval(() => {
                if(this.progress < 95) this.progress += Math.floor(Math.random() * 5) + 2;
            }, 300);
            
            fetch('{{ route('ai.swot.generate') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(this.formData)
            })
            .then(res => res.json())
            .then(data => {
                this.swotResult = {
                    strengths: data.strengths || data.Strengths || [],
                    weaknesses: data.weaknesses || data.Weaknesses || [],
                    opportunities: data.opportunities || data.Opportunities || [],
                    threats: data.threats || data.Threats || [],
                    conclusion: data.conclusion || data.Conclusion || '',
                    recommendation: data.recommendation || data.Recommendation || ''
                };
                
                this.progress = 100;
                setTimeout(() => {
                    this.showResult = true;
                    this.isGenerating = false;
                    clearInterval(this.progressInterval);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 500);
            })
            .catch(err => {
                this.isGenerating = false;
                clearInterval(this.progressInterval);
                this.showAlert('System Error', 'Gagal mengambil data dari server. Silakan coba beberapa saat lagi.', 'error');
            });
        },

        resetAnalysis() {
            fetch('{{ route('ai.swot.reset') }}', { 
                method: 'POST', 
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
            }).then(() => window.location.reload());
        },

        exportPDF() {
            const element = document.getElementById('pdfTemplate');
            element.style.display = 'block'; 
            
            const opt = {
                margin: [10, 10],
                filename: `SWOT-${this.formData.businessName}.pdf`,
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak: { mode: 'css', before: '#page2' }
            };

            // Menggunakan .output('bloburl') untuk membuka di tab baru
            html2pdf().set(opt).from(element).toPdf().get('pdf').then((pdf) => {
                const blobUrl = pdf.output('bloburl');
                window.open(blobUrl, '_blank');
                element.style.display = 'none'; 
            });
        }
    }" class="relative">

        {{-- 1. HEADER UTAMA --}}
        <header class="mb-12 text-center" x-show="!showResult && !isGenerating">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-full mb-4 border border-blue-100">
                <i class="fa-solid fa-brain text-[10px]"></i>
                <span class="text-[9px] font-black uppercase tracking-[0.2em]">AI | DeepSeek</span>
            </div>
            <h1 class="text-5xl font-[1000] text-slate-900 tracking-tighter leading-none mb-4">S.W.O.T <span class="text-blue-600">Analysis</span></h1>
        </header>

        <div class="max-w-6xl mx-auto">
            {{-- 2. FORM PANEL --}}
            <template x-if="!showResult && !isGenerating">
                <div class="bg-white p-10 md:p-14 rounded-[3.5rem] border border-slate-100 relative overflow-hidden text-left">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Nama Brand / Produk</label>
                                <input x-model="formData.businessName" type="text" placeholder="Misal: Kopi Senja" 
                                    class="w-full px-7 py-5 bg-slate-50 border-2 border-transparent rounded-[1.5rem] focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-slate-800 text-lg">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Industri</label>
                                    <input x-model="formData.businessType" type="text" placeholder="F&B" 
                                        class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-slate-800">
                                </div>
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Kompetitor</label>
                                    <input x-model="formData.competitors" type="text" placeholder="Starbucks" 
                                        class="w-full px-5 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-slate-800">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Narasi Strategis</label>
                            <textarea x-model="formData.description" class="w-full h-full min-h-[180px] px-7 py-6 bg-slate-50 border-2 border-transparent rounded-[2rem] focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-slate-800 resize-none leading-relaxed" 
                                placeholder="Jelaskan visi atau masalah bisnis Anda..."></textarea>
                        </div>
                    </div>
                    <div class="mt-10 pt-8 border-t border-slate-50 flex justify-end">
                        <button @click="generateSwot()" class="px-16 py-6 bg-blue-600 text-white rounded-[2rem] font-black text-[11px] uppercase tracking-[0.2em] shadow-xl hover:bg-slate-900 transition-all">
                            Generate Analysis
                        </button>
                    </div>
                </div>
            </template>

            {{-- 3. PROGRESS LOADER --}}
            <div x-show="isGenerating" class="py-24 text-center">
                <div class="inline-flex items-center justify-center relative w-32 h-32 mb-10 mx-auto">
                    <svg class="absolute w-full h-full -rotate-90">
                        <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="10" fill="transparent" class="text-slate-100" />
                        <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="10" fill="transparent" 
                            class="text-blue-600 transition-all duration-300"
                            stroke-dasharray="364.4" :stroke-dashoffset="364.4 - (progress / 100) * 364.4" stroke-linecap="round" />
                    </svg>
                    <span class="text-3xl font-[1000] text-slate-900" x-text="progress + '%'"></span>
                </div>
                <h2 class="text-2xl font-[1000] text-slate-900 uppercase tracking-widest animate-pulse">Computing Matrix...</h2>
            </div>

            {{-- 4. HASIL ANALISIS DI WEB --}}
            <div x-show="showResult" x-transition class="text-left">
                
                {{-- Nama Usaha di Hasil Web --}}
                <div class="mb-12 px-4 text-left border-l-8 border-blue-600 pl-8">
                    <p class="text-[10px] font-black text-blue-600 uppercase tracking-[0.5em] mb-2">Analysis Results For</p>
                    <h2 class="text-4xl font-[1000] text-slate-900 tracking-tighter uppercase" x-text="formData.businessName"></h2>
                    <p class="text-slate-400 font-bold mt-2" x-text="formData.businessType"></p>
                </div>

                <div class="flex justify-between items-center mb-10 px-4">
                    <h2 class="text-2xl font-[1000] text-slate-900 tracking-tighter">Report Overview</h2>
                    <div class="flex gap-4">
                        <button @click="showResetModal = true" class="px-6 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-50 hover:text-red-600 transition-all">Reset</button>
                        <button @click="exportPDF()" class="px-8 py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-600 shadow-xl transition-all">Download PDF</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    <template x-for="category in [
                        { id: 'strengths', label: 'Strengths', color: 'emerald', icon: 'fa-arrow-trend-up' },
                        { id: 'weaknesses', label: 'Weaknesses', color: 'rose', icon: 'fa-bolt-lightning' },
                        { id: 'opportunities', label: 'Opportunities', color: 'blue', icon: 'fa-lightbulb' },
                        { id: 'threats', label: 'Threats', color: 'amber', icon: 'fa-shield-halved' }
                    ]">
                        <div class="bg-white p-8 rounded-[1rem] border border-slate-100 shadow-sm relative overflow-hidden text-left">
                            <div :class="`absolute top-0 left-0 w-2 h-full`"></div>
                            <div class="flex items-center gap-4 mb-8 text-left">
                                <div :class="`w-14 h-14 bg-${category.color}-50 text-${category.color}-600 rounded-2xl flex items-center justify-center text-xl` text-left">
                                    <i :class="`fa-solid ${category.icon}`"></i>
                                </div>
                                <h3 class="text-xl font-[1000] text-slate-900 uppercase tracking-tight text-left" x-text="category.label"></h3>
                            </div>
                            
                            <div class="space-y-4 text-left">
                                <template x-if="swotResult && swotResult[category.id]">
                                    <template x-for="point in swotResult[category.id]">
                                        <div class="p-5 bg-slate-50 rounded-2xl border border-transparent hover:border-slate-200 transition-all text-left">
                                            <div class="flex justify-between items-start gap-4 mb-3 text-left">
                                                <p class="text-sm font-bold text-slate-700 leading-relaxed text-left" x-text="point.text"></p>
                                                <span :class="`text-xs font-black text-${category.color}-600`" x-text="point.percentage + '%'"></span>
                                            </div>
                                            <div class="w-full h-1.5 bg-slate-200/50 rounded-full overflow-hidden">
                                                <div :class="`h-full bg-${category.color}-500`" :style="`width: ${point.percentage}%`"></div>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- KESIMPULAN DI WEB --}}
                <div class="bg-slate-900 rounded-[3.5rem] p-10 md:p-16 text-white relative overflow-hidden mb-20 shadow-2xl text-left">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600/20 blur-[100px] rounded-full"></div>
                    <div class="relative z-10 space-y-8 text-left">
                        <div class="space-y-4 text-left">
                            <h3 class="text-3xl font-black tracking-tight text-left">Strategi Peningkatan Penjualan</h3>
                            <p class="text-slate-400 font-medium text-lg leading-relaxed italic text-left" x-text="swotResult?.conclusion"></p>
                        </div>
                        <div class="p-8 bg-white/5 border border-white/10 rounded-[2rem] text-left">
                            <p class="text-blue-400 text-[10px] font-black uppercase tracking-widest mb-2 text-left">Main Action Plan</p>
                            <p class="text-xl font-bold text-slate-100 text-left" x-text="swotResult?.recommendation"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TEMPLATE PDF --}}
        <div id="pdfTemplate" style="display: none; background: white; padding: 60px; text-align: left; color: #111;">
            {{-- Halaman 1 --}}
            <div>
                <div style="border-bottom: 8px solid #111; padding-bottom: 20px; margin-bottom: 40px;">
                    <h1 style="font-size: 32px; font-weight: 900; margin: 0; text-transform: uppercase;">STRATEGIC ANALYSIS REPORT</h1>
                    <p style="color: #2563eb; font-weight: bold; margin: 10px 0; font-size: 18px;">PREPARED FOR: <span x-text="formData.businessName"></span></p>
                </div>
                <div style="margin-bottom: 40px; font-size: 14px; background: #f8fafc; padding: 20px; border-radius: 10px;">
                    <p style="margin: 5px 0;"><strong>Sektor Bisnis:</strong> <span x-text="formData.businessType"></span></p>
                    <p style="margin: 5px 0;"><strong>Pesaing Utama:</strong> <span x-text="formData.competitors"></span></p>
                    <p style="margin: 5px 0;"><strong>Tanggal Analisis:</strong> {{ date('d F Y') }}</p>
                </div>
                <template x-for="cat in ['strengths', 'weaknesses', 'opportunities', 'threats']">
                    <div style="margin-bottom: 30px;">
                        <h3 style="text-transform: uppercase; border-bottom: 2px solid #2563eb; padding-bottom: 5px; color: #2563eb; font-size: 16px;" x-text="cat"></h3>
                        <template x-if="swotResult">
                            <template x-for="p in swotResult[cat]">
                                <div style="border-bottom: 1px solid #eee; padding: 10px 0; display: flex; justify-content: space-between; font-size: 13px;">
                                    <span style="width: 85%;" x-text="p.text"></span>
                                    <span style="font-weight: bold;" x-text="p.percentage + '%'"></span>
                                </div>
                            </template>
                        </template>
                    </div>
                </template>
            </div>
            {{-- Halaman 2 --}}
            <div style="page-break-before: always; padding-top: 40px;">
                <div style="border-bottom: 4px solid #111; padding-bottom: 15px; margin-bottom: 30px;">
                    <h2 style="font-size: 24px; font-weight: 900; margin: 0; text-transform: uppercase;">KESIMPULAN & ARAH STRATEGIS</h2>
                </div>
                <div style="margin-bottom: 40px;">
                    <h4 style="color: #2563eb; text-transform: uppercase; font-size: 14px; margin-bottom: 15px;">Analisis Strategis Penjualan:</h4>
                    <p style="font-size: 14px; line-height: 1.8; color: #334155; text-align: justify;" x-text="swotResult?.conclusion"></p>
                </div>
                <div style="background: #1e293b; color: white; padding: 30px; border-radius: 15px;">
                    <h4 style="color: #60a5fa; text-transform: uppercase; font-size: 12px; margin: 0 0 10px 0; letter-spacing: 2px;">Main Action Plan:</h4>
                    <p style="font-size: 16px; font-weight: bold; margin: 0; line-height: 1.5;" x-text="swotResult?.recommendation"></p>
                </div>
            </div>
        </div>

        {{-- 5. MODAL NOTIFIKASI (Pengganti Alert) --}}
        <template x-teleport="body">
            <div x-show="notification.show" class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak x-transition>
                <div class="bg-white w-full max-w-sm rounded-[2.5rem] p-8 shadow-2xl text-center border border-slate-100">
                    <div :class="{
                        'bg-amber-50 text-amber-500': notification.type === 'warning',
                        'bg-red-50 text-red-500': notification.type === 'error',
                        'bg-blue-50 text-blue-500': notification.type === 'info'
                    }" class="w-20 h-20 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-6">
                        <i class="fa-solid" :class="{
                            'fa-triangle-exclamation': notification.type === 'warning',
                            'fa-circle-xmark': notification.type === 'error',
                            'fa-circle-info': notification.type === 'info'
                        }"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2" x-text="notification.title"></h3>
                    <p class="text-slate-500 text-sm font-medium mb-8 leading-relaxed" x-text="notification.message"></p>
                    <button @click="notification.show = false" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-600 transition-all">Tutup</button>
                </div>
            </div>
        </template>

        {{-- 6. MODAL RESET (DENGAN PESAN CUSTOM) --}}
        <template x-teleport="body">
            <div x-show="showResetModal" class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md" x-cloak x-transition>
                <div @click.away="showResetModal = false" class="bg-white w-full max-w-md rounded-[3rem] p-10 text-center shadow-2xl">
                    <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-6">
                        <i class="fa-solid fa-rotate-left"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Mulai Analisis Baru?</h3>
                    <p class="text-slate-500 mb-8 font-medium leading-relaxed">
                        Anda akan mereset analisis ini. Seluruh data progres akan dihapus, <span class="text-red-600 font-bold underline">pastikan Anda sudah mendownload laporan PDFnya</span> sebelum melanjutkan.
                    </p>
                    <div class="flex gap-4 mt-8">
                        <button @click="showResetModal = false" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">Batal</button>
                        <button @click="resetAnalysis()" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-700 transition-all">Ya, Reset Sekarang</button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        .text-left { text-align: left !important; }
        /* Smooth transitions */
        .transition-all { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</x-user-layout>