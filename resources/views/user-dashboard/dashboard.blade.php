<x-user-layout>
    @if(!auth()->user()->hasVerifiedEmail())
        <div class="mb-10 p-4 border border-amber-200 bg-amber-50 rounded-2xl flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-triangle-exclamation text-amber-600 text-sm"></i>
            </div>
            <div>
                <p class="text-[14px] font-bold text-amber-900">Email verification required</p>
                <p class="text-[13px] text-amber-700 mt-0.5">Please check your inbox at <span class="font-bold underline">{{ auth()->user()->email }}</span> to unlock all features.</p>
            </div>
        </div>
    @endif

    <div class="mb-12">
        <h1 class="text-[32px] md:text-[40px] font-black tracking-tighter text-black leading-none">Overview</h1>
        <p class="text-[#666] text-[16px] font-medium mt-2">Manage your projects and monitor your resource usage.</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
        <div class="bg-white p-8 rounded-[24px] border border-[#eaeaea] hover:border-black transition-all group">
            <div class="flex items-center justify-between mb-6">
                <span class="text-[12px] font-bold text-[#888] tracking-widest leading-none">Account Status</span>
                <i class="fa-solid fa-crown text-amber-400"></i>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-black tracking-tight">Free Forever</h3>
            </div>
            <p class="text-[13px] text-[#999] mt-4 font-bold tracking-tight">Standard limits apply</p>
        </div>

        <div class="bg-white p-8 rounded-[24px] border border-[#eaeaea] hover:border-black transition-all group">
            <div class="flex items-center justify-between mb-6">
                <span class="text-[12px] font-bold text-[#888] tracking-widest leading-none">Storage Used</span>
                <i class="fa-solid fa-database text-[#666]"></i>
            </div>
            <div class="flex items-baseline gap-1">
                <h3 class="text-3xl font-black tracking-tight">{{ $totalSizeMb }}</h3>
                <span class="text-[14px] font-bold text-[#999]">/ 512 MB</span>
            </div>
            <div class="mt-6 w-full h-1.5 bg-[#f5f5f5] rounded-full overflow-hidden">
                <div class="h-full {{ $storagePercent > 90 ? 'bg-red-500' : 'bg-black' }} transition-all duration-500" style="width: {{ $storagePercent }}%"></div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[24px] border border-[#eaeaea] hover:border-black transition-all group">
            <div class="flex items-center justify-between mb-6">
                <span class="text-[12px] font-bold text-[#888] tracking-widest leading-none">Active Projects</span>
                <i class="fa-solid fa-layer-group text-[#666]"></i>
            </div>
            <h3 class="text-3xl font-black tracking-tight">{{ $siteCount }}</h3>
            <p class="text-[13px] text-[#999] mt-4 font-bold tracking-tight">Deployed on w3site.id</p>
        </div>
    </div>

    {{-- Projects Section --}}
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-[20px] font-bold tracking-tight text-black">Recent Projects</h2>
        <a href="{{ route('mysite') }}" class="inline-flex items-center gap-2 px-6 h-11 bg-[#171717] text-white rounded-full text-[14px] font-bold hover:bg-[#383838] transition-all transform hover:scale-[1.02] active:scale-[0.98]">
            <i class="fa-solid fa-plus text-[10px]"></i> New Project
        </a>
    </div>

    @if($sites->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($sites->take(6) as $site)
            @php $isGit = !empty($site->repository_url); @endphp
            <div class="group bg-white border border-[#eaeaea] rounded-[24px] overflow-hidden hover:border-black transition-all flex flex-col h-full shadow-sm">
                <div class="p-6 flex-1">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl border border-[#eaeaea] bg-zinc-50 flex items-center justify-center text-zinc-400 group-hover:bg-black group-hover:text-white transition-colors">
                            @if($isGit)<i class="fa-brands fa-github text-[18px]"></i>
                            @else<i class="fa-solid fa-cube text-[18px]"></i>@endif
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[16px] font-bold text-black leading-none truncate">{{ $site->subdomain }}</span>
                            <span class="text-[12px] text-zinc-400 mt-1">.w3site.id</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between text-[13px] font-bold">
                            <span class="text-zinc-400">Status</span>
                            @if($site->status == 'active')
                                <div class="flex items-center gap-1.5 text-green-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Ready
                                </div>
                            @else
                                <div class="flex items-center gap-1.5 text-amber-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Building
                                </div>
                            @endif
                        </div>
                        <div class="flex items-center justify-between text-[13px] font-bold">
                            <span class="text-zinc-400">Environment</span>
                            <span class="text-black italic">Production</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-zinc-50 border-t border-[#eaeaea] flex items-center justify-between">
                    <span class="text-[11px] font-bold text-zinc-400">{{ $site->created_at->format('M d, Y') }}</span>
                    <div class="flex items-center gap-2">
                        @if($site->status == 'active')
                            <a href="//{{ $site->subdomain }}.w3site.id" target="_blank" class="w-8 h-8 rounded-lg border border-[#eaeaea] bg-white flex items-center justify-center hover:border-black transition-colors">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
        <div class="border-2 border-dashed border-[#eaeaea] rounded-[32px] py-32 text-center bg-[#fafafa]">
            <p class="text-[18px] font-bold text-black mb-1">No projects yet</p>
            <p class="text-[14px] font-medium text-[#888] mb-8">Deploy your first site to see it here.</p>
            <a href="{{ route('mysite') }}" class="inline-flex items-center gap-2 px-8 h-12 bg-[#171717] text-white rounded-full text-[15px] font-bold hover:bg-[#383838] transition-all">
                <i class="fa-solid fa-plus text-[10px]"></i> Create Your First Project
            </a>
        </div>
    @endif
</x-user-layout>