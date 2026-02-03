<x-user-layout>
    <script id="articles-data" type="application/json">
        {!! json_encode($articles->map(function($a) {
            return [
                'id' => $a->id,
                'title' => e($a->title),
                'seo_score' => $a->seo_score,
                'date' => $a->created_at->format('d M')
            ];
        })) !!}
    </script>

    <div x-data="blogStudio()" class="relative">

        {{-- 1. HEADER --}}
        <header class="mb-12 px-6 text-center" x-show="modalStep === 'idle'" x-transition x-cloak>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-full mb-4 border border-blue-100">
                <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
                <span class="text-[9px] font-black uppercase tracking-[0.2em]">AI Writer Article Studio</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-[1000] text-slate-900 tracking-tighter leading-none mb-4">AI <span class="text-blue-600">Article </span> Creator</h1>
            <p class="text-slate-400 font-medium max-w-lg mx-auto text-sm">Hasilkan artikel blog berkualitas tinggi dalam hitungan detik.</p>
        </header>

        {{-- CONTAINER UTAMA (Ideal Width) --}}
        <div class="max-w-6xl mx-auto px-4 md:px-6">
            
            {{-- 2. GRID WORKSPACE (DASHBOARD) --}}
            <div x-show="modalStep === 'idle'" x-transition x-cloak>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
                    {{-- Tombol New Draft --}}
                    <div @click="modalStep = 'input'" class="group cursor-pointer space-y-3">
                        <div class="aspect-[3/4] bg-white border-2 border-dashed border-slate-200 rounded-[1rem] flex flex-col items-center justify-center transition-all group-hover:border-blue-500 group-hover:bg-blue-50/30 group-hover:shadow-xl group-hover:shadow-blue-100/50">
                            <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all transform group-hover:scale-110">
                                <i class="fa-solid fa-plus text-lg"></i>
                            </div>
                        </div>
                        <p class="text-[10px] font-black text-center tracking-tighter text-slate-600 group-hover:text-blue-600">New Article</p>
                    </div>

                    {{-- JEJERAN HASIL DATABASE --}}
                    <template x-for="article in savedArticles" :key="article.id">
                        <div class="group cursor-pointer animate-in fade-in zoom-in duration-300 relative">
                            <div @click="openArticle(article)" 
                                class="aspect-[3/4] bg-white border border-slate-100 rounded-[1rem] p-6 shadow-sm group-hover:shadow-xl group-hover:shadow-slate-200 transition-all flex flex-col relative overflow-hidden">
                                
                                {{-- 1. JUDUL DI ATAS --}}
                                <div class="mb-4">
                                    <h3 class="text-[11px] font-[1000] text-slate-800 leading-tight line-clamp-3 tracking-tighter" x-text="article.title"></h3>
                                    <p class="text-[8px] font-bold text-slate-300 mt-2 uppercase tracking-widest" x-text="article.date"></p>
                                </div>

                                {{-- 2. VISUAL DEKORASI (TENGAH) --}}
                                <div class="flex-1 opacity-5 group-hover:opacity-10 transition-opacity mt-2">
                                    <div class="h-1.5 w-full bg-slate-900 rounded-full mb-2"></div>
                                    <div class="h-1.5 w-full bg-slate-900 rounded-full mb-2"></div>
                                    <div class="h-1.5 w-3/4 bg-slate-900 rounded-full"></div>
                                </div>

                                {{-- 3. FOOTER KARTU --}}
                                <div class="flex items-end justify-between mt-4">
                                    {{-- SEO DI KIRI BAWAH --}}
                                    <div class="flex flex-col">
                                        <span class="text-[7px] font-black text-slate-300 uppercase tracking-widest mb-1">SEO Score</span>
                                        <span class="text-[10px] font-black text-green-600 bg-green-50 px-2 py-1 rounded-lg w-fit" x-text="article.seo_score + '%'"></span>
                                    </div>

                                    {{-- BUTTON DELETE DI KANAN BAWAH --}}
                                    <button @click.stop="confirmDelete(article.id)" 
                                        class="w-10 h-10 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all transform active:scale-90">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 3. FORM INPUT --}}
            <template x-if="modalStep === 'input'">
                <div class="bg-white p-8 md:p-12 rounded-[2.5rem] border border-slate-100 relative overflow-hidden text-left shadow-sm animate-in slide-in-from-bottom-4 duration-500">
                    <div class="mb-10 flex justify-between items-center">
                        <h2 class="text-2xl font-[1000] text-slate-900 tracking-tighter">Article <span class="text-blue-600"> Deatails</span></h2>
                        <button @click="modalStep = 'idle'" class="text-slate-300 hover:text-red-500 transition-colors"><i class="fa-solid fa-circle-xmark text-2xl"></i></button>
                    </div>
                    
                    <div class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Ide Konten</label>
                                <input x-model="formData.blogTitle" type="text" placeholder="Misal: Strategi Digital Marketing" 
                                    class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-slate-800">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Cover Image URL</label>
                                <input x-model="formData.imageUrl" type="text" placeholder="https://..." 
                                    class="w-full px-6 py-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-slate-800">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Brief Deskripsi / Outline</label>
                            <textarea x-model="formData.description" class="w-full min-h-[180px] px-6 py-5 bg-slate-50 border-2 border-transparent rounded-[1.5rem] focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-slate-800 resize-none leading-relaxed" 
                                placeholder="Apa inti dari artikel yang ingin Anda buat?"></textarea>
                        </div>
                    </div>
                    <div class="mt-10 pt-8 border-t border-slate-50 flex justify-end">
                        <button @click="generateAI()" class="w-full md:w-auto px-12 py-5 bg-blue-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.2em] shadow-xl hover:bg-slate-900 transition-all active:scale-95 shadow-blue-200">
                            Generate Article
                        </button>
                    </div>
                </div>
            </template>

            {{-- 4. LOADER --}}
            <div x-show="modalStep === 'loading'" class="py-24 text-center" x-cloak>
                <div class="w-20 h-20 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin mx-auto mb-8"></div>
                <h2 class="text-2xl font-[1000] text-slate-900 uppercase tracking-widest animate-pulse">AI is Writing...</h2>
                <p class="text-slate-400 font-bold text-xs mt-2 uppercase tracking-widest" x-text="progress + '%'"></p>
            </div>

            {{-- 5. RESULT PREVIEW (BLOG STYLE) --}}
            <div x-show="modalStep === 'result'" x-transition class="animate-in fade-in duration-700" x-cloak>
                
                {{-- Floating Action Bar --}}
                <div class="sticky top-6 z-40 bg-white/80 backdrop-blur-lg border border-slate-100 p-4 rounded-3xl shadow-xl mb-10 flex justify-between items-center">
                    <button @click="modalStep = 'idle'" class="w-10 h-10 flex items-center justify-center bg-slate-100 rounded-full text-slate-500 hover:bg-slate-900 hover:text-white transition-all">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                    </button>
                    
                    <div class="flex gap-2">
                        <button @click="copyContent()" 
                            class="px-5 py-2.5 rounded-2xl font-black text-[9px] uppercase tracking-widest transition-all"
                            :class="isCopied ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                            <span x-show="!isCopied"><i class="fa-solid fa-copy mr-2"></i> Copy Content</span>
                            <span x-show="isCopied"><i class="fa-solid fa-check mr-2"></i> Copied!</span>
                        </button>

                        <template x-if="!currentResult.id">
                            <button @click="saveToDatabase()" class="px-6 py-2.5 bg-blue-600 text-white rounded-2xl font-black text-[9px] uppercase tracking-widest shadow-lg shadow-blue-100 hover:scale-105 active:scale-95 transition-all">
                                Save Article
                            </button>
                        </template>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] md:rounded-[4rem] border border-slate-50 shadow-sm overflow-hidden">
                    {{-- Blog Body --}}
                    <div class="p-8 md:p-20">
                        <article id="article-body" class="prose prose-slate max-w-none prose-headings:font-[1000] prose-headings:uppercase prose-headings:tracking-tighter prose-p:text-slate-600 prose-p:text-lg prose-p:leading-relaxed prose-img:rounded-[2rem]">
                            <div x-html="currentResult.content"></div>
                        </article>

                        {{-- Hashtags at Bottom --}}
                        <div class="mt-16 pt-10 border-t border-slate-50">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">Keywords & Hashtags</h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="tag in currentResult.hashtags">
                                    <span class="px-4 py-2 bg-slate-50 text-slate-500 rounded-xl text-[10px] font-bold border border-slate-100" x-text="'#' + tag"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL PESAN --}}
        <template x-teleport="body">
            <div x-show="messageModal.show" class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md" x-cloak x-transition>
                <div @click.away="messageModal.show = false" class="bg-white w-full max-w-sm rounded-[2.5rem] p-10 text-center shadow-2xl">
                    <div :class="messageModal.type === 'error' ? 'bg-red-50 text-red-500' : 'bg-blue-50 text-blue-500'" class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6">
                        <i :class="messageModal.type === 'error' ? 'fa-solid fa-circle-xmark' : 'fa-solid fa-circle-info'"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2" x-text="messageModal.title"></h3>
                    <p class="text-slate-400 mb-8 font-medium text-xs leading-relaxed" x-text="messageModal.message"></p>
                    <button @click="messageModal.show = false" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl">Saya Mengerti</button>
                </div>
            </div>
        </template>

        {{-- MODAL RESET SESSION --}}
        <template x-teleport="body">
            <div x-show="showResetModal" class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md" x-cloak x-transition>
                <div @click.away="showResetModal = false" class="bg-white w-full max-w-sm rounded-[2.5rem] p-10 text-center shadow-2xl">
                    <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2">Hancurkan Sesi?</h3>
                    <p class="text-slate-400 mb-8 font-medium text-xs">File session fisik akan dihapus secara permanen.</p>
                    <div class="flex gap-4">
                        <button @click="showResetModal = false" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase">Batal</button>
                        <button @click="hancurkanSesi()" class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-black text-[10px] uppercase shadow-xl">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </template>

        {{-- MODAL KONFIRMASI HAPUS --}}
        <template x-teleport="body">
            <div x-show="showDeleteModal" 
                class="fixed inset-0 z-[1001] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" 
                x-cloak x-transition>
                
                <div @click.away="showDeleteModal = false" 
                    class="bg-white w-full max-w-sm rounded-[2.5rem] p-8 text-center shadow-2xl animate-in zoom-in duration-300">
                    
                    <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-6">
                        <i class="fa-solid fa-trash-can"></i>
                    </div>
                    
                    <h3 class="text-xl font-[1000] text-slate-900 uppercase tracking-tighter mb-2">Hapus Artikel?</h3>
                    <p class="text-slate-400 mb-8 font-medium text-xs leading-relaxed">
                        Tindakan ini tidak dapat dibatalkan. Artikel akan dihapus secara permanen dari database Anda.
                    </p>
                    
                    <div class="flex gap-3">
                        <button @click="showDeleteModal = false" 
                            class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                        <button @click="deleteArticle()" 
                            class="flex-1 py-4 bg-red-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-red-100 hover:bg-red-700 transition-all">
                            Ya, Hapus
                        </button>
                    </div>
                </div>
            </div>
        </template>

    </div>

    <script>
        function blogStudio() {
            return {
                modalStep: 'idle',
                showResetModal: false,
                isCopied: false,
                progress: 0,
                messageModal: { show: false, title: '', message: '', type: 'info' },
                formData: { blogTitle: '', imageUrl: '', description: '' },
                currentResult: { id: null, title: '', content: '', seo_score: 0, hashtags: [] },
                savedArticles: JSON.parse(document.getElementById('articles-data').textContent),

                showAlert(title, message, type = 'info') {
                    this.messageModal = { show: true, title, message, type };
                },

                copyContent() {
                    const articleEl = document.getElementById('article-body');
                    if (!articleEl) return;

                    // Cara yang lebih kompatibel dengan berbagai browser
                    const range = document.createRange();
                    range.selectNode(articleEl);
                    window.getSelection().removeAllRanges();
                    window.getSelection().addRange(range);

                    try {
                        // Eksekusi copy
                        document.execCommand('copy');
                        
                        // Efek UI
                        this.isCopied = true;
                        setTimeout(() => this.isCopied = false, 2000);
                    } catch (err) {
                        this.showAlert('Error', 'Gagal menyalin teks.', 'error');
                    }

                    // Bersihkan seleksi
                    window.getSelection().removeAllRanges();
                },

                generateAI() {
                    if(!this.formData.blogTitle || !this.formData.description) {
                        this.showAlert('Data Minim', 'Lengkapi judul dan deskripsi.', 'warning');
                        return;
                    }
                    
                    this.modalStep = 'loading';
                    this.progress = 0;
                    let interval = setInterval(() => { if(this.progress < 95) this.progress += 2; }, 150);

                    fetch('{{ route('ai.blog.generate') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(this.formData)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.error) throw new Error(data.error);
                        this.currentResult = {
                            id: null,
                            title: this.formData.blogTitle,
                            content: data.full_content,
                            seo_score: data.seo_score || 85,
                            hashtags: data.hashtags || []
                        };
                        this.progress = 100;
                        clearInterval(interval);
                        setTimeout(() => { this.modalStep = 'result'; window.scrollTo({ top: 0, behavior: 'smooth' }); }, 500);
                    })
                    .catch(err => {
                        clearInterval(interval);
                        this.modalStep = 'input';
                        this.showAlert('System Error', err.message || 'Gagal terhubung ke AI.', 'error');
                    });
                },

                openArticle(article) {
                    fetch(`/dashboard/ai-blog/show/${article.id}`)
                        .then(res => res.json())
                        .then(data => {
                            this.currentResult = {
                                id: data.id,
                                title: data.title,
                                content: data.content,
                                seo_score: data.seo_score,
                                hashtags: data.hashtags || []
                            };
                            this.modalStep = 'result';
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        })
                        .catch(() => this.showAlert('Error', 'Gagal memuat detail.', 'error'));
                },

                saveToDatabase() {
                    fetch('{{ route('ai.blog.store') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ ...this.currentResult, image_url: this.formData.imageUrl, description: this.formData.description })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            this.savedArticles.unshift(data.article);
                            this.modalStep = 'idle';
                            this.formData = { blogTitle: '', imageUrl: '', description: '' };
                        }
                    });
                },

                // Fungsi untuk memicu modal muncul
                confirmDelete(id) {
                    this.articleToDelete = id;
                    this.showDeleteModal = true;
                },

                // Fungsi eksekusi hapus yang sebenarnya
                deleteArticle() {
                    if (!this.articleToDelete) return;

                    fetch(`/ai-blog/delete/${this.articleToDelete}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.savedArticles = this.savedArticles.filter(a => a.id !== this.articleToDelete);
                        this.showDeleteModal = false;
                        this.articleToDelete = null;
                        // Opsional: tampilkan alert sukses
                        this.showAlert('Deleted', 'Konten berhasil dihapus selamanya.', 'info');
                    })
                    .catch(() => this.showAlert('Error', 'Gagal menghapus konten.', 'error'));
                },
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .prose h2 { font-size: 2rem; margin-top: 3rem; margin-bottom: 1.5rem; color: #0f172a; }
        .prose h3 { font-size: 1.5rem; margin-top: 2.5rem; margin-bottom: 1rem; color: #1e293b; }
        .prose p { margin-bottom: 1.5rem; color: #475569; }
        .prose img { width: 100%; border-radius: 2rem; margin: 3rem 0; }
    </style>
</x-user-layout>