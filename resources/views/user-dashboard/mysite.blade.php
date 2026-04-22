<x-user-layout>
    <style>[x-cloak]{display:none!important}@keyframes progress{0%{transform:translateX(-100%)}100%{transform:translateX(200%)}}</style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div x-data="siteManager()" x-cloak>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <h1 class="text-[32px] md:text-[40px] font-black tracking-tighter text-black leading-none">Projects</h1>
                <p class="text-[#666] text-[16px] font-medium mt-2">Manage and deploy your static websites.</p>
            </div>
            <button @click="showAddNameModal = true" class="inline-flex items-center gap-2 px-8 h-12 bg-[#171717] text-white rounded-full text-[15px] font-bold hover:bg-[#383838] transition-all transform hover:scale-[1.02] active:scale-[0.98]">
                <i class="fa-solid fa-plus text-[10px]"></i> Create New Project
            </button>
        </div>

        {{-- Project Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($sites as $site)
                @php $isGit = !empty($site->repository_url); @endphp
                <div class="group bg-white border border-[#eaeaea] rounded-[32px] overflow-hidden hover:border-black transition-all flex flex-col h-full shadow-sm" 
                     x-data="{ is_public: {{ $site->is_public ? 'true' : 'false' }} }">
                    <div class="p-8 flex-1">
                        <div class="flex items-start justify-between mb-8">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-14 h-14 rounded-[20px] border border-[#eaeaea] bg-zinc-50 flex items-center justify-center text-zinc-400 group-hover:bg-black group-hover:text-white transition-colors">
                                    @if($isGit)<i class="fa-brands fa-github text-[22px]"></i>
                                    @else<i class="fa-solid fa-cube text-[20px]"></i>@endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-[18px] font-extrabold text-black leading-none truncate">{{ $site->subdomain }}</h3>
                                    <p class="text-[13px] text-zinc-400 mt-1.5 font-medium tracking-tight">{{ $site->subdomain }}.w3site.id</p>
                                </div>
                            </div>
                            
                            {{-- Public Switch --}}
                            <div class="flex flex-col items-end gap-1.5">
                                <span class="text-[10px] font-bold text-zinc-300 tracking-[0.1em]">SHOWCASE</span>
                                <button @click="is_public = !is_public; toggleShowcase({{ $site->id }})" 
                                        :disabled="isPublicUpdating === {{ $site->id }}"
                                        class="relative inline-flex h-5 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                        :class="is_public ? 'bg-black' : 'bg-zinc-200'">
                                    <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out"
                                          :class="is_public ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mb-8">
                             <div class="flex items-center gap-2 px-3 py-1.5 bg-zinc-50 rounded-full border border-[#eaeaea]">
                                @if($site->status == 'active')
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    <span class="text-[11px] font-bold text-zinc-600">Active</span>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                    <span class="text-[11px] font-bold text-zinc-600">Building</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                @if($isGit)
                                    <span class="text-[11px] font-black text-zinc-300 italic tracking-tighter">GIT REPO</span>
                                @else
                                    <span class="text-[11px] font-black text-zinc-300 italic tracking-tighter">ZIP FILE</span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            @if($isGit)
                                <button @click="syncGitHub({{ $site->id }}, '{{ $site->subdomain }}')" class="h-10 border border-[#eaeaea] bg-white rounded-xl text-[12px] font-bold text-black hover:border-black transition-all flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-rotate text-[10px]"></i> Sync
                                </button>
                                <a href="{{ $site->repository_url }}" target="_blank" class="h-10 border border-[#eaeaea] bg-white rounded-xl text-[12px] font-bold text-black hover:border-black transition-all flex items-center justify-center gap-2">
                                    <i class="fa-brands fa-github text-[11px]"></i> Repo
                                </a>
                            @else
                                <button @click="subdomain = '{{ $site->subdomain }}'; showUploadModal = true" class="h-10 border border-[#eaeaea] bg-white rounded-xl text-[12px] font-bold text-black hover:border-black transition-all flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-file-zipper text-[10px]"></i> Upload
                                </button>
                                <a href="{{ route('deploy.github', ['subdomain' => $site->subdomain]) }}" class="h-10 border border-[#eaeaea] bg-white rounded-xl text-[12px] font-bold text-black hover:border-black transition-all flex items-center justify-center gap-2">
                                    <i class="fa-brands fa-github text-[11px]"></i> Connect
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 bg-zinc-50 border-t border-[#eaeaea] flex items-center justify-between gap-3">
                        <button @click="openDeleteModal({{ $site->id }}, '{{ $site->subdomain }}')" class="px-3 py-1.5 text-[11px] font-bold text-zinc-400 hover:text-red-500 transition-colors">
                            Delete
                        </button>
                        <div class="flex items-center gap-2">
                            @if($site->status == 'active')
                                <button @click="openUpdateModal('{{ $site->subdomain }}')" class="w-9 h-9 rounded-xl border border-[#eaeaea] bg-white flex items-center justify-center hover:border-black transition-colors" title="Update Files">
                                    <i class="fa-solid fa-arrow-up-from-bracket text-[11px]"></i>
                                </button>
                                <a href="//{{ $site->subdomain }}.w3site.id" target="_blank" class="px-5 h-9 bg-black text-white rounded-full text-[12px] font-bold hover:bg-zinc-800 transition-all flex items-center gap-2">
                                    Visit Site <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full border-2 border-dashed border-[#eaeaea] rounded-[40px] py-32 text-center bg-[#fafafa]">
                    <div class="w-20 h-20 rounded-full bg-white border border-[#eaeaea] mx-auto flex items-center justify-center mb-6">
                        <i class="fa-solid fa-layer-group text-zinc-300 text-3xl"></i>
                    </div>
                    <p class="text-[20px] font-black text-black">No projects discovered</p>
                    <p class="text-[15px] font-medium text-[#888] mt-2 mb-10">Start your journey by creating your first static site.</p>
                    <button @click="showAddNameModal = true" class="inline-flex items-center gap-2 px-10 h-14 bg-[#171717] text-white rounded-full text-[16px] font-black hover:bg-[#383838] transition-all transform hover:scale-105 active:scale-95 shadow-xl shadow-black/10">
                        <i class="fa-solid fa-plus text-[12px]"></i> Create New Project
                    </button>
                </div>
            @endforelse
        </div>

        {{-- Modals remain consistent but styled to match --}}
        {{-- Notification Modal --}}
        <div x-show="showNotif" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showNotif = false"></div>
            <div class="relative bg-white border border-[#eaeaea] w-full max-w-xs rounded-3xl p-8 text-center shadow-2xl">
                <div :class="notifType === 'success' ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500'" class="w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-6 text-3xl">
                    <i class="fa-solid" :class="notifType === 'success' ? 'fa-check' : 'fa-times'"></i>
                </div>
                <h3 class="text-[20px] font-black text-black" x-text="notifTitle"></h3>
                <p class="text-[14px] font-medium text-[#888] mt-2 leading-relaxed" x-text="notifMsg"></p>
                <button @click="showNotif = false" class="mt-8 w-full py-4 bg-[#171717] text-white rounded-2xl text-[14px] font-bold hover:bg-[#383838] transition-all shadow-lg shadow-black/10">Got it</button>
            </div>
        </div>

        {{-- Add Name Modal --}}
        <div x-show="showAddNameModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="if(!isDeploying) showAddNameModal = false"></div>
            <div class="relative bg-white border border-[#eaeaea] w-full max-w-md rounded-[32px] p-10 shadow-2xl">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-[24px] font-black text-black leading-none">New Project</h3>
                        <p class="text-zinc-400 text-[14px] font-medium mt-2">Reserve your subdomain.</p>
                    </div>
                    <button x-show="!isDeploying" @click="showAddNameModal = false" class="w-10 h-10 rounded-full border border-[#eaeaea] flex items-center justify-center text-zinc-400 hover:text-black transition-colors"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="space-y-6">
                    <div>
                        <label class="text-[12px] font-black text-zinc-400 mb-2 block tracking-[0.1em]">PROJECT NAME</label>
                        <div class="flex border-2 border-[#eaeaea] rounded-2xl overflow-hidden focus-within:border-black transition-all">
                            <input type="text" x-model="subdomain" @input="checkSubdomain()" placeholder="my-project" class="flex-1 px-5 py-4 text-[16px] font-bold outline-none">
                            <div class="px-5 flex items-center bg-zinc-50 border-l border-[#eaeaea] font-black text-zinc-400 ">.w3site.id</div>
                        </div>
                        <p x-show="subdomain && !isAvailable" class="mt-2 text-red-500 text-[12px] font-bold ml-1">This name is already reserved.</p>
                    </div>
                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100">
                        <p class="text-[13px] text-amber-700 leading-relaxed font-medium"><i class="fa-solid fa-circle-info text-[11px] mr-2"></i>Choose wisely! The subdomain cannot be changed later.</p>
                    </div>
                    <button @click="reserveName()" :disabled="!subdomain || !isAvailable || isDeploying" class="w-full h-14 bg-[#171717] text-white rounded-2xl text-[16px] font-black disabled:bg-zinc-100 disabled:text-zinc-300 hover:bg-[#383838] transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-black/10">
                        <span x-show="!isDeploying">Reserve My Project</span>
                        <span x-show="isDeploying" class="flex items-center justify-center gap-3"><i class="fa-solid fa-spinner animate-spin"></i> Reserving...</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Upload ZIP Modal --}}
        <div x-show="showUploadModal || showUpdateZipModal" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="if(!isDeploying) closeModal()"></div>
            <div class="relative bg-white border border-[#eaeaea] w-full max-w-md rounded-[32px] p-10 shadow-2xl">
                <div class="mb-8">
                    <h3 class="text-[24px] font-black text-black leading-none" x-text="showUpdateZipModal ? 'Update Files' : 'Deploy ZIP'"></h3>
                    <p class="text-zinc-400 text-[14px] font-medium mt-2" x-text="'Target: ' + subdomain + '.w3site.id'"></p>
                </div>

                <div x-show="showUpdateZipModal" class="p-4 bg-amber-50 rounded-2xl border border-amber-100 mb-6">
                    <p class="text-[13px] text-amber-700 leading-relaxed font-medium"><i class="fa-solid fa-triangle-exclamation text-[11px] mr-2"></i>Warning: All current files will be permanently replaced.</p>
                </div>

                <div class="border-2 border-dashed border-[#eaeaea] bg-zinc-50 rounded-[24px] p-8 text-center hover:border-black transition-all relative mb-8 group">
                    <input type="file" @change="handleFile($event)" class="absolute inset-0 opacity-0 cursor-pointer" accept=".zip">
                    <div class="w-16 h-16 rounded-2xl bg-white border border-[#eaeaea] mx-auto flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-cloud-arrow-up text-zinc-300 text-2xl group-hover:text-black transition-colors"></i>
                    </div>
                    <p class="text-[15px] font-black text-zinc-600 truncate px-4" x-text="fileName ? fileName : 'Select ZIP Bundle'"></p>
                    <p class="text-[12px] font-medium text-zinc-400 mt-1">Maximum file size: 512MB</p>
                </div>

                <div x-show="isDeploying" class="mb-8">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-[12px] font-black text-black tracking-widest text-zinc-400">UPLOADING</span>
                        <span class="text-[12px] font-black text-black" x-text="uploadProgress + '%'"></span>
                    </div>
                    <div class="w-full h-2 bg-zinc-100 rounded-full overflow-hidden">
                        <div class="h-full bg-black transition-all duration-300" :style="'width:'+uploadProgress+'%'"></div>
                    </div>
                </div>

                <button @click="showUpdateZipModal ? startUpdateZip() : startUpload()" :disabled="!selectedFile || isDeploying" class="w-full h-14 bg-[#171717] text-white rounded-2xl text-[16px] font-black hover:bg-[#383838] disabled:bg-zinc-100 disabled:text-zinc-300 transition-all shadow-lg shadow-black/10">
                    <span x-show="!isDeploying">Start Deployment</span>
                    <span x-show="isDeploying" class="flex items-center justify-center gap-3"><i class="fa-solid fa-spinner animate-spin"></i> Deploying...</span>
                </button>
            </div>
        </div>

        {{-- Delete Modal --}}
        <div x-show="showDeleteModal" x-cloak x-transition class="fixed inset-0 z-[120] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="if(!isDeploying) showDeleteModal = false"></div>
            <div class="relative bg-white border border-[#eaeaea] w-full max-w-sm rounded-[32px] p-10 text-center shadow-2xl">
                <div class="w-20 h-20 rounded-full bg-red-50 text-red-500 mx-auto flex items-center justify-center mb-6 text-3xl">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <h3 class="text-[24px] font-black text-black">Destroy Project?</h3>
                <p class="text-[14px] font-medium text-zinc-400 mt-3 leading-relaxed">
                    This will permanently remove <span class="font-black text-red-600 underline" x-text="siteToDeleteSubdomain"></span> and all associated storage.
                </p>
                <div class="mt-10 flex flex-col gap-3">
                    <button @click="confirmDelete()" :disabled="isDeploying" class="w-full h-14 bg-red-600 text-white rounded-2xl text-[16px] font-black hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">
                        <span x-show="!isDeploying">Confirm Delete</span>
                        <span x-show="isDeploying"><i class="fa-solid fa-spinner animate-spin"></i></span>
                    </button>
                    <button @click="showDeleteModal = false" :disabled="isDeploying" class="w-full h-14 bg-white border border-[#eaeaea] rounded-2xl text-[16px] font-bold text-zinc-400 hover:border-black hover:text-black transition-colors">Cancel</button>
                </div>
            </div>
        </div>

        {{-- Sync Modal --}}
        <div x-show="showSyncModal" x-cloak class="fixed inset-0 z-[120] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="if(!isDeploying) showSyncModal = false"></div>
            <div class="relative bg-white border border-[#eaeaea] w-full max-w-sm rounded-[32px] p-10 text-center shadow-2xl">
                <div class="w-20 h-20 rounded-full bg-zinc-50 border border-[#eaeaea] mx-auto flex items-center justify-center mb-6 text-3xl text-black">
                    <i class="fa-solid fa-rotate" :class="isDeploying ? 'animate-spin' : ''"></i>
                </div>
                <h3 class="text-[24px] font-black text-black">Sync Repository</h3>
                <p class="text-[14px] font-medium text-zinc-400 mt-3 leading-relaxed">Fetch the latest code for <span class="font-black text-black" x-text="siteToSyncSubdomain"></span>.</p>
                
                <div x-show="isDeploying" class="mt-8">
                    <div class="w-full bg-[#f5f5f5] h-2 rounded-full overflow-hidden">
                        <div class="bg-black h-full animate-[progress_2s_ease-in-out_infinite] w-1/2"></div>
                    </div>
                </div>

                <div class="mt-10 flex flex-col gap-3" x-show="!isDeploying">
                    <button @click="confirmSync()" class="w-full h-14 bg-black text-white rounded-2xl text-[16px] font-black hover:bg-zinc-800 transition-all shadow-lg shadow-black/20">Sync Now</button>
                    <button @click="showSyncModal = false" class="w-full h-14 bg-white border border-[#eaeaea] rounded-2xl text-[16px] font-bold text-zinc-400 hover:border-black hover:text-black transition-colors">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        function siteManager() {
            return {
                subdomain: '', isAvailable: true, isDeploying: false,
                showAddNameModal: false, showUploadModal: false, showDeleteModal: false, showUpdateZipModal: false,
                showSyncModal: false, siteToSyncId: null, siteToSyncSubdomain: '',
                showNotif: false, notifType: 'success', notifTitle: '', notifMsg: '',
                selectedFile: null, fileName: '', uploadProgress: 0,
                siteToDeleteId: null, siteToDeleteSubdomain: '',
                isPublicUpdating: null,
                closeModal() { this.showUploadModal = false; this.showUpdateZipModal = false; this.selectedFile = null; this.fileName = ''; },
                notify(title, msg, type = 'success') { this.notifTitle = title; this.notifMsg = msg; this.notifType = type; this.showNotif = true; },
                handleFile(e) { const f = e.target.files[0]; if(f) { this.fileName = f.name; this.selectedFile = f; } },
                checkSubdomain() { this.subdomain = this.subdomain.toLowerCase().replace(/[^a-z0-9-]/g, ''); const r = ['admin','google','web','dashboard','login']; this.isAvailable = !r.includes(this.subdomain) && this.subdomain.length > 2; },
                syncGitHub(id, sub) { this.siteToSyncId = id; this.siteToSyncSubdomain = sub; this.showSyncModal = true; },
                async confirmSync() { this.isDeploying = true; try { const res = await axios.post(`/dashboard/deploy/github/sync/${this.siteToSyncId}`); this.showSyncModal = false; this.notify('Success', res.data.message); setTimeout(() => location.reload(), 1500); } catch(e) { this.isDeploying = false; this.notify('Error', e.response?.data?.message || 'Sync failed.', 'error'); } },
                async reserveName() { if(!this.subdomain || !this.isAvailable) return; this.isDeploying = true; try { await axios.post('/dashboard/sites/store-name', { subdomain: this.subdomain }); this.showAddNameModal = false; this.notify('Created', 'Project reserved.'); setTimeout(() => location.reload(), 1500); } catch(e) { this.isDeploying = false; this.notify('Error', e.response?.data?.message || 'Failed.', 'error'); } },
                async startUpload() { if(!this.selectedFile) return; await this.uploadLogic('/dashboard/sites/upload-file', 'Deployed', 'Your site is live.'); },
                async startUpdateZip() { if(!this.selectedFile) return; await this.uploadLogic('/dashboard/sites/upload-file', 'Updated', 'Files replaced.'); },
                async uploadLogic(url, t, m) { this.isDeploying = true; let fd = new FormData(); fd.append('file', this.selectedFile); fd.append('subdomain', this.subdomain); try { await axios.post(url, fd, { onUploadProgress: p => { this.uploadProgress = Math.round((p.loaded*100)/p.total); } }); this.notify(t, m); setTimeout(() => location.reload(), 1500); } catch(e) { this.isDeploying = false; this.notify('Error', e.response?.data?.message || 'Error.', 'error'); } },
                openDeleteModal(id, sub) { this.siteToDeleteId = id; this.siteToDeleteSubdomain = sub; this.showDeleteModal = true; },
                openUpdateModal(sub) { this.subdomain = sub; this.fileName = ''; this.selectedFile = null; this.uploadProgress = 0; this.showUpdateZipModal = true; },
                async confirmDelete() { this.isDeploying = true; try { await axios.delete(`/dashboard/delete-site/${this.siteToDeleteId}`); this.showDeleteModal = false; this.notify('Deleted', 'Project removed.'); setTimeout(() => location.reload(), 1200); } catch(e) { this.isDeploying = false; this.notify('Error', 'Could not delete.', 'error'); } },
                async toggleShowcase(id, current) {
                    this.isPublicUpdating = id;
                    try {
                        const res = await axios.post(`/dashboard/sites/${id}/toggle-public`);
                         this.isPublicUpdating = null;
                    } catch(e) {
                        this.isPublicUpdating = null;
                        this.notify('Error', 'Failed to update visibility.', 'error');
                    }
                }
            }
        }
    </script>
</x-user-layout>