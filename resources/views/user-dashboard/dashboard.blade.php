<x-user-layout>
    @if(!auth()->user()->hasVerifiedEmail())
        <div class="mb-6">
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-xl shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-amber-100 p-2 rounded-lg">
                        <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-bold text-amber-800">
                            Email Anda belum terverifikasi!
                        </p>
                        <p class="text-xs text-amber-700 mt-1">
                            Silakan cek inbox atau folder spam di <span class="font-semibold">{{ auth()->user()->email }}</span> untuk mengaktifkan fitur penuh.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Header --}}
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">
                Halo, <span class="text-blue-600">{{ $user->name }}</span>! 👋
            </h1>
            <p class="text-slate-500 text-sm font-medium">Panel kendali paket <strong class="text-slate-900">{{ $currentPkg['name'] }}</strong> Anda.</p>
        </div>
    </header>

    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-10">
        {{-- Card: Tier --}}
        <div class="bg-white p-5 rounded-[1.2rem] border border-slate-100 shadow-sm relative overflow-hidden group">
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2">Tier Akun</p>
            <span class="text-xl font-black text-slate-900 leading-tight block">{{ $currentPkg['name'] }}</span>
            <div class="mt-2 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-tight">Active Plan</p>
            </div>
            <div class="absolute -right-1 -bottom-1 text-slate-50 text-4xl rotate-12 pointer-events-none group-hover:text-slate-100 transition-colors">
                <i class="fa-solid {{ $currentPkg['icon'] }}"></i>
            </div>
        </div>
    
        {{-- Card: Storage --}}
        <div class="bg-white p-5 rounded-[1.2rem] border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex justify-between items-start mb-2">
                    <p class="text-slate-400 text-[10px] font-[1000] uppercase tracking-widest">SSD Storage</p>
                </div>
                <div class="flex items-end gap-1">
                    <span class="text-2xl font-[1000] text-slate-900 leading-none">{{ $totalSizeMb }}</span>
                    <span class="text-slate-400 font-bold text-[10px] mb-0.5 tracking-tighter uppercase">
                        / {{ $currentPkg['storage'] }} {{ $currentPkg['storage_unit'] }}
                    </span>
                </div>
            </div>
            
            <div class="mt-4">
                {{-- Progress Bar: Berubah merah jika > 90% --}}
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full {{ $storagePercent > 90 ? 'bg-red-500' : 'bg-blue-600' }} transition-all duration-700 ease-out" 
                        style="width: {{ $storagePercent }}%">
                    </div>
                </div>
                
                <div class="flex justify-between items-center mt-2">
                    {{-- Status Label --}}
                    <div class="flex items-center gap-1">
                        <div class="w-1 h-1 rounded-full {{ $storagePercent > 90 ? 'bg-red-500 animate-pulse' : 'bg-blue-500' }}"></div>
                        <p class="text-[8px] {{ $storagePercent > 90 ? 'text-red-500' : 'text-slate-400' }} font-black uppercase tracking-widest">
                            {{ $storagePercent > 90 ? 'Hampir Penuh' : 'Storage' }}
                        </p>
                    </div>
                    {{-- Persentase --}}
                    <p class="text-[8px] {{ $storagePercent > 90 ? 'text-red-500 font-black' : 'text-slate-400 font-bold' }} uppercase tracking-widest text-right">
                        {{ round($storagePercent, 1) }}%
                    </p>
                </div>
            </div>
        </div>
    
        {{-- Card: Website Slot --}}
        <div class="bg-white p-5 rounded-[1.2rem] border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2">Web Slot</p>
                <div class="flex items-end gap-1">
                    <span class="text-2xl font-black {{ $isFull ? 'text-red-600' : 'text-slate-900' }} leading-none">{{ $siteCount }}</span>
                    <span class="text-slate-400 font-bold text-[10px] mb-0.5">/ {{ $currentPkg['limit'] }}</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-slate-900 transition-all duration-700" style="width: {{ $percentUsage }}%"></div>
                </div>
                <p class="text-[8px] text-slate-400 font-bold uppercase mt-2 tracking-widest">{{ $siteCount }} Active</p>
            </div>
        </div>
    
        {{-- Card: Bio Link (LINKTREE) --}}
        <div class="bg-white p-5 rounded-[1.2rem] border border-slate-100 shadow-sm flex flex-col justify-between group">
            <div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2">Bio Link</p>
                <div class="flex items-end gap-1">
                    <span class="text-2xl font-black text-slate-900 leading-none">{{ $linktreeCount }}</span>
                    <span class="text-slate-400 font-bold text-[10px] mb-0.5 uppercase">/ {{ $ltLimit }}</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-fuchsia-500 transition-all duration-700" style="width: {{ min($ltPercent, 100) }}%"></div>
                </div>
                <p class="text-[8px] text-fuchsia-500 font-black uppercase mt-2 tracking-widest group-hover:animate-pulse">Biolink</p>
            </div>
        </div>
    
        {{-- Card: Shortlink --}}
        <div class="bg-white p-5 rounded-[1.2rem] border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-2">Shortlink</p>
                <div class="flex items-end gap-1">
                    <span class="text-2xl font-black text-slate-900 leading-none">{{ $linkCount }}</span>
                    <span class="text-slate-400 font-bold text-[10px] mb-0.5 uppercase">/ {{ $linkLimitText }}</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 transition-all duration-700" style="width: {{ min($linkPercent, 100) }}%"></div>
                </div>
                <p class="text-[8px] text-emerald-500 font-bold uppercase mt-2 tracking-widest italic">
                    {{ $isUnlimitedLink ? 'Unlimited' : 'Quota' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Daftar Situs Real --}}
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex justify-between items-center">
            <h3 class="font-black text-slate-900 text-lg tracking-tight">Situs Anda</h3>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1 rounded-full border border-slate-100">
                {{ $sites->count() }} / {{ auth()->user()->site_limit }} Slot Terpakai
            </span>
        </div>

        {{-- Grid Card --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($sites as $site)
                @php 
                    $isGit = !empty($site->repository_url); 
                @endphp
                
                <div class="bg-white rounded-[1.5rem] border {{ $isGit ? 'border-blue-100' : 'border-slate-100' }} shadow-sm p-6 group hover:border-blue-400 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300 relative overflow-hidden">
                    {{-- Status & Source Indicator --}}
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex flex-wrap gap-2">
                            {{-- Status Online --}}
                            @if($site->status == 'active')
                                <span class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-[9px] uppercase font-black tracking-widest border border-green-100/50 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Online
                                </span>
                            @else
                                <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[9px] uppercase font-black tracking-widest border border-amber-100/50 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span> Pending
                                </span>
                            @endif

                            {{-- Source Badge --}}
                            @if($isGit)
                                <span class="px-3 py-1 bg-gray-800 text-white rounded-lg text-[9px] uppercase font-black tracking-widest flex items-center gap-1.5 shadow-sm shadow-blue-200">
                                    <i class="fa-brands fa-github text-[11px]"></i> GitHub
                                </span>
                            @else
                                <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-[9px] uppercase font-black tracking-widest flex items-center gap-1.5 border border-slate-200">
                                    <i class="fa-solid fa-file-zipper"></i> ZIP
                                </span>
                            @endif
                        </div>
                        
                        <div class="{{ $isGit ? 'text-blue-500' : 'text-slate-200' }} group-hover:scale-110 transition-transform">
                            <i class="fa-solid {{ $isGit ? 'fa-code-branch' : 'fa-server' }} text-sm"></i>
                        </div>
                    </div>

                    {{-- Domain Preview --}}
                    <div class="mb-8">
                        <h4 class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.1em] mb-1">Alamat Situs</h4>
                        @if($site->status == 'active')
                            <a href="{{ route('sites.details', $site->subdomain) }}" class="text-base font-black text-slate-900 hover:text-blue-600 transition-colors break-all underline decoration-slate-100 underline-offset-4 leading-tight block">
                                {{ $site->subdomain }}.w3site.id
                            </a>
                        @else
                            <p class="text-base font-black text-slate-600 break-all italic leading-tight">
                                {{ $site->subdomain }}.w3site.id
                            </p>
                        @endif

                        {{-- Repo Name (Hanya jika Git) --}}
                        @if($isGit)
                            <p class="text-[10px] text-blue-400 font-medium mt-2 truncate italic">
                                <i class="fa-solid fa-link mr-1"></i> {{ basename($site->repository_url) }}
                            </p>
                        @endif
                    </div>

                    {{-- Footer Info --}}
                    <div class="grid grid-cols-2 gap-4 pt-5 border-t border-slate-50">
                        <div>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1">Deployment</p>
                            <p class="text-[10px] font-black {{ $isGit ? 'text-blue-600' : 'text-slate-700' }} uppercase">
                                {{ $isGit ? 'Auto Sync' : 'Manual ZIP' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mb-1">SSL Status</p>
                            <p class="text-[10px] font-black {{ $site->status == 'active' ? 'text-green-600' : 'text-slate-300' }} uppercase italic">
                                {{ $site->status == 'active' ? 'Secured' : 'Waiting' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="col-span-full py-20 text-center bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200">
                    <i class="fa-solid fa-folder-open text-slate-200 text-4xl mb-4"></i>
                    <p class="text-slate-400 font-black uppercase text-xs tracking-[0.2em]">Belum ada website yang dibuat</p>
                    <a href="{{ route('mysite') }}" class="inline-block mt-4 text-blue-500 font-bold text-xs underline uppercase hover:text-blue-700">Buat Situs Sekarang →</a>
                </div>
            @endforelse
        </div>
    </div>
</x-user-layout>