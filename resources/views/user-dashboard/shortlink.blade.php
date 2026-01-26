<x-user-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div x-data="{ showDeleteModal: false, linkToDelete: '', deleteRoute: '' }">
        
        {{-- Header & Stats Card (Tetap Sama) --}}
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                    Shortlink <span class="text-blue-600">Generator</span> 🔗
                </h1>
                <p class="text-slate-500 text-sm font-medium">Sederhanakan tautan panjang Anda secara instan.</p>
            </div>
        </header>

        {{-- Stats & Input Grid (Gunakan data dari Controller) --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[1rem] border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-3">Total Tautan</p>
                    <div class="flex items-end gap-1">
                        <span class="text-3xl font-[1000] {{ $isFull ? 'text-red-600' : 'text-slate-900' }} leading-none">{{ $count }}</span>
                        <span class="text-slate-400 font-bold text-xs mb-0.5">/ {{ $limitText }}</span>
                    </div>
                </div>
                <div class="mt-6">
                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full transition-all duration-700 {{ $isFull ? 'bg-red-500' : 'bg-blue-600' }}" style="width: {{ $percent }}%"></div>
                    </div>
                    <div class="flex justify-between items-center mt-3">
                        <p class="text-[8px] font-black uppercase text-slate-400 tracking-widest">Kapasitas</p>
                        <p class="text-[9px] font-black text-slate-900 uppercase">{{ round($percent) }}%</p>
                    </div>
                </div>
            </div>

            <div class="md:col-span-3 bg-white p-6 rounded-[1rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-4">Buat Tautan Baru</p>
                <form action="{{ route('shortlink.store') }}" method="POST" class="relative z-10 flex flex-col md:flex-row gap-3 {{ $isFull ? 'opacity-20 pointer-events-none grayscale' : '' }}">
                    @csrf
                    <div class="flex-1">
                        <input type="url" name="destination_url" required placeholder="https://..." class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <button type="submit" class="px-8 py-3.5 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-600 transition-all shadow-md active:scale-95">
                        Generate Link <i class="fa-solid fa-bolt ml-1"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Daftar Link --}}
        <div class="space-y-3">
            @forelse($links as $link)
                <div class="bg-white rounded-[1rem] border border-slate-100 shadow-sm p-4 hover:border-blue-200 transition-all flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <div class="w-11 h-11 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 border border-blue-100/50">
                            <i class="fa-solid fa-link text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <a href="{{ url($link->slug) }}" target="_blank" class="text-[15px] font-[1000] text-slate-900 tracking-tight hover:text-blue-600 transition-colors">
                                    {{ request()->getHost() }}/{{ $link->slug }}
                                </a>                                
                                <span class="text-[9px] px-2 py-0.5 bg-slate-100 text-slate-500 rounded font-bold uppercase">{{ $link->clicks }} Hits</span>
                            </div>
                            <p class="text-[11px] text-slate-400 font-medium truncate italic underline decoration-slate-100">
                                {{ $link->destination_url }}
                            </p>
                        </div>
                    </div>
            
                    <div class="flex items-center gap-2 ml-auto">
                        {{-- Tombol Copy dengan fungsi Vanilla JS --}}
                        <button onclick="copyToClipboard('{{ url($link->slug) }}', this)" 
                            class="btn-copy flex items-center gap-2 min-w-[110px] justify-center px-4 py-2.5 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-sm active:scale-95">
                            <i class="fa-solid fa-copy"></i>
                            <span>COPY</span>
                        </button>
                        
                        <button @click="linkToDelete = '{{ $link->slug }}'; deleteRoute = '{{ route('shortlink.destroy', $link->id) }}'; showDeleteModal = true" 
                            class="p-2.5 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                            <i class="fa-solid fa-trash-can text-sm"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white rounded-[2rem] border border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold text-sm">Belum ada shortlink.</p>
                </div>
            @endforelse
        </div>

        {{-- Modal Delete (Menggunakan Alpine.js agar ringan) --}}
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[120] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 text-center border border-slate-100">
                <h3 class="font-black text-xl text-slate-900 tracking-tight">Hapus Tautan?</h3>
                <p class="text-slate-500 text-sm mt-3" x-text="linkToDelete"></p>
                <div class="mt-8 space-y-3">
                    <form :action="deleteRoute" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full px-6 py-4 bg-red-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest">Hapus Permanen</button>
                    </form>
                    <button @click="showDeleteModal = false" class="w-full px-6 py-4 bg-slate-100 text-slate-600 rounded-2xl text-xs font-black uppercase tracking-widest">Batalkan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function copyToClipboard(text, btn) {
        const btnText = btn.querySelector('span');
        const btnIcon = btn.querySelector('i');
        
        // Fungsi sukses UI
        const setSuccess = () => {
            const originalText = btnText.innerText;
            
            // Perubahan Warna & Text
            btn.classList.add('!bg-emerald-500'); // Paksa hijau
            btnText.innerText = 'COPIED!';
            btnIcon.classList.replace('fa-copy', 'fa-check');

            setTimeout(() => {
                btn.classList.remove('!bg-emerald-500');
                btnText.innerText = originalText;
                btnIcon.classList.replace('fa-check', 'fa-copy');
            }, 2000);
        };

        // Logika Eksekusi Copy
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(setSuccess).catch(err => {
                console.error('Clipboard gagal:', err);
            });
        } else {
            // Fallback Textarea (untuk non-HTTPS)
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-9999px";
            textArea.style.top = "0";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                setSuccess();
            } catch (err) {
                console.error('Gagal menyalin:', err);
            }
            document.body.removeChild(textArea);
        }
    }
    </script>
</x-user-layout>