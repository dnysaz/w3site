<x-user-layout>
    <style>[x-cloak]{display:none!important}</style>

    <div class="max-w-xl">
        <div class="mb-12">
            <h1 class="text-[32px] md:text-[40px] font-black tracking-tighter text-black leading-none">Settings</h1>
            <p class="text-[#666] text-[16px] font-medium mt-2">Manage your personal information and security.</p>
        </div>

        <div class="space-y-10" x-data="{ isSavingProfile: false }">

            {{-- Profile Card --}}
            <div class="bg-white border border-[#eaeaea] rounded-[24px] overflow-hidden shadow-sm">
                <div class="px-8 py-4 border-b border-[#eaeaea] bg-zinc-50/50">
                    <h3 class="text-[13px] font-bold text-zinc-400 tracking-widest uppercase">Profile</h3>
                </div>
                <div class="p-8">
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6" @submit="isSavingProfile = true">
                        @csrf @method('PATCH')
                        <div>
                            <label class="text-[13px] font-bold text-black mb-2 block tracking-tight">Display Name</label>
                            <input name="name" type="text" value="{{ old('name', $user->name) }}" class="w-full px-5 py-3 border border-[#eaeaea] rounded-2xl text-[15px] font-bold outline-none focus:border-black transition-all">
                            @error('name')<p class="text-red-500 text-[12px] mt-2 font-bold">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-[13px] font-bold text-zinc-400 mb-2 block tracking-tight">Email Address</label>
                            <input type="email" value="{{ $user->email }}" class="w-full px-5 py-3 border border-[#eaeaea] rounded-2xl text-[15px] font-bold text-zinc-400 bg-zinc-50 cursor-not-allowed" disabled title="Email cannot be changed">
                            <p class="text-[11px] text-zinc-400 mt-2 font-medium">Logged in via <span class="font-bold text-zinc-600">{{ $user->social_type ?: 'email' }}</span></p>
                        </div>
                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit" :class="isSavingProfile ? 'opacity-70' : ''" class="px-8 h-11 bg-black text-white rounded-full text-[14px] font-bold hover:bg-zinc-800 transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-black/10">
                                <span x-show="!isSavingProfile">Save Changes</span>
                                <span x-show="isSavingProfile" class="flex items-center gap-2"><i class="fa-solid fa-spinner animate-spin"></i> Saving</span>
                            </button>
                            @if(session('status') === 'profile-updated')
                                <span x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false,3000)" class="text-[13px] font-bold text-green-600 flex items-center gap-1.5"><i class="fa-solid fa-check"></i> Saved</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Password Card --}}
            <div class="bg-white border border-[#eaeaea] rounded-[24px] overflow-hidden shadow-sm">
                <div class="px-8 py-4 border-b border-[#eaeaea] bg-zinc-50/50">
                    <h3 class="text-[13px] font-bold text-zinc-400 tracking-widest uppercase">Security</h3>
                </div>
                <div class="p-8" x-data="{ showCurrent:false, showNew:false, showConfirm:false, isSavingPassword:false }">
                    @if(auth()->user()->social_type === 'google')
                        <div class="py-4 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-zinc-50 border border-[#eaeaea] flex items-center justify-center text-zinc-400">
                                <i class="fa-brands fa-google text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[14px] font-bold text-black leading-none">External Auth</p>
                                <p class="text-[13px] text-zinc-400 mt-1 font-medium">Password is managed by your Google Account.</p>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('password.update') }}" method="POST" class="space-y-6" @submit="isSavingPassword = true">
                            @csrf @method('PUT')
                            <div>
                                <label class="text-[13px] font-bold text-black mb-2 block tracking-tight">Current Password</label>
                                <div class="relative">
                                    <input :type="showCurrent?'text':'password'" name="current_password" class="w-full px-5 py-3 border border-[#eaeaea] rounded-2xl text-[15px] font-bold outline-none focus:border-black pr-12 transition-all">
                                    <button type="button" @click="showCurrent=!showCurrent" class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-300 hover:text-black transition-colors"><i class="fa-solid" :class="showCurrent?'fa-eye-slash':'fa-eye'"></i></button>
                                </div>
                                @error('current_password')<p class="text-red-500 text-[12px] mt-2 font-bold">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-[13px] font-bold text-black mb-2 block tracking-tight">New Password</label>
                                <div class="relative">
                                    <input :type="showNew?'text':'password'" name="password" class="w-full px-5 py-3 border border-[#eaeaea] rounded-2xl text-[15px] font-bold outline-none focus:border-black pr-12 transition-all">
                                    <button type="button" @click="showNew=!showNew" class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-300 hover:text-black transition-colors"><i class="fa-solid" :class="showNew?'fa-eye-slash':'fa-eye'"></i></button>
                                </div>
                                @error('password')<p class="text-red-500 text-[12px] mt-2 font-bold">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="text-[13px] font-bold text-black mb-2 block tracking-tight">Confirm Password</label>
                                <div class="relative">
                                    <input :type="showConfirm?'text':'password'" name="password_confirmation" class="w-full px-5 py-3 border border-[#eaeaea] rounded-2xl text-[15px] font-bold outline-none focus:border-black pr-12 transition-all">
                                    <button type="button" @click="showConfirm=!showConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-300 hover:text-black transition-colors"><i class="fa-solid" :class="showConfirm?'fa-eye-slash':'fa-eye'"></i></button>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 pt-2">
                                <button type="submit" :class="isSavingPassword?'opacity-70':''" class="px-8 h-11 bg-black text-white rounded-full text-[14px] font-bold hover:bg-zinc-800 transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-black/10">
                                    <span x-show="!isSavingPassword">Update Password</span>
                                    <span x-show="isSavingPassword" class="flex items-center gap-2"><i class="fa-solid fa-spinner animate-spin"></i> Updating</span>
                                </button>
                                @if(session('status') === 'password-updated')
                                    <span x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false,3000)" class="text-[13px] font-bold text-green-600 flex items-center gap-1.5"><i class="fa-solid fa-check"></i> Updated</span>
                                @endif
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Danger Zone Card --}}
            <div class="bg-white border border-red-100 rounded-[24px] overflow-hidden shadow-sm">
                <div class="px-8 py-4 border-b border-red-100 bg-red-50/50">
                    <h3 class="text-[13px] font-bold text-red-400 tracking-widest uppercase">Danger Zone</h3>
                </div>
                <div class="p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div>
                            <p class="text-[16px] font-bold text-black leading-none">Delete Account</p>
                            <p class="text-[13px] text-zinc-400 mt-2 font-medium leading-relaxed">Permanently remove your account and all associated data from w3site.id.</p>
                        </div>
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="px-6 h-11 bg-red-600 text-white rounded-full text-[14px] font-bold hover:bg-red-700 transition-all transform hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-red-600/10 flex-shrink-0">Delete Forever</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div x-data="{ showDeleteModal: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }}, isDeleting: false }"
        @open-modal.window="if($event.detail === 'confirm-user-deletion') showDeleteModal = true"
        @close.window="showDeleteModal = false" @keydown.escape.window="showDeleteModal = false" x-cloak>
        <div x-show="showDeleteModal" class="fixed inset-0 z-[150] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" x-transition @click="showDeleteModal = false"></div>
            <div x-show="showDeleteModal" x-transition class="relative bg-white border border-[#eaeaea] w-full max-w-md rounded-[32px] p-10 shadow-2xl">
                <div class="w-20 h-20 rounded-full bg-red-50 text-red-500 mx-auto flex items-center justify-center mb-6 text-3xl">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <h2 class="text-[24px] font-black text-black text-center">Confirm Deletion</h2>
                <p class="text-[15px] font-medium text-zinc-400 mt-3 text-center leading-relaxed">This will erase your account and all projects permanently. This cannot be undone.</p>
                
                <form method="post" action="{{ route('profile.destroy') }}" class="mt-10 space-y-6" @submit="isDeleting = true">
                    @csrf @method('delete')
                    
                    @if(auth()->user()->social_type === 'google')
                        <div class="p-4 bg-zinc-50 border border-[#eaeaea] rounded-2xl flex items-center gap-3">
                            <i class="fa-brands fa-google text-zinc-400"></i>
                            <p class="text-[14px] font-bold text-zinc-600">{{ auth()->user()->email }}</p>
                        </div>
                        <input type="hidden" name="password" value="google-auth-bypass">
                    @else
                        <div x-data="{ showPass: false }">
                            <label class="text-[12px] font-black text-zinc-400 mb-2 block tracking-[0.1em]">CONFIRM WITH PASSWORD</label>
                            <div class="relative">
                                <input :type="showPass?'text':'password'" name="password" required placeholder="••••••••" class="w-full px-5 py-4 border-2 border-[#eaeaea] rounded-2xl text-[16px] font-bold outline-none focus:border-red-500 pr-12 transition-all shadow-sm">
                                <button type="button" @click="showPass=!showPass" class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-300 hover:text-black transition-colors"><i class="fa-solid" :class="showPass?'fa-eye-slash':'fa-eye'"></i></button>
                            </div>
                            @if($errors->userDeletion->has('password'))<p class="text-red-500 text-[12px] mt-2 font-bold ml-1">{{ $errors->userDeletion->first('password') }}</p>@endif
                        </div>
                    @endif

                    <div class="flex flex-col gap-3">
                        <button type="submit" :disabled="isDeleting" class="w-full h-14 bg-red-600 text-white rounded-2xl text-[16px] font-black hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">
                            <span x-show="!isDeleting">Delete Account Permanently</span>
                            <span x-show="isDeleting"><i class="fa-solid fa-spinner animate-spin"></i></span>
                        </button>
                        <button type="button" @click="showDeleteModal = false" class="w-full h-14 bg-white border border-[#eaeaea] rounded-2xl text-[16px] font-bold text-zinc-400 hover:border-black hover:text-black transition-colors">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-user-layout>