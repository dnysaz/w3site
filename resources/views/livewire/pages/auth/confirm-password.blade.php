<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col md:flex-row min-h-[650px]">
    
    <div class="hidden md:flex md:w-[45%] bg-slate-900 p-12 flex-col justify-between relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/20 blur-[100px] rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-500/10 blur-[80px] rounded-full -ml-24 -mb-24"></div>

        <div class="relative z-10">
            <a href="/" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold italic text-xl shadow-lg shadow-blue-900/50">
                    w3
                </div>
                <span class="text-2xl font-extrabold tracking-tighter text-white">
                    w3site<span class="text-blue-400">.id</span>
                </span>
            </a>
        </div>

        <div class="relative z-10">
            <h2 class="text-4xl font-extrabold text-white leading-[1.2] mb-6 tracking-tight">
                Keamanan <br><span class="text-blue-400 italic">Terverifikasi.</span>
            </h2>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <span class="text-blue-400 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <p class="text-slate-400 text-sm">Anda memasuki area sensitif. Konfirmasi identitas diperlukan.</p>
                </div>
            </div>
        </div>

        <div class="relative z-10 flex items-center gap-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">
            <span>Secure Session Authorization</span>
        </div>
    </div>

    <div class="w-full md:w-[55%] p-8 md:p-16 flex flex-col justify-center bg-white">
        
        <div class="max-w-sm mx-auto w-full">
            <div class="mb-10 text-center md:text-left">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Konfirmasi Password</h1>
                <p class="text-slate-500 font-medium">Selesaikan langkah ini untuk melanjutkan akses aman Anda.</p>
            </div>

            <form wire:submit="confirmPassword" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2 px-1">Password Anda</label>
                    <input wire:model="password" type="password" required autocomplete="current-password" autofocus 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-600 transition-all outline-none font-semibold text-slate-700 placeholder:font-normal placeholder:text-slate-400" 
                           placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <button type="submit" wire:loading.attr="disabled"
                        class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold shadow-xl shadow-slate-200 hover:bg-black active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                    <span wire:loading.remove>Konfirmasi Identitas</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memverifikasi...
                    </span>
                </button>

                <div class="pt-4 text-center">
                    <button type="button" onclick="history.back()" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">
                        Batal & Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>