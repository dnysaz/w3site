<x-user-layout>
    <style>[x-cloak]{display:none!important}</style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div x-data="gitManager()" x-cloak class="max-w-xl mx-auto">
        <a href="{{ route('mysite') }}" class="inline-flex items-center gap-1.5 text-[13px] text-[#666] hover:text-[#000] transition-colors mb-5">
            <i class="fa-solid fa-arrow-left text-[10px]"></i> Back to Projects
        </a>

        <div class="border border-[#eaeaea] rounded-md overflow-hidden">
            <div class="px-5 py-4 border-b border-[#eaeaea] text-center">
                <i class="fa-brands fa-github text-[#666] text-xl mb-2"></i>
                <h1 class="text-[18px] font-bold mb-0.5 text-black">Import Git Repository</h1>
                <p class="text-[15px] font-medium text-[#888]">Connect a public GitHub repository.</p>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="text-[12px] text-[#666] mb-1 block">Project</label>
                    <div class="relative">
                        <select x-model="selectedSubdomain" class="w-full px-4 h-10 border border-[#eaeaea] rounded-xl text-[13px] outline-none focus:border-[#999] appearance-none transition-colors bg-white font-medium text-black">
                            <option value="">Select a project...</option>
                            @foreach($sites as $site)<option value="{{ $site->subdomain }}">{{ $site->subdomain }}.w3site.id</option>@endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[#ccc] pointer-events-none text-[9px]"></i>
                    </div>
                </div>
                <div>
                    <label class="text-[12px] text-[#666] mb-1 block">Repository URL</label>
                    <div class="flex border border-[#eaeaea] rounded-xl overflow-hidden focus-within:border-black transition-colors">
                        <span class="px-4 py-2.5 text-[#888] bg-[#fafafa] border-r border-[#eaeaea]"><i class="fa-brands fa-github text-[15px]"></i></span>
                        <input type="text" x-model="repoUrl" placeholder="https://github.com/user/repo" class="flex-1 px-4 py-2.5 text-[14px] outline-none font-bold">
                    </div>
                </div>
                <div x-show="repoUrl.length > 0 && selectedSubdomain" x-transition class="p-2.5 bg-amber-50 border border-amber-200 rounded-md">
                    <p class="text-[12px] text-amber-700"><i class="fa-solid fa-triangle-exclamation text-[10px] mr-1"></i>This will <strong>overwrite</strong> existing files.</p>
                </div>
                <div class="p-2.5 bg-[#fafafa] border border-[#eaeaea] rounded-md">
                    <p class="text-[12px] text-[#666]"><i class="fa-solid fa-circle-info text-[10px] mr-1 text-[#999]"></i>Repository must contain an <code class="font-mono text-[#000]">index.html</code> in root.</p>
                </div>
                <button @click="startDeploy()" :disabled="!selectedSubdomain || !repoUrl || isDeploying" class="w-full h-11 bg-[#171717] text-white rounded-xl text-[13px] font-medium hover:bg-[#383838] disabled:bg-[#eaeaea] disabled:text-[#999] transition-colors">
                    <span x-show="!isDeploying">Start Deployment</span><span x-show="isDeploying"><i class="fa-solid fa-spinner animate-spin mr-1"></i>Deploying...</span>
                </button>
            </div>
        </div>

        <div x-show="showNotif" x-transition class="fixed bottom-6 inset-x-0 flex justify-center z-[200]">
            <div class="px-4 py-2.5 bg-white border border-[#eaeaea] rounded-md flex items-center gap-2">
                <i class="fa-solid text-[12px]" :class="notifType === 'success' ? 'fa-check-circle text-green-600' : 'fa-circle-xmark text-red-500'"></i>
                <p class="text-[13px]" x-text="notifMsg"></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function gitManager() {
            return {
                selectedSubdomain: '{{ $subdomain ?? "" }}', repoUrl: '', isDeploying: false,
                showNotif: false, notifType: 'success', notifMsg: '',
                notify(msg, type = 'success') { this.notifMsg = msg; this.notifType = type; this.showNotif = true; setTimeout(() => this.showNotif = false, 4000); },
                async startDeploy() {
                    this.isDeploying = true;
                    try { await axios.post('{{ route("deploy.process") }}', { subdomain: this.selectedSubdomain, repository_url: this.repoUrl }); this.notify('Deployment started!'); setTimeout(() => window.location.href = '{{ route("mysite") }}', 1500); }
                    catch(e) { this.isDeploying = false; this.notify(e.response?.data?.message || 'Failed.', 'error'); }
                }
            }
        }
    </script>
</x-user-layout>