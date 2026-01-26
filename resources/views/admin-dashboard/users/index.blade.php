<x-admin-layout>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">
                User <span class="text-blue-600">Management</span>
            </h2>
            <p class="text-slate-500 text-sm font-medium">Kelola akses dan paket langganan pengguna w3site.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden" 
         x-data="{ 
            openModal: false, 
            openDeleteModal: false,
            selectedUser: null, 
            userName: '',
            currentPackage: 0 
         }">

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden" ...>
            <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="font-black text-slate-800 flex items-center gap-2 text-lg">
                    <i class="fa-solid fa-list-ul text-blue-600"></i> Daftar Pengguna
                </h3>
        
                <form action="{{ route('admin.users.index') }}" method="GET" class="relative group">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari nama atau email..." 
                           class="w-full md:w-72 pl-12 pr-4 py-3 bg-slate-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl text-sm font-bold transition-all outline-none text-slate-700">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                    
                    @if(request('search'))
                        <a href="{{ route('admin.users.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black uppercase text-red-500 hover:text-red-700">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Info User</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Paket Saat Ini</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tgl Join</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50/30 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xs uppercase">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ $user->name }}</p>
                                    <p class="text-[11px] text-slate-400 font-medium">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if($user->package == 2)
                                <span class="px-3 py-1 bg-blue-600 text-white text-[10px] font-black rounded-lg shadow-sm">PRO</span>
                            @elseif($user->package == 1)
                                <span class="px-3 py-1 bg-amber-500 text-white text-[10px] font-black rounded-lg shadow-sm">PEMULA</span>
                            @else
                                <span class="px-3 py-1 bg-slate-100 text-slate-400 text-[10px] font-black rounded-lg">GRATIS</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-center">
                            <p class="text-xs font-bold text-slate-600">{{ $user->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Hanya tampilkan tombol Edit & Hapus jika user yang sedang di-loop BUKAN admin --}}
                                @if($user->role !== 'admin')
                                    <button @click="openModal = true; selectedUser = {{ $user->id }}; currentPackage = {{ $user->package }}" 
                                        class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    
                                    <button @click="openDeleteModal = true; selectedUser = {{ $user->id }}; userName = '{{ $user->name }}'"
                                        class="w-9 h-9 rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                @else
                                    {{-- Opsional: Tampilkan badge atau icon pelindung untuk akun Admin --}}
                                    <span class="px-3 py-1 bg-slate-100 text-slate-400 text-[10px] font-bold uppercase rounded-lg tracking-widest">
                                        <i class="fa-solid fa-shield-halved mr-1"></i> Admin Protected
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-8 bg-slate-50/30">
            {{ $users->links() }}
        </div>

        <div x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
            <div @click.away="openModal = false" class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl">
                <div class="p-8">
                    <h4 class="text-xl font-black text-slate-900 mb-2">Update Package</h4>
                    <p class="text-sm text-slate-500 font-medium mb-6">Pilih paket baru untuk pengguna ini.</p>
                    
                    <form :action="'{{ url('admin/users') }}/' + selectedUser + '/package'" method="POST">
                        @csrf @method('PATCH')
                        <div class="space-y-3">
                            <template x-for="(label, val) in {0: 'Gratis (Default)', 1: 'Pemula (Rp 29k)', 2: 'Pro (Rp 49k)'}">
                                <label class="block p-4 rounded-2xl border-2 transition-all cursor-pointer" 
                                    :class="currentPackage == val ? 'border-blue-600 bg-blue-50/50' : 'border-slate-100'">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="package" :value="val" x-model="currentPackage" class="hidden">
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center" :class="currentPackage == val ? 'border-blue-600' : 'border-slate-300'">
                                            <div class="w-2.5 h-2.5 rounded-full bg-blue-600" x-show="currentPackage == val"></div>
                                        </div>
                                        <span class="font-bold text-slate-700" x-text="label"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                        <div class="mt-8 flex gap-3">
                            <button type="button" @click="openModal = false" class="flex-1 py-4 bg-slate-100 text-slate-500 font-black rounded-2xl text-xs uppercase tracking-widest">Batal</button>
                            <button type="submit" class="flex-1 py-4 bg-blue-600 text-white font-black rounded-2xl shadow-lg shadow-blue-200 text-xs uppercase tracking-widest">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="openDeleteModal" 
             x-transition.opacity
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
            
            <div @click.away="openDeleteModal = false" class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl">
                <div class="p-8 text-center">
                    <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-6">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    
                    <h4 class="text-xl font-black text-slate-900 mb-2">Hapus Pengguna?</h4>
                    <p class="text-sm text-slate-500 font-medium mb-1">Anda akan menghapus akun:</p>
                    <p class="text-sm font-black text-red-500 mb-8 underline" x-text="userName"></p>
                    
                    <div class="bg-amber-50 border border-amber-100 p-4 rounded-2xl mb-8">
                        <p class="text-[10px] font-bold text-amber-700 leading-relaxed uppercase tracking-tight text-left">
                            <i class="fa-solid fa-circle-info mr-1"></i> Perhatian:
                        </p>
                        <p class="text-[10px] text-amber-600 text-left mt-1">Seluruh data situs, linktree, dan shortlink milik user ini akan ikut terhapus secara permanen.</p>
                    </div>

                    <form :action="'{{ url('admin/users') }}/' + selectedUser" method="POST">
                        @csrf @method('DELETE')
                        <div class="flex gap-3">
                            <button type="button" @click="openDeleteModal = false" 
                                class="flex-1 py-4 bg-slate-100 text-slate-500 font-black rounded-2xl text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">
                                Batal
                            </button>
                            <button type="submit" 
                                class="flex-1 py-4 bg-red-500 text-white font-black rounded-2xl shadow-lg shadow-red-200 hover:bg-red-600 transition-all text-xs uppercase tracking-widest">
                                Ya, Hapus
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>