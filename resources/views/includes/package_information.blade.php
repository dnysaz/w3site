{{-- Container Utama: Height otomatis di mobile (min-h), tetap 80px di desktop --}}
<div class="w-full min-h-[80px] md:h-[80px] bg-slate-900 border-b border-white/5 flex items-center overflow-hidden relative group mb-8 py-4 md:py-0">
    
    {{-- Efek Cahaya Bergerak --}}
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-blue-500/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-[1500ms]"></div>

    <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4 relative z-10">
        
        {{-- Sisi Kiri: Ikon & Pesan --}}
        <div class="flex items-center gap-4 text-center md:text-left">
            {{-- Ikon: Disembunyikan di HP sangat kecil agar hemat ruang, atau tetap tampil --}}
            <div class="hidden sm:flex items-center justify-center w-8 h-8 shrink-0 rounded-xl bg-white/5 border border-white/10 shadow-inner">
                @if(auth()->user()->package == 2)
                    <i class="fa-solid fa-crown text-xs text-amber-400"></i>
                @elseif(auth()->user()->package == 1)
                    <i class="fa-solid fa-rocket text-xs text-blue-400"></i>
                @else
                    <i class="fa-solid fa-bolt text-xs text-emerald-400"></i>
                @endif
            </div>
            
            <p class="text-[13px] md:text-[14px] leading-relaxed font-medium text-slate-300">
                @php
                    $firstName = explode(' ', trim(auth()->user()->name))[0];
                @endphp

                @if(auth()->user()->package == 0)
                    Halo <span class="text-white font-bold">{{ $firstName }}</span>, Anda menggunakan <span class="text-white font-bold">Paket Gratis</span>. <span class="hidden md:inline">Upgrade ke <span class="text-blue-400 font-bold">Pemula</span> untuk situs lebih banyak & fitur AI tambahan.</span>
                    <span class="md:hidden text-blue-400 block mt-1 font-bold">Upgrade untuk fitur lebih lengkap!</span>
                @elseif(auth()->user()->package == 1)
                    Halo <span class="text-white font-bold">{{ $firstName }}</span>, Anda di paket <span class="text-blue-400 font-bold uppercase">Pemula</span>. <span class="hidden md:inline">Upgrade ke <span class="text-amber-400 font-bold uppercase">Pro</span> untuk storage maksimal & AI tanpa batas!</span>
                    <span class="md:hidden text-amber-400 block mt-1 font-bold">Upgrade ke Pro sekarang!</span>
                @else
                    Halo <span class="text-white font-bold">{{ $firstName }}</span>, terima kasih telah menggunakan <span class="text-amber-400 font-bold">Layanan Pro.</span> Kami terus memaksimalkan pengalaman Anda.
                @endif
            </p>
        </div>

        {{-- Sisi Kanan: Action Button --}}
        <div class="shrink-0">
            @if(auth()->user()->package < 2)
                <a href="{{ route('pricing') }}" class="group/btn flex items-center gap-3 bg-blue-600 hover:bg-blue-500 px-6 py-2.5 md:py-3 rounded-full transition-all shadow-xl shadow-blue-900/40">
                    <span class="text-[10px] font-black uppercase tracking-widest text-white">Upgrade</span>
                    <i class="fa-solid fa-chevron-right text-[8px] text-white/70 group-hover/btn:translate-x-1 transition-transform"></i>
                </a>
            @else
                <div class="flex items-center gap-2 px-4 py-2 bg-emerald-500/10 rounded-full border border-emerald-500/20">
                    <span class="text-[9px] font-black uppercase tracking-[0.15em] text-emerald-400">Account Verified</span>
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                </div>
            @endif
        </div>
    </div>
</div>