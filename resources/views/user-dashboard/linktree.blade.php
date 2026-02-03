<x-user-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <div x-data="linktreeStudio()" class="relative min-h-screen bg-slate-50 font-sans selection:bg-blue-100">

        {{-- 1. HEADER --}}
        <header class="mb-12 text-center" x-show="!isGenerating && !showResult" x-transition>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-full mb-4 border border-blue-100">
                <i class="fa-solid fa-link text-[10px]"></i>
                <span class="text-[9px] font-black uppercase tracking-[0.2em]">AI Linktree Builder v.2026</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-[1000] text-slate-900 tracking-tighter leading-none mb-4">Link <span class="text-blue-600">Bio Creator</span></h1>
            <p class="text-slate-400 font-medium max-w-lg mx-auto text-sm" x-show="!isCreating">Kelola dan rancang halaman bio link unik Anda dengan bantuan AI.</p>
        </header>

        <div :class="showResult ? 'max-w-full' : 'max-w-6xl mx-auto px-4 md:px-0'">
            
            {{-- 2. DASHBOARD LIST --}}
            <template x-if="!isCreating && !isGenerating && !showResult">
                <div class="space-y-6 animate-in fade-in duration-500 text-left">
                    
                    {{-- Slim Stats Header --}}
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 px-2">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Bio Links</h2>
                            <div class="flex items-center gap-4 mt-2">
                                {{-- Status Kuota Sederhana --}}
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full" :class="savedLinks.length >= siteLimit ? 'bg-red-500' : 'bg-blue-500'"></div>
                                    <p class="text-[16px] font-medium text-slate-600">
                                        <span class="font-bold text-slate-900" x-text="savedLinks.length"></span> dari <span x-text="siteLimit"></span> slot terpakai
                                    </p>
                                </div>
                                <div class="h-4 w-[1px] bg-slate-200"></div>
                                <p class="text-[16px] font-medium text-slate-500">
                                    Paket: <span class="text-blue-600 font-bold" x-text="userPackage === 0 ? 'Gratis' : (userPackage === 1 ? 'Pemula' : 'Pro')"></span>
                                </p>
                            </div>
                        </div>

                        <button @click="createNewSite()" 
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-sm active:scale-95"
                            :class="savedLinks.length >= siteLimit ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-blue-600 text-white hover:bg-blue-700'">
                            <i class="fa-solid" :class="savedLinks.length >= siteLimit ? 'fa-crown' : 'fa-plus'"></i>
                            <span x-text="savedLinks.length >= siteLimit ? 'Upgrade Kuota' : 'Buat Link Baru'"></span>
                        </button>
                    </div>

                    {{-- List Linktrees --}}
                    <div class="grid grid-cols-1 gap-3">
                        <template x-if="savedLinks.length === 0">
                            <div class="py-20 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                                    <i class="fa-solid fa-link-slash text-slate-300 text-xl"></i>
                                </div>
                                <p class="text-slate-500 font-medium">Belum ada biolink yang dibuat.</p>
                            </div>
                        </template>

                        <template x-for="item in savedLinks" :key="item.id">
                            <div class="group bg-white rounded-2xl p-4 border border-slate-200 hover:border-blue-400 hover:shadow-md transition-all">
                                <div class="flex flex-col md:flex-row md:items-center gap-4">
                                    
                                    {{-- Title & Slug --}}
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-bold text-slate-900 truncate" x-text="item.title"></h3>
                                        <div class="flex items-center gap-2 mt-1">
                                            <a :href="'/' + item.slug" target="_blank" class="text-xs font-medium text-blue-600 hover:underline flex items-center gap-1">
                                                <span>w3site.id/</span><span x-text="item.slug"></span>
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                            </a>
                                            <button @click="navigator.clipboard.writeText(window.location.origin + '/' + item.slug); showAlert('Copied!', 'URL disalin', 'info')" 
                                                    class="p-1 text-slate-400 hover:text-slate-600 transition-colors">
                                                <i class="fa-solid fa-copy text-[10px]"></i>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Quick Stats --}}
                                    <div class="flex items-center gap-6 px-4 py-2 bg-slate-50 rounded-xl border border-slate-100">
                                        <div class="text-left">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">Views</p>
                                            <p class="text-sm font-bold text-slate-900" x-text="item.views_count || 0"></p>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">Created</p>
                                            <p class="text-sm font-bold text-slate-900" x-text="new Date(item.created_at).toLocaleDateString('id-ID')"></p>
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex items-center gap-2">
                                        <button @click="openFullView(item)" class="h-10 px-4 bg-slate-900 text-white rounded-lg font-bold text-xs hover:bg-slate-800 transition-all flex items-center gap-2">
                                            <i class="fa-solid fa-eye text-[10px]"></i> View
                                        </button>
                                        <button @click="viewDesign(item)" class="h-10 w-10 flex items-center justify-center bg-white border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-all shadow-sm">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>
                                        <button @click.stop="confirmDelete(item.id)" class="h-10 w-10 flex items-center justify-center bg-white border border-slate-200 text-red-500 rounded-lg hover:bg-red-50 hover:border-red-100 transition-all shadow-sm">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- 3. FORM EDITOR --}}
            <template x-if="isCreating && !isGenerating && !showResult">
                <div class="max-w-5xl mx-auto animate-in slide-in-from-bottom-8 duration-500 pb-20">
                    
                    {{-- Navigation Header --}}
                    <div class="flex items-center gap-4 mb-10">
                        <button @click="isCreating = false" class="w-10 h-10 rounded-xl bg-white border border-slate-100 text-slate-400 flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all shadow-sm">
                            <i class="fa-solid fa-arrow-left text-xs"></i>
                        </button>
                        <div>
                            <h2 class="text-xl font-[1000] text-slate-800 tracking-tighter uppercase leading-none">Studio Editor</h2>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1.5">Rancang bio link unik Anda</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        {{-- 01. Identitas Profil --}}
                        <div class="bg-white p-6 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
                            <div class="flex items-center gap-3 mb-8">
                                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-black">01</span>
                                <label class="text-[10px] font-black text-slate-800 uppercase tracking-[0.2em]">Identitas Profil</label>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <p class="text-[9px] font-black text-slate-400 uppercase ml-2">Nama Profil</p>
                                    <input 
                                        x-model="formData.profileName" 
                                        @input="checkNameAvailability()"
                                        type="text" 
                                        placeholder="ex: John Doe" 
                                        :class="isNameTaken ? 'border-red-500 bg-red-50' : 'border-transparent bg-slate-50'"
                                        class="w-full px-6 py-4 border-2 rounded-2xl focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-sm">
                                    
                                    {{-- Pesan Validasi --}}
                                    <template x-if="isNameTaken">
                                        <p class="text-[10px] font-bold text-red-500 ml-2 mt-1 animate-pulse">
                                            <i class="fa-solid fa-circle-exclamation mr-1"></i> Nama ini sudah digunakan, pilih nama lain.
                                        </p>
                                    </template>
                                </div>
                                
                                <div class="space-y-2">
                                    <p class="text-[9px] font-black text-slate-400 uppercase ml-2">URL Foto Profil</p>
                                    <input x-model="formData.profileImage" type="text" placeholder="https://..." class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-sm">
                                </div>
                            </div>
                        </div>

                        {{-- 02. Daftar Link --}}
                        <div class="bg-white p-6 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-sm">
                            <div class="flex justify-between items-center mb-8">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-black">02</span>
                                    <label class="text-[10px] font-black text-slate-800 uppercase tracking-[0.2em]">Daftar Link Sosial</label>
                                </div>
                                <button @click="addLink()" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all">
                                    <i class="fa-solid fa-plus mr-1"></i> Add Link
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(link, index) in formData.links" :key="index">
                                    <div class="flex flex-col md:flex-row gap-3 p-2 bg-slate-50 rounded-[1.8rem] border border-slate-100 group transition-all hover:border-blue-200">
                                        <div class="flex-1 flex flex-col md:flex-row gap-2 p-2">
                                            <input x-model="link.label" type="text" placeholder="Label" class="flex-1 px-6 py-3 bg-white rounded-2xl font-bold text-[12px] outline-none border border-transparent focus:border-blue-500 transition-all">
                                            <input x-model="link.url" type="text" placeholder="https://..." class="flex-[2] px-6 py-3 bg-white rounded-2xl font-bold text-[12px] outline-none border border-transparent focus:border-blue-500 transition-all text-blue-500">
                                        </div>
                                        <button @click="removeLink(index)" class="w-12 h-12 flex items-center justify-center text-slate-300 hover:text-red-500 transition-colors self-end md:self-center mr-2">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- 03. AI Concept --}}
                        <div class="bg-slate-900 rounded-[2.5rem] p-6 md:p-10 text-white shadow-2xl shadow-slate-200">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center">
                                    <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
                                </div>
                                <label class="text-[10px] font-black text-blue-400 uppercase tracking-[0.2em]">03. AI Design Concept</label>
                            </div>
                            
                            <textarea x-model="formData.designConcept" 
                                class="w-full h-40 bg-white/5 border border-white/10 rounded-[1.5rem] p-6 text-sm font-medium outline-none focus:border-blue-500 transition-all resize-none mb-8 text-slate-200 leading-relaxed" 
                                placeholder="Contoh: Tema profesional dengan nuansa warna biru gelap, font minimalis, dan animasi halus saat scroll..."></textarea>

                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div class="flex items-center gap-4">
                                    <div class="flex -space-x-2">
                                        <div class="w-7 h-7 rounded-full border-2 border-slate-900 bg-blue-500 flex items-center justify-center text-[8px] font-bold text-white">1</div>
                                        <div class="w-7 h-7 rounded-full border-2 border-slate-900 bg-purple-500 flex items-center justify-center text-[8px] font-bold text-white">2</div>
                                        <div class="w-7 h-7 rounded-full border-2 border-slate-900 bg-pink-500 flex items-center justify-center text-[8px] font-bold text-white">3</div>
                                    </div>
                                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">3 steps to be awesome</p>
                                </div>

                                <button @click="generateLinktree()" class="w-full md:w-auto px-12 py-5 bg-blue-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] shadow-xl shadow-blue-500/20 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3">
                                    Generate Site <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            {{-- 4. LOADER --}}
            <div x-show="isGenerating" class="py-24 text-center" x-cloak>
                <div class="w-20 h-20 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin mx-auto mb-8"></div>
                <h2 class="text-2xl font-[1000] text-slate-900 uppercase tracking-widest animate-pulse">AI is Building Your Site...</h2>
            </div>

            {{-- 5. FULLSCREEN STUDIO MODE (SIMPLIFIED) --}}
            <div x-show="showResult" class="fixed inset-0 z-[100] bg-white flex flex-col overflow-hidden shadow-2xl" x-cloak>
                {{-- TOP BAR --}}
                <div class="flex items-center justify-between px-4 md:px-6 py-3 bg-white border-b border-slate-100 shrink-0">
                    <div class="flex items-center gap-4">
                        <button @click="showResult = false; isCreating = false; isEditingHTML = false" class="flex items-center gap-2 text-slate-400 hover:text-slate-900 transition-all group">
                            <i class="fa-solid fa-chevron-left text-xs group-hover:-translate-x-1 transition-transform"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest hidden md:inline">Exit Studio</span>
                        </button>
                        <div class="h-4 w-[1px] bg-slate-200"></div>
                        <div class="flex md:hidden bg-slate-100 p-1 rounded-lg">
                            <button @click="isEditingHTML = true" :class="isEditingHTML ? 'bg-white shadow-sm text-blue-600' : 'text-slate-400'" class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest">Code</button>
                            <button @click="isEditingHTML = false" :class="!isEditingHTML ? 'bg-white shadow-sm text-blue-600' : 'text-slate-400'" class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest">Preview</button>
                        </div>
                        <h2 class="hidden md:block text-[10px] font-black text-slate-800 uppercase tracking-[0.2em]" x-text="formData.profileName || 'Untitled Project'"></h2>
                    </div>

                    <div class="flex items-center gap-2">
                        <button @click="isEditingHTML = !isEditingHTML" class="hidden md:flex px-4 py-2 rounded-lg font-black text-[9px] uppercase tracking-widest transition-all border border-slate-100" :class="isEditingHTML ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-500'">
                            <i class="fa-solid fa-code mr-2"></i> Toggle Editor
                        </button>
                        <button @click="saveToDatabase()" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-black text-[9px] md:text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100 active:scale-95 transition-all">
                            Save Design
                        </button>
                    </div>
                </div>

                {{-- STUDIO CONTENT --}}
                <div class="flex-1 flex overflow-hidden">
                    {{-- HTML EDITOR (Default Textarea) --}}
                    <div x-show="isEditingHTML" class="w-full md:w-3/4 lg:w-[65%] bg-slate-900 flex flex-col border-r border-slate-800" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0">
                        <div class="flex items-center justify-between px-6 py-2 bg-slate-800/50 shrink-0">
                            <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Source Code</span>
                            <div class="flex gap-1">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                            </div>
                        </div>
                        <textarea 
                            x-model="currentResult.full_html" 
                            class="flex-1 w-full bg-[#1d1f21] border-none text-blue-200 font-mono text-sm leading-relaxed outline-none resize-none p-8 selection:bg-blue-500/30"
                            spellcheck="false"
                            wrap="off"
                            placeholder="Paste your code here..."></textarea>
                    </div>

                    {{-- PREVIEW AREA --}}
                    <div x-show="!isEditingHTML || (isEditingHTML && window.innerWidth >= 768)" class="flex-1 bg-slate-100 relative flex items-center justify-center overflow-hidden">
                        <div class="w-full h-full max-w-[480px] md:max-w-none mx-auto bg-white shadow-inner relative">
                             <iframe class="w-full h-full" :srcdoc="currentResult.full_html" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL PESAN & KONFIRMASI --}}
        <template x-teleport="body">
            <div x-show="messageModal.show" class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md" x-cloak x-transition>
                <div @click.away="messageModal.show = false" class="bg-white w-full max-w-md rounded-[3rem] p-10 text-center shadow-2xl">
                    <div :class="messageModal.type === 'error' ? 'bg-red-50 text-red-500' : 'bg-blue-50 text-blue-500'" class="w-20 h-20 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-6">
                        <i :class="messageModal.type === 'error' ? 'fa-solid fa-trash-can' : 'fa-solid fa-circle-info'"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2 tracking-tighter" x-text="messageModal.title"></h3>
                    <p class="text-slate-500 mb-8 font-medium text-sm leading-relaxed" x-text="messageModal.message"></p>
                    <div class="flex flex-col gap-3">
                        <template x-if="messageModal.title.includes('Konfirmasi')">
                            <button @click="deleteItem()" class="w-full py-4 bg-red-500 text-white rounded-2xl font-black text-[10px] shadow-xl hover:bg-red-600 transition-all">Hapus Permanen</button>
                        </template>
                        <button @click="messageModal.show = false; deleteId = null" :class="messageModal.title.includes('Konfirmasi') ? 'bg-slate-100 text-slate-500' : 'bg-blue-600 text-white'" class="w-full py-4 rounded-2xl font-black text-[10px] uppercase transition-all hover:opacity-90">
                            <span x-text="messageModal.title.includes('Konfirmasi') ? 'Batalkan' : 'Tutup'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        function linktreeStudio() {
            return {
                isCreating: false,
                isGenerating: false,
                showResult: false,
                isEditingHTML: false,
                deleteId: null,
                
                // Integrasi Data Database
                userPackage: {{ auth()->user()->package }}, 
                savedLinks: @json($myLinks),
                
                messageModal: { show: false, title: '', message: '', type: 'info' },
                currentResult: { id: null, full_html: '' },
                isNameTaken: false,
                formData: {
                    profileName: '',
                    profileImage: '',
                    designConcept: '',
                    links: [{ label: 'Instagram', url: '' }, { label: 'WhatsApp', url: '' }]
                },
    
                get siteLimit() {
                    const limits = { 0: 10, 1: 100, 2: 1000 };
                    return limits[this.userPackage] ?? 10;
                },
    
                init() {
                    this.$watch('showResult', value => {
                        document.body.style.overflow = value ? 'hidden' : 'auto';
                    });
                },
    
                showAlert(title, message, type = 'info') {
                    this.messageModal = { show: true, title, message, type };
                },
    
                createNewSite() {
                    if (this.savedLinks.length >= this.siteLimit) {
                        window.location.href = "{{ route('pricing') }}";
                        return;
                    }
                    this.resetForm();
                    this.isCreating = true;
                },
    
                prepareData(item) {
                    this.currentResult.id = item.id;
                    this.currentResult.full_html = item.html_content;
                    this.formData.profileName = item.title;
                    this.formData.profileImage = item.image_url;
                    this.formData.designConcept = item.design_concept;
                    if(item.links_json) {
                        try {
                            this.formData.links = typeof item.links_json === 'string' 
                                ? JSON.parse(item.links_json) 
                                : item.links_json;
                        } catch (e) { console.error(e); }
                    }
                },
    
                viewDesign(item) {
                    this.prepareData(item);
                    this.isEditingHTML = true; 
                    this.showResult = true;
                },
    
                openFullView(item) {
                    this.prepareData(item);
                    this.isEditingHTML = false; 
                    this.showResult = true;
                },
    
                resetForm() {
                    this.currentResult.id = null;
                    this.currentResult.full_html = '';
                    this.isNameTaken = false;
                    this.formData = { 
                        profileName: '', 
                        profileImage: '', 
                        designConcept: '', 
                        links: [{ label: 'Instagram', url: '' }, { label: 'WhatsApp', url: '' }] 
                    };
                    this.isEditingHTML = false;
                },
    
                addLink() { 
                    this.formData.links.push({ label: '', url: '' }); 
                },
    
                removeLink(index) { 
                    if(this.formData.links.length > 1) this.formData.links.splice(index, 1); 
                },
    
                checkNameAvailability() {
                    if (!this.formData.profileName) {
                        this.isNameTaken = false;
                        return;
                    }
                    const inputName = this.formData.profileName.trim().toLowerCase();
                    this.isNameTaken = this.savedLinks.some(link => {
                        if (this.currentResult.id && link.id === this.currentResult.id) return false;
                        return link.title.trim().toLowerCase() === inputName;
                    });
                },
    
                generateLinktree() {
                    if(this.isNameTaken) {
                        this.showAlert('Nama Digunakan', 'Silahkan gunakan nama lain sebelum generate.', 'error');
                        return;
                    }
                    if(!this.formData.profileName || !this.formData.designConcept) {
                        this.showAlert('Data Kurang', 'Lengkapi profil dan konsep desain Anda.', 'error');
                        return;
                    }
                    this.isGenerating = true;
                    this.isCreating = false;
                    fetch('{{ route('ai.linktree.generate') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(this.formData)
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.currentResult.full_html = data.full_html;
                        this.isGenerating = false;
                        this.showResult = true;
                    })
                    .catch(err => {
                        this.isGenerating = false;
                        this.showAlert('Gagal', 'Koneksi AI Terputus.', 'error');
                    });
                },
    
                saveToDatabase() {
                    if (!this.currentResult.full_html) {
                        this.showAlert('Gagal', 'Tidak ada konten HTML untuk disimpan.', 'error');
                        return;
                    }
    
                    fetch('{{ route('ai.linktree.store') }}', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            id: this.currentResult.id,
                            profileName: this.formData.profileName,
                            profileImage: this.formData.profileImage,
                            links: this.formData.links,
                            designConcept: this.formData.designConcept,
                            html_content: this.currentResult.full_html 
                        })
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.error || data.message || 'Server Error');
                        return data;
                    })
                    .then(data => {
                        if(data.success) {
                            this.showAlert('Sukses Disimpan', `Halaman aktif di: ${data.url}`, 'info');
                            setTimeout(() => window.location.reload(), 2000);
                        }
                    })
                    .catch(err => {
                        console.error('Save Error:', err);
                        this.showAlert('Gagal Simpan', err.message, 'error');
                    });
                },
    
                confirmDelete(id) {
                    this.deleteId = id;
                    this.showAlert('Konfirmasi Hapus', 'Hapus desain ini secara permanen?', 'error');
                },
    
                deleteItem() {
                    if(!this.deleteId) return;
                    fetch(`/dashboard/ai-linktree/delete/${this.deleteId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(() => window.location.reload())
                    .catch(err => console.error('Delete Error:', err));
                },
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        textarea {
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace !important;
            font-size: 14px !important;
            line-height: 1.6 !important;
            scrollbar-width: thin;
            scrollbar-color: #334155 transparent;
        }

        textarea::-webkit-scrollbar { width: 6px; height: 6px; }
        textarea::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        textarea::-webkit-scrollbar-track { background: transparent; }
    </style>
</x-user-layout>