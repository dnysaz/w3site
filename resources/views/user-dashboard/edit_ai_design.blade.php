<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup.min.js"></script>
<script src="https://unpkg.com/split.js/dist/split.min.js"></script>

<style>
    /* Reset & Base */
    .editor-container { 
        position: relative; 
        width: 100%; 
        height: 100%; 
        background: #1d1f21; 
        overflow: hidden;
    }
    
    /* Layer Teks & Layer Warna harus identik font & paddingnya */
    #editing-area, #highlighting-area {
        margin: 0; 
        padding: 60px 30px 30px 30px; 
        width: 100%; 
        height: 100%;
        /* Pastikan font sama persis di semua browser */
        font-family: 'JetBrains Mono', monospace;
        font-size: 14px;
        line-height: 21px; /* Gunakan PX agar presisi, jangan 1.5 */
        tab-size: 4;
        -moz-tab-size: 4;
        white-space: pre; 
        word-wrap: normal;
        position: absolute; 
        top: 0; 
        left: 0;
        border: none;
        box-sizing: border-box; /* Sangat penting agar padding tidak menambah ukuran */
    }

    /* Textarea di ATAS (Z-index 2) dan TRANSPARAN */
    #editing-area {
        z-index: 2;
        color: transparent; 
        background: transparent;
        caret-color: #3b82f6; /* Kursor biru agar lebih modern */
        resize: none; 
        outline: none; 
        overflow: auto;
        /* Hilangkan smoothing khusus MacOS yang sering bikin meleset 1px */
        -webkit-text-fill-color: transparent;
    }

    /* Prism di BAWAH (Z-index 1) */
    #highlighting-area { 
        z-index: 1; 
        pointer-events: none; 
        background: #1d1f21;
        overflow: hidden; /* Scroll dikontrol oleh textarea */
        display: block;
    }
    
    /* Reset default Prism style agar tidak merusak alignment */
    #highlighting-area code[class*="language-"], 
    #highlighting-area pre[class*="language-"] {
        padding: 0 !important;
        margin: 0 !important;
        background: transparent !important;
        font-family: inherit !important;
        font-size: inherit !important;
        line-height: inherit !important;
    }

    /* Split.js Gutter */
    .gutter { 
        background-color: #0b1120; 
        cursor: col-resize; 
        z-index: 30; 
        position: relative;
    }
    .gutter:after {
        content: "";
        position: absolute;
        top: 0; left: 50%;
        width: 1px; height: 100%;
        background: rgba(255,255,255,0.05);
    }
    .gutter:hover { background-color: #1e293b; }
    .gutter.gutter-horizontal { width: 6px; }

    /* Scrollbar Styling */
    #editing-area::-webkit-scrollbar { width: 8px; height: 8px; }
    #editing-area::-webkit-scrollbar-track { background: #1d1f21; }
    #editing-area::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
    #editing-area::-webkit-scrollbar-thumb:hover { background: #475569; }
</style>

<x-user-layout>
    <div x-data="{ 
        code: `{{ addslashes($content) }}`,
        isSaving: false,
        modal: { show: false, type: 'success', title: '', message: '' },

        openModal(type, title, message) {
            this.modal.type = type;
            this.modal.title = title;
            this.modal.message = message;
            this.modal.show = true;
        },

        save() {
            this.isSaving = true;
            fetch('{{ route('ai.update', $design->file_name) }}', { 
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ html: this.code })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    this.openModal('success', 'Update Berhasil', 'Perubahan telah disimpan.');
                    setTimeout(() => window.location.href = '{{ route('my.ai.design') }}', 1500);
                }
            })
            .finally(() => this.isSaving = false);
        }
    }" class="fixed inset-0 flex flex-col bg-[#0b1120] z-[60]">
        
        <div class="h-14 border-b border-slate-800 bg-[#0b1120] flex items-center justify-between px-6 z-50">
            <div class="flex items-center gap-4">
                <a href="{{ route('my.ai.design') }}" class="text-slate-400 hover:text-white"><i class="fa-solid fa-arrow-left"></i></a>
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Editor: {{ $design->title }}</span>
            </div>
            <span class="text-[9px] font-bold text-slate-600 bg-slate-900 px-3 py-1 rounded border border-slate-800 uppercase">Live Editor</span>
        </div>

        <div class="flex-1 flex overflow-hidden bg-[#0f172a]" id="split-container">
            <div id="split-left" class="h-full relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-10 bg-slate-900 z-30 flex items-center px-4 border-b border-white/5 shadow-md">
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em]">Source Code</span>
                </div>
                
                <div class="editor-container">
                    <pre id="highlighting-area" class="language-html"><code id="highlighting-content" class="language-html" x-text="code"></code></pre>
                    
                    <textarea id="editing-area" 
                              x-model="code" 
                              @input="Prism.highlightElement(document.getElementById('highlighting-content'))"
                              @keydown.tab.prevent="code = code + '\t'"
                              spellcheck="false"
                              class="custom-scrollbar"></textarea>
                </div>
            </div>
        
            <div id="split-right" class="h-full bg-white relative">
                <div class="absolute top-0 left-0 right-0 h-10 bg-slate-50 flex items-center px-4 border-b border-slate-200 z-30 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Live Preview</span>
                </div>
                <iframe :srcdoc="code" class="w-full h-full border-none pt-10"></iframe>
            </div>
        </div>

        <div class="h-20 border-t border-slate-800 bg-[#0b1120] flex items-center justify-end px-10 gap-4 z-50">
            <button @click="save()" :disabled="isSaving"
                    class="px-10 py-4 bg-blue-600 text-white rounded-xl font-black text-[11px] uppercase tracking-widest hover:bg-blue-500 active:scale-95 flex items-center gap-3">
                <i x-show="!isSaving" class="fa-solid fa-cloud-arrow-up"></i>
                <i x-show="isSaving" class="fa-solid fa-circle-notch animate-spin"></i>
                <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
            </button>
        </div>

        <template x-teleport="body">
            <div x-show="modal.show" class="fixed inset-0 z-[999] flex items-center justify-center p-4 backdrop-blur-md bg-slate-900/60" style="display: none;">
                <div class="bg-white w-full max-w-[350px] rounded-[2rem] p-10 text-center shadow-2xl transition-all">
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2" x-text="modal.title"></h3>
                    <p class="text-slate-500 text-sm mb-6" x-text="modal.message"></p>
                    <button @click="modal.show = false" class="w-full py-3 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-widest">Lanjutkan</button>
                </div>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Inisialisasi Split.js
            Split(['#split-left', '#split-right'], {
                sizes: [50, 50],
                minSize: 200,
                gutterSize: 6,
            });

            const area = document.getElementById('editing-area');
            const high = document.getElementById('highlighting-area');

            // Sinkronisasi Scroll Sempurna
            area.addEventListener('scroll', () => {
                high.scrollTop = area.scrollTop;
                high.scrollLeft = area.scrollLeft;
            });

            // Jalankan highlight awal
            setTimeout(() => {
                Prism.highlightElement(document.getElementById('highlighting-content'));
            }, 500);
        });
    </script>
</x-user-layout>