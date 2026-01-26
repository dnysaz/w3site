<x-admin-layout>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">
                Websites <span class="text-blue-600">Inventory</span>
            </h2>
            <p class="text-slate-500 text-sm font-medium">Monitoring semua situs yang di-deploy oleh pengguna.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center gap-6 relative overflow-hidden group">
            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl transition-transform group-hover:scale-110">
                <i class="fa-solid fa-file-zipper"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Deployment via ZIP</p>
                <h3 class="text-2xl font-black text-slate-900">{{ number_format($siteStats['zip']) }} <span class="text-xs font-medium text-slate-400 uppercase">Situs</span></h3>
            </div>
            <i class="fa-solid fa-file-zipper absolute -right-4 -bottom-4 text-slate-50 text-6xl rotate-12"></i>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center gap-6 relative overflow-hidden group">
            <div class="w-14 h-14 bg-slate-900 text-white rounded-2xl flex items-center justify-center text-xl transition-transform group-hover:scale-110">
                <i class="fa-brands fa-github"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Deployment via GitHub</p>
                <h3 class="text-2xl font-black text-slate-900">{{ number_format($siteStats['github']) }} <span class="text-xs font-medium text-slate-400 uppercase">Situs</span></h3>
            </div>
            <i class="fa-brands fa-github absolute -right-4 -bottom-4 text-slate-50 text-6xl rotate-12"></i>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="font-black text-slate-800 flex items-center gap-2 text-lg">
                <i class="fa-solid fa-globe text-blue-600"></i> Aktif Websites
            </h3>
            
            <form action="{{ route('admin.sites.index') }}" method="GET" class="relative group">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari subdomain..." 
                       class="w-full md:w-72 pl-12 pr-4 py-3 bg-slate-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-2xl text-sm font-bold transition-all outline-none text-slate-700">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                
                @if(request('search'))
                    <a href="{{ route('admin.sites.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-black uppercase text-red-500 hover:text-red-700">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Website / Owner</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Source</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($sites as $site)
                    <tr class="hover:bg-slate-50/30 transition-colors group {{ $site->status == 'pending' ? 'bg-red-50/30' : '' }}">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center border {{ $site->status == 'pending' ? 'bg-red-100 text-red-500 border-red-200' : 'bg-slate-100 text-slate-400 border-slate-200' }}">
                                    <i class="fa-solid {{ $site->status == 'pending' ? 'fa-lock' : 'fa-window-restore' }} text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black {{ $site->status == 'pending' ? 'text-slate-400 line-through' : 'text-slate-900' }}">
                                        {{ $site->subdomain }}.w3site.id
                                    </p>
                                    <p class="text-[11px] text-slate-400 font-medium italic">Owner: {{ $site->user->name ?? 'User Terhapus' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if($site->repository_url)
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-900 text-white text-[9px] font-black rounded-lg">
                                    <i class="fa-brands fa-github"></i> GITHUB
                                </div>
                            @else
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-500 text-white text-[9px] font-black rounded-lg">
                                    <i class="fa-solid fa-file-zipper"></i> ZIP
                                </div>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if($site->status == 'active')
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full uppercase">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-black text-amber-600 bg-amber-50 px-3 py-1 rounded-full uppercase">
                                    <i class="fa-solid fa-hourglass-half"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="https://{{ $site->subdomain }}.w3site.id" target="_blank" class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>

                                <form action="{{ route('admin.sites.toggle', $site->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" 
                                        class="w-9 h-9 rounded-xl flex items-center justify-center transition-all shadow-sm {{ $site->status == 'active' ? 'bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white' }}"
                                        title="{{ $site->status == 'active' ? 'Set Pending' : 'Set Active' }}">
                                        <i class="fa-solid {{ $site->status == 'active' ? 'fa-ban' : 'fa-check' }} text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">
                            Tidak ada website ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-8 bg-slate-50/30">
            {{ $sites->links() }}
        </div>
    </div>
</x-admin-layout>