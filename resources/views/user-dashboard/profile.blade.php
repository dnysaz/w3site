<x-user-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Profil 👋</h1>
            <p class="text-slate-500 text-sm font-medium">Kelola informasi publik dan keamanan akun Anda.</p>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" x-data="{ isSavingProfile: false, isSavingPassword: false }">
    
        <div class="bg-white p-8 rounded-[1rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="flex items-center gap-3 mb-8 relative z-10">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm border border-blue-100/50">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 tracking-tight">Informasi Dasar</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Identitas Akun</p>
                </div>
            </div>
    
            <div class="relative z-10">
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6" @submit="isSavingProfile = true">
                    @csrf
                    @method('PATCH')
            
                    <div class="group">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Nama Lengkap</label>
                        <div class="relative mt-2 flex items-center">
                            <span class="absolute left-4 text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <i class="fa-solid fa-signature"></i>
                            </span>
                            <input name="name" type="text" value="{{ old('name', $user->name) }}" 
                                class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-bold text-slate-900 shadow-sm placeholder:text-slate-300">
                        </div>
                        @error('name') 
                            <p class="text-red-500 text-[10px] font-black uppercase mt-2 ml-1 tracking-widest">{{ $message }}</p> 
                        @enderror
                    </div>
            
                    <div class="opacity-70 group">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Email Aktif</label>
                        <div class="relative mt-2 flex items-center">
                            <span class="absolute left-4 text-slate-300">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input type="email" value="{{ $user->email }}" 
                                class="w-full pl-11 pr-4 py-4 bg-slate-100 border border-slate-200 rounded-2xl font-bold text-slate-400 cursor-not-allowed shadow-inner" 
                                disabled>
                            <span class="absolute right-4 text-slate-300">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5 mt-3 ml-1">
                            <div class="w-1 h-1 bg-blue-400 rounded-full animate-pulse"></div>
                            <span class="text-[9px] text-slate-400 font-black uppercase tracking-tight">Permanen & Terverifikasi</span>
                        </div>
                    </div>
            
                    <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
                        <button type="submit" 
                            :class="isSavingProfile ? 'opacity-70 cursor-wait' : 'hover:scale-[1.02] active:scale-[0.98]'"
                            class="w-full sm:w-fit px-8 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all shadow-xl shadow-slate-200/50 flex items-center justify-center gap-2">
                            <span x-show="!isSavingProfile">Simpan Perubahan</span>
                            <span x-show="isSavingProfile" class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-notch animate-spin"></i> Memproses...
                            </span>
                        </button>
            
                        @if (session('status') === 'profile-updated')
                            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                                class="flex items-center gap-2 text-green-600 bg-green-50 px-4 py-2 rounded-xl border border-green-100 transition-all">
                                <i class="fa-solid fa-circle-check"></i>
                                <span class="text-[9px] font-black uppercase tracking-widest">Updated</span>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="absolute -right-6 -top-6 text-slate-50/40 text-8xl pointer-events-none rotate-12">
                <i class="fa-solid fa-address-card"></i>
            </div>
        </div>
    
        <div class="bg-white p-8 rounded-[1rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="flex items-center gap-3 mb-8 relative z-10">
                <div class="w-10 h-10 bg-slate-50 text-slate-900 rounded-2xl flex items-center justify-center shadow-sm border border-slate-200/50">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 tracking-tight">Kata Sandi</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Keamanan Akun</p>
                </div>
            </div>
        
            <div class="relative z-10" x-data="{ 
                isSavingPassword: false,
                showCurrent: false,
                showNew: false,
                showConfirm: false 
            }">
                <form action="{{ route('password.update') }}" method="POST" class="space-y-5" @submit="isSavingPassword = true">
                    @csrf
                    @method('PUT')
        
                    <div class="group">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Password Saat Ini</label>
                        <div class="relative mt-2 flex items-center">
                            <span class="absolute left-4 text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <i class="fa-solid fa-key"></i>
                            </span>
                            <input :type="showCurrent ? 'text' : 'password'" name="current_password" 
                                class="w-full pl-11 pr-12 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-bold text-slate-900 shadow-sm">
                            
                            <button type="button" @click="showCurrent = !showCurrent" class="absolute right-4 text-slate-400 hover:text-blue-500 transition-colors">
                                <i class="fa-solid" :class="showCurrent ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('current_password') <p class="text-red-500 text-[10px] font-black mt-2 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>
        
                    <div class="group">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Password Baru</label>
                        <div class="relative mt-2 flex items-center">
                            <span class="absolute left-4 text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <i class="fa-solid fa-lock-open"></i>
                            </span>
                            <input :type="showNew ? 'text' : 'password'" name="password" 
                                class="w-full pl-11 pr-12 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-bold text-slate-900 shadow-sm">
                            
                            <button type="button" @click="showNew = !showNew" class="absolute right-4 text-slate-400 hover:text-blue-500 transition-colors">
                                <i class="fa-solid" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-[10px] font-black mt-2 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>
        
                    <div class="group">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Konfirmasi Password Baru</label>
                        <div class="relative mt-2 flex items-center">
                            <span class="absolute left-4 text-slate-400 group-focus-within:text-blue-500 transition-colors">
                                <i class="fa-solid fa-shield-check"></i>
                            </span>
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" 
                                class="w-full pl-11 pr-12 py-4 bg-slate-50 border border-slate-100 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-bold text-slate-900 shadow-sm">
                            
                            <button type="button" @click="showConfirm = !showConfirm" class="absolute right-4 text-slate-400 hover:text-blue-500 transition-colors">
                                <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>
        
                    <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
                        <button type="submit" 
                            :class="isSavingPassword ? 'opacity-70 cursor-wait' : 'hover:scale-[1.02] active:scale-[0.98]'"
                            class="w-full sm:w-fit px-8 py-4 bg-blue-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all shadow-xl shadow-blue-100 flex items-center justify-center gap-2">
                            <span x-show="!isSavingPassword">Perbarui Password</span>
                            <span x-show="isSavingPassword" class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-notch animate-spin"></i> Memproses...
                            </span>
                        </button>
        
                        @if (session('status') === 'password-updated')
                            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                                class="flex items-center gap-2 text-green-600 bg-green-50 px-4 py-2 rounded-xl border border-green-100 transition-all">
                                <i class="fa-solid fa-circle-check"></i>
                                <span class="text-[9px] font-black uppercase tracking-widest">Changed</span>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="absolute -right-6 -top-6 text-slate-50/40 text-8xl pointer-events-none rotate-12">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white p-8 rounded-[1rem] border border-red-100 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 text-red-50/50 text-8xl pointer-events-none rotate-12 group-hover:rotate-0 transition-transform duration-500">
            <i class="fa-solid fa-user-slash"></i>
        </div>
    
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center shadow-sm border border-red-100/50">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 tracking-tight">Zona Berbahaya</h3>
                    <p class="text-[10px] text-red-400 font-bold uppercase tracking-widest">Tindakan Tidak Terpulihkan</p>
                </div>
            </div>
    
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 p-6 bg-red-50/30 border border-dashed border-red-200 rounded-[2rem]">
                <div class="max-w-md">
                    <h4 class="text-sm font-black text-red-700 mb-1 uppercase tracking-tight">Hapus Akun Permanen</h4>
                    <p class="text-[11px] text-slate-500 font-bold leading-relaxed">
                        Menghapus akun akan memusnahkan seluruh data subdomain, file situs, dan konfigurasi selamanya.
                    </p>
                </div>
    
                <button 
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    class="w-full lg:w-fit px-8 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all shadow-xl shadow-red-100 hover:bg-red-700 hover:scale-[1.02] active:scale-95">
                    Hapus Akun
                </button>
            </div>
        </div>
    </div>

    {{-- Logic Pembungkus Modal: Mencegah Pop-up Random --}}
    <div x-data="{ 
        showDeleteModal: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }},
        isDeleting: false 
    }" 
        {{-- Listener Global yang lebih ketat --}}
        @open-modal.window="if($event.detail === 'confirm-user-deletion') showDeleteModal = true"
        @close.window="showDeleteModal = false"
        @keydown.escape.window="showDeleteModal = false"
        x-cloak>

        {{-- Overlay & Modal Container --}}
        <div x-show="showDeleteModal" 
            class="fixed inset-0 z-[150] overflow-y-auto flex items-center justify-center p-4">
            
            {{-- Backdrop dengan Blur --}}
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" 
                x-show="showDeleteModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                @click="showDeleteModal = false"></div>

            {{-- Box Modal --}}
            <div x-show="showDeleteModal" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative bg-white w-full max-w-xl rounded-[2rem] shadow-2xl p-8 border border-slate-100 overflow-hidden">
                
                {{-- Background Decoration Icon --}}
                <div class="absolute -right-10 -top-10 text-slate-50 text-9xl pointer-events-none rotate-12">
                    <i class="fa-solid fa-user-slash"></i>
                </div>

                <div class="flex items-center gap-4 mb-8 relative z-10">
                    <div class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-red-100/50">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h2 class="font-black text-slate-900 text-xl tracking-tight">Hapus Akun Permanen</h2>
                        <p class="text-[10px] text-red-500 font-black uppercase tracking-[0.2em]">Konfirmasi Terakhir</p>
                    </div>
                </div>

                <form method="post" action="{{ route('profile.destroy') }}" class="relative z-10 space-y-4" @submit="isDeleting = true">
                    @csrf
                    @method('delete')

                    <div class="flex gap-4 p-5 rounded-3xl bg-slate-50 border border-slate-100 group transition-all hover:bg-red-50/30">
                        <i class="fa-solid fa-dumpster-fire text-red-500 mt-1"></i>
                        <div>
                            <p class="text-xs font-black text-slate-800 mb-1 uppercase tracking-tight">Penghapusan Total</p>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-bold uppercase">Semua subdomain, file situs, dan data database akan dimusnahkan seketika.</p>
                        </div>
                    </div>

                    {{-- Password Input --}}
                    <div class="pt-4 group" x-data="{ showPass: false }">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Ketik Password Anda</label>
                        <div class="relative mt-2 flex items-center">
                            <span class="absolute left-4 text-slate-400 group-focus-within:text-red-500 transition-colors">
                                <i class="fa-solid fa-key text-sm"></i>
                            </span>
                            <input :type="showPass ? 'text' : 'password'" name="password" required
                                class="w-full pl-11 pr-12 py-4 bg-white border border-slate-100 rounded-2xl outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-500 transition-all font-bold text-slate-900 shadow-sm"
                                placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass" class="absolute right-4 text-slate-400 hover:text-red-500 transition-colors">
                                <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        
                        {{-- Pesan Error Validasi --}}
                        @if($errors->userDeletion->has('password'))
                            <div class="flex items-center gap-2 mt-3 ml-1 text-red-500 animate-pulse">
                                <i class="fa-solid fa-circle-exclamation text-xs"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest">
                                    {{ $errors->userDeletion->first('password') }}
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Action Buttons --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-6">
                        <button type="button" @click="showDeleteModal = false" 
                            class="py-4 bg-slate-100 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-slate-200 transition-all order-2 sm:order-1">
                            Batalkan
                        </button>
                        <button type="submit" 
                            :disabled="isDeleting"
                            class="py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-red-100 hover:bg-red-700 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 order-1 sm:order-2">
                            <span x-show="!isDeleting">Ya, Hapus Akun</span>
                            <span x-show="isDeleting" class="flex items-center gap-2">
                                <i class="fa-solid fa-circle-notch animate-spin"></i> Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-user-layout>