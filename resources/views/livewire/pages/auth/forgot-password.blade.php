<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
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
                Pulihkan akses <br><span class="text-blue-400 italic">keamanan Anda.</span>
            </h2>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <span class="text-blue-400 mt-1">✓</span>
                    <p class="text-slate-400 text-sm">Verifikasi identitas melalui email resmi.</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="text-blue-400 mt-1">✓</span>
                    <p class="text-slate-400 text-sm">Proteksi enkripsi pada setiap reset password.</p>
                </div>
            </div>
        </div>

        <div class="relative z-10 flex items-center gap-4 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">
            <span>Security & Compliance Center</span>
        </div>
    </div>

    <div class="w-full md:w-[55%] p-8 md:p-16 flex flex-col justify-center bg-white">
        
        <div class="max-w-sm mx-auto w-full">
            <div class="mb-10 text-center md:text-left">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-4">Lupa Password?</h1>
                <p class="text-slate-500 font-medium leading-relaxed">
                    Jangan khawatir. Masukkan email Anda dan kami akan mengirimkan link untuk mengatur ulang kata sandi.
                </p>
            </div>

            <x-auth-session-status class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-100 text-sm" :status="session('status')" />

            <form wire:submit="sendPasswordResetLink" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2 px-1">Alamat Email</label>
                    <input wire:model="email" type="email" required autofocus 
                           class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/5 focus:border-blue-600 transition-all outline-none font-semibold text-slate-700 placeholder:font-normal placeholder:text-slate-400" 
                           placeholder="nama@email.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <button type="submit" wire:loading.attr="disabled"
                        class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold shadow-xl shadow-blue-200 hover:bg-blue-700 active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                    <span wire:loading.remove>Email Link Reset Password</span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Mengirim...
                    </span>
                </button>

                <div class="pt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-extrabold text-blue-600 hover:text-blue-700 transition-colors inline-flex items-center gap-2 underline underline-offset-4" wire:navigate>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke halaman masuk
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>